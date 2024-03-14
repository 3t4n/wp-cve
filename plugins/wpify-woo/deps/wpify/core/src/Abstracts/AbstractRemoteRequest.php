<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WP_Error;
abstract class AbstractRemoteRequest extends AbstractComponent
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH = 'PATCH';
    const METHOD_PUT = 'PUT';
    /**
     * API URL
     *
     * @var string
     */
    private $api_url = '';
    /**
     * Default request args
     *
     * @var array
     */
    private $default_args = array();
    /**
     * Request args
     *
     * @var array
     */
    private $request_args = array();
    /**
     * Request response
     *
     * @var
     */
    private $response;
    /**
     * Request method
     *
     * @var
     */
    private $method;
    /**
     * Request endpoint
     *
     * @var
     */
    private $endpoint;
    /**
     * JSON encode body in the request
     *
     * @var bool
     */
    private $json_encode_body = \false;
    /**
     * Call the API with GET Method
     *
     * @return array|bool|mixed|object|WP_Error
     */
    function get()
    {
        $this->set_method($this::METHOD_GET);
        return $this->call();
    }
    /**
     * Call the API
     *
     * @return array|mixed|object|WP_Error
     */
    function call()
    {
        $args = $this->array_merge_recursive_distinct($this->default_args, $this->request_args);
        $args['method'] = $this->method;
        // Set the endpoint
        $url = $this->get_request_url();
        // JSON Encode the body if asked to
        if (isset($args['body']) && \is_array($args['body']) && $this->json_encode_body && $this->method !== $this::METHOD_GET) {
            $args['body'] = \json_encode($args['body']);
        }
        $response = \false;
        // Perform the request
        $this->response = wp_remote_request($url, $args);
        $log = array('args' => $args, 'response' => $this->response, 'datetime' => \date('Y-m-d H:i:s'));
        do_action('wpify_remote_request_sent', $log);
        if (!$this->response) {
            return new WP_Error(400, 'Error when sending request', $response);
        }
        // Return error or success response
        if (!$this->response_is_success()) {
            return $this->prepare_response_error();
        }
        return $this->prepare_response_success();
    }
    /**
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     */
    protected function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (\is_array($value) && isset($merged[$key]) && \is_array($merged[$key])) {
                $merged[$key] = $this->array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }
    /**
     * Get the request URL
     *
     * @return string
     */
    function get_request_url()
    {
        $url = $this->api_url . $this->endpoint;
        if ($this->method === $this::METHOD_GET && isset($this->request_args['body']) && \is_array($this->request_args['body'])) {
            $url = add_query_arg($this->request_args['body'], $url);
        }
        return $url;
    }
    /**
     * Check if the response was successful
     * To be overridden by sub-classes
     *
     * @return bool
     */
    public function response_is_success()
    {
        return $this->get_response_code() >= 200 && $this->get_response_code() < 300;
    }
    public function get_response_code()
    {
        return wp_remote_retrieve_response_code($this->response);
    }
    /**
     * Prepare the error response return
     * To be overridden by sub-classes
     *
     * @return WP_Error
     */
    public function prepare_response_error()
    {
        $errors = array();
        $body = $this->get_response_body();
        foreach ($body->error as $error) {
            $errors[] = $error;
        }
        return new WP_Error(wp_remote_retrieve_response_code($this->response), \implode(',', $errors), $body);
    }
    /**
     * Get the response body
     *
     * @return object
     */
    function get_response_body()
    {
        return \json_decode(wp_remote_retrieve_body($this->response));
    }
    /**
     * Prepare the success response return
     * To be overridden by sub-classes
     *
     * @return string
     */
    function prepare_response_success()
    {
        return $this->get_response_body();
    }
    /**
     * Call the API with POST Method
     *
     * @return array|bool|mixed|object|WP_Error
     */
    function post()
    {
        $this->set_method($this::METHOD_POST);
        return $this->call();
    }
    /**
     * Call the API with DELETE Method
     *
     * @return array|bool|mixed|object|WP_Error
     */
    function delete()
    {
        $this->set_method($this::METHOD_DELETE);
        return $this->call();
    }
    /**
     * Call the API with DELETE Method
     *
     * @return array|bool|mixed|object|WP_Error
     */
    function put()
    {
        $this->set_method($this::METHOD_PATCH);
        return $this->call();
    }
    /**
     * Call the API with PATCH Method
     *
     * @return array|bool|mixed|object|WP_Error
     */
    function patch()
    {
        $this->set_method($this::METHOD_PATCH);
        return $this->call();
    }
    /**
     * @return string
     */
    public function get_api_url() : string
    {
        return $this->api_url;
    }
    /**
     * Set the API URL
     *
     * @param $url
     */
    function set_api_url(string $url)
    {
        $this->api_url = $url;
    }
    /**
     * @return array
     */
    public function get_default_args() : array
    {
        return $this->default_args;
    }
    /**
     * @param array $default_args
     */
    public function set_default_args(array $default_args) : void
    {
        $this->default_args = $default_args;
    }
    /**
     * @param $encode
     *
     * @return void
     */
    public function set_json_encode_body($encode)
    {
        $this->json_encode_body = $encode;
    }
    /**
     * @return mixed
     */
    public function get_method()
    {
        return $this->method;
    }
    /**
     * @param mixed $method
     */
    public function set_method($method) : void
    {
        $this->method = $method;
    }
    /**
     * @return mixed
     */
    public function get_endpoint()
    {
        return $this->endpoint;
    }
    /**
     * @param mixed $endpoint
     */
    public function set_endpoint($endpoint) : void
    {
        $this->endpoint = $endpoint;
    }
    /**
     * @return array
     */
    public function get_request_args() : array
    {
        return $this->request_args;
    }
    /**
     * @param array $request_args
     */
    public function set_request_args(array $request_args) : void
    {
        $this->request_args = $request_args;
    }
    /**
     * @return mixed
     */
    public function get_response()
    {
        return $this->response;
    }
}
