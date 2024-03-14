<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

/**
 * Class HttpUnsupportedMediaTypeException.
 *
 * @package CKPL\Pay\Exception\Http
 */
class HttpUnsupportedMediaTypeException extends HttpException
{
    /**
     * @type int
     */
    const STATUS_CODE = 415;
}
