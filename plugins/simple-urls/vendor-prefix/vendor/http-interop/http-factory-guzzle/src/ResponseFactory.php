<?php

namespace LassoLiteVendor\Http\Factory\Guzzle;

use LassoLiteVendor\GuzzleHttp\Psr7\Response;
use LassoLiteVendor\Psr\Http\Message\ResponseFactoryInterface;
use LassoLiteVendor\Psr\Http\Message\ResponseInterface;
class ResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = '') : ResponseInterface
    {
        return new Response($code, [], null, '1.1', $reasonPhrase);
    }
}
