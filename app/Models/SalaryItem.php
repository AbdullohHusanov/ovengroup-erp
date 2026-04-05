<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryItem extends Model
{
    protected $table = 'salary_items';
    protected $fillable = [
        'salary_id',
        'amount',
        'type'
    ];

    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class, 'salary_id');
    }
}
