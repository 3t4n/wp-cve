<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Request;

use CKPL\Pay\Model\RequestModelInterface;

/**
 * Class TotalAmountRequestModel.
 *
 * @package CKPL\Pay\Model\Request
 */
class TotalAmountRequestModel implements RequestModelInterface
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
     * @param string|null $currency
     *
     * @return TotalAmountRequestModel
     */
    public function setCurrency(string $currency): TotalAmountRequestModel
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
     * @return TotalAmountRequestModel
     */
    public function setValue(string $value): TotalAmountRequestModel
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return RequestModelInterface::class;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return [
            'currency' => $this->getCurrency(),
            'value' => $this->getValue(),
        ];
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return RequestModelInterface::JSON_OBJECT;
    }
}
