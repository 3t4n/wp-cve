<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http\Factory;

use CKPL\Pay\Definition\Payload\PayloadInterface;
use CKPL\Pay\Exception\Api\ApiExceptionInterface;
use CKPL\Pay\Exception\Api\Strategy\ApiExceptionStrategy;
use CKPL\Pay\Exception\Http\HttpExceptionInterface;
use CKPL\Pay\Exception\Http\Strategy\HttpExceptionStrategy;
use CKPL\Pay\Exception\PayloadException;

/**
 * Class HttpExceptionFactory.
 *
 * @package CKPL\Pay\Exception\Http\Factory
 */
class HttpExceptionFactory implements HttpExceptionFactoryInterface
{
    /**
     * @param PayloadInterface $payload
     * @param int              $statusCode
     *
     * @throws PayloadException
     *
     * @return HttpExceptionInterface
     */
    public function getExceptionForResponse(PayloadInterface $payload, int $statusCode): HttpExceptionInterface
    {
        if (!$exception = $this->findReason($payload)) {
            $title = $payload->hasElement('title') ? $payload->expectStringOrNull('title') : null;
            $reason = $payload->hasElement('detail') ? $payload->expectStringOrNull('detail') : null;

            $exception = (new HttpExceptionStrategy($statusCode))->getException($reason, $title);
        }

        return $exception;
    }

    /**
     * @param PayloadInterface $payload
     *
     * @throws PayloadException
     *
     * @return ApiExceptionInterface|HttpExceptionInterface|null
     */
    protected function findReason(PayloadInterface $payload): ?ApiExceptionInterface
    {
        $apiExceptionStrategy = new ApiExceptionStrategy($payload);

        return $apiExceptionStrategy->isApi() ? $apiExceptionStrategy->getException() : null;
    }
}
