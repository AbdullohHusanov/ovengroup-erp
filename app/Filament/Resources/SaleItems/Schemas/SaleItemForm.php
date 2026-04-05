<?php

namespace App\Filament\Resources\SaleItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SaleItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sale_id')
                    ->relationship('sale', 'id')
                    ->required(),
                Select::make('product_id')
                    ->relationship('product', 'id')
                    ->required(),
                TextInput::make('count')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
