<?php

namespace App\Filament\Resources\SalaryItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SalaryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('salary_id')
                    ->relationship('salary', 'id')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('type')
                    ->options(['salary' => 'Salary', 'debt' => 'Debt'])
                    ->required(),
            ]);
    }
}
