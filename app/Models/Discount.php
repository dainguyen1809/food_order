<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Discount extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'discount_name',
        'discount_description',
        'discount_code',
        'discount_type',
        'discount_value',
        'discount_start_date',
        'discount_end_date',
        'discount_max_uses',
        'discount_uses_count',
        'discount_users_used',
        'discount_min_orders_value',
        'discount_max_uses_per_user',
        'discount_max_value',
        'discount_shop',
        'discount_is_active',
        'discount_applies_to',
        'discount_product_ids',
    ];

    protected $attributes = [
        'discount_is_active' => true,
    ];

    protected $casts = [
        'discount_is_active' => 'boolean',
        'discount_users_used' => 'array',
        'discount_product_ids' => 'array'
    ];

    public function Shop()
    {
        return $this->belongsTo(User::class);
    }
}
