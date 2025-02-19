<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'product_name',
        'product_thumb',
        'product_description',
        'product_price',
        'product_quantity',
        'product_type',
        'product_shop',
        'product_attributes',
    ];

    protected $casts = [
        'product_attributes' => 'array',
    ];
}
