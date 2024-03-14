<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification\Refund\Verifier;

use CKPL\Pay\Exception\RefundNotificationException;
use CKPL\Pay\Notification\Refund\RefundNotificationInterface;

/**
 * Interface VerifierInterface.
 *
 * @package CKPL\Pay\Notification\Refund\Verifier
 */
interface VerifierInterface
{
    /**
     * @return RefundNotificationInterface
     * @throws RefundNotificationException
     */
    public function getNotification(): RefundNotificationInterface;
}
