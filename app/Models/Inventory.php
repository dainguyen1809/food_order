<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Inventory extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'inven_shopID',
        'inven_productID',
        'inven_location',
        'inven_stock',
        'inven_reservation',
    ];


    protected $casts = [
        'inven_reservation' => 'array',
    ];

}
