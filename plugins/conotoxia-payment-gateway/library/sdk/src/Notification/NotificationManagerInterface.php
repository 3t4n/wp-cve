<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification;

use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Exception\PaymentNotificationException;
use CKPL\Pay\Exception\RefundNotificationException;

/**
 * Interface NotificationManagerInterface.
 *
 * @package CKPL\Pay\Notification
 */
interface NotificationManagerInterface
{
    /**
     * Decodes received payment notification response.
     *
     * Example:
     *     $notification = $this->notification()->getNotification(\file_get_contents('php://input'));
     *
     * @param string $input
     *
     * @return NotificationInterface
     *
     * @throws PayloadException
     * @throws RefundNotificationException
     * @throws PaymentNotificationException
     */
    public function getNotification(string $input): NotificationInterface;
}
