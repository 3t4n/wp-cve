<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

/**
 * Class HttpMethodNotAllowedException.
 *
 * @package CKPL\Pay\Exception\Http
 */
class HttpMethodNotAllowedException extends HttpException
{
    /**
     * @type int
     */
    const STATUS_CODE = 405;
}
