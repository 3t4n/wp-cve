<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Mailchimp_Client
{
    /**
     * @var array The Mailchimp API key in parts
     */
    protected $apiKey;

    /**
     * @var string The Mailchimp API URL
     */
    protected $url;

    /**
     * @var string The Authorization header
     */
    protected $authorizationHeader;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->authorizationHeader = sprintf('Basic %s', base64_encode(sprintf('apikey:%s', $this->apiKey)));

        $apiKeyParts = explode('-', $this->apiKey);
        $this->url = sprintf('https://%s.api.mailchimp.com/3.0', ! empty($apiKeyParts[1]) ? $apiKeyParts[1] : 'us1');
    }

    /**
     * Send a request to the Mailchimp API
     *
     * @param   string           $method    HTTP method verb
     * @param   string           $endpoint  The API endpoint to call
     * @param   array            $data      Data array to send
     * @return  array|WP_Error
     */
    public function request($method, $endpoint, array $data = array())
    {
        $url = $this->url . '/' . trim($endpoint, '/');

        $args = array(
            'method' => $method,
            'headers' => array(
                'Authorization' => $this->authorizationHeader,
                'Content-Type' => 'application/json'
            )
        );

        if (count($data)) {
            $args['body'] = $this->prepareData($data, $method);
        }

        $response = wp_remote_request($url, $args);

        return $response;
    }

    /**
     * Send a GET request to the Mailchimp API
     *
     * @param   string          $endpoint  The API endpoint to call
     * @param   array           $data      Data array to send
     * @return  array|WP_Error
     */
    public function get($endpoint, array $data = array())
    {
        return $this->request('GET', $endpoint, $data);
    }

    /**
     * Send a POST request to the Mailchimp API
     *
     * @param   string          $endpoint  The API endpoint to call
     * @param   array           $data      Data array to send
     * @return  array|WP_Error
     */
    public function post($endpoint, array $data = array())
    {
        return $this->request('POST', $endpoint, $data);
    }

    /**
     * Send a PUT request to the Mailchimp API
     *
     * @param   string          $endpoint  The API endpoint to call
     * @param   array           $data      Data array to send
     * @return  array|WP_Error
     */
    public function put($endpoint, array $data = array())
    {
        return $this->request('PUT', $endpoint, $data);
    }

    /**
     * Send a PATCH request to the Mailchimp API
     *
     * @param   string          $endpoint  The API endpoint to call
     * @param   array           $data      Data array to send
     * @return  array|WP_Error
     */
    public function patch($endpoint, array $data = array())
    {
        return $this->request('PATCH', $endpoint, $data);
    }

    /**
     * Convert the data into the correct format for the given request method
     *
     * @param   array         $data    The request data
     * @param   string        $method  HTTP method verb
     * @return  array|string           The prepared data
     */
    protected function prepareData(array $data, $method)
    {
        $method = strtoupper($method);

        switch ($method) {
            case 'POST':
            case 'PUT':
            case 'PATCH':
                $data = wp_json_encode($data);
                break;
        }

        return $data;
    }
}
