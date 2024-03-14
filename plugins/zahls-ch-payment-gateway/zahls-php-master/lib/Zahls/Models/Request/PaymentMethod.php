<?php

namespace Zahls\Models\Request;

/**
 * PaymentMethod class
 *
 * @package Zahls\Models\Request
 */
class PaymentMethod extends \Zahls\Models\Base
{
    /** @var string */
    protected $filterCurrency;

    /** @var string */
    protected $filterPaymentType;

    /** @var int */
    protected $filterPsp;

    /**
     * @return string
     */
    public function getFilterCurrency(): string
    {
        return $this->filterCurrency;
    }

    /**
     * @param string $filterCurrency
     */
    public function setFilterCurrency(string $filterCurrency): void
    {
        $this->filterCurrency = $filterCurrency;
    }

    /**
     * @return string
     */
    public function getFilterPaymentType(): string
    {
        return $this->filterPaymentType;
    }

    /**
     * @param string $filterPaymentType
     */
    public function setFilterPaymentType(string $filterPaymentType): void
    {
        $this->filterPaymentType = $filterPaymentType;
    }

    /**
     * @return int
     */
    public function getFilterPsp(): int
    {
        return $this->filterPsp;
    }

    /**
     * @param int $filterPsp
     */
    public function setFilterPsp(int $filterPsp): void
    {
        $this->filterPsp = $filterPsp;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseModel()
    {
        return new \Zahls\Models\Response\PaymentMethod();
    }
}
