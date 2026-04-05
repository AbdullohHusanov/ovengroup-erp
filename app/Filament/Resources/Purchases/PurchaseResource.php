<?php

namespace App\Filament\Resources\Purchases;

use App\Filament\Resources\Purchases\Pages\ListPurchases;
use App\Filament\Resources\Purchases\RelationManagers\PurchaseItemsRelationManager;
use App\Filament\Resources\Purchases\Schemas\PurchaseForm;
use App\Filament\Resources\Purchases\Tables\PurchasesTable;
use App\Models\Purchase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PurchaseResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static ?string $model = Purchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?string $navigationLabel = 'Xarid';
    protected static ?string $modelLabel = 'Xarid';
    protected static ?string $pluralLabel = 'Xarid';

    public static function form(Schema $schema): Schema
    {
        return PurchaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PurchaseItemsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchases::route('/'),
//            'create' => CreatePurchase::route('/create'),
//            'edit' => EditPurchase::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canView(Model $record): bool
    {
        return false;
    }
}
