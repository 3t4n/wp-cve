<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

/**
 * Class HttpForbiddenException.
 *
 * @package CKPL\Pay\Exception\Http
 */
class HttpForbiddenException extends HttpException
{
    /**
     * @type int
     */
    const STATUS_CODE = 403;
}
