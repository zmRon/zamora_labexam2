<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price_per_kg', 'stock_quantity', 'description',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
