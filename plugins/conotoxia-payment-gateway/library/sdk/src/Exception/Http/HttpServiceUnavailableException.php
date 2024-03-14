<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

/**
 * Class HttpServiceUnavailableException.
 *
 * @package CKPL\Pay\Exception\Http
 */
class HttpServiceUnavailableException extends HttpException
{
    /**
     * @type int
     */
    const STATUS_CODE = 503;
}
