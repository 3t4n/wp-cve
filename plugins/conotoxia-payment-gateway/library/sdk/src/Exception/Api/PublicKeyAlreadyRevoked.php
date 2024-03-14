<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpBadRequestException;

/**
 * Class PublicKeyAlreadyRevoked.
 *
 * @package CKPL\Pay\Exception\Api
 */
class PublicKeyAlreadyRevoked extends HttpBadRequestException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'public-key-already-revoked';

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}