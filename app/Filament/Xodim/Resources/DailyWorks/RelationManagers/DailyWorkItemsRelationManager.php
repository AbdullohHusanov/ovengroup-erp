<?php

namespace App\Filament\Xodim\Resources\DailyWorks\RelationManagers;

use App\Models\Product;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DailyWorkItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        $user = Auth::user();
        $role = $user->role;

        // Rolga qarab qaysi productlarni ko'rsatish va max count
        // Har bir rol faqat o'zi ishlashi mumkin bo'lgan productlarni ko'radi
        $products = $this->getAvailableProducts($role);

        return $schema
            ->components([
//                TextInput::make('daily_work_id')
//                    ->required()
//                    ->maxLength(255),

                    Select::make('product_id')
                        ->label('Mahsulot (Model)')
                        ->options($products)
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) use ($role) {
                            if (!$state) return;

                            $product  = Product::find($state);
                            $maxCount = $this->getMaxCountForRole($product, $role);

                            $set('max_count', $maxCount);
                            $set('count', 1);
                        }),

                TextInput::make('count')
                    ->numeric()
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('daily_work_id')
            ->columns([
//                TextColumn::make('daily_work_id')
//                    ->searchable(),

                TextColumn::make('product.model_name')
                    ->searchable(),

                TextColumn::make('count')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Rolga qarab qaysi productlar ko'rsatiladi.
     * Faqat o'zi ishlashi uchun tayyor (oldingi bosqich tugagan) productlar.
     */
    private function getAvailableProducts(string $role): array
    {
        return match ($role) {
            // Payvandchi: semi_finished ombordan olib ishlaydi
            'welder' => Product::where('total_semi_finished_product_count', '>', 0)
                ->pluck('model_name', 'id')
                ->toArray(),

            // Tekshiruvchi: payvandlangan productlarni tekshiradi
            'inspector' => Product::where('welded_product_count', '>', 0)
                ->pluck('model_name', 'id')
                ->toArray(),

            // Tozalovchi: tekshirilgan productlarni tozalaydi
            'cleaner' => Product::where('checked_product_count', '>', 0)
                ->pluck('model_name', 'id')
                ->toArray(),

            // Shtamplovchi: tozalangan productlardan faqat is_stamped=true bo'lganlar
            'stamper' => Product::where('cleaned_product_count', '>', 0)
                ->where('is_stamped', true)
                ->pluck('model_name', 'id')
                ->toArray(),

            default => [],
        };
    }
}
