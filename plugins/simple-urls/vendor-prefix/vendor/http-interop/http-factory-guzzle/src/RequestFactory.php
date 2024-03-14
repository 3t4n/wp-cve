<?php

namespace LassoLiteVendor\Http\Factory\Guzzle;

use LassoLiteVendor\GuzzleHttp\Psr7\Request;
use LassoLiteVendor\Psr\Http\Message\RequestFactoryInterface;
use LassoLiteVendor\Psr\Http\Message\RequestInterface;
class RequestFactory implements RequestFactoryInterface
{
    public function createRequest(string $method, $uri) : RequestInterface
    {
        return new Request($method, $uri);
    }
}
