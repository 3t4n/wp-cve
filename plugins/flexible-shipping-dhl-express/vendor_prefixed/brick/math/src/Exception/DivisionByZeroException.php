<?php

declare (strict_types=1);
namespace DhlVendor\Brick\Math\Exception;

/**
 * Exception thrown when a division by zero occurs.
 */
class DivisionByZeroException extends \DhlVendor\Brick\Math\Exception\MathException
{
    /**
     * @return DivisionByZeroException
     *
     * @psalm-pure
     */
    public static function divisionByZero() : \DhlVendor\Brick\Math\Exception\DivisionByZeroException
    {
        return new self('Division by zero.');
    }
    /**
     * @return DivisionByZeroException
     *
     * @psalm-pure
     */
    public static function modulusMustNotBeZero() : \DhlVendor\Brick\Math\Exception\DivisionByZeroException
    {
        return new self('The modulus must not be zero.');
    }
    /**
     * @return DivisionByZeroException
     *
     * @psalm-pure
     */
    public static function denominatorMustNotBeZero() : \DhlVendor\Brick\Math\Exception\DivisionByZeroException
    {
        return new self('The denominator of a rational number cannot be zero.');
    }
}
