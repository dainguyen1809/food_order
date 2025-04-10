<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class NotifyTypes extends Enum
{
    const ORDER_001 = 'ORDER-001';
    const ORDER_002 = 'ORDER-002';
    const SHOP_001 = 'SHOP-001';
    const PROMOTION_001 = 'PROMOTION-001';
}
