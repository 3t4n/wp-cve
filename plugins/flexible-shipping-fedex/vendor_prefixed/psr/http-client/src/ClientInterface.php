<?php

namespace FedExVendor\Psr\Http\Client;

use FedExVendor\Psr\Http\Message\RequestInterface;
use FedExVendor\Psr\Http\Message\ResponseInterface;
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
    public function sendRequest(\FedExVendor\Psr\Http\Message\RequestInterface $request) : \FedExVendor\Psr\Http\Message\ResponseInterface;
}
