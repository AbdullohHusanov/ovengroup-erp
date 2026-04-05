<?php

namespace App\Filament\Resources\Counterparties\Pages;

use App\Filament\Resources\Counterparties\CounterpartyResource;
use App\Models\Counterparty;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class CreatePurchasePage extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public $record = null;

    protected static string $resource = CounterpartyResource::class;

    protected string $view = 'filament.resources.counterparty-resource.pages.create-purchase-page';

    public ?array $data = [];

    public function mount(int|string $record): void
    {
        $this->record = \App\Models\Counterparty::findOrFail($record);

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
            ->statePath('data')
            ->schema([
                Repeater::make('items')
                    ->label('Mahsulotlar')
                    ->schema([
                        Select::make('product_id')
                            ->label('Model')
                            ->options(Product::pluck('model_name', 'id')->toArray())
                            ->searchable()
                            ->required(),

                        TextInput::make('count')
                            ->label('Soni')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->addActionLabel("Mahsulot qo'shish")
                    ->required(),

                TextInput::make('payed_sum')
                    ->label("To'langan summa")
                    ->numeric()
                    ->default(0),
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
                $product  = Product::findOrFail($item['product_id']);
                $itemSum  = $product->semi_finished_product_price * $item['count'];
                $totalSum   += $itemSum;
                $totalCount += $item['count'];

                $productsData[] = [
                    'product' => $product,
                    'count'   => $item['count'],
                ];
            }

            $payedSum = (float) $data['payed_sum'];
            $debtSum  = $totalSum - $payedSum;

            $purchase = Purchase::create([
                'counterparty_id' => $this->record->id,
                'count'           => $totalCount,
                'sum'             => $totalSum,
                'payed_sum'       => $payedSum,
                'debt_sum'        => $debtSum,
            ]);

            foreach ($productsData as $item) {
                /** @var Product $product */
                $product = $item['product'];
                $count   = $item['count'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $product->id,
                    'count'       => $count,
                ]);

                $product->increment('total_product_count', $count);
                $product->increment('total_semi_finished_product_count', $count);
            }

            // Counterparty summalarini yangilash
            $this->record->increment('purchased_products_total_sum', $totalSum);
            $this->record->increment('purchased_products_total_count', $totalCount);
            $this->record->increment('payed_sum', $payedSum);
            $this->record->increment('debt_sum', $debtSum);
        });

        $this->redirect(
            CounterpartyResource::getUrl('edit', ['record' => $this->record->id])
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Orqaga')
                ->color('gray')
                ->url(CounterpartyResource::getUrl('edit', ['record' => $this->record->id])),
        ];
    }
}
