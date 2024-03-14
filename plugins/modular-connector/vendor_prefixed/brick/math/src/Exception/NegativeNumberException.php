<?php

declare (strict_types=1);
namespace Modular\ConnectorDependencies\Brick\Math\Exception;

/**
 * Exception thrown when attempting to perform an unsupported operation, such as a square root, on a negative number.
 * @internal
 */
class NegativeNumberException extends MathException
{
}
