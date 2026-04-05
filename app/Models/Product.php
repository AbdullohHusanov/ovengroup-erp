<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'model_name',
        'image',
        'is_stamped',
        'total_product_count',
        'total_semi_finished_product_count',
        'total_complated_product_count',
        'total_sold_product_count',
        'welded_product_count',
        'checked_product_count',
        'cleaned_product_count',
        'stamped_product_count',
        'semi_finished_product_price',
        'welder_price',
        'inspector_price',
        'cleaner_price',
        'stamper_price',
        'selling_price'
    ];


}
