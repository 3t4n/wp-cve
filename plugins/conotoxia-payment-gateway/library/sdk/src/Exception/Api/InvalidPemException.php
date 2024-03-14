<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpBadRequestException;

/**
 * Class InvalidPemException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class InvalidPemException extends HttpBadRequestException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'invalid-pem';

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
