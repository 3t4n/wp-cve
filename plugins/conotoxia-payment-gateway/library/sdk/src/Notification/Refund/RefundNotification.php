<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification\Refund;

/**
 * Class RefundNotification.
 *
 * @package CKPL\Pay\Notification\Refund
 */
class RefundNotification implements RefundNotificationInterface
{
    /**
     * @var string
     */
    protected $refundId;

    /**
     * @var string|null
     */
    protected $externalRefundId;

    /**
     * @var string
     */
    protected $paymentId;

    /**
     * @var string
     */
    protected $externalPaymentId;

    /**
     * @var bool
     */
    protected $maxRefundAchieved;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var mixed|string|null
     */
    protected $additionalParameters;

    /**
     * @type string
     */
    const REFUND_STATUS_NEW = 'NEW';

    /**
     * @type string
     */
    const REFUND_STATUS_COMPLETED = 'COMPLETED';

    /**
     * @type string
     */
    const REFUND_STATUS_PROCESSING = 'PROCESSING';

    /**
     * @type string
     */
    const REFUND_STATUS_PENDING = 'PENDING';

    /**
     * @type string
     */
    const REFUND_STATUS_CANCELLED = 'CANCELLED';

    /**
     * Status constructor.
     *
     * @param string $refundId
     * @param string $code
     * @param string $paymentId
     * @param string $externalPaymentId
     * @param string|null $externalRefundId
     * @param bool|null $maxRefundAchieved
     * @param null $additionalParameters
     */
    public function __construct(string $refundId, string $code, string $paymentId, string $externalPaymentId, string $externalRefundId = null, bool $maxRefundAchieved = null, $additionalParameters = null)
    {
        $this->refundId = $refundId;
        $this->code = $code;
        $this->paymentId = $paymentId;
        $this->externalPaymentId = $externalPaymentId;
        $this->externalRefundId = $externalRefundId;
        $this->maxRefundAchieved = $maxRefundAchieved;
        $this->additionalParameters = $additionalParameters;
    }

    /**
     * @return string
     */
    public function getRefundId(): string
    {
        return $this->refundId;
    }

    /**
     * @return string|null
     */
    public function getExternalRefundId(): ?string
    {
        return $this->externalRefundId;
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
     * @return bool|null
     */
    public function isMaxRefundAchieved(): ?bool
    {
        return $this->maxRefundAchieved;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->code === static::REFUND_STATUS_NEW;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->code === static::REFUND_STATUS_COMPLETED;
    }

    /**
     * @return bool
     */
    public function isProcessing(): bool
    {
        return $this->code === static::REFUND_STATUS_PROCESSING;
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->code === static::REFUND_STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->code === static::REFUND_STATUS_CANCELLED;
    }

    /**
     * @return bool
     */
    public function isPaymentNotification(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isRefundNotification(): bool
    {
        return true;
    }
}
