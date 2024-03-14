<?php

namespace WPPayForm\Framework\Response;

use WP_Error;
use WP_REST_Response;

class Response
{
    /**
     * Response Data
     * @var mixed
     */
    protected $data = null;
    
    /**
     * Response Status Code
     * @var integer
     */
    protected $code = 200;
    
    /**
     * Response Headers
     * @var array
     */
    protected $headers = [];
    
    /**
     * Response Cookies
     * @var array
     */
    protected $cookies = [];

    /**
     * Construct the response instance
     * 
     * @param mixed $data
     */
    public function __construct($data = null, $code = 200)
    {
        $this->data = $data;
        $this->code = $code;
    }

    /**
     * Creates an instance of self
     * 
     * @param  mixed  $data
     * @param  integer $code
     * @return self
     */
    public static function make($data = null, $code = 200)
    {
        return new static($data, $code = 200);
    }

    /**
     * Send json response
     * @param  array  $data
     * @param  integer $code
     * @return string|false The JSON encoded string, or false if it cannot be encoded.
     */
    public function json($data = null, $code = 200)
    {
        return wp_send_json($data, $code);
    }

    /**
     * Send json response
     * @param  array  $data
     * @param  integer $code
     * @return \WP_REST_Response
     */
    public function send($data = null, $code = 200, $headers = [])
    {
        return new WP_REST_Response($data, $code, $headers);
    }

    /**
     * Send a success json response
     * @param  array  $data
     * @param  integer $code
     * @return \WP_REST_Response
     */
    public function sendSuccess($data = null, $code = 200, $headers = [])
    {
         return new WP_REST_Response($data, $code, $headers);
    }

    /**
     * Send an error json response
     * @param  array  $data
     * @param  integer $code
     * @return \WP_REST_Response
     */
    public function sendError($data = null, $code = 422, $headers = [])
    {
        if (!$code || $code < 400 ) {
            $code = 422;
        }

        return new WP_REST_Response($data, $code, $headers);
    }

    /**
     * Convert the WP_Error to WP_REST_Response
     * 
     * @param  \WP_Error $wpError
     * @return \WP_REST_Response
     */
    public function wpErrorToResponse(WP_Error $wpError)
    {
        return rest_convert_error_to_response($wpError);
    }

    /**
     * Set response headers
     * @param  string|array $key
     * @param  string|null $value
     * @return self
     */
    public function withHeader($key, $value = null)
    {
        if (is_array($key) && !$value) {
            $this->headers = $key;
        } else {
            $this->headers = [$key => $value];
        }

        return $this;
    }

    /**
     * Set response cookie
     * 
     * @param  string $name
     * @param  mixed $value
     * @param  int $minutes
     * @param  string $path
     * @param  string|null $domain
     * @param  bool $secure
     * @param  bool $httpOnly
     * @return self
     */
    public function withCookie(
        $name,
        $value,
        $minutes,
        $path = '/',
        $domain = null,
        $secure = false,
        $httpOnly = true
    )
    {
        $cookie = $this->buildCookie(
            $name, $value, $minutes, $path, $domain, $secure, $httpOnly
        );

        $this->cookies[] = $cookie;

        return $this;
    }

    /**
     * Build cookie header for response
     * 
     * @param  string $name
     * @param  mixed $value
     * @param  int $minutes
     * @param  string $path
     * @param  string|null $domain
     * @param  bool $secure
     * @param  bool $httpOnly
     * @return string
     */
    protected function buildCookie($name, $value, $minutes, $path, $domain, $secure, $httpOnly)
    {
        $expiration = time() + ($minutes * 60);

        $serializedValue = base64_encode(json_encode($value));

        $cookieHeader = "{$name}=" . rawurlencode($serializedValue);

        $cookieHeader .= "; Expires=" . gmdate('D, d-M-Y H:i:s T', $expiration);

        $cookieHeader .= "; Path=" . $path;

        if ($domain) {
            $cookieHeader .= "; Domain=" . $domain;
        }

        if ($secure) {
            $cookieHeader .= "; Secure";
        }

        if ($httpOnly) {
            $cookieHeader .= "; HttpOnly";
        }

        return $cookieHeader;
    }

    /**
     * Send the response
     * 
     * @return \WP_REST_Response
     */
    public function toArray()
    {
        $response = new WP_REST_Response(
            $this->data, $this->code, $this->headers
        );

        foreach ($this->cookies as $cookie) {
            $response->header('Set-Cookie', $cookie);
        }

        return $response;
    }
}
