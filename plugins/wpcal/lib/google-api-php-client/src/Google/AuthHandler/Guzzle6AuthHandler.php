<?php

namespace WPCal\GoogleAPI;

use WPCal\GoogleAPI\Google\Auth\CredentialsLoader;
use WPCal\GoogleAPI\Google\Auth\HttpHandler\HttpHandlerFactory;
use WPCal\GoogleAPI\Google\Auth\FetchAuthTokenCache;
use WPCal\GoogleAPI\Google\Auth\Middleware\AuthTokenMiddleware;
use WPCal\GoogleAPI\Google\Auth\Middleware\ScopedAccessTokenMiddleware;
use WPCal\GoogleAPI\Google\Auth\Middleware\SimpleMiddleware;
use WPCal\GoogleAPI\GuzzleHttp\Client;
use WPCal\GoogleAPI\GuzzleHttp\ClientInterface;
use WPCal\GoogleAPI\Psr\Cache\CacheItemPoolInterface;
/**
*
*/
class Google_AuthHandler_Guzzle6AuthHandler
{
    protected $cache;
    protected $cacheConfig;
    public function __construct(\WPCal\GoogleAPI\Psr\Cache\CacheItemPoolInterface $cache = null, array $cacheConfig = [])
    {
        $this->cache = $cache;
        $this->cacheConfig = $cacheConfig;
    }
    public function attachCredentials(\WPCal\GoogleAPI\GuzzleHttp\ClientInterface $http, \WPCal\GoogleAPI\Google\Auth\CredentialsLoader $credentials, callable $tokenCallback = null)
    {
        // use the provided cache
        if ($this->cache) {
            $credentials = new \WPCal\GoogleAPI\Google\Auth\FetchAuthTokenCache($credentials, $this->cacheConfig, $this->cache);
        }
        // if we end up needing to make an HTTP request to retrieve credentials, we
        // can use our existing one, but we need to throw exceptions so the error
        // bubbles up.
        $authHttp = $this->createAuthHttp($http);
        $authHttpHandler = \WPCal\GoogleAPI\Google\Auth\HttpHandler\HttpHandlerFactory::build($authHttp);
        $middleware = new \WPCal\GoogleAPI\Google\Auth\Middleware\AuthTokenMiddleware($credentials, $authHttpHandler, $tokenCallback);
        $config = $http->getConfig();
        $config['handler']->remove('google_auth');
        $config['handler']->push($middleware, 'google_auth');
        $config['auth'] = 'google_auth';
        $http = new \WPCal\GoogleAPI\GuzzleHttp\Client($config);
        return $http;
    }
    public function attachToken(\WPCal\GoogleAPI\GuzzleHttp\ClientInterface $http, array $token, array $scopes)
    {
        $tokenFunc = function ($scopes) use($token) {
            return $token['access_token'];
        };
        $middleware = new \WPCal\GoogleAPI\Google\Auth\Middleware\ScopedAccessTokenMiddleware($tokenFunc, $scopes, $this->cacheConfig, $this->cache);
        $config = $http->getConfig();
        $config['handler']->remove('google_auth');
        $config['handler']->push($middleware, 'google_auth');
        $config['auth'] = 'scoped';
        $http = new \WPCal\GoogleAPI\GuzzleHttp\Client($config);
        return $http;
    }
    public function attachKey(\WPCal\GoogleAPI\GuzzleHttp\ClientInterface $http, $key)
    {
        $middleware = new \WPCal\GoogleAPI\Google\Auth\Middleware\SimpleMiddleware(['key' => $key]);
        $config = $http->getConfig();
        $config['handler']->remove('google_auth');
        $config['handler']->push($middleware, 'google_auth');
        $config['auth'] = 'simple';
        $http = new \WPCal\GoogleAPI\GuzzleHttp\Client($config);
        return $http;
    }
    private function createAuthHttp(\WPCal\GoogleAPI\GuzzleHttp\ClientInterface $http)
    {
        return new \WPCal\GoogleAPI\GuzzleHttp\Client(['base_uri' => $http->getConfig('base_uri'), 'exceptions' => \true, 'verify' => $http->getConfig('verify'), 'proxy' => $http->getConfig('proxy')]);
    }
}
/**
*
*/
\class_alias('WPCal\\GoogleAPI\\Google_AuthHandler_Guzzle6AuthHandler', 'Google_AuthHandler_Guzzle6AuthHandler', \false);
