<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = [
        'name',
        'phone',
        'sold_products_total_sum',
        'sold_products_total_count',
        'payed_sum',
        'debt_sum'
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'client_id');
    }
}
