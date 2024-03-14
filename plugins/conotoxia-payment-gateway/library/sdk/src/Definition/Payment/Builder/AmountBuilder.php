<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Payment\Builder;

use CKPL\Pay\Definition\Amount\Amount;
use CKPL\Pay\Definition\Amount\AmountInterface;
use CKPL\Pay\Exception\Definition\AmountException;

/**
 * Class AmountBuilder.
 *
 * @package CKPL\Pay\Definition\Payment\Builder
 */
class AmountBuilder implements AmountBuilderInterface
{
    /**
     * @var Amount
     */
    protected $amount;

    /**
     * AmountBuilder constructor.
     */
    public function __construct()
    {
        $this->initializeAmount();
    }

    /**
     * Currency code in accordance with ISO 4217.
     *
     * This value is required!
     *
     * @param string $currency
     *
     * @return AmountBuilderInterface
     */
    public function setCurrency(string $currency): AmountBuilderInterface
    {
        $this->amount->setCurrency($currency);

        return $this;
    }

    /**
     * Amount. Max. 21 characters with support for 4 places after
     * the decimal separator (the dot is used as the decimal separator).
     *
     * This value is required!
     *
     * @param string $value
     *
     * @return AmountBuilderInterface
     */
    public function setValue(string $value): AmountBuilderInterface
    {
        $this->amount->setValue($value);

        return $this;
    }

    /**
     * Returns Amount definition.
     *
     * @throws AmountException if one of required parameters is missing
     *
     * @return AmountInterface
     */
    public function getAmount(): AmountInterface
    {
        if (empty($this->amount->getCurrency())) {
            throw new AmountException('Missing currency in payment amount.');
        }

        if (empty($this->amount->getValue())) {
            throw new AmountException('Missing value in payment amount.');
        }

        return $this->amount;
    }

    /**
     * @return void
     */
    protected function initializeAmount(): void
    {
        $this->amount = new Amount();
    }
}
