<?php

namespace App\Filament\Xodim\Resources\DailyWorks;

use App\Filament\Xodim\Resources\DailyWorks\Pages\CreateDailyWork;
use App\Filament\Xodim\Resources\DailyWorks\Pages\CreateDailyWorkPage;
use App\Filament\Xodim\Resources\DailyWorks\Pages\EditDailyWork;
use App\Filament\Xodim\Resources\DailyWorks\Pages\ListDailyWorks;
use App\Filament\Xodim\Resources\DailyWorks\RelationManagers\DailyWorkItemsRelationManager;
use App\Filament\Xodim\Resources\DailyWorks\Schemas\DailyWorkForm;
use App\Filament\Xodim\Resources\DailyWorks\Tables\DailyWorksTable;
use App\Models\DailyWork;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DailyWorkResource extends Resource
{
    protected static ?string $model = DailyWork::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $modelLabel = 'Kunlik hisob';
    protected static ?string $navigationLabel = 'Kunlik hisoblar';
    protected static ?string $pluralLabel = 'Kunlik hisoblar';

    public static function form(Schema $schema): Schema
    {
        return DailyWorkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DailyWorksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DailyWorkItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDailyWorks::route('/'),
            'create' => CreateDailyWorkPage::route('/create'),
            'edit' => EditDailyWork::route('/{record}/edit'),
        ];
    }
}
