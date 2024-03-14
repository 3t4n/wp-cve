<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

/**
 * Interface HttpExceptionInterface.
 *
 * @package CKPL\Pay\Exception\Http
 */
interface HttpExceptionInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int;
}
