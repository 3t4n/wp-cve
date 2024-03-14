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
use ReflectionClass;

class ClassHelper
{
    public static function isBuiltin(string $type): bool
    {
        if ($type === 'mixed' || ScalarType::isValid($type) || ! \class_exists($type)) {
            return false;
        }

        $reflection = new ReflectionClass($type);
        return $reflection->isInternal();
    }

    public static function isCustom(string $type): bool
    {
        if ($type === 'mixed' || ScalarType::isValid($type) || ! \class_exists($type)) {
            return false;
        }

        $reflection = new ReflectionClass($type);
        return !$reflection->isInternal();
    }
}
