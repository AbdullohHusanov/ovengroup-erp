<?php

namespace App\Filament\Resources\Counterparties\Pages;

use App\Filament\Resources\Counterparties\CounterpartyResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateCounterparty extends CreateRecord
{
    protected static string $resource = CounterpartyResource::class;

    public function getBreadcrumb(): string
    {
        return 'Qo\'shish';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Yangi ';//parent::getTitle();
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Saqlash');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()->label('Bekor qilish');
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()->hidden();
    }
}
