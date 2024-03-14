<?php

declare (strict_types=1);
namespace DhlVendor\Brick\Math\Exception;

use DhlVendor\Brick\Math\BigInteger;
/**
 * Exception thrown when an integer overflow occurs.
 */
class IntegerOverflowException extends \DhlVendor\Brick\Math\Exception\MathException
{
    /**
     * @param BigInteger $value
     *
     * @return IntegerOverflowException
     *
     * @psalm-pure
     */
    public static function toIntOverflow(\DhlVendor\Brick\Math\BigInteger $value) : \DhlVendor\Brick\Math\Exception\IntegerOverflowException
    {
        $message = '%s is out of range %d to %d and cannot be represented as an integer.';
        return new self(\sprintf($message, (string) $value, \PHP_INT_MIN, \PHP_INT_MAX));
    }
}
