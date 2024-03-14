<?php
namespace PHPF\WP\Api;

/**
 * Base class for API client
 *
 * @author  Petr Stastny <petr@stastny.eu>
 * @license GPLv3
 */
class ApiClient
{
    /**
     * HTTP request URL address
     * @var string
     */
    private $url;

    /**
     * API client username
     * @var string
     */
    private $user;

    /**
     * API client token
     * @var string
     */
    private $token;

    /**
     * Raw POST data for request (NULL = none)
     * @var string
     */
    private $rawPostData = null;

    /**
     * Response data object
     * @var \stdClass|null
     */
    private $responseData;

    /**
     * WP HTTP API response object
     * @var mixed
     */
    private $response;


    /**
     * Construct
     *
     * @param string $url URL address
     * @param string $user API client username
     * @param string $token API client token
     */
    public function __construct($url, $user, $token)
    {
        $this->url = $url;
        $this->user = $user;
        $this->token = $token;
    }


    /**
     * Perform HTTP request - return content
     *
     * @param \stdClass $inputData input data (JSON object)
     * @return \stdClass|bool
     */
    public function send($inputData = null)
    {
        if ($inputData) {
            $this->rawPostData = json_encode($inputData);
        }

        $headers = [
            'Accept' => 'application/json',
            'X-Auth-User' => $this->user,
            'X-Auth-Key' => $this->token,
        ];

        $args = [
            'headers' => $headers,
            'httpversion' => '1.1',
            'redirection' => 0,
            'blocking' => true,
        ];

        if ($this->rawPostData) {
            $args['method'] = 'POST';
            $args['body'] = $this->rawPostData;
            $args['data_format'] = 'body';
            $args['headers']['Content-Type'] = 'application/json; charset=utf-8';

            $this->response = wp_remote_post($this->url, $args);

        } else {
            $this->response = wp_remote_get($this->url, $args);
        }

        if (is_array($this->response)) {
            $object = json_decode($this->response['body']);
            if (is_object($object)) {
                $this->responseData = $object;
            }
        }

        return $this->response;
    }


    /**
     * Get HTTP response code
     *
     * @return int|null
     */
    public function getHttpCode()
    {
        if (!is_array($this->response)) {
            return null;
        }

        return $this->response['response']['code'];
    }


    /**
     * Get HTTP response message
     *
     * @return int|null
     */
    public function getHttpMessage()
    {
        if (!is_array($this->response)) {
            return null;
        }

        return $this->response['response']['message'];
    }


    /**
     * Get response data object (parsed JSON)
     *
     * @return \stdClass|null
     */
    public function getResponseData()
    {
        return $this->responseData;
    }
}
