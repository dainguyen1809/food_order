<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RoleShop extends Enum
{
    const SUPER_ADMIN = '00000';
    const SHOP = '11111';
    const WRITER = '22222';
    const EDITOR = '33333';
}
