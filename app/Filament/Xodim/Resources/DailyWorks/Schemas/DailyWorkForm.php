<?php

namespace App\Filament\Xodim\Resources\DailyWorks\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DailyWorkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')->default(auth()->id()),
//                TextInput::make('user_id')
//                    ->required()
//                    ->numeric(),
                TextInput::make('count')
                    ->label('Maxsulot soni')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('sum')
                    ->label('Summa')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
