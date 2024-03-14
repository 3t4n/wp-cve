<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Common\Plugin;

use Dotdigital_WordPress_Vendor\Http\Message\Stream\BufferedStream;
use Dotdigital_WordPress_Vendor\Http\Promise\Promise;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestInterface;
/**
 * Allow body used in request to be always seekable.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class RequestSeekableBodyPlugin extends SeekableBodyPlugin
{
    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first) : Promise
    {
        if (!$request->getBody()->isSeekable()) {
            $request = $request->withBody(new BufferedStream($request->getBody(), $this->useFileBuffer, $this->memoryBufferSize));
        }
        return $next($request);
    }
}
