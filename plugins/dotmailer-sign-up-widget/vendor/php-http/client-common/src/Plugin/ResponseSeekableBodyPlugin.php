<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Common\Plugin;

use Dotdigital_WordPress_Vendor\Http\Message\Stream\BufferedStream;
use Dotdigital_WordPress_Vendor\Http\Promise\Promise;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ResponseInterface;
/**
 * Allow body used in response to be always seekable.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class ResponseSeekableBodyPlugin extends SeekableBodyPlugin
{
    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first) : Promise
    {
        return $next($request)->then(function (ResponseInterface $response) {
            if ($response->getBody()->isSeekable()) {
                return $response;
            }
            return $response->withBody(new BufferedStream($response->getBody(), $this->useFileBuffer, $this->memoryBufferSize));
        });
    }
}
