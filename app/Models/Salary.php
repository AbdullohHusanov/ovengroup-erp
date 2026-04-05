<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salary extends Model
{
    protected $table = 'salaries';
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'product_count',
        'sum',
        'debt_sum',
        'payed_sum',
        'total_sum',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalaryItem::class, 'salary_id');
    }
}
