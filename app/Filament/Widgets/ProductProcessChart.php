<?php
// app/Filament/Widgets/ProductProcessChart.php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class ProductProcessChart extends ChartWidget
{
    protected ?string $heading = 'Jarayonlardagi umumiy sonlar';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $stages = [
            'total_product_count'                   => 'Payvandlangan',
            'total_semi_finished_product_count'     => 'Payvandlangan',
            'welded_product_count'                  => 'Payvandlangan',
            'checked_product_count'                 => 'Tekshirilgan',
            'cleaned_product_count'                 => 'Tozalangan',
            'stamped_product_count'                 => 'Shtamplangan',
            'total_complated_product_count'         => "Tayyor",
            'total_sold_product_count'              => 'Sotilgan',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Soni',
                    'data'  => array_map(
                        fn($col) => Product::sum($col),
                        array_keys($stages)
                    ),
                    'backgroundColor' => [
                        '#378ADD', '#1D9E75', '#EF9F27',
                        '#D85A30', '#7F77DD', '#D4537E',
                    ],
                    'borderRadius' => 6,
                ],
            ],
            'labels' => array_values($stages),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
