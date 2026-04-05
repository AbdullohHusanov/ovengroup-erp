<?php

namespace App\Filament\Xodim\Resources\DailyWorkItems;

use App\Filament\Xodim\Resources\DailyWorkItems\Pages\CreateDailyWorkItem;
use App\Filament\Xodim\Resources\DailyWorkItems\Pages\EditDailyWorkItem;
use App\Filament\Xodim\Resources\DailyWorkItems\Pages\ListDailyWorkItems;
use App\Filament\Xodim\Resources\DailyWorkItems\Schemas\DailyWorkItemForm;
use App\Filament\Xodim\Resources\DailyWorkItems\Tables\DailyWorkItemsTable;
use App\Models\DailyWorkItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DailyWorkItemResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = DailyWorkItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DailyWorkItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DailyWorkItemsTable::configure($table);
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
            'index' => ListDailyWorkItems::route('/'),
            'create' => CreateDailyWorkItem::route('/create'),
            'edit' => EditDailyWorkItem::route('/{record}/edit'),
        ];
    }
}
