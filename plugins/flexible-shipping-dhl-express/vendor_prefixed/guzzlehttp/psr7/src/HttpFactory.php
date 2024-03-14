<?php

declare (strict_types=1);
namespace DhlVendor\GuzzleHttp\Psr7;

use DhlVendor\Psr\Http\Message\RequestFactoryInterface;
use DhlVendor\Psr\Http\Message\RequestInterface;
use DhlVendor\Psr\Http\Message\ResponseFactoryInterface;
use DhlVendor\Psr\Http\Message\ResponseInterface;
use DhlVendor\Psr\Http\Message\ServerRequestFactoryInterface;
use DhlVendor\Psr\Http\Message\ServerRequestInterface;
use DhlVendor\Psr\Http\Message\StreamFactoryInterface;
use DhlVendor\Psr\Http\Message\StreamInterface;
use DhlVendor\Psr\Http\Message\UploadedFileFactoryInterface;
use DhlVendor\Psr\Http\Message\UploadedFileInterface;
use DhlVendor\Psr\Http\Message\UriFactoryInterface;
use DhlVendor\Psr\Http\Message\UriInterface;
/**
 * Implements all of the PSR-17 interfaces.
 *
 * Note: in consuming code it is recommended to require the implemented interfaces
 * and inject the instance of this class multiple times.
 */
final class HttpFactory implements \DhlVendor\Psr\Http\Message\RequestFactoryInterface, \DhlVendor\Psr\Http\Message\ResponseFactoryInterface, \DhlVendor\Psr\Http\Message\ServerRequestFactoryInterface, \DhlVendor\Psr\Http\Message\StreamFactoryInterface, \DhlVendor\Psr\Http\Message\UploadedFileFactoryInterface, \DhlVendor\Psr\Http\Message\UriFactoryInterface
{
    public function createUploadedFile(\DhlVendor\Psr\Http\Message\StreamInterface $stream, int $size = null, int $error = \UPLOAD_ERR_OK, string $clientFilename = null, string $clientMediaType = null) : \DhlVendor\Psr\Http\Message\UploadedFileInterface
    {
        if ($size === null) {
            $size = $stream->getSize();
        }
        return new \DhlVendor\GuzzleHttp\Psr7\UploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }
    public function createStream(string $content = '') : \DhlVendor\Psr\Http\Message\StreamInterface
    {
        return \DhlVendor\GuzzleHttp\Psr7\Utils::streamFor($content);
    }
    public function createStreamFromFile(string $file, string $mode = 'r') : \DhlVendor\Psr\Http\Message\StreamInterface
    {
        try {
            $resource = \DhlVendor\GuzzleHttp\Psr7\Utils::tryFopen($file, $mode);
        } catch (\RuntimeException $e) {
            if ('' === $mode || \false === \in_array($mode[0], ['r', 'w', 'a', 'x', 'c'], \true)) {
                throw new \InvalidArgumentException(\sprintf('Invalid file opening mode "%s"', $mode), 0, $e);
            }
            throw $e;
        }
        return \DhlVendor\GuzzleHttp\Psr7\Utils::streamFor($resource);
    }
    public function createStreamFromResource($resource) : \DhlVendor\Psr\Http\Message\StreamInterface
    {
        return \DhlVendor\GuzzleHttp\Psr7\Utils::streamFor($resource);
    }
    public function createServerRequest(string $method, $uri, array $serverParams = []) : \DhlVendor\Psr\Http\Message\ServerRequestInterface
    {
        if (empty($method)) {
            if (!empty($serverParams['REQUEST_METHOD'])) {
                $method = $serverParams['REQUEST_METHOD'];
            } else {
                throw new \InvalidArgumentException('Cannot determine HTTP method');
            }
        }
        return new \DhlVendor\GuzzleHttp\Psr7\ServerRequest($method, $uri, [], null, '1.1', $serverParams);
    }
    public function createResponse(int $code = 200, string $reasonPhrase = '') : \DhlVendor\Psr\Http\Message\ResponseInterface
    {
        return new \DhlVendor\GuzzleHttp\Psr7\Response($code, [], null, '1.1', $reasonPhrase);
    }
    public function createRequest(string $method, $uri) : \DhlVendor\Psr\Http\Message\RequestInterface
    {
        return new \DhlVendor\GuzzleHttp\Psr7\Request($method, $uri);
    }
    public function createUri(string $uri = '') : \DhlVendor\Psr\Http\Message\UriInterface
    {
        return new \DhlVendor\GuzzleHttp\Psr7\Uri($uri);
    }
}
