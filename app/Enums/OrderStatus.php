<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatus extends Enum
{
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const SHIPPED = 'shipped';
    const CANCELLED = 'cancelled';
    const DELIVERED = 'delivered';
}
