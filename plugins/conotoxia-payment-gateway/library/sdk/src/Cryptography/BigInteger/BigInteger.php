<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger;

use CKPL\Pay\Cryptography\BigInteger\Add\Add;
use CKPL\Pay\Cryptography\BigInteger\Calculator\Base10;
use CKPL\Pay\Cryptography\BigInteger\Calculator\Base256;
use CKPL\Pay\Cryptography\BigInteger\Compare\Compare;
use CKPL\Pay\Cryptography\BigInteger\Converter\BytesConverter;
use CKPL\Pay\Cryptography\BigInteger\Multiply\Multiply;
use CKPL\Pay\Cryptography\BigInteger\Utils\Base256ShiftTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\BytesToIntegerTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\IntegerToBytesTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\PhpIntConfigTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\RepeatTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\TrimTrait;
use CKPL\Pay\Cryptography\BigInteger\Value\IntegerValue;
use CKPL\Pay\Cryptography\BigInteger\Value\ValueInterface;
use CKPL\Pay\Exception\BigIntegerException;
use function abs;
use function count;
use function sprintf;

/**
 * Class BigInteger.
 *
 * @package CKPL\Pay\Cryptography\BigInteger
 */
class BigInteger implements BigIntegerInterface
{
    use PhpIntConfigTrait, TrimTrait, RepeatTrait, Base256ShiftTrait, IntegerToBytesTrait, BytesToIntegerTrait;

    /**
     * @var array
     */
    protected $value = [];

    /**
     * BigInteger constructor.
     *
     * @param ValueInterface $value
     * @param int            $base
     *
     * @throws BigIntegerException
     */
    public function __construct(ValueInterface $value = null, int $base = BigIntegerInterface::BASE_10)
    {
        $this->value = [];
        $value = $value ?: $this->fromNull();

        if (empty($value->getValue()) && (BigIntegerInterface::BASE_256 != abs($base) || '0' !== $value->getValue())) {
            return;
        }

        if (BigIntegerInterface::BASE_256 === $base) {
            $this->calculateForBase256($value);
        } elseif (BigIntegerInterface::BASE_10 === $base) {
            $this->calculateForBase10($value);
        } else {
            throw new BigIntegerException(
                sprintf(BigIntegerException::UNSUPPORTED_BASE, $base)
            );
        }
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * @param array $value
     *
     * @return BigIntegerInterface
     */
    public function setValue(array $value): BigIntegerInterface
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param bool $moreThanOneCompliment
     *
     * @throws BigIntegerException
     *
     * @return string|null
     */
    public function toBytes(bool $moreThanOneCompliment = false): string
    {
        return (new BytesConverter($this, $moreThanOneCompliment))->convert();
    }

    /**
     * @param BigIntegerInterface $component
     *
     * @throws BigIntegerException
     *
     * @return BigIntegerInterface
     */
    public function add(BigIntegerInterface $component): BigIntegerInterface
    {
        $temp = (new Add($this->value, $component->getValue()))->perform();

        $result = new static();
        $result->setValue($temp);

        return $this->normalize($result);
    }

    /**
     * @param BigIntegerInterface $bigInteger
     *
     * @return int
     */
    public function compare(BigIntegerInterface $bigInteger): int
    {
        return (new Compare($this, $bigInteger))->perform();
    }

    /**
     * @param BigIntegerInterface $multiplier
     *
     * @throws BigIntegerException
     *
     * @return BigIntegerInterface
     */
    public function multiply(BigIntegerInterface $multiplier): BigIntegerInterface
    {
        return (new Multiply($this, $multiplier))->perform();
    }

    /**
     * @param BigIntegerInterface $result
     *
     * @return BigIntegerInterface
     */
    protected function normalize(BigIntegerInterface $result): BigIntegerInterface
    {
        $value = $result->getValue();

        if (count($value)) {
            $value = $this->trim($value);

            $result->setValue($value);
        }

        return $result;
    }

    /**
     * @param ValueInterface $value
     *
     * @return void
     */
    protected function calculateForBase256(ValueInterface $value): void
    {
        $this->value = (new Base256($value))->getCalculated();
    }

    /**
     * @param ValueInterface $value
     *
     * @throws BigIntegerException
     *
     * @return void
     */
    protected function calculateForBase10(ValueInterface $value): void
    {
        $this->value = (new Base10($value))->getCalculated();
    }

    /**
     * @return ValueInterface
     */
    protected function fromNull(): ValueInterface
    {
        return new IntegerValue(0);
    }
}
