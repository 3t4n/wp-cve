<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification;

use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Exception\PaymentNotificationException;
use CKPL\Pay\Exception\RefundNotificationException;
use CKPL\Pay\Service\BaseService;

class NotificationManager extends BaseService implements NotificationManagerInterface
{
    /**
     * Decodes received payment notification response.
     *
     * @param string $input
     *
     * @return NotificationInterface
     *
     * @throws PayloadException
     * @throws RefundNotificationException
     * @throws PaymentNotificationException
     */
    public function getNotification(string $input): NotificationInterface
    {
        $notification = $this->dependencyFactory->getSecurityManager()->decodeResponse($input);
        $payload = $notification->getPayload();
        if (!$payload->hasElement('type')) {
            throw new PayloadException('Notification should contains type field');
        }

        $type = $payload->raw()['type'];
        switch ($type) {
            case 'PAYMENT':
                return $this->dependencyFactory->createPaymentVerifier($notification)->getNotification();
            case 'REFUND':
                return $this->dependencyFactory->createRefundVerifier($notification)->getNotification();
            default:
                throw new PayloadException('Unknown notification type: ' . $type);
        }
    }
}
