<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class PaymentNotBookedException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class PaymentNotBookedException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'payment-not-booked';

    protected $messages = [
        'pl' => 'Płatność, na którą jest realizowany zwrot nie jest zaksięgowana.',
        'en' => 'The payment for which the refund is made is not booked.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
