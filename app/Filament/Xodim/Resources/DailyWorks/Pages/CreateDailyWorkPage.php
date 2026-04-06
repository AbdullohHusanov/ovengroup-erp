<?php

namespace App\Filament\Xodim\Resources\DailyWorks\Pages;

use App\Models\DailyWork;
use App\Models\DailyWorkItem;
use App\Models\Product;
use App\Models\Salary;
use App\Models\SalaryItem;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateDailyWorkPage extends /*CreateRecord*/Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = \App\Filament\Xodim\Resources\DailyWorks\DailyWorkResource::class;

    protected string $view = 'filament.xodim.resources.daily-works.pages.create-daily-work-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'items' => [['product_id' => null, 'count' => 1]],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        $user = Auth::user();
        $role = $user->role;

        // Rolga qarab qaysi productlarni ko'rsatish va max count
        // Har bir rol faqat o'zi ishlashi mumkin bo'lgan productlarni ko'radi
        $products = $this->getAvailableProducts($role);

        return $schema
            ->statePath('data')
            ->schema([
                Repeater::make('items')
                    ->label('Bugungi ish')
                    ->schema([
                        Select::make('product_id')
                            ->label('Mahsulot (Model)')
                            ->options($products)
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) use ($role) {
                                if (!$state) return;

                                $product  = Product::find($state);
                                $maxCount = $this->getMaxCountForRole($product, $role);

                                $set('max_count', $maxCount);
                                $set('count', 1);
                            }),

                        TextInput::make('count')
                            ->label('Soni')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->maxValue(fn ($get) => $get('max_count') ?? 9999)
                            ->hint(fn ($get) => $get('max_count')
                                ? "Mavjud: {$get('max_count')} ta"
                                : null
                            ),

                        Hidden::make('max_count')
                            ->default(9999),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->addActionLabel("Mahsulot qo'shish")
                    ->required(),
            ]);
    }

    public function save(): void
    {
//        dd('asdasdasda');
        $data = $this->form->getState();
        $user = Auth::user();
        $role = $user->role;
//dd($user, $data);
        DB::transaction(function () use ($data, $user, $role) {
            $totalCount = 0;
            $totalSum   = 0;
            $productsData = [];

            foreach ($data['items'] as $item) {
                $product  = Product::findOrFail($item['product_id']);
                $count    = (int) $item['count'];
                $price    = $this->getPriceForRole($product, $role);
                $itemSum  = $price * $count;

                // Validatsiya: mavjud countdan oshmasin
                $maxCount = $this->getMaxCountForRole($product, $role);
                if ($count > $maxCount) {
                    Notification::make()
                        ->title("{$product->model_name}: mavjud miqdordan ({$maxCount} ta) oshib ketdi!")
                        ->danger()
                        ->send();
                    return;
                }

                $totalCount += $count;
                $totalSum   += $itemSum;

                $productsData[] = [
                    'product'  => $product,
                    'count'    => $count,
                    'item_sum' => $itemSum,
                    'price'    => $price,
                ];
            }

            // DailyWork yaratish
            $dailyWork = DailyWork::create([
                'user_id' => $user->id,
                'count'   => $totalCount,
                'sum'     => $totalSum,
            ]);

            foreach ($productsData as $item) {
                /** @var Product $product */
                $product = $item['product'];
                $count   = $item['count'];

                DailyWorkItem::create([
                    'daily_work_id' => $dailyWork->id,
                    'product_id'    => $product->id,
                    'count'         => $count,
                ]);

                // Product countlarini yangilash (oldingi bosqichdan ayirib, o'ziga qo'shish)
                $this->updateProductCounts($product, $role, $count);
            }

            // Salary yangilash (joriy oy uchun)
            $this->updateSalary($user, $productsData, $totalSum);
        });

        Notification::make()
            ->title('Kunlik ish muvaffaqiyatli saqlandi!')
            ->success()
            ->send();

        $this->redirect(static::getResource()::getUrl('index'));
    }

    // -----------------------------------------------------------------------
    // Helper methodlar
    // -----------------------------------------------------------------------

    /**
     * Rolga qarab qaysi productlar ko'rsatiladi.
     * Faqat o'zi ishlashi uchun tayyor (oldingi bosqich tugagan) productlar.
     */
    private function getAvailableProducts(string $role): array
    {
        return match ($role) {
            // Payvandchi: semi_finished ombordan olib ishlaydi
            'welder' => Product::where('total_semi_finished_product_count', '>', 0)
                ->pluck('model_name', 'id')
                ->toArray(),

            // Tekshiruvchi: payvandlangan productlarni tekshiradi
            'inspector' => Product::where('welded_product_count', '>', 0)
                ->pluck('model_name', 'id')
                ->toArray(),

            // Tozalovchi: tekshirilgan productlarni tozalaydi
            'cleaner' => Product::where('checked_product_count', '>', 0)
                ->pluck('model_name', 'id')
                ->toArray(),

            // Shtamplovchi: tozalangan productlardan faqat is_stamped=true bo'lganlar
            'stamper' => Product::where('cleaned_product_count', '>', 0)
                ->where('is_stamped', true)
                ->pluck('model_name', 'id')
                ->toArray(),

            'warehousekeeper' => Product::where(function ($query) {
                    $query->where('is_stamped', true)
                        ->where('stamped_product_count', '>', 0);
                })
                ->orWhere(function ($query) {
                    $query->where('is_stamped', false)
                        ->where('cleaned_product_count', '>', 0);
                })
                ->pluck('model_name', 'id')
                ->toArray(),
            default => [],
        };
    }

    /**
     * Rolga qarab productning max ishlash miqdori (oldingi bosqich qoldig'i).
     */
    private function getMaxCountForRole(?Product $product, string $role): int
    {
        if (!$product) return 0;

        return match ($role) {
            'welder'    => $product->total_semi_finished_product_count,
            'inspector' => $product->welded_product_count,
            'cleaner'   => $product->checked_product_count,
            'stamper'   => $product->cleaned_product_count,
            'warehousekeeper'   => $product->stamped_product_count,
            default     => 0,
        };
    }

    /**
     * Rolga qarab product narxi.
     */
    private function getPriceForRole(Product $product, string $role): float
    {
        return match ($role) {
            'welder'    => (float) $product->welder_price,
            'inspector' => (float) $product->inspector_price,
            'cleaner'   => (float) $product->cleaner_price,
            'stamper'   => (float) $product->stamper_price,
            'warehousekeeper'   => 0,//(float) $product->stamper_price,
            default     => 0,
        };
    }

    /**
     * Product countlarini yangilash:
     * - Oldingi bosqichdan ayirish
     * - O'z bosqichiga qo'shish
     * - Agar oxirgi bosqich tugasa completed ga qo'shish
     *
     * Jarayon:
     * semi_finished → welder → inspector → cleaner → [stamper if is_stamped] → completed
     */
    private function updateProductCounts(Product $product, string $role, int $count): void
    {
        match ($role) {
            'welder' => (function () use ($product, $count) {
                $product->decrement('total_semi_finished_product_count', $count);
                $product->increment('welded_product_count', $count);
            })(),

            'inspector' => (function () use ($product, $count) {
                $product->decrement('welded_product_count', $count);
                $product->increment('checked_product_count', $count);
            })(),

            'cleaner' => (function () use ($product, $count) {
                $product->decrement('checked_product_count', $count);
                $product->increment('cleaned_product_count', $count);

                // Agar stamper kerak bo'lmasa — to'g'ridan completed ga o'tadi
                if (!$product->is_stamped) {
                    $product->decrement('cleaned_product_count', $count);
                    $product->increment('total_complated_product_count', $count);
                }
            })(),

            'stamper' => (function () use ($product, $count) {
                $product->decrement('cleaned_product_count', $count);
                $product->increment('stamped_product_count', $count);
                // Stamper oxirgi bosqich — completed ga o'tadi
//                $product->decrement('stamped_product_count', $count);
//                $product->increment('total_complated_product_count', $count);
            })(),

            'warehousekeeper' => (function () use ($product, $count) {
//                $product->decrement('cleaned_product_count', $count);
//                $product->increment('stamped_product_count', $count);
                // Stamper oxirgi bosqich — completed ga o'tadi
                $product->decrement('stamped_product_count', $count);
                $product->increment('total_complated_product_count', $count);
            })(),

            default => null,
        };
    }

    /**
     * Joriy oy uchun Salary yaratish yoki mavjudini yangilash.
     */
    private function updateSalary(
        \App\Models\User $user,
        array $productsData,
        float $totalSum
    ): void {
        $now = now();

        $salary = Salary::firstOrCreate(
            [
                'user_id' => $user->id,
                'year'    => $now->year,
                'month'   => $now->month,
            ],
            [
                'product_count' => 0,
                'sum'           => 0,
                'payed_sum'     => 0,
                'debt_sum'      => 0,
                'total_sum'     => 0,
                'status'        => 'open',
            ]
        );

        $totalCount = array_sum(array_column($productsData, 'count'));

        $salary->increment('product_count', $totalCount);
        $salary->increment('sum', $totalSum);
        $salary->increment('total_sum', $totalSum);
        // debt_sum = to'lanmagan qism (payed_sum oshirilmaydi, admin to'laydi)
        $salary->increment('debt_sum', $totalSum);

        // SalaryItem — har bir product uchun yozuv
//        foreach ($productsData as $item) {
//            $existingItem = \App\Models\SalaryItem::where('salary_id', $salary->id)
//                ->where('product_id', $item['product']->id)
//                ->first();
//
//            if ($existingItem) {
//                $existingItem->increment('count', $item['count']);
//                $existingItem->increment('sum', $item['item_sum']);
//            } else {
//                \App\Models\SalaryItem::create([
//                    'salary_id'  => $salary->id,
//                    'product_id' => $item['product']->id,
//                    'count'      => $item['count'],
//                    'sum'        => $item['item_sum'],
//                    'price'      => $item['price'],
//                ]);
//            }
//        }
    }
}
