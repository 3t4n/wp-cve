<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Container;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\Abstraction\DataTypeInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataType;
/**
 * Class ParameterContainer
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Container
 */
class ParameterContainer
{
    /**
     * Stores all merged arrays.
     *
     * @var array
     */
    private $array = [];
    public function __construct(array $array)
    {
        $this->array = $array;
    }
    public function add_params(array $array)
    {
        $this->array = $this->array + $array;
    }
    public function param_exists(string $key) : bool
    {
        return $this->get_param($key)->isSet();
    }
    /**
     * @param string $param
     *
     * @return DataTypeInterface
     */
    public function get_param(string $param) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\Abstraction\DataTypeInterface
    {
        $keys = \explode('.', $param);
        $parameters = $this->array;
        foreach ($keys as $key) {
            $parameters = \array_change_key_case($parameters, \CASE_UPPER);
            $key = \strtoupper($key);
            if (isset($parameters[$key])) {
                $parameters = $parameters[$key];
            } else {
                $parameters = null;
                break;
            }
        }
        return new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataType($parameters);
    }
}
