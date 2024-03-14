<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class ContractCategoryNotSupportedException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class CurrencyUnavailableException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'currency-unavailable';

    protected $messages = [
        'pl' => 'Wybrana waluta jest niedostępna.',
        'en' => 'The selected currency is unavailable.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
