<?php

namespace App\Filament\Resources\SalaryItems\Pages;

use App\Filament\Resources\SalaryItems\SalaryItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSalaryItem extends CreateRecord
{
    protected static string $resource = SalaryItemResource::class;
}
