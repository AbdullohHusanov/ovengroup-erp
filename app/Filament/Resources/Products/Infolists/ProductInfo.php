<?php

namespace App\Filament\Resources\Products\Infolists;

use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

;

class ProductInfo
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Mahsulot ma\'lumotlari')
//                    ->columns(3)
                    ->inlineLabel()
                    ->schema([
                        TextEntry::make('model_name')
                            ->label('Model nomi'),

                        ImageEntry::make('image')
                            ->label('Rasm'),

//                        ImageEntry::make('image')
//                            ->label('Rasm')
//                            ->height(200)
//                            ->extraImgAttributes(fn ($record) => [
//                                'onclick' => "window.open('".Storage::disk('')->url($record->image)."', '_blank')",
//                                'class' => 'cursor-pointer hover:opacity-80 transition rounded-lg',
//                                'title' => 'Kattalashtirish uchun bosing'
//                            ])
//                            ->hintActions([
//                                Action::make('download')
//                                    ->label('Yuklab olish')
//                                    ->icon('heroicon-o-arrow-down-tray')
//                                    ->action(function ($record) {
//                                        return response()->download(Storage::path($record->image));
//                                    }),
//                            ]),

                        IconEntry::make('is_stamped')
                            ->label('Shtamplangan')
                            ->boolean(),
                    ]),

                Section::make('Narxlar')
//                    ->columns(3)
                    ->inlineLabel()
                    ->schema([
                        TextEntry::make('semi_finished_product_price')
                            ->label('Yarim tayyor mahsulot narxi')
                            ->numeric()
                            ->suffix(' UZS'),

                        TextEntry::make('welder_price')
                            ->label('Payvandchi narxi')
                            ->numeric()
                            ->suffix(' UZS'),

                        TextEntry::make('inspector_price')
                            ->label('Nazoratchi narxi')
                            ->numeric()
                            ->suffix(' UZS'),

                        TextEntry::make('cleaner_price')
                            ->label('Tozalovchi narxi')
                            ->numeric()
                            ->suffix(' UZS'),

                        TextEntry::make('stamper_price')
                            ->label('Shtamplovchi narxi')
                            ->numeric()
                            ->suffix(' UZS'),

                        TextEntry::make('selling_price')
                            ->label('Sotuv narxi')
                            ->numeric()
                            ->suffix(' UZS'),
                    ]),

                Section::make('Mahsulot soni')
//                    ->columns(3)
                    ->inlineLabel()
                    ->schema([
                        TextEntry::make('total_product_count')
                            ->label('Jami mahsulot soni')
                            ->numeric()
                            ->suffix(' dona'),

                        TextEntry::make('total_semi_finished_product_count')
                            ->label('Jami yarim tayyor mahsulot soni')
                            ->numeric()
                            ->suffix(' dona'),

                        TextEntry::make('welded_product_count')
                            ->label('Payvandlangan mahsulot soni')
                            ->numeric()
                            ->suffix(' dona'),

                        TextEntry::make('checked_product_count')
                            ->label('Tekshirilgan mahsulot soni')
                            ->numeric()
                            ->suffix(' dona'),

                        TextEntry::make('cleaned_product_count')
                            ->label('Tozalangan mahsulot soni')
                            ->numeric()
                            ->suffix(' dona'),

                        TextEntry::make('stamped_product_count')
                            ->label('Shtamplangan mahsulot soni')
                            ->numeric()
                            ->suffix(' dona'),

                        TextEntry::make('total_complated_product_count')
                            ->label('Jami tayyor mahsulot soni')
                            ->numeric()
                            ->suffix(' dona'),

                        TextEntry::make('total_sold_product_count')
                            ->label('Sotilgan mahsulot soni')
                            ->numeric()
                            ->suffix(' dona'),

                    ]),

            ]);
    }
}
