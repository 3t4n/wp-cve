<?php

namespace Reamaze\API\Clients;

abstract class BaseClient {
    public static function getHeaders() {
        return array(
            'Content-Type: application/json',
            'Accept: application/json'
        );
    }

    abstract public function makeRequest( $method, $url, $params = null );
}
