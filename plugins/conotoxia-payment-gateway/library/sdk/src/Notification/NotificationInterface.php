<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification;

/**
 * Interface NotificationInterface.
 *
 * @package CKPL\Pay\Notification
 */
interface NotificationInterface
{
    /**
     * @return bool
     */
    public function isPaymentNotification(): bool;

    /**
     * @return bool
     */
    public function isRefundNotification(): bool;
}
