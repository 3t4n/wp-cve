<?php

/**
 * This file is part of the ramsey/uuid library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
declare (strict_types=1);
namespace DhlVendor\Ramsey\Uuid\Math;

use DhlVendor\Brick\Math\BigDecimal;
use DhlVendor\Brick\Math\BigInteger;
use DhlVendor\Brick\Math\Exception\MathException;
use DhlVendor\Brick\Math\RoundingMode as BrickMathRounding;
use DhlVendor\Ramsey\Uuid\Exception\InvalidArgumentException;
use DhlVendor\Ramsey\Uuid\Type\Decimal;
use DhlVendor\Ramsey\Uuid\Type\Hexadecimal;
use DhlVendor\Ramsey\Uuid\Type\Integer as IntegerObject;
use DhlVendor\Ramsey\Uuid\Type\NumberInterface;
/**
 * A calculator using the brick/math library for arbitrary-precision arithmetic
 *
 * @psalm-immutable
 */
final class BrickMathCalculator implements \DhlVendor\Ramsey\Uuid\Math\CalculatorInterface
{
    private const ROUNDING_MODE_MAP = [\DhlVendor\Ramsey\Uuid\Math\RoundingMode::UNNECESSARY => \DhlVendor\Brick\Math\RoundingMode::UNNECESSARY, \DhlVendor\Ramsey\Uuid\Math\RoundingMode::UP => \DhlVendor\Brick\Math\RoundingMode::UP, \DhlVendor\Ramsey\Uuid\Math\RoundingMode::DOWN => \DhlVendor\Brick\Math\RoundingMode::DOWN, \DhlVendor\Ramsey\Uuid\Math\RoundingMode::CEILING => \DhlVendor\Brick\Math\RoundingMode::CEILING, \DhlVendor\Ramsey\Uuid\Math\RoundingMode::FLOOR => \DhlVendor\Brick\Math\RoundingMode::FLOOR, \DhlVendor\Ramsey\Uuid\Math\RoundingMode::HALF_UP => \DhlVendor\Brick\Math\RoundingMode::HALF_UP, \DhlVendor\Ramsey\Uuid\Math\RoundingMode::HALF_DOWN => \DhlVendor\Brick\Math\RoundingMode::HALF_DOWN, \DhlVendor\Ramsey\Uuid\Math\RoundingMode::HALF_CEILING => \DhlVendor\Brick\Math\RoundingMode::HALF_CEILING, \DhlVendor\Ramsey\Uuid\Math\RoundingMode::HALF_FLOOR => \DhlVendor\Brick\Math\RoundingMode::HALF_FLOOR, \DhlVendor\Ramsey\Uuid\Math\RoundingMode::HALF_EVEN => \DhlVendor\Brick\Math\RoundingMode::HALF_EVEN];
    public function add(\DhlVendor\Ramsey\Uuid\Type\NumberInterface $augend, \DhlVendor\Ramsey\Uuid\Type\NumberInterface ...$addends) : \DhlVendor\Ramsey\Uuid\Type\NumberInterface
    {
        $sum = \DhlVendor\Brick\Math\BigInteger::of($augend->toString());
        foreach ($addends as $addend) {
            $sum = $sum->plus($addend->toString());
        }
        return new \DhlVendor\Ramsey\Uuid\Type\Integer((string) $sum);
    }
    public function subtract(\DhlVendor\Ramsey\Uuid\Type\NumberInterface $minuend, \DhlVendor\Ramsey\Uuid\Type\NumberInterface ...$subtrahends) : \DhlVendor\Ramsey\Uuid\Type\NumberInterface
    {
        $difference = \DhlVendor\Brick\Math\BigInteger::of($minuend->toString());
        foreach ($subtrahends as $subtrahend) {
            $difference = $difference->minus($subtrahend->toString());
        }
        return new \DhlVendor\Ramsey\Uuid\Type\Integer((string) $difference);
    }
    public function multiply(\DhlVendor\Ramsey\Uuid\Type\NumberInterface $multiplicand, \DhlVendor\Ramsey\Uuid\Type\NumberInterface ...$multipliers) : \DhlVendor\Ramsey\Uuid\Type\NumberInterface
    {
        $product = \DhlVendor\Brick\Math\BigInteger::of($multiplicand->toString());
        foreach ($multipliers as $multiplier) {
            $product = $product->multipliedBy($multiplier->toString());
        }
        return new \DhlVendor\Ramsey\Uuid\Type\Integer((string) $product);
    }
    public function divide(int $roundingMode, int $scale, \DhlVendor\Ramsey\Uuid\Type\NumberInterface $dividend, \DhlVendor\Ramsey\Uuid\Type\NumberInterface ...$divisors) : \DhlVendor\Ramsey\Uuid\Type\NumberInterface
    {
        $brickRounding = $this->getBrickRoundingMode($roundingMode);
        $quotient = \DhlVendor\Brick\Math\BigDecimal::of($dividend->toString());
        foreach ($divisors as $divisor) {
            $quotient = $quotient->dividedBy($divisor->toString(), $scale, $brickRounding);
        }
        if ($scale === 0) {
            return new \DhlVendor\Ramsey\Uuid\Type\Integer((string) $quotient->toBigInteger());
        }
        return new \DhlVendor\Ramsey\Uuid\Type\Decimal((string) $quotient);
    }
    public function fromBase(string $value, int $base) : \DhlVendor\Ramsey\Uuid\Type\Integer
    {
        try {
            return new \DhlVendor\Ramsey\Uuid\Type\Integer((string) \DhlVendor\Brick\Math\BigInteger::fromBase($value, $base));
        } catch (\DhlVendor\Brick\Math\Exception\MathException|\InvalidArgumentException $exception) {
            throw new \DhlVendor\Ramsey\Uuid\Exception\InvalidArgumentException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }
    public function toBase(\DhlVendor\Ramsey\Uuid\Type\Integer $value, int $base) : string
    {
        try {
            return \DhlVendor\Brick\Math\BigInteger::of($value->toString())->toBase($base);
        } catch (\DhlVendor\Brick\Math\Exception\MathException|\InvalidArgumentException $exception) {
            throw new \DhlVendor\Ramsey\Uuid\Exception\InvalidArgumentException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }
    public function toHexadecimal(\DhlVendor\Ramsey\Uuid\Type\Integer $value) : \DhlVendor\Ramsey\Uuid\Type\Hexadecimal
    {
        return new \DhlVendor\Ramsey\Uuid\Type\Hexadecimal($this->toBase($value, 16));
    }
    public function toInteger(\DhlVendor\Ramsey\Uuid\Type\Hexadecimal $value) : \DhlVendor\Ramsey\Uuid\Type\Integer
    {
        return $this->fromBase($value->toString(), 16);
    }
    /**
     * Maps ramsey/uuid rounding modes to those used by brick/math
     */
    private function getBrickRoundingMode(int $roundingMode) : int
    {
        return self::ROUNDING_MODE_MAP[$roundingMode] ?? 0;
    }
}
