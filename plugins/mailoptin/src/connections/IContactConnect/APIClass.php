<?php

namespace MailOptin\IContactConnect;

class APIClass
{
    protected $app_id;
    protected $username;
    protected $password;
    /**
     * @var string
     */
    protected $api_url = 'https://app.icontact.com/icp/a/';

    public function __construct($app_id, $username, $password, $account_id, $client_folder_id)
    {
        $this->app_id   = $app_id;
        $this->username = $username;
        $this->password = $password;

        $this->api_url .= "$account_id/c/$client_folder_id/";
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
            "Content-Type" => 'application/json',
            'Accept'       => 'application/json',
            'API-Version'  => '2.2',
            'API-AppId'    => $this->app_id,
            'API-Username' => $this->username,
            'API-Password' => $this->password
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