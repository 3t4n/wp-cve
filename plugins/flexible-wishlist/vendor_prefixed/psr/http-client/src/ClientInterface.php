<?php

namespace FlexibleWishlistVendor\Psr\Http\Client;

use FlexibleWishlistVendor\Psr\Http\Message\RequestInterface;
use FlexibleWishlistVendor\Psr\Http\Message\ResponseInterface;
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
    public function sendRequest(\FlexibleWishlistVendor\Psr\Http\Message\RequestInterface $request) : \FlexibleWishlistVendor\Psr\Http\Message\ResponseInterface;
}
