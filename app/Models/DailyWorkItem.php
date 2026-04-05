<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyWorkItem extends Model
{
    protected $table = 'daily_work_items';
    protected $fillable = [
        'daily_work_id',
        'product_id',
        'count',
    ];

    public function dailyWork(): BelongsTo
    {
        return $this->belongsTo(DailyWork::class, 'daily_work_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
