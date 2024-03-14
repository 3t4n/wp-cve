<?php

namespace FluentConnect\App\Services\ThriveCart;

class Api
{
    private $apiKey;

    private $apiAurl = 'https://thrivecart.com/api/external/';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function get($route, $params = [])
    {
        return $this->request($route, $params, 'GET');
    }

    public function post($route, $data = [])
    {
        return $this->request($route, $data, 'POST');
    }

    private function request($route, $data = [], $method = 'GET')
    {
        $response = wp_remote_request($this->apiAurl.$route, [
            'method'     => $method,
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiKey
            ],
            'cookies' => [],
            'sslverify' => false,
            'body' => $data
        ]);

        if(is_wp_error($response)) {
            return $response;
        }

        $responseCode = wp_remote_retrieve_response_code($response);
        if($responseCode >= 300) {
            $errorBody = json_decode(wp_remote_retrieve_body($response), true);
            $message = 'API Request Failed';
            if($errorBody && is_array($errorBody) && !empty($errorBody['error'])) {
                $message = $errorBody['error'];
            }

            return new \WP_Error($responseCode, $message , $response);
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }
}
