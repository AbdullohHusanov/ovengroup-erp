<?php

namespace App\Filament\Resources\SalaryItems\Pages;

use App\Filament\Resources\SalaryItems\SalaryItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSalaryItem extends EditRecord
{
    protected static string $resource = SalaryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
