<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\Abstraction;

/**
 * Interface DataTypeInterface, abstraction layer for data type access.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Data
 */
interface DataTypeInterface
{
    /**
     * DataTypeInterface constructor.
     *
     * @param mixed $data, any kind of data.
     */
    public function __construct($data);
    public function isSet() : bool;
    public function isEmpty() : bool;
    public function isString() : bool;
    public function isInteger() : bool;
    public function isFloat() : bool;
    public function isNumeric() : bool;
    public function isArray() : bool;
    public function isObject() : bool;
    public function isUrl() : bool;
    public function isSerialized() : bool;
    public function isJson() : bool;
    /**
     * @return mixed
     */
    public function get();
    public function getAsString() : string;
    public function getAsJson() : string;
    public function getSerialized() : string;
    /**
     * Return sanitized value, if data is a string, then return string, if it is array or object then return sanitized array.
     *
     * @return array|string
     */
    public function getSanitized();
    /**
     * Return sanitized value, if data is a string, then return string, if it is array or object then return sanitized array.
     *
     * @return array|string
     */
    public function getSanitizedForTextarea();
    public function __toString() : string;
}
