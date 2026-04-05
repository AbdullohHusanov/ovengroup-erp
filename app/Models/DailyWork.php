<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyWork extends Model
{
    protected $table = 'daily_works';
    protected $fillable = [
        'user_id',
        'count',
        'sum',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(DailyWorkItem::class, 'daily_work_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
