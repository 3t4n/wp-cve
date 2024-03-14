<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Http\Client\Common\Plugin;

use Dotdigital_WordPress_Vendor\Http\Client\Common\Plugin;
use Dotdigital_WordPress_Vendor\Http\Message\Authentication;
use Dotdigital_WordPress_Vendor\Http\Promise\Promise;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestInterface;
/**
 * Send an authenticated request.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class AuthenticationPlugin implements Plugin
{
    /**
     * @var Authentication An authentication system
     */
    private $authentication;
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }
    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first) : Promise
    {
        $request = $this->authentication->authenticate($request);
        return $next($request);
    }
}
