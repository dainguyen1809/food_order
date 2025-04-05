<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Comment extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'comment_productId',
        'comment_userId',
        'comment_content',
        'comment_left',
        'comment_right',
        'comment_parentId',
    ];
}
