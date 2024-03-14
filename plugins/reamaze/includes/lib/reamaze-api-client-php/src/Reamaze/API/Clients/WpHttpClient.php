<?php

namespace Reamaze\API\Clients;

use Reamaze\API\Config;
use Reamaze\API\Exceptions\Api as ApiException;

class WpHttpClient extends BaseClient {
    public static function getHeaders() {
        return array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        );
    }

    public function makeRequest( $method, $url, $params = null ) {
        $method = strtolower( $method );
        $headers = self::getHeaders();

        $credentials = Config::getCredentials();

        if ( empty( $credentials ) || empty( $credentials['login'] ) || empty( $credentials['apiToken'] ) ) {
            throw new ApiException("Authorization error. Please ensure your login and API Token credentials are correct.");
        }

        $headers['Authorization'] = 'Basic ' . base64_encode( $credentials['login'] . ':' . $credentials['apiToken'] );

        $opts = array(
          'timeout' => Config::DEFAULT_REQUEST_TIMEOUT,
          'headers' => $headers
        );

        switch ($method) {
            case 'get':
                if ( count( $params ) > 0 ) {
                    $url = "{$url}?" .  http_build_query( $params, null, '&' );
                }
                $response = wp_remote_get( $url, $opts );
                break;

            case 'post':
                $opts['body'] = json_encode( $params );
                $response = wp_remote_post( $url, $opts );
                break;

            case 'delete':
                $opts['method'] = 'DELETE';
                if (count($params) > 0) {
                    $url = "{$url}?" .  http_build_query( $params, null, '&' );
                }
                $response = wp_remote_request( $url, $opts );
                break;

            case 'put':
                $opts['method'] = 'PUT';
                $opts['body'] = json_encode( $params );

                $response = wp_remote_request( $url, $opts );
                break;

            default:
                throw new ApiException( "Unrecognized API Method: {$method}" );
        }

        $response_code = wp_remote_retrieve_response_code( $response );

        if ( $response_code < 200 || $response_code >= 300 ) {
            $message = wp_remote_retrieve_response_message( $response );

            if ( empty( $message ) ) {
                $message = "API Error ($httpCode)";
            }

            throw new ApiException( $message, $response_code );
        }

        try {
            $result = json_decode( wp_remote_retrieve_body( $response ), true );
        } catch (Exception $e) {
            throw new ApiException( "Invalid API Response: " . wp_remote_retrieve_body( $response ), $response_code );
        }

        return $result;
    }
}
