<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ProductTypes extends Enum
{
    const FOOD = 'food';
    const DRINK = 'drink';
    const DESSERT = 'dessert';

}
