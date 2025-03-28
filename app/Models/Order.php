<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory, Notifiable;

    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'modified_on';

    protected $fillable = [
        'order_checkout',
        'order_payment',
        'order_shipping',
        'order_status',
        'order_tracking',
        'order_products',
        'order_status',
    ];

    protected $casts = [
        'order_checkout' => 'array',
        'order_payment' => 'array',
        'order_shipping' => 'array',
        'order_products' => 'array',
    ];
}
