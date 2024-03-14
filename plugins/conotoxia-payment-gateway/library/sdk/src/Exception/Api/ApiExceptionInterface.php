<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

/**
 * Interface ApiExceptionInterface.
 *
 * @package CKPL\Pay\Exception\Api
 */
interface ApiExceptionInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
