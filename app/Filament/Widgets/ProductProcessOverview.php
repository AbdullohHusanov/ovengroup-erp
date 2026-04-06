<?php
// app/Filament/Widgets/ProductProcessOverview.php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductProcessOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Umumiy', Product::sum('total_product_count'))
                ->icon('heroicon-o-fire')
                ->color('info'),

            Stat::make('Xomashyo', Product::sum('total_semi_finished_product_count'))
                ->icon('heroicon-o-fire')
                ->color('info'),

            Stat::make('Payvandlangan', Product::sum('welded_product_count'))
                ->icon('heroicon-o-fire')
                ->color('info'),

            Stat::make('Tekshirilgan', Product::sum('checked_product_count'))
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Tozalangan', Product::sum('cleaned_product_count'))
                ->icon('heroicon-o-sparkles')
                ->color('warning'),

            Stat::make('Shtamplangan', Product::sum('stamped_product_count'))
                ->icon('heroicon-o-tag')
                ->color('danger'),

            Stat::make("Tayyor", Product::sum('total_complated_product_count'))
                ->icon('heroicon-o-cube')
                ->color('gray'),

            Stat::make('Sotilgan', Product::sum('total_sold_product_count'))
                ->icon('heroicon-o-shopping-cart')
                ->color('success'),
        ];
    }
}
