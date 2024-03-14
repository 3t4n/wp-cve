<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction;

/**
 * intreface ConditionalLogic
 * @package WPDesk\Library\DropshippingXmlCore\ConditionalLogic
 */
interface ConditionalLogicWithCompareValue extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogic
{
    /**
     * 
     * @param string $value 
     * @return bool 
     */
    public function set_compare_value(string $value);
    public function get_value_field() : string;
}
