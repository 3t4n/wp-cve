<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

use CKPL\Pay\Exception\Exception;
use function sprintf;

/**
 * Class HttpException.
 *
 * @package CKPL\Pay\Exception\Http
 */
abstract class HttpException extends Exception implements HttpExceptionInterface
{
    /**
     * @type int
     */
    const STATUS_CODE = 418;

    /**
     * HttpException constructor.
     *
     * @param string      $title
     * @param string|null $reason
     */
    public function __construct(string $title, string $reason = null)
    {
        parent::__construct(null !== $reason ? sprintf('%s: %s', $title, $reason) : $title);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return static::STATUS_CODE;
    }
}
