<?php

namespace OctolizeShippingNoticesVendor\Psr\Http\Client;

use OctolizeShippingNoticesVendor\Psr\Http\Message\RequestInterface;
use OctolizeShippingNoticesVendor\Psr\Http\Message\ResponseInterface;
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
    public function sendRequest(\OctolizeShippingNoticesVendor\Psr\Http\Message\RequestInterface $request) : \OctolizeShippingNoticesVendor\Psr\Http\Message\ResponseInterface;
}
