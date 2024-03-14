<?php

namespace LassoLiteVendor\Http\Factory\Guzzle;

use LassoLiteVendor\GuzzleHttp\Psr7\Uri;
use LassoLiteVendor\Psr\Http\Message\UriFactoryInterface;
use LassoLiteVendor\Psr\Http\Message\UriInterface;
class UriFactory implements UriFactoryInterface
{
    public function createUri(string $uri = '') : UriInterface
    {
        return new Uri($uri);
    }
}
