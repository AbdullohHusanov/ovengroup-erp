<?php

namespace App\Filament\Resources\SalaryItems\Pages;

use App\Filament\Resources\SalaryItems\SalaryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalaryItems extends ListRecords
{
    protected static string $resource = SalaryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
