<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


class Notification extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'noti_type',
        'noti_senderId',
        'noti_receiverId',
        'noti_content',
        'noti_options',
    ];

    protected $casts = [
        'noti_options' => 'array'
    ];
    public function sender()
    {
        return $this->belongsTo(User::class);
    }
}
