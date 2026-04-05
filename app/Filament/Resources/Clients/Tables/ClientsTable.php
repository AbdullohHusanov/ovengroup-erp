<?php

namespace App\Filament\Resources\Clients\Tables;

use App\Filament\Resources\Clients\ClientResource;
use App\Models\Client;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')
                    ->label('Mijoz nomi')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                TextColumn::make('sold_products_total_count')
                    ->label('Maxsulot soni')
                    ->numeric()
                    ->suffix(' dona')
                    ->sortable(),
                TextColumn::make('payed_sum')
                    ->label('To\'langan summa')
                    ->color('success')
                    ->suffix(' UZS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('debt_sum')
                    ->label('Qarz summa')
                    ->color('danger')
                    ->suffix(' UZS')
                    ->numeric()
                    ->sortable(),


//                TextColumn::make('name')
//                    ->label('Mijoz nomi')
//                    ->searchable(),
//                TextColumn::make('phone')
//                    ->label('Telefon')
//                    ->searchable(),
//                TextColumn::make('sold_products_total_sum')
//                    ->label('Sotilgan maxsulot summasi')
//                    ->numeric()
//                    ->sortable(),
//                TextColumn::make('sold_products_total_count')
//                    ->label('Sotilgan maxsulot soni')
//                    ->numeric()
//                    ->sortable(),
//                TextColumn::make('payed_sum')
//                    ->label('To\'langan summa')
//                    ->numeric()
//                    ->sortable(),
//                TextColumn::make('debt_sum')
//                    ->label('Qarz summa')
//                    ->numeric()
//                    ->sortable(),
//                TextColumn::make('created_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
//                TextColumn::make('updated_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\Action::make('create_sale')
                    ->label('Sotuv qo\'shish')
                    ->icon('heroicon-o-shopping-cart')
                    ->color('success')
                    ->url(fn(Client $record) => ClientResource::getUrl('create-sale', ['record' => $record->id])),
            ])
            ->recordUrl(fn (Client $record) => ClientResource::getUrl('view', ['record' => $record->id]))
            ->actionsColumnLabel('Amallar');
    }
}
