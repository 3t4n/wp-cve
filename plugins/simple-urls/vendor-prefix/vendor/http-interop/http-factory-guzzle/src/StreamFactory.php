<?php

namespace LassoLiteVendor\Http\Factory\Guzzle;

use LassoLiteVendor\GuzzleHttp\Psr7\Stream;
use LassoLiteVendor\GuzzleHttp\Psr7\Utils;
use LassoLiteVendor\Psr\Http\Message\StreamFactoryInterface;
use LassoLiteVendor\Psr\Http\Message\StreamInterface;
use function function_exists;
use function LassoLiteVendor\GuzzleHttp\Psr7\stream_for;
use function LassoLiteVendor\GuzzleHttp\Psr7\try_fopen;
class StreamFactory implements StreamFactoryInterface
{
    public function createStream(string $content = '') : StreamInterface
    {
        if (function_exists('LassoLiteVendor\\GuzzleHttp\\Psr7\\stream_for')) {
            // fallback for guzzlehttp/psr7<1.7.0
            return stream_for($content);
        }
        return Utils::streamFor($content);
    }
    public function createStreamFromFile(string $file, string $mode = 'r') : StreamInterface
    {
        if (function_exists('LassoLiteVendor\\GuzzleHttp\\Psr7\\try_fopen')) {
            // fallback for guzzlehttp/psr7<1.7.0
            $resource = try_fopen($file, $mode);
        } else {
            $resource = Utils::tryFopen($file, $mode);
        }
        return $this->createStreamFromResource($resource);
    }
    public function createStreamFromResource($resource) : StreamInterface
    {
        return new Stream($resource);
    }
}
