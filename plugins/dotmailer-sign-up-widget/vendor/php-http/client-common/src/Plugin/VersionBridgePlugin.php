<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Common\Plugin;

use Dotdigital_WordPress_Vendor\Http\Promise\Promise;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestInterface;
/**
 * A plugin that helps you migrate from php-http/client-common 1.x to 2.x. This
 * will also help you to support PHP5 at the same time you support 2.x.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
trait VersionBridgePlugin
{
    protected abstract function doHandleRequest(RequestInterface $request, callable $next, callable $first);
    public function handleRequest(RequestInterface $request, callable $next, callable $first) : Promise
    {
        return $this->doHandleRequest($request, $next, $first);
    }
}
