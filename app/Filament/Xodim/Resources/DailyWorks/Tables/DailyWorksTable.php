<?php

namespace App\Filament\Xodim\Resources\DailyWorks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DailyWorksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Sana')
                    ->date('Y-m-d')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('count')
                    ->label('Maxsulot soni')
                    ->numeric()
                    ->suffix(' dona')
                    ->sortable(),
                TextColumn::make('sum')
                    ->label('Summa')
                    ->numeric()
                    ->suffix(' UZS')
                    ->sortable(),
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
