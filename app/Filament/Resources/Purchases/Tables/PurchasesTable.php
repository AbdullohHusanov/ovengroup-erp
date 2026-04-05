<?php

namespace App\Filament\Resources\Purchases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('counterparty.name')
                    ->label('Agent nomi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('count')
                    ->label('Maxsulot soni')
                    ->suffix(' dona')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sum')
                    ->label('Maxsulot summasi')
                    ->suffix(' UZS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payed_sum')
                    ->label('To\'langan summa')
                    ->suffix(' UZS')
                    ->color('success')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('debt_sum')
                    ->label('Qarzdorlik')
                    ->suffix(' UZS')
                    ->color('danger')
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
            ]);
    }
}
