<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Connector\Client;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Connector\Client\Options\CurlHttpClientOptions;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\Connector\CurlHttpException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Connector\Abstraction\Connector;
/**
 * Class CurlHttpClient, curl http client.
 * @package WPDesk\Library\DropshippingXmlCore\Connector\Client
 */
class CurlHttpClient implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Connector\Abstraction\Connector
{
    const MAX_HTTP_CODE = 302;
    /**
     * @var CurlHttpClientOptions
     */
    private $options;
    /**
     * @var resource
     */
    private $curl_resource;
    /**
     * @var int
     */
    private $http_response_code = 0;
    /**
     * @var string
     */
    private $raw_response = '';
    /**
     * @var array
     */
    private $headers = [];
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Connector\Client\Options\CurlHttpClientOptions $options)
    {
        $this->options = $options;
    }
    public function get_content() : string
    {
        $this->options->set_return_transfer(\true);
        $this->options->set_header_function($this->get_header_function());
        $this->send($this->options);
        return $this->raw_response;
    }
    public function get_file(string $destination) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject
    {
        $fp = \fopen($destination, 'w+');
        $this->options->set_return_transfer(\false);
        $this->options->set_file_resource($fp);
        $this->options->set_header_function($this->get_header_function());
        $this->send($this->options);
        \flock($fp, \LOCK_UN);
        \fclose($fp);
        return new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject($destination);
    }
    public function get_headers() : array
    {
        return $this->headers;
    }
    public function get_response_code() : int
    {
        return $this->http_response_code;
    }
    public function is_initialized() : bool
    {
        return isset($this->curl_resource);
    }
    private function send(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Connector\Client\Options\CurlHttpClientOptions $options)
    {
        try {
            $this->curl_init();
            $this->prepare_connection($options);
            $this->send_request();
            $this->throwExceptionIfError();
            $this->close_connection();
        } catch (\Exception $e) {
            $this->close_connection();
            throw $e;
        }
    }
    private function get_header_function() : callable
    {
        $result = function ($curl, $header) {
            $len = \strlen($header);
            $this->headers[] = \trim($header);
            return $len;
        };
        return $result;
    }
    private function prepare_connection(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Connector\Client\Options\CurlHttpClientOptions $options)
    {
        \curl_setopt_array($this->curl_resource, $options->get_options());
    }
    private function send_request()
    {
        $this->raw_response = \trim(\curl_exec($this->curl_resource));
        $this->http_response_code = $this->get_http_response_code();
        return $this->raw_response;
    }
    private function throwExceptionIfError()
    {
        $error_number = \curl_errno($this->curl_resource);
        if ($error_number !== 0) {
            $error_message = \curl_error($this->curl_resource);
            $this->close_connection();
            throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\Connector\CurlHttpException($error_message);
        }
        if ($this->get_http_response_code() > self::MAX_HTTP_CODE) {
            $error_message = \sprintf(\__('File not exists or it\'s forbidden to download, HTTP error code: %1$d', 'dropshipping-xml-for-woocommerce'), $this->get_http_response_code());
            $this->close_connection();
            throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\Connector\CurlHttpException($error_message);
        }
    }
    private function close_connection()
    {
        if (null !== $this->curl_resource) {
            \curl_close($this->curl_resource);
            $this->curl_resource = null;
        }
    }
    private function get_http_response_code() : int
    {
        return \intval(\curl_getinfo($this->curl_resource, \CURLINFO_HTTP_CODE));
    }
    private function curl_init()
    {
        $this->curl_resource = \curl_init();
    }
}
