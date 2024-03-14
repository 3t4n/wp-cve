<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Amount;

/**
 * Class Amount.
 *
 * @package CKPL\Pay\Definition\Amount
 */
class Amount implements AmountInterface
{
    /**
     * @var string|null
     */
    protected $currency;

    /**
     * @var string|null
     */
    protected $value;

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return Amount
     */
    public function setCurrency(string $currency): Amount
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     *
     * @return Amount
     */
    public function setValue(string $value): Amount
    {
        $this->value = $value;

        return $this;
    }
}
