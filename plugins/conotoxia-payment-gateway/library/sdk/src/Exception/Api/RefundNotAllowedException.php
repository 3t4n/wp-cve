<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

class RefundNotAllowedException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'refund-not-allowed';

    protected $messages = [
        'pl' => 'Nie można zlecić zwrotu dla wybranej metody płatności.',
        'en' => 'Refund cannot be ordered for the selected payment method.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}