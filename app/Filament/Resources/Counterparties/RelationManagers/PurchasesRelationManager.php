<?php

namespace App\Filament\Resources\Counterparties\RelationManagers;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\Purchase;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class PurchasesRelationManager extends RelationManager
{
    protected static string $relationship = 'purchases';

    protected static ?string $relatedResource = PurchaseResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }

    protected function afterCreate(): void
    {
        $purchase = $this->getOwnerRecord()->purchases()->latest()->first();
        $this->recalculatePurchase($purchase);
    }

    protected function afterSave(): void
    {
        $this->recalculatePurchase($this->getRelationship()->latest()->first());
    }

    private function recalculatePurchase(Purchase $purchase): void
    {
        $totalSum   = 0;
        $totalCount = 0;

        foreach ($purchase->purchaseItems as $item) {
            $product = $item->product;
            $itemSum = $product->semi_finished_product_price * $item->count;

            $totalSum   += $itemSum;
            $totalCount += $item->count;

            // Productni yangilash
            DB::transaction(function () use ($product, $item) {
                $product->increment('total_product_count', $item->count);
                $product->increment('total_semi_finished_product_count', $item->count);
            });
        }

        $debtSum = max(0, $totalSum - $purchase->payed_sum);

        // Purchase ni yangilash
        $purchase->update([
            'count'    => $totalCount,
            'sum'      => $totalSum,
            'debt_sum' => $debtSum,
        ]);

        // Agentni yangilash
        $counterparty = $purchase->counterparty;
        DB::transaction(function () use ($counterparty, $totalSum, $purchase, $debtSum) {
            $counterparty->increment('purchased_products_total_sum', $totalSum);
            $counterparty->increment('purchased_products_total_count', $purchase->count);
            $counterparty->increment('payed_sum', $purchase->payed_sum);
            $counterparty->increment('debt_sum', $debtSum);
        });
    }

}
// app/Filament/Admin/Resources/CounterpartyResource/RelationManagers/PurchasesRelationManager.php
//class PurchasesRelationManager extends RelationManager
//{
//    protected static string $relationship = 'purchases';
//    protected static ?string $title = 'Xaridlar';
//
//    public function form(Form $form): Form
//    {
//        return $form->schema([
//            Forms\Components\Repeater::make('purchaseItems')
//                ->label('Mahsulotlar')
//                ->relationship('purchaseItems')
//                ->schema([
//                    Forms\Components\Select::make('product_id')
//                        ->label('Mahsulot')
//                        ->options(Product::pluck('model_name', 'id'))
//                        ->searchable()
//                        ->required()
//                        ->columnSpan(2),
//                    Forms\Components\TextInput::make('count')
//                        ->label('Miqdor')
//                        ->numeric()
//                        ->minValue(1)
//                        ->required()
//                        ->columnSpan(1),
//                ])
//                ->columns(3)
//                ->addActionLabel('+ Mahsulot qo\'shish')
//                ->minItems(1)
//                ->columnSpanFull(),
//
//            Forms\Components\TextInput::make('payed_sum')
//                ->label('To\'langan summa (so\'m)')
//                ->numeric()
//                ->minValue(0)
//                ->default(0)
//                ->required(),
//        ]);
//    }
//
//    public function table(Table $table): Table
//    {
//        return $table->columns([
//            Tables\Columns\TextColumn::make('created_at')
//                ->label('Sana')
//                ->dateTime('d.m.Y H:i'),
//            Tables\Columns\TextColumn::make('count')->label('Jami dona'),
//            Tables\Columns\TextColumn::make('sum')->label('Jami summa')->suffix(" so'm"),
//            Tables\Columns\TextColumn::make('payed_sum')->label('To\'langan')->suffix(" so'm"),
//            Tables\Columns\TextColumn::make('debt_sum')
//                ->label('Qarz')
//                ->suffix(" so'm")
//                ->color(fn($state) => $state > 0 ? 'danger' : 'success'),
//        ]);
//    }
//
//    // Xarid yaratilgandan KEYIN barcha hisob-kitoblarni qilish
//    protected function afterCreate(): void
//    {
//        $purchase = $this->getOwnerRecord()->purchases()->latest()->first();
//        $this->recalculatePurchase($purchase);
//    }
//
//    protected function afterSave(): void
//    {
//        $this->recalculatePurchase($this->getRelationship()->latest()->first());
//    }
//
//    private function recalculatePurchase(Purchase $purchase): void
//    {
//        $totalSum   = 0;
//        $totalCount = 0;
//
//        foreach ($purchase->purchaseItems as $item) {
//            $product = $item->product;
//            $itemSum = $product->semi_finished_product_price * $item->count;
//
//            $totalSum   += $itemSum;
//            $totalCount += $item->count;
//
//            // Productni yangilash
//            DB::transaction(function () use ($product, $item) {
//                $product->increment('total_product_count', $item->count);
//                $product->increment('total_semi_finished_product_count', $item->count);
//            });
//        }
//
//        $debtSum = max(0, $totalSum - $purchase->payed_sum);
//
//        // Purchase ni yangilash
//        $purchase->update([
//            'count'    => $totalCount,
//            'sum'      => $totalSum,
//            'debt_sum' => $debtSum,
//        ]);
//
//        // Agentni yangilash
//        $counterparty = $purchase->counterparty;
//        DB::transaction(function () use ($counterparty, $totalSum, $purchase, $debtSum) {
//            $counterparty->increment('purchased_products_total_sum', $totalSum);
//            $counterparty->increment('purchased_products_total_count', $purchase->count);
//            $counterparty->increment('payed_sum', $purchase->payed_sum);
//            $counterparty->increment('debt_sum', $debtSum);
//        });
//    }
//}
