<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\Abstraction\DataTypeInterface;
/**
 * Class DataType data type access class.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Data
 */
class DataType implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\Abstraction\DataTypeInterface
{
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function isSet() : bool
    {
        return isset($this->data);
    }
    public function isEmpty() : bool
    {
        return empty($this->data);
    }
    public function isString() : bool
    {
        return \is_string($this->data);
    }
    public function isInteger() : bool
    {
        return \is_int($this->data);
    }
    public function isFloat() : bool
    {
        return \is_float($this->data);
    }
    public function isNumeric() : bool
    {
        return \is_numeric($this->data);
    }
    public function isArray() : bool
    {
        return \is_array($this->data);
    }
    public function isObject() : bool
    {
        return \is_object($this->data);
    }
    public function isUrl() : bool
    {
        return \is_string($this->data) && \filter_var($this->data, \FILTER_VALIDATE_URL);
    }
    public function isSerialized() : bool
    {
        return $this->data === 'b:0;' || @\unserialize($this->data) !== \false;
    }
    public function isJson() : bool
    {
        if (!(\is_numeric($this->data) || \is_string($this->data))) {
            return \false;
        }
        \json_decode($this->data);
        return \json_last_error() === \JSON_ERROR_NONE;
    }
    /**
     * @see DataTypeInterface::get()
     */
    public function get()
    {
        return $this->data;
    }
    public function getAsString() : string
    {
        if ($this->isString()) {
            return $this->data;
        }
        if ($this->isNumeric()) {
            return (string) $this->data;
        }
        if ($this->isArray() || $this->isObject()) {
            return $this->getSerialized();
        }
        return '';
    }
    public function getAsJson() : string
    {
        return \json_encode($this->data);
    }
    public function getSerialized() : string
    {
        return \serialize($this->data);
    }
    /**
     * @see DataTypeInterface::getSanitized();
     */
    public function getSanitized()
    {
        return $this->get_sanitized_data('sanitize_text_field');
    }
    /**
     * @see DataTypeInterface::getSanitized();
     */
    public function getSanitizedForTextarea()
    {
        return $this->get_sanitized_data('sanitize_textarea_field');
    }
    public function __toString() : string
    {
        return $this->getAsString();
    }
    /**
     * @param string $sanitization_function_name
     *
     * @return array|string
     */
    private function get_sanitized_data(string $sanitization_function_name)
    {
        if (!$this->isSet()) {
            return '';
        }
        if ($this->isObject() || $this->isArray()) {
            $map = function ($func, array $arr) {
                \array_walk_recursive($arr, function (&$v) use($func) {
                    $v = $func($v);
                });
                return $arr;
            };
            return $map($sanitization_function_name, $this->isObject() ? (array) $this->data : $this->data);
        }
        $data = (string) $this->data;
        return $sanitization_function_name($data);
    }
}
