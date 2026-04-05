<?php

namespace App\Filament\Xodim\Resources\DailyWorkItems\Pages;

use App\Filament\Xodim\Resources\DailyWorkItems\DailyWorkItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyWorkItem extends EditRecord
{
    protected static string $resource = DailyWorkItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
