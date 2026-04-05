<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->required(),
                TextInput::make('count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('sum')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('discount_sum')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('payed_sum')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('debt_sum')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
