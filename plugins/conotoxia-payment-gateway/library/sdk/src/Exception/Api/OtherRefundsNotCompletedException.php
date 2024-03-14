<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class OtherRefundsNotCompletedException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class OtherRefundsNotCompletedException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'other-refunds-not-completed';

    protected $messages = [
        'pl' => 'Część zwrotów utworzonych dla tej płatności nie została jeszcze zakończona.',
        'en' => 'Other refunds are not completed yet for given payment.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
