<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Add;

use CKPL\Pay\Cryptography\BigInteger\Utils\CompleteTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\PhpIntConfigTrait;
use CKPL\Pay\Cryptography\BigInteger\Utils\TrimTrait;
use function count;
use function intval;

/**
 * Class Add.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Add
 */
class Add implements AddInterface
{
    use PhpIntConfigTrait, TrimTrait, CompleteTrait;

    /**
     * @var array
     */
    protected $addValue;

    /**
     * @var array
     */
    protected $addComponent;

    /**
     * Add constructor.
     *
     * @param array $addValue
     * @param array $addComponent
     */
    public function __construct(array $addValue, array $addComponent)
    {
        $this->addValue = $addValue;
        $this->addComponent = $addComponent;
    }

    /**
     * @return array
     */
    public function perform(): array
    {
        $addValueSize = count($this->addValue);
        $addComponentSize = count($this->addComponent);

        if (0 === $addValueSize) {
            $result = $this->addComponent;
        } elseif (0 === $addComponentSize) {
            $result = $this->addValue;
        } else {
            $result = $this->performCalculation($this->addValue, $this->addComponent);
        }

        return $result;
    }

    /**
     * @param array $addValue
     * @param array $addComponent
     *
     * @return array
     */
    protected function performCalculation(array $addValue, array $addComponent): array
    {
        $addValueSize = count($addValue);
        $addComponentSize = count($addComponent);

        if ($addValueSize < $addComponentSize) {
            $size = $addValueSize;
            $value = $addComponent;
        } else {
            $size = $addComponentSize;
            $value = $addValue;
        }

        $value[count($value)] = 0;
        $carry = 0;

        for ($i = 0, $j = 1; $j < $size; $i += 2, $j += 2) {
            $sum = $addValue[$j] * static::getBaseFull()
                + $addValue[$i] + $addComponent[$j]
                * static::getBaseFull() +
                $addComponent[$i] + $carry;

            $carry = $sum >= static::getMaxDigitY();
            $sum = $carry ? $sum - static::getMaxDigitY() : $sum;

            $temp = 26 === static::getBase() ? intval($sum / 0x4000000) : ($sum >> 31);

            $value[$i] = (int) ($sum - static::getBaseFull() * $temp);
            $value[$j] = $temp;
        }

        if ($j === $size) {
            $sum = $addValue[$i] + $addComponent[$i] + $carry;
            $carry = $sum >= static::getBaseFull();
            $value[$i] = $carry ? $sum - static::getBaseFull() : $sum;

            $i++;
        }

        if ($carry) {
            $this->completeValue($value, $i, static::getMaxDigitX());
        }

        return $this->trim($value);
    }
}
