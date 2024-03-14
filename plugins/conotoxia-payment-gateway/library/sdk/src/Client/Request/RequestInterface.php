<?php

declare(strict_types=1);

namespace CKPL\Pay\Client\Request;

use CKPL\Pay\Exception\ClientException;

/**
 * Interface RequestInterface.
 *
 * @package CKPL\Pay\Client\Request
 */
interface RequestInterface
{
    /**
     * @param array $parameters
     *
     * @return RequestInterface
     */
    public function parameters(array $parameters): RequestInterface;

    /**
     * @param array $headers
     *
     * @return RequestInterface
     */
    public function headers(array $headers): RequestInterface;

    /**
     * @throws ClientException
     *
     * @return void
     */
    public function send(): void;
}
