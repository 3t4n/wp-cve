<?php

declare (strict_types=1);
namespace DhlVendor\Brick\Math\Exception;

/**
 * Exception thrown when attempting to perform an unsupported operation, such as a square root, on a negative number.
 */
class NegativeNumberException extends \DhlVendor\Brick\Math\Exception\MathException
{
}
