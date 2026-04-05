<?php

namespace App\Filament\Resources\Purchases\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('counterparty_id')
                    ->required()
                    ->numeric(),
                TextInput::make('count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('sum')
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
