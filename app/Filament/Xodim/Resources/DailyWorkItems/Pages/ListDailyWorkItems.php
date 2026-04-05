<?php

namespace App\Filament\Xodim\Resources\DailyWorkItems\Pages;

use App\Filament\Xodim\Resources\DailyWorkItems\DailyWorkItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyWorkItems extends ListRecords
{
    protected static string $resource = DailyWorkItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
