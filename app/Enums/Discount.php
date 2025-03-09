<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Discount extends Enum
{
    const FIXED_AMOUNT = 'fixed_amount';
    const PERCENTAGE = 'percentage';
    const ALL = 'all';
    const SPECIFIC = 'specific';
}
