<?php
// app/Filament/Widgets/ProductBreakdownChart.php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class ProductBreakdownChart extends ChartWidget
{
    protected ?string $heading = 'Product kesimida jarayonlar';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $products = Product::all();

        $colors = [
            '#378ADD', '#1D9E75', '#EF9F27',
            '#D85A30', '#7F77DD', '#D4537E',
        ];

        $stages = [
            'welded_product_count'              => 'Payvandlangan',
            'checked_product_count'             => 'Tekshirilgan',
            'cleaned_product_count'             => 'Tozalangan',
            'stamped_product_count'             => 'Shtamplangan',
            'total_semi_finished_product_count' => "Yarim tayyor",
            'total_sold_product_count'          => 'Sotilgan',
        ];

        $datasets = [];
        $i = 0;

        foreach ($stages as $col => $label) {
            $datasets[] = [
                'label'           => $label,
                'data'            => $products->pluck($col)->toArray(),
                'backgroundColor' => $colors[$i++],
                'borderRadius'    => 4,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels'   => $products->pluck('model_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
