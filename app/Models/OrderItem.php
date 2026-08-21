<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',
        'product_name',
        'quantity',
        'price',
        'attributes'
    ];

    protected $casts = [
        'attributes' => 'array',
    ];
}
