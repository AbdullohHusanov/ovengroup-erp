<?php

namespace App\Filament\Resources\Clients\RelationManagers;

use App\Filament\Resources\Sales\SaleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class SalesRelationManager extends RelationManager
{
    protected static string $relationship = 'sales';

    protected static ?string $relatedResource = SaleResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
