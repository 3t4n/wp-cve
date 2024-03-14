<?php

namespace Reamaze\API;

/**
 * Class Config
 *
 * @package Reamaze\API
 */
class Config {
    const BASE_DOMAIN = 'reamaze.com';

    const DEFAULT_REQUEST_TIMEOUT = 60;

    public static $brand = null;

    public static $credentials = null;

    public static function setBrand($brand) {
        self::$brand = $brand;
    }

    public static function getBrand() {
        return self::$brand;
    }

    public static function setCredentials($login, $apiToken) {
        self::$credentials = array(
            'login' => $login,
            'apiToken' => $apiToken
        );
    }

    public static function getCredentials() {
        return self::$credentials;
    }

    public static function getBaseUrl() {
        $brand = self::getBrand();

        if (empty($brand)) {
            throw new Exceptions\Config( 'Brand is not set.' );
        }

        return "https://{$brand}." . self::BASE_DOMAIN . '/api';
    }
}

