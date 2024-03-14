<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

/**
 * Class HttpUnauthorizedException.
 *
 * @package CKPL\Pay\Exception\Http
 */
class HttpUnauthorizedException extends HttpException
{
    /**
     * @type int
     */
    const STATUS_CODE = 401;
}
