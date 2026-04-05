<?php

namespace App\Filament\Xodim\Resources\DailyWorkItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DailyWorkItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('daily_work_id')
                    ->relationship('dailyWork', 'id')
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
