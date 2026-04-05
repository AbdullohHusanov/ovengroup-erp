<?php

namespace App\Filament\Resources\Clients;

use App\Filament\Resources\Clients\Pages\CreateClient;
use App\Filament\Resources\Clients\Pages\CreateSalePage;
use App\Filament\Resources\Clients\Pages\EditClient;
use App\Filament\Resources\Clients\Pages\ListClients;
use App\Filament\Resources\Clients\Pages\ViewClient;
use App\Filament\Resources\Clients\RelationManagers\SalesRelationManager;
use App\Filament\Resources\Clients\Schemas\ClientForm;
use App\Filament\Resources\Clients\Tables\ClientsTable;
use App\Models\Client;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static ?string $navigationLabel = 'Mijozlar';
    protected static ?string $pluralLabel = 'Mijozlar';
    protected static ?string $modelLabel = 'Mijoz';

    public static function form(Schema $schema): Schema
    {
        return ClientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')->label('Mijoz nomi'),
                TextEntry::make('phone')->label('Telefon'),
                TextEntry::make('sold_products_total_sum')->label('Maxsulot summasi')->suffix(' UZS'),
                TextEntry::make('sold_products_total_count')->label('Maxsulot soni')->suffix(' dona'),
                TextEntry::make('payed_sum')->label('To\'langan summa')->suffix(' UZS')->color('success'),
                TextEntry::make('debt_sum')->label('Qarzdorlik')->suffix(' UZS')->color('danger'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SalesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClients::route('/'),
            'create' => CreateClient::route('/create'),
            'view' => ViewClient::route('/{record}'),
            'edit' => EditClient::route('/{record}/edit'),
            'create-sale' => CreateSalePage::route('/{record}/sale/create'),
        ];
    }
}
