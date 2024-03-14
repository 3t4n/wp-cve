<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

/**
 * Class HttpInternalServerErrorException.
 *
 * @package CKPL\Pay\Exception\Http
 */
class HttpInternalServerErrorException extends HttpException
{
    /**
     * @type int
     */
    const STATUS_CODE = 500;
}
