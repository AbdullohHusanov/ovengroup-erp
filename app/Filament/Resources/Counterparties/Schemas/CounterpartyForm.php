<?php

namespace App\Filament\Resources\Counterparties\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CounterpartyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Agent nomi')
                    ->required(),
                TextInput::make('phone')
                    ->label('Telefon')
                    ->tel()
                    ->required(),
                TextInput::make('purchased_products_total_sum')
                    ->label('Maxsulot summasi')
                    ->required()
                    ->numeric()
                    ->visibleOn('edit'),

                TextInput::make('purchased_products_total_count')
                    ->label('Maxsulot soni')
                    ->required()
                    ->numeric()
                    ->visibleOn('edit'),

                TextInput::make('payed_sum')
                    ->label('To\'langan summa')
                    ->required()
                    ->numeric()
                    ->visibleOn('edit'),

                TextInput::make('debt_sum')
                    ->label('Qarzdorlik')
                    ->required()
                    ->numeric()
                    ->visibleOn('edit'),

            ]);
    }

}
