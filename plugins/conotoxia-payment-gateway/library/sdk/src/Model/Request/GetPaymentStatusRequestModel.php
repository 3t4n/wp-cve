<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Request;

use CKPL\Pay\Endpoint\GetPaymentStatusEndpoint;
use CKPL\Pay\Model\RequestModelInterface;

/**
 * Class GetPaymentStatusRequestModel.
 *
 * @package CKPL\Pay\Model\Request
 */
class GetPaymentStatusRequestModel implements RequestModelInterface
{

    /**
     * @var string|null
     */
    protected $paymentId;

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return GetPaymentStatusEndpoint::class;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        $result = [];

        if (null !== $this->getPaymentId()) {
            $result['paymentId'] = $this->getPaymentId();
        }

        return $result;
    }

    /**
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    /**
     * @param string|null $paymentId
     */
    public function setPaymentId(?string $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return RequestModelInterface::JSON_OBJECT;
    }
}