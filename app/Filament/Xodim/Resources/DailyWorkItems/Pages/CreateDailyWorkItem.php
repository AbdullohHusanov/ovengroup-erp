<?php

namespace App\Filament\Xodim\Resources\DailyWorkItems\Pages;

use App\Filament\Xodim\Resources\DailyWorkItems\DailyWorkItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyWorkItem extends CreateRecord
{
    protected static string $resource = DailyWorkItemResource::class;
}
