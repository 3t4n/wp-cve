<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Response;

use CKPL\Pay\Endpoint\GetPaymentStatusEndpoint;
use CKPL\Pay\Model\ResponseModelInterface;

/**
 * Class RefundResponseModel.
 *
 * @package CKPL\Pay\Model\Response
 */
class PaymentStatusResponseModel implements ResponseModelInterface
{
    /**
     * @type string
     */
    const PAYMENT_STATUS_INITIATED = 'INITIATED';
    /**
     * @type string
     */
    const PAYMENT_STATUS_WAITING_FOR_NOTIFICATION = 'WAITING_FOR_NOTIFICATION';
    /**
     * @type string
     */
    const PAYMENT_STATUS_CONFIRMED = 'CONFIRMED';
    /**
     * @type string
     */
    const PAYMENT_STATUS_CANCELLED = 'CANCELLED';

    /**
     * @var string|null
     */
    protected $status;

    /**
     * @var string|null
     */
    protected $paymentId;

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
     * @return PaymentStatusResponseModel
     */
    public function setStatus(string $status): PaymentStatusResponseModel
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    /**
     * @param string $paymentId
     * @return PaymentStatusResponseModel
     */
    public function setPaymentId(string $paymentId): PaymentStatusResponseModel
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInitiated(): bool
    {
        return $this->status === static::PAYMENT_STATUS_INITIATED;
    }

    /**
     * @return bool
     */
    public function isInWaitingForNotificationStatus(): bool
    {
        return $this->status === static::PAYMENT_STATUS_WAITING_FOR_NOTIFICATION;
    }


    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->status === static::PAYMENT_STATUS_CONFIRMED;
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->status === static::PAYMENT_STATUS_CANCELLED;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return GetPaymentStatusEndpoint::class;
    }
}