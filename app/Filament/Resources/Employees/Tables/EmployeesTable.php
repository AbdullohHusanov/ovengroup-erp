<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ism')
                    ->searchable(),
                TextColumn::make('surname')
                    ->label('Familiya')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                TextColumn::make('currentSalary.sum')
                    ->numeric()
                    ->suffix(' UZS')
                    ->label('Oylik to\'lov'),
                TextColumn::make('role')
                    ->label('Lavozimi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin'          => 'Admin',
                        'welder'         => 'Payvandchi',
                        'inspector'      => 'Inspektor',
                        'cleaner'        => 'Tozalovchi',
                        'stamper'        => 'Shtamplovchi',
                        'warehousekeeper'=> 'Omborchi',
                        'accountant'     => 'Hisobchi',
                        default          => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin'          => 'danger',
                        'inspector'      => 'warning',
                        'accountant'     => 'info',
                        default          => 'gray',
                    }),
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
