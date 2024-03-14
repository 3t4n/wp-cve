<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification\Refund;

use CKPL\Pay\Notification\NotificationInterface;

/**
 * Interface RefundNotificationInterface.
 *
 * @package CKPL\Pay\Notification\Refund
 */
interface RefundNotificationInterface extends NotificationInterface
{
    /**
     * @return string
     */
    public function getRefundId(): string;

    /**
     * @return string|null
     */
    public function getExternalRefundId(): ?string;

    /**
     * @return string
     */
    public function getPaymentId(): string;

    /**
     * @return string
     */
    public function getExternalPaymentId(): string;

    /**
     * @return bool|null
     */
    public function isMaxRefundAchieved(): ?bool;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return mixed
     */
    public function getAdditionalParameters();

    /**
     * @return bool
     */
    public function isNew(): bool;

    /**
     * @return bool
     */
    public function isCompleted(): bool;

    /**
     * @return bool
     */
    public function isProcessing(): bool;

    /**
     * @return bool
     */
    public function isPending(): bool;

    /**
     * @return bool
     */
    public function isCancelled(): bool;
}
