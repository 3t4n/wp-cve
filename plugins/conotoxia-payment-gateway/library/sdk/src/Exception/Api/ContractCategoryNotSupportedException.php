<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class ContractCategoryNotSupportedException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class ContractCategoryNotSupportedException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'contract-category-not-supported';

    protected $messages = [
        'pl' => 'Kategoria podana w dyspozycji płatności jest inna niż zdefiniowana w umowie.',
        'en' => 'The category specified in the payment order is different from that defined in the contract.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
