<?php

namespace Reamaze\API;

use Reamaze\API\Config;
use Reamaze\API\Clients\CurlClient;
use Reamaze\API\Clients\WpHttpClient;
/**
 * Abstract Class Resource
 *
 * @package Reamaze\API
 */
abstract class Resource {
    public static $API_VERSION = 'v1';

    public static $client = null;

    public static function className() {
        $className = get_called_class();

        $className = substr(strrchr($className, '\\'), 1);

        return strtolower($className);
    }

    public static function path() {
        return "/" . self::$API_VERSION . "/" . static::className() . "s";
    }

    public static function url() {
        return Config::getBaseUrl() . static::path();
    }

    public static function getClient() {
        if (!self::$client) {
            if ( function_exists( 'curl_version' ) ) {
                self::setClient( new CurlClient() );
            } else {
                self::setClient( new WpHttpClient() );
            }
        }

        return self::$client;
    }

    public static function setClient($client) {
        self::$client = $client;
    }

    public static function retrieve($id, $params = null) {
        $client = self::getClient();

        return $client->makeRequest('GET', static::url() . '/' . $id, $params);
    }

    public static function all($params = null) {
        $client = self::getClient();

        return $client->makeRequest('GET', static::url(), $params);
    }

    public static function create($params = null) {
        $client = self::getClient();

        return $client->makeRequest('POST', static::url(), $params);
    }

    public static function update($id, $params = null) {
        $client = self::getClient();

        return $client->makeRequest('PUT', static::url() . '/' . $id, $params);
    }

    public static function delete($id, $params = null) {
        $client = self::getClient();

        return $client->makeRequest('DELETE', static::url() . '/' . $id, $params);
    }

    protected static function _makeRequest($method, $url, $params) {
        $client = self::getClient();

        return $client->makeRequest($method, $url, $params);
    }
}

