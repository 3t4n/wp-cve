<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class MaxRefundsReachedException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class MaxRefundsReachedException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'max-refunds-reached';

    protected $messages = [
        'pl' => 'Osiągnięto maksymalną liczbę zwrotów dla płatności.',
        'en' => 'The maximum number of refunds for payment has been reached.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
