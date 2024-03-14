<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api\Strategy;

use CKPL\Pay\Exception\Api\ApiExceptionInterface;

/**
 * Interface ApiExceptionStrategyInterface.
 *
 * @package CKPL\Pay\Exception\Api\Strategy
 */
interface ApiExceptionStrategyInterface
{
    /**
     * @return bool
     */
    public function isApi(): bool;

    /**
     * @return ApiExceptionInterface
     */
    public function getException(): ApiExceptionInterface;
}
