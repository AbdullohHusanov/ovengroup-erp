<?php

namespace App\Filament\Xodim\Resources\DailyWorks\Pages;

use App\Filament\Xodim\Resources\DailyWorks\DailyWorkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyWork extends EditRecord
{
    protected static string $resource = DailyWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
