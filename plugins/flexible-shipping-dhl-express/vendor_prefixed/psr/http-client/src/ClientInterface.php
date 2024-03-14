<?php

namespace DhlVendor\Psr\Http\Client;

use DhlVendor\Psr\Http\Message\RequestInterface;
use DhlVendor\Psr\Http\Message\ResponseInterface;
interface ClientInterface
{
    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     */
    public function sendRequest(\DhlVendor\Psr\Http\Message\RequestInterface $request) : \DhlVendor\Psr\Http\Message\ResponseInterface;
}
