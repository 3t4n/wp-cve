<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Converter;

use CKPL\Pay\Cryptography\BigInteger\BigInteger;
use CKPL\Pay\Cryptography\BigInteger\BigIntegerInterface;
use CKPL\Pay\Cryptography\BigInteger\Utils\Base256ShiftTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\BytesToIntegerTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\IntegerToBytesTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\PhpIntConfigTrait;
use CKPL\Pay\Cryptography\BigInteger\Value\IntegerValue;
use CKPL\Pay\Exception\BigIntegerException;
use function chr;
use function count;
use function ord;
use function str_pad;
use function strlen;

/**
 * Class BytesConverter.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Converter
 */
class BytesConverter implements ConverterInterface
{
    use PhpIntConfigTrait, IntegerToBytesTrait, BytesToIntegerTrait, Base256ShiftTrait;

    /**
     * @var BigIntegerInterface
     */
    protected $bigInteger;
    /**
     * @var bool
     */
    protected $moreThanOneCompliment;

    /**
     * BytesConverter constructor.
     *
     * @param BigIntegerInterface $bigInteger
     * @param bool                $moreThanOneCompliment
     */
    public function __construct(BigIntegerInterface $bigInteger, bool $moreThanOneCompliment = false)
    {
        $this->bigInteger = $bigInteger;
        $this->moreThanOneCompliment = $moreThanOneCompliment;
    }

    /**
     * @throws BigIntegerException
     *
     * @return string
     */
    public function convert(): string
    {
        return $this->moreThanOneCompliment
            ? $this->convertWithMoreThanOneCompliment()
            : $this->convertWithSingleCompliment();
    }

    /**
     * @throws BigIntegerException
     *
     * @return string
     */
    protected function convertWithMoreThanOneCompliment(): string
    {
        $comparison = $this->bigInteger->compare(new BigInteger());

        if (0 === $comparison) {
            $result = '';
        } else {
            $temp = $comparison < 0 ? $this->bigInteger->add(new BigInteger(new IntegerValue(1))) : clone
                $this->bigInteger;

            $bytes = (new static($temp))->convert();
            $bytes = !strlen($bytes) ? chr(0) : $bytes;

            if (ord($bytes[0]) & 0x80) {
                $bytes = chr(0).$bytes;
            }

            $result = $comparison < 0 ? ~$bytes : $bytes;
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function convertWithSingleCompliment(): string
    {
        if (!count($this->bigInteger->getValue())) {
            $result = '';
        } else {
            $result = $this->integerToBytes($this->bigInteger->getValue()[count($this->bigInteger->getValue()) - 1]);
            $imposter = clone $this->bigInteger;

            for ($i = count($imposter->getValue()) - 2; $i >= 0; $i--) {
                $this->base256LeftShift(
                    $result,
                    static::getBase()
                );

                $component = $this->integerToBytes($imposter->getValue()[$i]);
                $component = str_pad($component, strlen($result), chr(0), STR_PAD_LEFT);

                $result = $result | $component;
            }
        }

        return $result;
    }
}
