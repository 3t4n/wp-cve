<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Exception;

class TypeError extends \TypeError
{
    /** @param mixed $argument */
    public static function forArgument(
        string $method,
        string $expectedType,
        $argument,
        int $argumentNumber,
        string $argumentName
    ): TypeError {
        $trace = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        return new TypeError(\sprintf(
            '%s(): Argument #%d (%s) must be of type %s, %s given, called in %s on line %d',
            $method,
            $argumentNumber,
            $argumentName,
            $expectedType,
            gettype($argument),
            $trace[1]['file'],
            $trace[1]['line']
        ));
    }
}
