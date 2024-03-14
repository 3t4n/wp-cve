<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class PointOfSaleCurrencyNotSupportedException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class PointOfSaleCurrencyNotSupportedException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'point-of-sale-currency-not-supported';

    protected $messages = [
        'pl' => 'Waluta podana w dyspozycji płatności jest inna niż zdefiniowana dla punktu płatności.',
        'en' => 'The currency specified in the payment order is different from that defined for the point of sale.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
