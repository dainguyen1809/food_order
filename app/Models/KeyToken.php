<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyToken extends Model
{
    protected $fillable = [
        'user_id',
        'public_key',
        'private_key',
        'refresh_tokens_used',
        'refresh_token'
    ];

    protected $hidden = [
        'private_key',
    ];

    protected $casts = [
        'refresh_tokens_used' => 'array',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
