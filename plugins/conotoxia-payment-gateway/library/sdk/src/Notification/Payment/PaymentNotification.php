<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification\Payment;

/**
 * Class PaymentNotification.
 *
 * @package CKPL\Pay\Notification\Payment
 */
class PaymentNotification implements PaymentNotificationInterface
{
    /**
     * @type string
     */
    const PAYMENT_STATUS_COMPLETED = 'COMPLETED';
    /**
     * @type string
     */
    const PAYMENT_STATUS_CANCELLED = 'CANCELLED';
    /**
     * @type string
     */
    const PAYMENT_STATUS_REJECTED = 'REJECTED';
    /**
     * @type string
     */
    const PAYMENT_STATUS_BOOKED = 'BOOKED';
    /**
     * @var string
     */
    protected $paymentId;
    /**
     * @var string
     */
    protected $externalPaymentId;
    /**
     * @var string
     */
    protected $code;
    /**
     * @var string|null
     */
    protected $description;
    /**
     * @var mixed|string|null
     */
    protected $additionalParameters;

    /**
     * Status constructor.
     *
     * @param string $paymentId
     * @param string $externalPaymentId
     * @param string $code
     * @param string|null $description
     * @param mixed|string|null $additionalParameters
     */
    public function __construct(string $paymentId, string $externalPaymentId, string $code, string $description = null, $additionalParameters = null)
    {
        $this->paymentId = $paymentId;
        $this->externalPaymentId = $externalPaymentId;
        $this->code = $code;
        $this->description = $description;
        $this->additionalParameters = $additionalParameters;
    }

    /**
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * @return string
     */
    public function getExternalPaymentId(): string
    {
        return $this->externalPaymentId;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return mixed|string|null
     */
    public function getAdditionalParameters()
    {
        return $this->additionalParameters;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->code === static::PAYMENT_STATUS_COMPLETED;
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->code === static::PAYMENT_STATUS_CANCELLED;
    }

    /**
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->code === static::PAYMENT_STATUS_REJECTED;
    }

    /**
     * @return bool
     */
    public function isBooked(): bool
    {
        return $this->code === static::PAYMENT_STATUS_BOOKED;
    }

    /**
     * @return bool
     */
    public function isPaymentNotification(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isRefundNotification(): bool
    {
        return false;
    }
}
