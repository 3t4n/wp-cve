<?php

namespace MailOptin\EgoiConnect;

class APIClass
{
    protected $api_key;
    /**
     * @var string
     */
    protected $api_url = 'https://api.egoiapp.com/';

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

        $wp_args = ['method' => strtoupper($method), 'timeout' => 30];

        $wp_args['headers'] = [
            "Content-Type"  => 'application/json',
            'Apikey' => $this->api_key
        ];

        switch ($method) {
            case 'post':
            case 'put':
                $wp_args['body'] = json_encode($args);
                break;
            case 'get':
                $url = add_query_arg($args, $url);
                break;
        }

        $response = wp_remote_request($url, $wp_args);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        if (isset($response['body']['errors'])) {
            throw new \Exception($response['body']['errors']);
        }

        $response_body      = json_decode(wp_remote_retrieve_body($response), true);
        $response_http_code = wp_remote_retrieve_response_code($response);

        return ['status_code' => $response_http_code, 'body' => $response_body];
    }
}