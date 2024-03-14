<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Response;

use CKPL\Pay\Endpoint\GetPaymentsEndpoint;
use CKPL\Pay\Model\ResponseModelInterface;

/**
 * Class PaymentResponseModel.
 *
 * @package CKPL\Pay\Model\Response
 */
class PaymentResponseModel implements ResponseModelInterface
{
    /**
     * @var string|null
     */
    protected $paymentId;

    /**
     * @var string|null
     */
    protected $externalPaymentId;

    /**
     * @var string|null
     */
    protected $status;

    /**
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    /**
     * @param string $paymentId
     *
     * @return PaymentResponseModel
     */
    public function setPaymentId(string $paymentId): PaymentResponseModel
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalPaymentId(): ?string
    {
        return $this->externalPaymentId;
    }

    /**
     * @param string $externalPaymentId
     *
     * @return PaymentResponseModel
     */
    public function setExternalPaymentId(?string $externalPaymentId): PaymentResponseModel
    {
        $this->externalPaymentId = $externalPaymentId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return PaymentResponseModel
     */
    public function setStatus(?string $status): PaymentResponseModel
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return GetPaymentsEndpoint::class;
    }
}
