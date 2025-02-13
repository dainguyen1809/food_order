<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Headers extends Enum
{
    const API_KEY = 'x-api-key';
    const CLIENT_ID = 'x-client-id';
    const AUTHORIZATION = 'authorization';
}
