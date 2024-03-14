<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification\Payment;

use CKPL\Pay\Notification\NotificationInterface;

/**
 * Interface PaymentNotificationInterface.
 *
 * @package CKPL\Pay\Notification\Payment
 */
interface PaymentNotificationInterface extends NotificationInterface
{
    /**
     * @return string
     */
    public function getPaymentId(): string;

    /**
     * @return string
     */
    public function getExternalPaymentId(): string;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @return mixed
     */
    public function getAdditionalParameters();

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @return bool
     */
    public function isCompleted(): bool;

    /**
     * @return bool
     */
    public function isCancelled(): bool;

    /**
     * @return bool
     */
    public function isRejected(): bool;

    /**
     * @return bool
     */
    public function isBooked(): bool;
}
