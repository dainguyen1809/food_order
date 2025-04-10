<?php

namespace App\Services\Contracts;

interface NotificationServiceInterface
{
    public static function pushNotifyToSystem($payload);
    public static function getListNotifyByUser($userId, $type);
}
