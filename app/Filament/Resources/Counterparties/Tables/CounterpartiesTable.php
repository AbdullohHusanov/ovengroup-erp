<?php

namespace App\Filament\Resources\Counterparties\Tables;

use App\Models\Counterparty;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CounterpartiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Agent nomi')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                TextColumn::make('purchased_products_total_sum')
                    ->label('Xarid qilingan maxsulot narxi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('purchased_products_total_count')
                    ->label('Xarid qilingan maxsulot soni')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payed_sum')
                    ->label('To\'langan summa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('debt_sum')
                    ->label('Qarz summa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->actions([
                \Filament\Actions\Action::make('create_purchase')
                    ->label('Xarid qo\'shish')
                    ->icon('heroicon-o-shopping-cart')
                    ->color('success')
                    ->url(fn(Counterparty $record) => static::getUrl('create-purchase', ['record' => $record->id])),
            ]);

    }
}
