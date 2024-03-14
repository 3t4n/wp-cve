<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Calculator;

use CKPL\Pay\Cryptography\BigInteger\BigInteger;
use CKPL\Pay\Cryptography\BigInteger\BigIntegerInterface;
use CKPL\Pay\Cryptography\BigInteger\Utils\IntegerToBytesTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\PhpIntConfigTrait;
use CKPL\Pay\Cryptography\BigInteger\Value\StringValue;
use CKPL\Pay\Cryptography\BigInteger\Value\ValueInterface;
use CKPL\Pay\Exception\BigIntegerException;
use function preg_replace;
use function str_pad;
use function strlen;
use function substr;

/**
 * Class Base10.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Calculator
 */
class Base10 implements CalculatorInterface
{
    use PhpIntConfigTrait, IntegerToBytesTrait;

    /**
     * @var ValueInterface
     */
    protected $value;

    /**
     * Base10 constructor.
     *
     * @param ValueInterface $value
     */
    public function __construct(ValueInterface $value)
    {
        $this->value = $value;
    }

    /**
     * @throws BigIntegerException
     *
     * @return array
     */
    public function getCalculated(): array
    {
        $value = preg_replace('#(?<!^)(?:-).*|(?<=^|-)0*|[^-0-9].*#', '', (string)$this->value->getValue());
        $temp = new BigInteger();

        $multiplier = new BigInteger();
        $multiplier->setValue([static::getMax10()]);

        $value = str_pad(
            $value,
            (strlen($value) + ((static::getMax10Length() - 1) * strlen($value)) % static::getMax10Length()),
            '0',
            STR_PAD_LEFT
        );

        while (strlen($value)) {
            $temp = $temp->multiply($multiplier);

            $temp = $temp->add(
                new BigInteger(
                    new StringValue($this->integerToBytes((int) substr($value, 0, static::getMax10Length()))),
                    BigIntegerInterface::BASE_256
                )
            );

            $value = substr($value, static::getMax10Length());
        }

        return $temp->getValue();
    }
}
