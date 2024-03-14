<?php

namespace LassoLiteVendor\Http\Factory\Guzzle;

use LassoLiteVendor\GuzzleHttp\Psr7\ServerRequest;
use LassoLiteVendor\Psr\Http\Message\ServerRequestFactoryInterface;
use LassoLiteVendor\Psr\Http\Message\ServerRequestInterface;
class ServerRequestFactory implements ServerRequestFactoryInterface
{
    public function createServerRequest(string $method, $uri, array $serverParams = []) : ServerRequestInterface
    {
        if (empty($method)) {
            if (!empty($serverParams['REQUEST_METHOD'])) {
                $method = $serverParams['REQUEST_METHOD'];
            } else {
                throw new \InvalidArgumentException('Cannot determine HTTP method');
            }
        }
        return new ServerRequest($method, $uri, [], null, '1.1', $serverParams);
    }
}
