<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->components([
                    TextInput::make('model_name')
                        ->required(),
                    FileUpload::make('image')
                        ->downloadable()
                        ->image(),
                    Toggle::make('is_stamped')
                        ->required(),
                    TextInput::make('semi_finished_product_price')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->prefix('UZS'),
                    TextInput::make('welder_price')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->prefix('UZS'),
                    TextInput::make('inspector_price')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->prefix('UZS'),
                    TextInput::make('cleaner_price')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->prefix('UZS'),
                    TextInput::make('stamper_price')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->prefix('UZS'),
                    TextInput::make('selling_price')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->prefix('UZS'),
                ])
            ]);
    }
}
