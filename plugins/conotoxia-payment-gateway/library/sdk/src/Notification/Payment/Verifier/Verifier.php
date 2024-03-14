<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification\Payment\Verifier;

use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Exception\PaymentNotificationException;
use CKPL\Pay\Notification\Payment\PaymentNotification;
use CKPL\Pay\Notification\Payment\PaymentNotificationInterface;
use CKPL\Pay\Security\JWT\Collection\DecodedCollectionInterface;

/**
 * Class Verifier.
 *
 * @package CKPL\Pay\Payment\Verifier
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
    protected const PAYMENT_ID = 'paymentId';

    /**
     * @type string
     */
    protected const EXTERNAL_PAYMENT_ID = 'externalPaymentId';

    /**
     * @type string
     */
    protected const CODE = 'code';

    /**
     * @type string
     */
    protected const DESCRIPTION = 'description';

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
     * @throws PaymentNotificationException
     *
     * @return PaymentNotificationInterface
     */
    public function getNotification(): PaymentNotificationInterface
    {
        $data = $this->getDataFromPayload();
        return new PaymentNotification($data['payment_id'], $data['external_payment_id'], $data[static::CODE], $data[static::DESCRIPTION], $data[static::ADDITIONAL_PARAMETERS]);
    }

    /**
     * @throws PaymentNotificationException
     *
     * @return array
     */
    protected function getDataFromPayload(): array
    {
        $payload = $this->decodedCollection->getPayload();

        try {
            $description = $payload->hasElement(static::DESCRIPTION)
                ? $payload->expectStringOrNull(static::DESCRIPTION)
                : null;

            return [
                'payment_id' => $payload->expectStringOrNull(static::PAYMENT_ID),
                'external_payment_id' => $payload->expectStringOrNull(static::EXTERNAL_PAYMENT_ID),
                static::CODE => $payload->expectStringOrNull(static::CODE),
                static::DESCRIPTION => $description,
                static::ADDITIONAL_PARAMETERS => $payload->getArrayValueByKey(static::ADDITIONAL_PARAMETERS)
            ];
        } catch (PayloadException $e) {
            throw new PaymentNotificationException('Payment notification response contains invalid data.', 0, $e);
        }
    }
}
