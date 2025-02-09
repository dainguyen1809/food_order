<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'status',
        'permissions'
    ];

    protected $hidden = [
        'key'
    ];

    protected $casts = [
        'permissions' => 'array'
    ];

}
