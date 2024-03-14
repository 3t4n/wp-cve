<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class RefundAmountTooLargeException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class RefundAmountTooLargeException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'refund-amount-too-large';

    protected $messages = [
        'pl' => 'Kwota zwrotu przekracza kwotę płatności. W przypadku zwrotu częściowego suma wszystkich kwot zwrotów częściowych przekracza kwotę płatności.',
        'en' => 'The refund amount exceeds the payment amount. In the case of partial refunds, the sum of all partial refunds exceeds the amount of payment.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
