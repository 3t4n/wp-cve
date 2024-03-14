<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Enums;

use CoffeeCode\MyCLabs\Enum\Enum;

/**
 * @method static TextNotation STUDLY_CAPS()
 * @method static TextNotation CAMEL_CASE()
 * @method static TextNotation UNDERSCORE()
 * @method static TextNotation KEBAB_CASE()
 *
 * @psalm-immutable
 */
class TextNotation extends Enum
{
    private const STUDLY_CAPS = 'studly_caps';
    private const CAMEL_CASE = 'camel_case';
    private const UNDERSCORE = 'underscore';
    private const KEBAB_CASE = 'kebab-case';
}
