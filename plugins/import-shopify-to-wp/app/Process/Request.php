<?php

namespace S2WPImporter\Process;

use WP_REST_Request;

class Request
{
    protected $endpoint;

    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function get($parameters = []): array
    {
        return $this->request('GET', $parameters);
    }

    public function post($parameters = []): array
    {
        return $this->request('POST', $parameters);
    }

    public function request($method, $parameters = []): array
    {
        $request = new WP_REST_Request($method, $this->endpoint);
        $request->set_query_params($parameters);
        $response = rest_do_request($request);
        $server = rest_get_server();

        return $server->response_to_data($response, false);
    }

}
