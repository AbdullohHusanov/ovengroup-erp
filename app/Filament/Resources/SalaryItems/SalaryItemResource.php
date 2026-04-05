<?php

namespace App\Filament\Resources\SalaryItems;

use App\Filament\Resources\SalaryItems\Pages\CreateSalaryItem;
use App\Filament\Resources\SalaryItems\Pages\EditSalaryItem;
use App\Filament\Resources\SalaryItems\Pages\ListSalaryItems;
use App\Filament\Resources\SalaryItems\Schemas\SalaryItemForm;
use App\Filament\Resources\SalaryItems\Tables\SalaryItemsTable;
use App\Models\SalaryItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalaryItemResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static ?string $model = SalaryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SalaryItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalaryItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSalaryItems::route('/'),
            'create' => CreateSalaryItem::route('/create'),
            'edit' => EditSalaryItem::route('/{record}/edit'),
        ];
    }
}
