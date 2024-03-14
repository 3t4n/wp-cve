<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification\Refund\Verifier;

use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Exception\RefundNotificationException;
use CKPL\Pay\Notification\Refund\RefundNotification;
use CKPL\Pay\Notification\Refund\RefundNotificationInterface;
use CKPL\Pay\Security\JWT\Collection\DecodedCollectionInterface;

/**
 * Class Verifier.
 *
 * @package CKPL\Pay\Refund\Verifier
 */
class Verifier implements VerifierInterface
{
    /**
     * @var DecodedCollectionInterface
     */
    protected $decodedCollection;

    /**
     * @type string
     */
    protected const ADDITIONAL_PARAMETERS = 'additionalParameters';

    /**
     * Verifier constructor.
     *
     * @param DecodedCollectionInterface $decodedCollection
     */
    public function __construct(DecodedCollectionInterface $decodedCollection)
    {
        $this->decodedCollection = $decodedCollection;
    }

    /**
     * @return RefundNotificationInterface
     * @throws RefundNotificationException
     */
    public function getNotification(): RefundNotificationInterface
    {
        $data = $this->getDataFromPayload();
        return new RefundNotification($data['refund_id'], $data['code'], $data['payment_id'], $data['external_payment_id'], $data['external_refund_id'], $data['max_refund_achieved'], $data[static::ADDITIONAL_PARAMETERS]);
    }

    /**
     * @return array
     * @throws RefundNotificationException
     *
     */
    protected function getDataFromPayload(): array
    {
        $payload = $this->decodedCollection->getPayload();

        try {
            $externalRefundId = $payload->hasElement('externalRefundId')
                ? $payload->expectStringOrNull('externalRefundId')
                : null;
            $maxRefundAchieved = $payload->hasElement('maxRefundAchieved')
                ? $payload->expectBooleanOrNull('maxRefundAchieved')
                : null;

            return [
                'refund_id' => $payload->expectStringOrNull('refundId'),
                'payment_id' => $payload->expectStringOrNull('paymentId'),
                'external_payment_id' => $payload->expectStringOrNull('externalPaymentId'),
                'external_refund_id' => $externalRefundId,
                'code' => $payload->expectStringOrNull('code'),
                'max_refund_achieved' => $maxRefundAchieved,
                static::ADDITIONAL_PARAMETERS => $payload->getArrayValueByKey(static::ADDITIONAL_PARAMETERS)
            ];
        } catch (PayloadException $e) {
            throw new RefundNotificationException('Refund notification response contains invalid data.', 0, $e);
        }
    }
}
