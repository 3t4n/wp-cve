<?php

declare (strict_types=1);
namespace DhlVendor\Brick\Math;

use DhlVendor\Brick\Math\Exception\DivisionByZeroException;
use DhlVendor\Brick\Math\Exception\MathException;
use DhlVendor\Brick\Math\Exception\NumberFormatException;
use DhlVendor\Brick\Math\Exception\RoundingNecessaryException;
/**
 * An arbitrarily large rational number.
 *
 * This class is immutable.
 *
 * @psalm-immutable
 */
final class BigRational extends \DhlVendor\Brick\Math\BigNumber
{
    /**
     * The numerator.
     *
     * @var BigInteger
     */
    private $numerator;
    /**
     * The denominator. Always strictly positive.
     *
     * @var BigInteger
     */
    private $denominator;
    /**
     * Protected constructor. Use a factory method to obtain an instance.
     *
     * @param BigInteger $numerator        The numerator.
     * @param BigInteger $denominator      The denominator.
     * @param bool       $checkDenominator Whether to check the denominator for negative and zero.
     *
     * @throws DivisionByZeroException If the denominator is zero.
     */
    protected function __construct(\DhlVendor\Brick\Math\BigInteger $numerator, \DhlVendor\Brick\Math\BigInteger $denominator, bool $checkDenominator)
    {
        if ($checkDenominator) {
            if ($denominator->isZero()) {
                throw \DhlVendor\Brick\Math\Exception\DivisionByZeroException::denominatorMustNotBeZero();
            }
            if ($denominator->isNegative()) {
                $numerator = $numerator->negated();
                $denominator = $denominator->negated();
            }
        }
        $this->numerator = $numerator;
        $this->denominator = $denominator;
    }
    /**
     * Creates a BigRational of the given value.
     *
     * @param BigNumber|int|float|string $value
     *
     * @return BigRational
     *
     * @throws MathException If the value cannot be converted to a BigRational.
     *
     * @psalm-pure
     */
    public static function of($value) : \DhlVendor\Brick\Math\BigNumber
    {
        return parent::of($value)->toBigRational();
    }
    /**
     * Creates a BigRational out of a numerator and a denominator.
     *
     * If the denominator is negative, the signs of both the numerator and the denominator
     * will be inverted to ensure that the denominator is always positive.
     *
     * @param BigNumber|int|float|string $numerator   The numerator. Must be convertible to a BigInteger.
     * @param BigNumber|int|float|string $denominator The denominator. Must be convertible to a BigInteger.
     *
     * @return BigRational
     *
     * @throws NumberFormatException      If an argument does not represent a valid number.
     * @throws RoundingNecessaryException If an argument represents a non-integer number.
     * @throws DivisionByZeroException    If the denominator is zero.
     *
     * @psalm-pure
     */
    public static function nd($numerator, $denominator) : \DhlVendor\Brick\Math\BigRational
    {
        $numerator = \DhlVendor\Brick\Math\BigInteger::of($numerator);
        $denominator = \DhlVendor\Brick\Math\BigInteger::of($denominator);
        return new \DhlVendor\Brick\Math\BigRational($numerator, $denominator, \true);
    }
    /**
     * Returns a BigRational representing zero.
     *
     * @return BigRational
     *
     * @psalm-pure
     */
    public static function zero() : \DhlVendor\Brick\Math\BigRational
    {
        /**
         * @psalm-suppress ImpureStaticVariable
         * @var BigRational|null $zero
         */
        static $zero;
        if ($zero === null) {
            $zero = new \DhlVendor\Brick\Math\BigRational(\DhlVendor\Brick\Math\BigInteger::zero(), \DhlVendor\Brick\Math\BigInteger::one(), \false);
        }
        return $zero;
    }
    /**
     * Returns a BigRational representing one.
     *
     * @return BigRational
     *
     * @psalm-pure
     */
    public static function one() : \DhlVendor\Brick\Math\BigRational
    {
        /**
         * @psalm-suppress ImpureStaticVariable
         * @var BigRational|null $one
         */
        static $one;
        if ($one === null) {
            $one = new \DhlVendor\Brick\Math\BigRational(\DhlVendor\Brick\Math\BigInteger::one(), \DhlVendor\Brick\Math\BigInteger::one(), \false);
        }
        return $one;
    }
    /**
     * Returns a BigRational representing ten.
     *
     * @return BigRational
     *
     * @psalm-pure
     */
    public static function ten() : \DhlVendor\Brick\Math\BigRational
    {
        /**
         * @psalm-suppress ImpureStaticVariable
         * @var BigRational|null $ten
         */
        static $ten;
        if ($ten === null) {
            $ten = new \DhlVendor\Brick\Math\BigRational(\DhlVendor\Brick\Math\BigInteger::ten(), \DhlVendor\Brick\Math\BigInteger::one(), \false);
        }
        return $ten;
    }
    /**
     * @return BigInteger
     */
    public function getNumerator() : \DhlVendor\Brick\Math\BigInteger
    {
        return $this->numerator;
    }
    /**
     * @return BigInteger
     */
    public function getDenominator() : \DhlVendor\Brick\Math\BigInteger
    {
        return $this->denominator;
    }
    /**
     * Returns the quotient of the division of the numerator by the denominator.
     *
     * @return BigInteger
     */
    public function quotient() : \DhlVendor\Brick\Math\BigInteger
    {
        return $this->numerator->quotient($this->denominator);
    }
    /**
     * Returns the remainder of the division of the numerator by the denominator.
     *
     * @return BigInteger
     */
    public function remainder() : \DhlVendor\Brick\Math\BigInteger
    {
        return $this->numerator->remainder($this->denominator);
    }
    /**
     * Returns the quotient and remainder of the division of the numerator by the denominator.
     *
     * @return BigInteger[]
     */
    public function quotientAndRemainder() : array
    {
        return $this->numerator->quotientAndRemainder($this->denominator);
    }
    /**
     * Returns the sum of this number and the given one.
     *
     * @param BigNumber|int|float|string $that The number to add.
     *
     * @return BigRational The result.
     *
     * @throws MathException If the number is not valid.
     */
    public function plus($that) : \DhlVendor\Brick\Math\BigRational
    {
        $that = \DhlVendor\Brick\Math\BigRational::of($that);
        $numerator = $this->numerator->multipliedBy($that->denominator);
        $numerator = $numerator->plus($that->numerator->multipliedBy($this->denominator));
        $denominator = $this->denominator->multipliedBy($that->denominator);
        return new \DhlVendor\Brick\Math\BigRational($numerator, $denominator, \false);
    }
    /**
     * Returns the difference of this number and the given one.
     *
     * @param BigNumber|int|float|string $that The number to subtract.
     *
     * @return BigRational The result.
     *
     * @throws MathException If the number is not valid.
     */
    public function minus($that) : \DhlVendor\Brick\Math\BigRational
    {
        $that = \DhlVendor\Brick\Math\BigRational::of($that);
        $numerator = $this->numerator->multipliedBy($that->denominator);
        $numerator = $numerator->minus($that->numerator->multipliedBy($this->denominator));
        $denominator = $this->denominator->multipliedBy($that->denominator);
        return new \DhlVendor\Brick\Math\BigRational($numerator, $denominator, \false);
    }
    /**
     * Returns the product of this number and the given one.
     *
     * @param BigNumber|int|float|string $that The multiplier.
     *
     * @return BigRational The result.
     *
     * @throws MathException If the multiplier is not a valid number.
     */
    public function multipliedBy($that) : \DhlVendor\Brick\Math\BigRational
    {
        $that = \DhlVendor\Brick\Math\BigRational::of($that);
        $numerator = $this->numerator->multipliedBy($that->numerator);
        $denominator = $this->denominator->multipliedBy($that->denominator);
        return new \DhlVendor\Brick\Math\BigRational($numerator, $denominator, \false);
    }
    /**
     * Returns the result of the division of this number by the given one.
     *
     * @param BigNumber|int|float|string $that The divisor.
     *
     * @return BigRational The result.
     *
     * @throws MathException If the divisor is not a valid number, or is zero.
     */
    public function dividedBy($that) : \DhlVendor\Brick\Math\BigRational
    {
        $that = \DhlVendor\Brick\Math\BigRational::of($that);
        $numerator = $this->numerator->multipliedBy($that->denominator);
        $denominator = $this->denominator->multipliedBy($that->numerator);
        return new \DhlVendor\Brick\Math\BigRational($numerator, $denominator, \true);
    }
    /**
     * Returns this number exponentiated to the given value.
     *
     * @param int $exponent The exponent.
     *
     * @return BigRational The result.
     *
     * @throws \InvalidArgumentException If the exponent is not in the range 0 to 1,000,000.
     */
    public function power(int $exponent) : \DhlVendor\Brick\Math\BigRational
    {
        if ($exponent === 0) {
            $one = \DhlVendor\Brick\Math\BigInteger::one();
            return new \DhlVendor\Brick\Math\BigRational($one, $one, \false);
        }
        if ($exponent === 1) {
            return $this;
        }
        return new \DhlVendor\Brick\Math\BigRational($this->numerator->power($exponent), $this->denominator->power($exponent), \false);
    }
    /**
     * Returns the reciprocal of this BigRational.
     *
     * The reciprocal has the numerator and denominator swapped.
     *
     * @return BigRational
     *
     * @throws DivisionByZeroException If the numerator is zero.
     */
    public function reciprocal() : \DhlVendor\Brick\Math\BigRational
    {
        return new \DhlVendor\Brick\Math\BigRational($this->denominator, $this->numerator, \true);
    }
    /**
     * Returns the absolute value of this BigRational.
     *
     * @return BigRational
     */
    public function abs() : \DhlVendor\Brick\Math\BigRational
    {
        return new \DhlVendor\Brick\Math\BigRational($this->numerator->abs(), $this->denominator, \false);
    }
    /**
     * Returns the negated value of this BigRational.
     *
     * @return BigRational
     */
    public function negated() : \DhlVendor\Brick\Math\BigRational
    {
        return new \DhlVendor\Brick\Math\BigRational($this->numerator->negated(), $this->denominator, \false);
    }
    /**
     * Returns the simplified value of this BigRational.
     *
     * @return BigRational
     */
    public function simplified() : \DhlVendor\Brick\Math\BigRational
    {
        $gcd = $this->numerator->gcd($this->denominator);
        $numerator = $this->numerator->quotient($gcd);
        $denominator = $this->denominator->quotient($gcd);
        return new \DhlVendor\Brick\Math\BigRational($numerator, $denominator, \false);
    }
    /**
     * {@inheritdoc}
     */
    public function compareTo($that) : int
    {
        return $this->minus($that)->getSign();
    }
    /**
     * {@inheritdoc}
     */
    public function getSign() : int
    {
        return $this->numerator->getSign();
    }
    /**
     * {@inheritdoc}
     */
    public function toBigInteger() : \DhlVendor\Brick\Math\BigInteger
    {
        $simplified = $this->simplified();
        if (!$simplified->denominator->isEqualTo(1)) {
            throw new \DhlVendor\Brick\Math\Exception\RoundingNecessaryException('This rational number cannot be represented as an integer value without rounding.');
        }
        return $simplified->numerator;
    }
    /**
     * {@inheritdoc}
     */
    public function toBigDecimal() : \DhlVendor\Brick\Math\BigDecimal
    {
        return $this->numerator->toBigDecimal()->exactlyDividedBy($this->denominator);
    }
    /**
     * {@inheritdoc}
     */
    public function toBigRational() : \DhlVendor\Brick\Math\BigRational
    {
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function toScale(int $scale, int $roundingMode = \DhlVendor\Brick\Math\RoundingMode::UNNECESSARY) : \DhlVendor\Brick\Math\BigDecimal
    {
        return $this->numerator->toBigDecimal()->dividedBy($this->denominator, $scale, $roundingMode);
    }
    /**
     * {@inheritdoc}
     */
    public function toInt() : int
    {
        return $this->toBigInteger()->toInt();
    }
    /**
     * {@inheritdoc}
     */
    public function toFloat() : float
    {
        return $this->numerator->toFloat() / $this->denominator->toFloat();
    }
    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        $numerator = (string) $this->numerator;
        $denominator = (string) $this->denominator;
        if ($denominator === '1') {
            return $numerator;
        }
        return $this->numerator . '/' . $this->denominator;
    }
    /**
     * This method is required for serializing the object and SHOULD NOT be accessed directly.
     *
     * @internal
     *
     * @return array{numerator: BigInteger, denominator: BigInteger}
     */
    public function __serialize() : array
    {
        return ['numerator' => $this->numerator, 'denominator' => $this->denominator];
    }
    /**
     * This method is only here to allow unserializing the object and cannot be accessed directly.
     *
     * @internal
     * @psalm-suppress RedundantPropertyInitializationCheck
     *
     * @param array{numerator: BigInteger, denominator: BigInteger} $data
     *
     * @return void
     *
     * @throws \LogicException
     */
    public function __unserialize(array $data) : void
    {
        if (isset($this->numerator)) {
            throw new \LogicException('__unserialize() is an internal function, it must not be called directly.');
        }
        $this->numerator = $data['numerator'];
        $this->denominator = $data['denominator'];
    }
    /**
     * This method is required by interface Serializable and SHOULD NOT be accessed directly.
     *
     * @internal
     *
     * @return string
     */
    public function serialize() : string
    {
        return $this->numerator . '/' . $this->denominator;
    }
    /**
     * This method is only here to implement interface Serializable and cannot be accessed directly.
     *
     * @internal
     * @psalm-suppress RedundantPropertyInitializationCheck
     *
     * @param string $value
     *
     * @return void
     *
     * @throws \LogicException
     */
    public function unserialize($value) : void
    {
        if (isset($this->numerator)) {
            throw new \LogicException('unserialize() is an internal function, it must not be called directly.');
        }
        [$numerator, $denominator] = \explode('/', $value);
        $this->numerator = \DhlVendor\Brick\Math\BigInteger::of($numerator);
        $this->denominator = \DhlVendor\Brick\Math\BigInteger::of($denominator);
    }
}
