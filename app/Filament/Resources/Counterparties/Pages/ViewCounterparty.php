<?php

namespace App\Filament\Resources\Counterparties\Pages;

use App\Filament\Resources\Counterparties\CounterpartyResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewCounterparty extends ViewRecord
{
    protected static string $resource = CounterpartyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_purchase')
                ->label("Xarid qo'shish")
                ->icon('heroicon-o-shopping-cart')
                ->color('success')
                ->url(CounterpartyResource::getUrl('create-purchase', ['record' => $this->record->id])),

            Action::make('edit')
                ->label('Tahrirlash')
                ->icon('heroicon-o-pencil')
                ->color('gray')
                ->url(CounterpartyResource::getUrl('edit', ['record' => $this->record->id])),

            Action::make('back')
                ->label('Orqaga')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(CounterpartyResource::getUrl('index')),
        ];
    }
}
