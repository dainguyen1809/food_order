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
        'product_slug',
        'product_price',
        'product_quantity',
        'product_rating',
        'isDraft',
        'isPublished',
        'product_type',
        'product_shop',
        'product_attributes',
        'product_variation',
    ];

    protected $attributes = [
        'isDraft' => true,
        'isPublished' => false,
        'product_rating' => 4.5,
    ];

    protected $casts = [
        'isDraft' => 'boolean',
        'isPublished' => 'boolean',
        'product_attributes' => 'array',
        'product_variation' => 'array',
    ];


    public function users()
    {
        return $this->belongsTo(User::class, 'product_shop', 'id');
    }
}
