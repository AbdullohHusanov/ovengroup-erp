<?php

//namespace App\Filament\Resources\Counterparties;
//
//use App\Filament\Resources\Counterparties\Pages\CreateCounterparty;
//use App\Filament\Resources\Counterparties\Pages\EditCounterparty;
//use App\Filament\Resources\Counterparties\Pages\ListCounterparties;
//use App\Filament\Resources\Counterparties\Schemas\CounterpartyForm;
//use App\Filament\Resources\Counterparties\Tables\CounterpartiesTable;
//use App\Models\Counterparty;
//use BackedEnum;
//use Filament\Forms\Components\TextInput;
//use Filament\Resources\Resource;
//use Filament\Schemas\Schema;
//use Filament\Support\Icons\Heroicon;
//use Filament\Tables\Columns\TextColumn;
//use Filament\Tables\Table;
//
//class CounterpartyResource extends Resource
//{
//    protected static ?string $model = Counterparty::class;
//
//    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;
//    protected static ?string $navigationLabel = 'Agent';
//    protected static ?string $pluralLabel = 'Agent';
//
//    public static function form(Schema $schema): Schema
//    {
//        return CounterpartyForm::configure($schema);
//    }
//
//    public static function table(Table $table): Table
//    {
//        return CounterpartiesTable::configure($table);
//    }
//
//    public static function getRelations(): array
//    {
//        return [
//            RelationManagers\PurchasesRelationManager::class,
//        ];
//    }
//
//    public static function getPages(): array
//    {
//        return [
//            'index' => ListCounterparties::route('/'),
//            'create' => CreateCounterparty::route('/create'),
//            'edit' => EditCounterparty::route('/{record}/edit'),
//        ];
//    }
//}
// app/Filament/Admin/Resources/CounterpartyResource.php
//class CounterpartyResource extends Resource
//{
//    protected static ?string $model = Counterparty::class;
//    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
////    protected static ?string $navigationIcon = 'heroicon-o-building-office';
//    protected static ?string $navigationLabel = 'Agentlar';
//    protected static ?string $modelLabel = 'Agent';
//    protected static ?string $pluralModelLabel = 'Agentlar';
//
//    public static function form(Schema $schema): Schema
//    {
//        return $schema->components([
//            TextInput::make('name')
//                ->label('Nomi')
//                ->maxLength(60)
//                ->required(),
//            TextInput::make('phone')
//                ->label('Telefon')
//                ->maxLength(12)
//                ->tel()
//                ->required(),
//        ]);
//    }
//
//    public static function table(Table $table): Table
//    {
//        return $table->columns([
//            TextColumn::make('name')->label('Nomi')->searchable(),
//            TextColumn::make('phone')->label('Telefon'),
//            TextColumn::make('purchased_products_total_sum')
//                ->label('Jami xarid (so\'m)')
//                ->numeric()
//                ->suffix(" so'm"),
//            TextColumn::make('payed_sum')
//                ->label('To\'langan')
//                ->numeric()
//                ->suffix(" so'm"),
//            TextColumn::make('debt_sum')
//                ->label('Qarz')
//                ->numeric()
//                ->suffix(" so'm")
//                ->color(fn($state) => $state > 0 ? 'danger' : 'success'),
//        ]);
//    }
//
//    // Agent sahifasida uning xaridlarini ko'rish uchun
//    public static function getRelations(): array
//    {
//        return [
//            RelationManagers\PurchasesRelationManager::class,
//        ];
//    }
//}


namespace App\Filament\Resources\Counterparties;

use App\Filament\Pages\CreatePurchase;
use App\Filament\Resources\Counterparties\Schemas\CounterpartyForm;
use App\Filament\Resources\Counterparties\Tables\CounterpartiesTable;
use App\Models\Counterparty;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class CounterpartyResource extends Resource
{
    protected static ?string $model = Counterparty::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;
    protected static ?string $navigationLabel = 'Kontragentlar';

    protected static ?string $modelLabel = 'Kontragent';

    protected static ?string $pluralModelLabel = 'Kontragentlar';

    public static function form(Schema $schema): Schema
    {
        return CounterpartyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')->label('Agent nomi'),
                TextEntry::make('phone')->label('Telefon'),
                TextEntry::make('purchased_products_total_sum')->label('Maxsulot summasi')->suffix(' UZS'),
                TextEntry::make('purchased_products_total_count')->label('Maxsulot soni')->suffix(' dona'),
                TextEntry::make('payed_sum')->label('To\'langan summa')->suffix(' UZS')->color('success'),
                TextEntry::make('debt_sum')->label('Qarzdorlik')->suffix(' UZS')->color('danger'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')
                    ->label('Agent nomi')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                TextColumn::make('purchased_products_total_count')
                    ->label('Maxsulot soni')
                    ->numeric()
                    ->suffix(' dona')
                    ->sortable(),
                TextColumn::make('payed_sum')
                    ->label('To\'langan summa')
                    ->color('success')
                    ->suffix(' UZS')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('debt_sum')
                    ->label('Qarz summa')
                    ->color('danger')
                    ->suffix(' UZS')
                    ->numeric()
                    ->sortable(),
            ])
            ->actions([
                \Filament\Actions\Action::make('create_purchase')
                    ->label('Xarid qo\'shish')
                    ->icon('heroicon-o-shopping-cart')
                    ->color('success')
                    ->url(fn(Counterparty $record) => static::getUrl('create-purchase', ['record' => $record->id])),
            ])
            ->recordUrl(fn (Counterparty $record) => static::getUrl('view', ['record' => $record->id]))
            ->actionsColumnLabel('Amallar');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCounterparties::route('/'),
            'create' => Pages\CreateCounterparty::route('/create'),
            'view' => Pages\ViewCounterparty::route('/{record}'),
            'edit' => Pages\EditCounterparty::route('/{record}/edit'),
            'create-purchase' => Pages\CreatePurchasePage::route('/{record}/purchases/create'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PurchasesRelationManager::class,
        ];
    }

}
