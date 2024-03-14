<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

/**
 * Class HttpBadRequestException.
 *
 * @package CKPL\Pay\Exception\Http
 */
class HttpBadRequestException extends HttpException
{
    /**
     * @type int
     */
    const STATUS_CODE = 400;
}
