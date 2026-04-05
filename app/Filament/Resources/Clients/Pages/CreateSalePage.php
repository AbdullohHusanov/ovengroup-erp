<?php

namespace App\Filament\Resources\Clients\Pages;

use App\Filament\Resources\Clients\ClientResource;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class CreateSalePage extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public $record = null;

    protected static string $resource = ClientResource::class;

    protected string $view = 'filament.resources.counterparty-resource.pages.create-purchase-page';

    public ?array $data = [];

    public function mount(int|string $record): void
    {
        $this->record = \App\Models\Client::findOrFail($record);

        $this->form->fill([
            'items'        => [['product_id' => null, 'count' => 1]],
            'discount_sum' => 0,
            'payed_sum'    => 0,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([
                Repeater::make('items')
                    ->label('Mahsulotlar')
                    ->schema([
                        Select::make('product_id')
                            ->label('Model')
                            ->options(Product::pluck('model_name', 'id')->toArray())
                            ->searchable()
                            ->required()
                            ->live()  // <- muhim
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('count', 1);
                                $set('max_count', $state
                                    ? Product::find($state)?->total_complated_product_count ?? 1
                                    : 1
                                );
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

                        // Yashirin field — max qiymatni saqlash uchun
                        \Filament\Forms\Components\Hidden::make('max_count')
                            ->default(9999),                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->addActionLabel("Mahsulot qo'shish")
                    ->required(),

                TextInput::make('discount_sum')
                    ->label('Chegirma summasi')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                TextInput::make('payed_sum')
                    ->label("To'langan summa")
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {
            $totalSum   = 0;
            $totalCount = 0;
            $productsData = [];

            foreach ($data['items'] as $item) {
                $product     = Product::findOrFail($item['product_id']);
                $itemSum     = $product->selling_price * $item['count'];
                $totalSum   += $itemSum;
                $totalCount += $item['count'];

                $productsData[] = [
                    'product' => $product,
                    'count'   => $item['count'],
                ];
            }

            $discountSum = (float) ($data['discount_sum'] ?? 0);
            $payedSum    = (float) ($data['payed_sum'] ?? 0);
            $debtSum     = $totalSum - $discountSum - $payedSum;

            $sale = Sale::create([
                'client_id'    => $this->record->id,
                'count'        => $totalCount,
                'sum'          => $totalSum,
                'discount_sum' => $discountSum,
                'payed_sum'    => $payedSum,
                'debt_sum'     => $debtSum,
            ]);

            foreach ($productsData as $item) {
                /** @var Product $product */
                $product = $item['product'];
                $count   = $item['count'];

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'count'      => $count,
                ]);

                $product->increment('total_sold_product_count', $count);
            }

            // Client summalarini yangilash
            $this->record->increment('sold_products_total_sum', $totalSum);
            $this->record->increment('sold_products_total_count', $totalCount);
            $this->record->increment('payed_sum', $payedSum);
            $this->record->increment('debt_sum', $debtSum);
        });

        $this->redirect(
            ClientResource::getUrl('edit', ['record' => $this->record->id])
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Orqaga')
                ->color('gray')
                ->url(ClientResource::getUrl('edit', ['record' => $this->record->id])),
        ];
    }
}
