<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class PaymentMethodNotAvailableException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class PaymentMethodNotAvailableException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'payment-method-not-available';

    protected $messages = [
        'pl' => 'Metoda płatności jest niedostępna.',
        'en' => 'The selected payment method is not available.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
