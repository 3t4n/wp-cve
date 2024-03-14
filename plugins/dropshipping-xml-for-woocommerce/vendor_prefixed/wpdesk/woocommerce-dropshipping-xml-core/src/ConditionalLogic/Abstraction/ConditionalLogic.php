<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction;

/**
 * intreface ConditionalLogic
 * @package WPDesk\Library\DropshippingXmlCore\ConditionalLogic
 */
interface ConditionalLogic
{
    public function set_xpath_field_value(string $xpath_value);
    public function is_valid() : bool;
    public static function get_name() : string;
}
