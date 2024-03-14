<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Connector\Client\Options;

use InvalidArgumentException;
/**
 * Class CurlHttpClientOptions, curl http cleint options.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Connector\Client\Options
 */
class CurlHttpClientOptions
{
    /**
     * @var string
     */
    private $url = '';
    /**
     * @var string
     */
    private $method = 'GET';
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $pass;
    /**
     * @var string
     */
    private $auth_type;
    /**
     * @var array
     */
    private $body = [];
    /**
     * @var array
     */
    private $headers = [];
    /**
     * @var int
     */
    private $connect_timeout = 10;
    /**
     * @var int
     */
    private $timeout = 600;
    /**
     * @var bool
     */
    private $return_transfer = \true;
    /**
     * @var bool
     */
    private $header_in_content = \false;
    /**
     * @var int
     */
    private $ssl_verify_host = 2;
    /**
     * @var bool
     */
    private $ssl_verify_peer = \true;
    /**
     * @var bool
     */
    private $follow_location = \true;
    /**
     * @var resource
     */
    private $file_resorce;
    /**
     * @var callable
     */
    private $header_function;
    public function set_url(string $url)
    {
        if (!\filter_var($url, \FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(\__('Error: file url is not valid http address.', 'dropshipping-xml-for-woocommerce'));
        }
        $this->url = $url;
    }
    public function set_method(string $string)
    {
        $string = \strtoupper($string);
        if (!\in_array($string, $this->get_supported_methods())) {
            throw new \InvalidArgumentException(__METHOD__ . ' require one of the valid method type: ' . \implode(', ', $this->get_supported_methods()) . ' - ' . $string . ' given.');
        }
        $this->method = $string;
    }
    public function set_body(array $array_parameters)
    {
        $this->body = $array_parameters;
    }
    public function set_headers(array $array_parameters)
    {
        $this->headers = $this->convert_array($array_parameters);
    }
    public function set_connect_timeout(int $timeout)
    {
        $this->connect_timeout = $timeout;
    }
    public function set_timeout(int $timeout)
    {
        $this->timeout = $timeout;
    }
    public function set_return_transfer(bool $bool)
    {
        $this->return_transfer = $bool;
    }
    public function set_header_in_content(bool $bool)
    {
        $this->header_in_content = $bool;
    }
    public function set_credentials(string $login, string $pass, string $auth_type = \CURLAUTH_BASIC)
    {
        $this->login = $login;
        $this->pass = $pass;
        $this->auth_type = $auth_type;
    }
    public function set_ssl_verify_host(int $number)
    {
        if (!\in_array($number, $this->get_verify_host_values())) {
            throw new \InvalidArgumentException(__METHOD__ . ' require one of the valid number: ' . \implode(', ', $this->get_verify_host_values()) . ' - ' . $number . ' given.');
        }
        $this->ssl_verify_host = $number;
    }
    public function set_ssl_verify_peer(bool $bool)
    {
        $this->ssl_verify_peer = $bool;
    }
    public function set_follow_location(bool $bool)
    {
        $this->follow_location = $bool;
    }
    public function set_header_function(callable $function)
    {
        $this->header_function = $function;
    }
    /**
     * @param resource $resource
     *
     * @throws InvalidArgumentException
     */
    public function set_file_resource($resource)
    {
        if (!\is_resource($resource)) {
            throw new \InvalidArgumentException(__METHOD__ . ' require resource as parameter: ' . \gettype($resource) . ' given.');
        }
        $this->file_resorce = $resource;
    }
    public function get_url() : string
    {
        return $this->url;
    }
    public function get_options() : array
    {
        $options = [\CURLOPT_CUSTOMREQUEST => $this->method, \CURLOPT_HTTPHEADER => $this->headers, \CURLOPT_URL => $this->url, \CURLOPT_CONNECTTIMEOUT => $this->connect_timeout, \CURLOPT_TIMEOUT => $this->timeout, \CURLOPT_RETURNTRANSFER => $this->return_transfer, \CURLOPT_HEADER => $this->header_in_content, \CURLOPT_SSL_VERIFYHOST => $this->ssl_verify_host, \CURLOPT_SSL_VERIFYPEER => $this->ssl_verify_peer, \CURLOPT_FOLLOWLOCATION => $this->follow_location];
        if ($this->method !== 'GET') {
            $options[\CURLOPT_POSTFIELDS] = $this->body;
        }
        if (isset($this->header_function)) {
            $options[\CURLOPT_HEADERFUNCTION] = $this->header_function;
        }
        if (\false === $this->return_transfer && isset($this->file_resorce)) {
            $options[\CURLOPT_FILE] = $this->file_resorce;
        }
        if (\false === $this->return_transfer && isset($this->file_resorce)) {
            $options[\CURLOPT_FILE] = $this->file_resorce;
        }
        if (isset($this->login) && isset($this->pass) && isset($this->auth_type)) {
            $options[\CURLOPT_USERPWD] = $this->login . ':' . $this->pass;
            $options[\CURLOPT_HTTPAUTH] = $this->auth_type;
        }
        return $options;
    }
    private function convert_array(array $headers) : array
    {
        $return = [];
        foreach ($headers as $key => $value) {
            if (\is_string($value)) {
                $return[] = $key . ': ' . $value;
            }
        }
        return $return;
    }
    private function get_supported_methods() : array
    {
        return ['GET', 'POST', 'PUT', 'DELETE'];
    }
    private function get_verify_host_values() : array
    {
        return [0, 1, 2];
    }
}
