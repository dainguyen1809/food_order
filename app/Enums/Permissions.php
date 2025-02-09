<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Permissions extends Enum
{
    const SUPER_ADMIN = 11111;
    const EDITOR = 22222;
    const VIEWER = 33333;
}
