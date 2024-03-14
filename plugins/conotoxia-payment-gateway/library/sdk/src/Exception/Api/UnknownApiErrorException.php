<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpNotFoundException;

/**
 * Class UnknownApiErrorException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class UnknownApiErrorException extends HttpNotFoundException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'unknown-api-exception';

    protected $messages = [
        'pl' => 'Wystąpił błąd.',
        'en' => 'Error occurred.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
