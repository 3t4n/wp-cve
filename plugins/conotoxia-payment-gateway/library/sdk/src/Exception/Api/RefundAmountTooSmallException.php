<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class RefundAmountTooSmallException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class RefundAmountTooSmallException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'refund-money-below-minimal-amount';

    /**
     * @type array
     */
    protected $messages = [];

    /**
     * RefundAmountTooSmallException constructor.
     *
     * @param array $limit
     */
    public function __construct(string $title, array $limit)
    {
        parent::__construct($title);

        $this->messages = [
            'pl' => sprintf('Wartość zwrotu jest poniżej minimalnej wartości wynoszącej %s %s.',
                $limit['value'],
                $limit['currency']),
            'en' => sprintf('The refund value is below the minimum value of %s %s.',
                $limit['value'],
                $limit['currency'])
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
