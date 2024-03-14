<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Calculator;

use CKPL\Pay\Cryptography\BigInteger\Utils\Base256ShiftTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\BytesToIntegerTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\PhpIntConfigTrait;
use CKPL\Pay\Cryptography\BigInteger\Value\IntegerValue;
use CKPL\Pay\Cryptography\BigInteger\Value\ValueInterface;
use function strlen;

/**
 * Class Base256.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Calculator
 */
class Base256 implements CalculatorInterface
{
    use BytesToIntegerTrait, Base256ShiftTrait, PhpIntConfigTrait;

    /**
     * @var ValueInterface
     */
    protected $value;

    /**
     * Base256 constructor.
     *
     * @param $value
     */
    public function __construct(ValueInterface $value)
    {
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getCalculated(): array
    {
        $value = [];
        $rawValue = (string) $this->value->getValue();

        while (strlen($rawValue)) {
            $value[] = $this->bytesToInteger($this->base256RightShift($rawValue, static::getBase()));
        }

        $this->value->setValue($this->value instanceof IntegerValue ? (int) $rawValue : $rawValue);

        return $value;
    }
}
