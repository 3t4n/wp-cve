<?php

declare (strict_types=1);
namespace WPPayVendor\GuzzleHttp\Psr7;

use WPPayVendor\Psr\Http\Message\RequestFactoryInterface;
use WPPayVendor\Psr\Http\Message\RequestInterface;
use WPPayVendor\Psr\Http\Message\ResponseFactoryInterface;
use WPPayVendor\Psr\Http\Message\ResponseInterface;
use WPPayVendor\Psr\Http\Message\ServerRequestFactoryInterface;
use WPPayVendor\Psr\Http\Message\ServerRequestInterface;
use WPPayVendor\Psr\Http\Message\StreamFactoryInterface;
use WPPayVendor\Psr\Http\Message\StreamInterface;
use WPPayVendor\Psr\Http\Message\UploadedFileFactoryInterface;
use WPPayVendor\Psr\Http\Message\UploadedFileInterface;
use WPPayVendor\Psr\Http\Message\UriFactoryInterface;
use WPPayVendor\Psr\Http\Message\UriInterface;
/**
 * Implements all of the PSR-17 interfaces.
 *
 * Note: in consuming code it is recommended to require the implemented interfaces
 * and inject the instance of this class multiple times.
 */
final class HttpFactory implements \WPPayVendor\Psr\Http\Message\RequestFactoryInterface, \WPPayVendor\Psr\Http\Message\ResponseFactoryInterface, \WPPayVendor\Psr\Http\Message\ServerRequestFactoryInterface, \WPPayVendor\Psr\Http\Message\StreamFactoryInterface, \WPPayVendor\Psr\Http\Message\UploadedFileFactoryInterface, \WPPayVendor\Psr\Http\Message\UriFactoryInterface
{
    public function createUploadedFile(\WPPayVendor\Psr\Http\Message\StreamInterface $stream, int $size = null, int $error = \UPLOAD_ERR_OK, string $clientFilename = null, string $clientMediaType = null) : \WPPayVendor\Psr\Http\Message\UploadedFileInterface
    {
        if ($size === null) {
            $size = $stream->getSize();
        }
        return new \WPPayVendor\GuzzleHttp\Psr7\UploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }
    public function createStream(string $content = '') : \WPPayVendor\Psr\Http\Message\StreamInterface
    {
        return \WPPayVendor\GuzzleHttp\Psr7\Utils::streamFor($content);
    }
    public function createStreamFromFile(string $file, string $mode = 'r') : \WPPayVendor\Psr\Http\Message\StreamInterface
    {
        try {
            $resource = \WPPayVendor\GuzzleHttp\Psr7\Utils::tryFopen($file, $mode);
        } catch (\RuntimeException $e) {
            if ('' === $mode || \false === \in_array($mode[0], ['r', 'w', 'a', 'x', 'c'], \true)) {
                throw new \InvalidArgumentException(\sprintf('Invalid file opening mode "%s"', $mode), 0, $e);
            }
            throw $e;
        }
        return \WPPayVendor\GuzzleHttp\Psr7\Utils::streamFor($resource);
    }
    public function createStreamFromResource($resource) : \WPPayVendor\Psr\Http\Message\StreamInterface
    {
        return \WPPayVendor\GuzzleHttp\Psr7\Utils::streamFor($resource);
    }
    public function createServerRequest(string $method, $uri, array $serverParams = []) : \WPPayVendor\Psr\Http\Message\ServerRequestInterface
    {
        if (empty($method)) {
            if (!empty($serverParams['REQUEST_METHOD'])) {
                $method = $serverParams['REQUEST_METHOD'];
            } else {
                throw new \InvalidArgumentException('Cannot determine HTTP method');
            }
        }
        return new \WPPayVendor\GuzzleHttp\Psr7\ServerRequest($method, $uri, [], null, '1.1', $serverParams);
    }
    public function createResponse(int $code = 200, string $reasonPhrase = '') : \WPPayVendor\Psr\Http\Message\ResponseInterface
    {
        return new \WPPayVendor\GuzzleHttp\Psr7\Response($code, [], null, '1.1', $reasonPhrase);
    }
    public function createRequest(string $method, $uri) : \WPPayVendor\Psr\Http\Message\RequestInterface
    {
        return new \WPPayVendor\GuzzleHttp\Psr7\Request($method, $uri);
    }
    public function createUri(string $uri = '') : \WPPayVendor\Psr\Http\Message\UriInterface
    {
        return new \WPPayVendor\GuzzleHttp\Psr7\Uri($uri);
    }
}
