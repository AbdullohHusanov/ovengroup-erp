<?php
// app/Filament/Pages/Dashboard.php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProductProcessOverview;
use App\Filament\Widgets\ProductProcessChart;
use App\Filament\Widgets\ProductBreakdownChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            ProductProcessOverview::class,
            ProductProcessChart::class,
//            ProductBreakdownChart::class,
        ];
    }
}
