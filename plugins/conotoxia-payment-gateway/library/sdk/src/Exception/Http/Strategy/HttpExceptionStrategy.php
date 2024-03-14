<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http\Strategy;

use CKPL\Pay\Exception\Http\HttpBadRequestException;
use CKPL\Pay\Exception\Http\HttpConflictException;
use CKPL\Pay\Exception\Http\HttpExceptionInterface;
use CKPL\Pay\Exception\Http\HttpForbiddenException;
use CKPL\Pay\Exception\Http\HttpInternalServerErrorException;
use CKPL\Pay\Exception\Http\HttpMethodNotAllowedException;
use CKPL\Pay\Exception\Http\HttpServiceUnavailableException;
use CKPL\Pay\Exception\Http\HttpUnsupportedMediaTypeException;
use CKPL\Pay\Exception\Http\UnsupportedHttpCodeException;

/**
 * Class HttpExceptionStrategy.
 *
 * @package CKPL\Pay\Exception\Http\Strategy
 */
class HttpExceptionStrategy implements HttpExceptionStrategyInterface
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * HttpExceptionStrategy constructor.
     *
     * @param int $statusCode
     */
    public function __construct(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @param string|null $reason
     * @param string|null $title
     *
     * @return HttpExceptionInterface
     */
    public function getException(string $reason = null, string $title = null): HttpExceptionInterface
    {
        switch ($this->statusCode) {
            case HttpBadRequestException::STATUS_CODE:
                $exception = new HttpBadRequestException(($title ?? 'Bad Request'), $reason);
                break;
            case HttpForbiddenException::STATUS_CODE:
                $exception = new HttpForbiddenException('Forbidden' ?? null);
                break;
            case HttpMethodNotAllowedException::STATUS_CODE:
                $exception = new HttpMethodNotAllowedException(($title ?? 'Method Not Allowed'), $reason);
                break;
            case HttpConflictException::STATUS_CODE:
                $exception = new HttpConflictException(($title ?? 'Conflict'), $reason);
                break;
            case HttpUnsupportedMediaTypeException::STATUS_CODE:
                $exception = new HttpUnsupportedMediaTypeException(($title ?? 'Unsupported Media Type'), $reason);
                break;
            case HttpInternalServerErrorException::STATUS_CODE:
                $exception = new HttpInternalServerErrorException(($title ?? 'Internal Server Error'), $reason);
                break;
            case HttpServiceUnavailableException::STATUS_CODE:
                $exception = new HttpServiceUnavailableException($title ?? 'Service Unavailable');
                break;
            default:
                $exception = new UnsupportedHttpCodeException($this->statusCode);
                break;
        }

        return $exception;
    }
}
