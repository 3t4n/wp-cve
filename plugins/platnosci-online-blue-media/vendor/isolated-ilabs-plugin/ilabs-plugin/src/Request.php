<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Interface;
class Request
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    private static $request;
    /**
     * @var Request_Filter_Interface[]
     */
    private static $secure_filters = [];
    /**
     * @var string
     */
    private $method;
    public function get_payload() : ?array
    {
        if (null === self::$request) {
            $this->build();
        }
        return self::$request;
    }
    public function build() : void
    {
    }
    /**
     * @param string $key
     *
     * @return mixed|null
     */
    /*public function get_by_key( string $key ) {
    		$payload = $this->get_payload();
    		return $payload[ $key ] ?? null;
    	}*/
    /**
     * @param Request_Filter_Interface $request_filter
     *
     * @return void
     */
    public function register_request_filter(Request_Filter_Interface $request_filter)
    {
        if (!\in_array($request_filter, self::$secure_filters)) {
            self::$secure_filters[] = $request_filter;
        }
    }
    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function get_by_key(string $key)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST[$key])) {
                //The request is fully secured. Each requested POST or GET is filtered via sanitize_text_field
                //check: Security_Request_Filter.php
                return $this->secure_raw_request_recursive($_POST[$key]);
            } else {
                return null;
            }
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET[$key])) {
                //The request is fully secured. Each requested POST or GET is filtered via sanitize_text_field
                //check: Security_Request_Filter.php
                return $this->secure_raw_request_recursive($_GET[$key]);
            } else {
                return null;
            }
        }
    }
    public function get_by_field(Field_Interface $field)
    {
        return $this->get_by_key($field->get_id());
    }
    /**
     * @param string $key
     *
     * @return bool
     */
    public function key_exsists(string $key) : bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return isset($_POST[$key]);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return isset($_GET[$key]);
        }
    }
    /**
     * @param $raw
     *
     * @return mixed
     */
    private function secure_raw_request_recursive($raw)
    {
        if (!\is_array($raw)) {
            $raw = [$raw];
        }
        $result = [];
        foreach ($raw as $key => $value) {
            if (\is_array($value)) {
                $result[$key] = $this->secure_raw_request_recursive($value);
            } else {
                $result[$key] = $this->secure_value($key, $value);
            }
        }
        if (\count($result) === 1 && \array_keys($result)[0] == 0) {
            return $result[0];
        }
        return $result;
    }
    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    private function secure_value($key, $value)
    {
        foreach (self::$secure_filters as $filter) {
            $value = $filter->filter($key, $value);
        }
        return $value;
    }
    /**
     * @param string $key
     * @param $value
     *
     * @return void
     */
    public function overwrite(string $key, $value)
    {
        self::$request[$key] = $value;
    }
}
