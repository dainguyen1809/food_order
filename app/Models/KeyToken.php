<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyToken extends Model
{
    protected $fillable = [
        'user_id',
        'publicKey',
        'privateKey',
        'refreshToken',
    ];

    protected $hidden = [
        'privateKey',
    ];

    protected $casts = [
        'refreshToken' => 'array',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
