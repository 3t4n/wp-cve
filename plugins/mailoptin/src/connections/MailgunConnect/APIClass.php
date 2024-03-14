<?php

namespace MailOptin\MailgunConnect;

class APIClass
{
    protected $api_key;

    protected $api_url;

    /** @var int */
    protected $api_version = 3;
    /**
     * @var string
     */
    protected $api_url_base = 'https://api.mailgun.net/';

    public function __construct($api_key, $domain_region)
    {
        $this->api_key = $api_key;

        if ($domain_region == 'eu') {
            $this->api_url_base = 'https://api.eu.mailgun.net/';
        }

        $this->api_url = $this->api_url_base . 'v' . $this->api_version . '/';
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
            "Authorization" => 'Basic ' . base64_encode('api:' . $this->api_key)
        ];

        switch ($method) {
            case 'post':
            case 'put':
                $wp_args['body'] = $args;
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