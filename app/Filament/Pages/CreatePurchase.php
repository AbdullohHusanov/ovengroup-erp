<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Counterparties\CounterpartyResource;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

class CreatePurchase extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    protected static bool $shouldRegisterNavigation = false;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected string $view = 'filament.pages.create-purchase';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'items' => [
                ['product_id' => null, 'count' => 1],
            ],
            'payed_sum' => 0,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data') // MUHIM
            ->schema([
                Repeater::make('items')
                    ->label('Mahsulotlar')
                    ->schema([
                        Select::make('product_id')
                            ->label('Mahsulot')
                            ->options($this->getProductsForSelect())
                            ->searchable()
                            ->required(),

                        TextInput::make('count')
                            ->label('Soni')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->addActionLabel('Mahsulot qo‘shish')
                    ->required(),

                TextInput::make('payed_sum')
                    ->label("To'langan summa")
                    ->numeric()
                    ->default(0),
            ]);
    }

    protected function getProductsForSelect(): array
    {
        return \App\Models\Product::pluck('model_name', 'id')->toArray();
    }

    public function saves()
    {
        $data = $this->form->getState();

        // Debug uchun
        // dd($data);

        foreach ($data['items'] as $item) {
            // save logic yozasiz
        }

        // Notification ham qo‘shish mumkin
//        $this->notify('success', 'Saqlandi');
    }

    public function save(): void
    {
        // Validatsiya
        $this->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.count' => 'required|integer|min:1',
            'payed_sum' => 'required|numeric|min:0',
        ], [
            'items.*.product_id.required' => 'Mahsulotni tanlang',
            'items.*.product_id.exists' => 'Mahsulot topilmadi',
            'items.*.count.required' => 'Sonini kiriting',
            'items.*.count.min' => 'Son 1 dan kichik bo\'lmaydi',
            'payed_sum.required' => 'To\'langan summani kiriting',
            'payed_sum.numeric' => 'To\'langan summa raqam bo\'lishi kerak',
        ]);

        DB::transaction(function () {
            $totalSum = 0;
            $totalCount = 0;

            // Har bir item uchun mahsulotni yuklab olamiz va summa hisoblaymiz
            $productsData = [];
            foreach ($this->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemSum = $product->semi_finished_product_price * $item['count'];
                $totalSum += $itemSum;
                $totalCount += $item['count'];
                $productsData[] = [
                    'product' => $product,
                    'count' => $item['count'],
                ];
            }

            $payedSum = (float)$this->payed_sum;
            $debtSum = $totalSum - $payedSum;

            // Purchase yaratish
            $purchase = Purchase::create([
                'counterparty_id' => $this->counterparty_id,
                'count' => $totalCount,
                'sum' => $totalSum,
                'payed_sum' => $payedSum,
                'debt_sum' => $debtSum,
            ]);

            // PurchaseItem va Product yangilash
            foreach ($productsData as $data) {
                /** @var Product $product */
                $product = $data['product'];
                $count = $data['count'];

                // PurchaseItem saqlash
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'count' => $count,
                ]);

                // Mahsulot umumiy countlarini yangilash
                $product->increment('total_product_count', $count);
                $product->increment('total_semi_finished_product_count', $count);
            }
        });

        $this->redirect(CounterpartyResource::getUrl('edit', ['record' => $this->counterparty_id]));
    }
}
