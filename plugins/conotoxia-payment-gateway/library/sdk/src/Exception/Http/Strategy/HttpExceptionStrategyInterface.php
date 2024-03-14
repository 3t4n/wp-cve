<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http\Strategy;

use CKPL\Pay\Exception\Http\HttpExceptionInterface;

/**
 * Interface HttpExceptionStrategyInterface.
 *
 * @package CKPL\Pay\Exception\Http\Strategy
 */
interface HttpExceptionStrategyInterface
{
    /**
     * @param string|null $reason
     * @param string|null $title
     *
     * @return HttpExceptionInterface
     */
    public function getException(string $reason = null, string $title = null): HttpExceptionInterface;
}
