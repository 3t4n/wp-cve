<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Multiply;

use CKPL\Pay\Cryptography\BigInteger\BigInteger;
use CKPL\Pay\Cryptography\BigInteger\BigIntegerInterface;
use CKPL\Pay\Cryptography\BigInteger\Utils\PhpIntConfigTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\RepeatTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\TrimTrait;
use CKPL\Pay\Exception\BigIntegerException;
use function count;
use function intval;

/**
 * Class Multiply.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Multiply
 */
class Multiply implements MultiplyInterface
{
    use PhpIntConfigTrait, RepeatTrait, TrimTrait;

    /**
     * @var BigIntegerInterface
     */
    protected $bigInteger;

    /**
     * @var BigIntegerInterface
     */
    protected $multiplier;

    /**
     * Multiply constructor.
     *
     * @param BigIntegerInterface $bigInteger
     * @param BigIntegerInterface $multiplier
     */
    public function __construct(BigIntegerInterface $bigInteger, BigIntegerInterface $multiplier)
    {
        $this->bigInteger = $bigInteger;
        $this->multiplier = $multiplier;
    }

    /**
     * @throws BigIntegerException
     *
     * @return BigIntegerInterface
     */
    public function perform(): BigIntegerInterface
    {
        $bigInteger = $this->bigInteger->getValue();
        $multiplier = $this->multiplier->getValue();

        $bigIntegerLength = count($bigInteger);
        $multiplierLength = count($multiplier);

        $result = !$bigIntegerLength || !$multiplierLength ? [] : $this->operation();

        $resultBigInteger = new BigInteger();
        $resultBigInteger->setValue($result);

        $value = $resultBigInteger->getValue();

        if (count($value)) {
            $value = $this->trim($value);

            $resultBigInteger->setValue($value);
        }

        return $resultBigInteger;
    }

    /**
     * @return array
     */
    protected function operation(): array
    {
        $bigInteger = $this->bigInteger->getValue();
        $multiplier = $this->multiplier->getValue();

        $bigIntegerLength = count($bigInteger);
        $multiplierLength = count($multiplier);

        if ($bigIntegerLength < $multiplierLength) {
            $temp = $bigInteger;
            $bigInteger = $multiplier;
            $multiplier = $temp;

            $bigIntegerLength = count($bigInteger);
            $multiplierLength = count($multiplier);
        }

        $resultValue = $this->repeat(0, $bigIntegerLength + $multiplierLength);
        $carry = 0;

        for ($j = 0; $j < $bigIntegerLength; $j++) {
            $temp = $bigInteger[$j] * $multiplier[0] + $carry;
            $carry = 26 === static::getBase() ? intval($temp / 0x4000000) : ($temp >> 31);
            $resultValue[$j] = (int) ($temp - static::getBaseFull() * $carry);
        }

        $resultValue[$j] = $carry;

        for ($i = 1; $i < $multiplierLength; $i++) {
            $carry = 0;

            for ($j = 0, $k = $i; $j < $bigIntegerLength; $j++, $k++) {
                $temp = $resultValue[$k] + $bigInteger[$j] * $multiplier[$i] + $carry;
                $carry = 26 === static::getBase() ? intval($temp / 0x4000000) : ($temp >> 31);
                $resultValue[$k] = (int) ($temp - static::getBaseFull() * $carry);
            }

            $resultValue[$k] = $carry;
        }

        return $resultValue;
    }
}
