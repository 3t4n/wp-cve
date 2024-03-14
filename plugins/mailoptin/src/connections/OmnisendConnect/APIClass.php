<?php

namespace MailOptin\OmnisendConnect;

class APIClass
{
    protected $api_key;

    protected $api_url = "https://api.omnisend.com/v3/";


    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @param $endpoint
     * @param array $args
     * @param string $method
     *
     * @return array
     * @throws \Exception
     */
    public function make_request($endpoint, $args = [], $method = 'get')
    {
        $url = $this->api_url . $endpoint;

        //Prepare http args
        $wp_args = [
            'method'  => strtoupper($method),
            'timeout' => 30,
            "headers" => ['X-API-KEY' => $this->api_key]
        ];

        switch ($method) {
            case 'post':
                $wp_args['headers']["Content-Type"] = "application/json";
                $wp_args['body']                    = json_encode($args);
                break;
            case 'get':
                $url = add_query_arg($args, $url);
                break;
        }

        $response = wp_remote_request($url, $wp_args);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        $response_http_code = wp_remote_retrieve_response_code($response);

        $response_body = wp_remote_retrieve_body($response);

        if ($response_http_code >= 200 && $response_http_code <= 299) {
            $response_body = json_decode($response_body);
        }

        return ['status' => $response_http_code, 'body' => $response_body];
    }

    /**
     * @param $endpoint
     * @param array $args
     *
     * @return array
     * @throws \Exception
     */
    public function post($endpoint, $args = [])
    {
        return $this->make_request($endpoint, $args, 'post');
    }
}