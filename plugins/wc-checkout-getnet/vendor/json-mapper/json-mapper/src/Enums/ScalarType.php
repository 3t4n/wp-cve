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
 * @method static ScalarType STRING()
 * @method static ScalarType BOOLEAN()
 * @method static ScalarType BOOL()
 * @method static ScalarType INTEGER()
 * @method static ScalarType INT()
 * @method static ScalarType DOUBLE()
 * @method static ScalarType FLOAT()
 * @method static ScalarType MIXED()
 *
 * @psalm-immutable
 */
class ScalarType extends Enum
{
    protected const STRING = 'string';
    protected const BOOLEAN = 'boolean';
    protected const BOOL = 'bool';
    protected const INTEGER = 'integer';
    protected const INT = 'int';
    protected const DOUBLE = 'double';
    protected const FLOAT = 'float';
    protected const MIXED = 'mixed';
}
