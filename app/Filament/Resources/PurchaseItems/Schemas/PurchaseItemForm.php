<?php

namespace App\Filament\Resources\PurchaseItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PurchaseItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('purchase_id')
                    ->relationship('purchase', 'id')
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
