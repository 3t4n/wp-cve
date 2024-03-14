<?php

namespace WPCal\GoogleAPI;

use WPCal\GoogleAPI\Google\Auth\CredentialsLoader;
use WPCal\GoogleAPI\Google\Auth\HttpHandler\HttpHandlerFactory;
use WPCal\GoogleAPI\Google\Auth\FetchAuthTokenCache;
use WPCal\GoogleAPI\Google\Auth\Subscriber\AuthTokenSubscriber;
use WPCal\GoogleAPI\Google\Auth\Subscriber\ScopedAccessTokenSubscriber;
use WPCal\GoogleAPI\Google\Auth\Subscriber\SimpleSubscriber;
use WPCal\GoogleAPI\GuzzleHttp\Client;
use WPCal\GoogleAPI\GuzzleHttp\ClientInterface;
use WPCal\GoogleAPI\Psr\Cache\CacheItemPoolInterface;
/**
*
*/
class Google_AuthHandler_Guzzle5AuthHandler
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
        $subscriber = new \WPCal\GoogleAPI\Google\Auth\Subscriber\AuthTokenSubscriber($credentials, $authHttpHandler, $tokenCallback);
        $http->setDefaultOption('auth', 'google_auth');
        $http->getEmitter()->attach($subscriber);
        return $http;
    }
    public function attachToken(\WPCal\GoogleAPI\GuzzleHttp\ClientInterface $http, array $token, array $scopes)
    {
        $tokenFunc = function ($scopes) use($token) {
            return $token['access_token'];
        };
        $subscriber = new \WPCal\GoogleAPI\Google\Auth\Subscriber\ScopedAccessTokenSubscriber($tokenFunc, $scopes, $this->cacheConfig, $this->cache);
        $http->setDefaultOption('auth', 'scoped');
        $http->getEmitter()->attach($subscriber);
        return $http;
    }
    public function attachKey(\WPCal\GoogleAPI\GuzzleHttp\ClientInterface $http, $key)
    {
        $subscriber = new \WPCal\GoogleAPI\Google\Auth\Subscriber\SimpleSubscriber(['key' => $key]);
        $http->setDefaultOption('auth', 'simple');
        $http->getEmitter()->attach($subscriber);
        return $http;
    }
    private function createAuthHttp(\WPCal\GoogleAPI\GuzzleHttp\ClientInterface $http)
    {
        return new \WPCal\GoogleAPI\GuzzleHttp\Client(['base_url' => $http->getBaseUrl(), 'defaults' => ['exceptions' => \true, 'verify' => $http->getDefaultOption('verify'), 'proxy' => $http->getDefaultOption('proxy')]]);
    }
}
/**
*
*/
\class_alias('WPCal\\GoogleAPI\\Google_AuthHandler_Guzzle5AuthHandler', 'Google_AuthHandler_Guzzle5AuthHandler', \false);
