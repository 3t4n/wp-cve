<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Helpers;

use CoffeeCode\JsonMapper\Enums\ScalarType;

class ScalarCaster implements IScalarCaster
{
    public function cast(ScalarType $scalarType, $value)
    {
        if ($scalarType->equals(ScalarType::MIXED())) {
            return $value;
        }
        if ($scalarType->equals(ScalarType::STRING())) {
            return (string) $value;
        }
        if ($scalarType->equals(ScalarType::BOOLEAN()) || $scalarType->equals(ScalarType::BOOL())) {
            return (bool) $value;
        }
        if ($scalarType->equals(ScalarType::INTEGER()) || $scalarType->equals(ScalarType::INT())) {
            return (int) $value;
        }
        if ($scalarType->equals(ScalarType::DOUBLE()) || $scalarType->equals(ScalarType::FLOAT())) {
            return (float) $value;
        }

        throw new \LogicException("Missing {$scalarType->getValue()} in cast method");
    }
}
