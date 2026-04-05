<?php

namespace App\Filament\Xodim\Resources\DailyWorks\Pages;

use App\Filament\Xodim\Resources\DailyWorks\DailyWorkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyWorks extends ListRecords
{
    protected static string $resource = DailyWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
