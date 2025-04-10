<?php

namespace App\Models\Repositories;

use App\Models\Notification;

class NotificationRepository
{
    public static function createNewNotify($data, $notify_content)
    {
        return Notification::create([
            'noti_type' => $data['notify_type'],
            'noti_senderId' => $data['notify_senderId'],
            'noti_receiverId' => $data['notify_receiverId'],
            'noti_content' => $notify_content,
            'noti_options' => $data['notify_options'],
        ]);
    }

    public static function getListNotifyByUser($userId, $type)
    {
        return Notification::where('noti_receiverId', $userId)
            ->where('noti_type', $type)
            ->select([
                'noti_type',
                'noti_senderId',
                'noti_receiverId',
                'noti_content',
                'noti_options',
            ])->get();
    }
}
