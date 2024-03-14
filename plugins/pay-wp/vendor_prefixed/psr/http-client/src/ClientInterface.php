<?php

namespace WPPayVendor\Psr\Http\Client;

use WPPayVendor\Psr\Http\Message\RequestInterface;
use WPPayVendor\Psr\Http\Message\ResponseInterface;
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
    public function sendRequest(\WPPayVendor\Psr\Http\Message\RequestInterface $request) : \WPPayVendor\Psr\Http\Message\ResponseInterface;
}
