<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Counterparty extends Model
{
    protected $table = 'counterparties';
    protected $fillable = [
        'name',
        'phone',
        'purchased_products_total_sum',
        'purchased_products_total_count',
        'payed_sum',
        'debt_sum'
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'counterparty_id');
    }
}
