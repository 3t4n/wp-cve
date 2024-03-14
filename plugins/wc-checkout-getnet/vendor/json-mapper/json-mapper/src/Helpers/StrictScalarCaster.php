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

class StrictScalarCaster implements IScalarCaster
{
    /** @param $value mixed */
    public function cast(ScalarType $scalarType, $value)
    {
        $type = gettype($value);

        if (! is_string($value) && $scalarType->equals(ScalarType::STRING())) {
            throw new \Exception("Expected type string, type {$type} given");
        }
        if (
            ! is_bool($value) &&
            ($scalarType->equals(ScalarType::BOOLEAN()) || $scalarType->equals(ScalarType::BOOL()))
        ) {
            throw new \Exception("Expected type string, type {$type} given");
        }
        if (
            ! is_int($value) &&
            ($scalarType->equals(ScalarType::INTEGER()) || $scalarType->equals(ScalarType::INT()))
        ) {
            throw new \Exception("Expected type string, type {$type} given");
        }
        if (
            ! is_float($value)
            && ($scalarType->equals(ScalarType::DOUBLE()) || $scalarType->equals(ScalarType::FLOAT()))
        ) {
            throw new \Exception("Expected type string, type {$type} given");
        }

        return $value;
    }
}
