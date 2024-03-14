<?php

namespace DhlVendor\GuzzleHttp;

use DhlVendor\Psr\Http\Message\RequestInterface;
use DhlVendor\Psr\Http\Message\ResponseInterface;
interface MessageFormatterInterface
{
    /**
     * Returns a formatted message string.
     *
     * @param RequestInterface       $request  Request that was sent
     * @param ResponseInterface|null $response Response that was received
     * @param \Throwable|null        $error    Exception that was received
     */
    public function format(\DhlVendor\Psr\Http\Message\RequestInterface $request, \DhlVendor\Psr\Http\Message\ResponseInterface $response = null, \Throwable $error = null) : string;
}
