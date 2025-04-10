<?php

namespace App\Services\Notification;

use App\Enums\NotifyTypes;
use App\Models\Repositories\NotificationRepository;
use App\Services\Contracts\NotificationServiceInterface;

class NotificationService implements NotificationServiceInterface
{
    public static function pushNotifyToSystem($payload)
    {
        $notify_content = '';

        if ($payload['notify_type'] === NotifyTypes::SHOP_001)
            $notify_content = "SHOP_NAME:: Just created a new product: PRODUCT_NAME";
        else if ($payload['notify_type'] === NotifyTypes::PROMOTION_001)
            $notify_content = "SHOP_NAME:: Just created a new voucher: VOUCHER_NAME";

        return NotificationRepository::createNewNotify($payload, $notify_content);
    }

    public static function getListNotifyByUser($userId = 1, $type)
    {
        if ($type !== 'ALL')
            return NotificationRepository::getListNotifyByUser($userId, $type);
    }
}
