<?php

namespace App\Filament\Xodim\Resources\DailyWorks\RelationManagers;

use App\Filament\Xodim\Resources\DailyWorks\DailyWorkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class DailyWorksRelationManager extends RelationManager
{
    protected static string $relationship = 'dailyWorks';

    protected static ?string $relatedResource = DailyWorkResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
