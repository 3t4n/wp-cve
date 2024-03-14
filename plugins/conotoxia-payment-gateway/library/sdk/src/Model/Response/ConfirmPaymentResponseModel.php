<?php

namespace CKPL\Pay\Model\Response;

use CKPL\Pay\Endpoint\ConfirmPaymentEndpoint;
use CKPL\Pay\Model\ResponseModelInterface;


/**
 * Class PaymentConfirmResponseModel.
 *
 * @package CKPL\Pay\Model\Response
 */
class ConfirmPaymentResponseModel implements ResponseModelInterface
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
    const PAYMENT_STATUS_CANCELLED = 'CANCELLED';

    /**
     * @var string|null
     */
    protected $paymentStatus;

    /**
     * @return string|null
     */
    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    /**
     * @param string $paymentStatus
     * @return ConfirmPaymentResponseModel
     */
    public function setPaymentStatus(string $paymentStatus): ConfirmPaymentResponseModel
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInitiated(): bool
    {
        return $this->paymentStatus === static::PAYMENT_STATUS_INITIATED;
    }

    /**
     * @return bool
     */
    public function isInWaitingForNotificationStatus(): bool
    {
        return $this->paymentStatus === static::PAYMENT_STATUS_WAITING_FOR_NOTIFICATION;
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->paymentStatus === static::PAYMENT_STATUS_CANCELLED;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return ConfirmPaymentEndpoint::class;
    }
}
