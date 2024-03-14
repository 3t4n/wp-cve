<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogic;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\ConditionalLogicNoValueException;
/**
 * 
 * class EmptyConditionalLogic
 * @package WPDesk\Library\DropshippingXmlCore\ConditionalLogic
 */
class EmptyConditionalLogic implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogic
{
    /**
     * 
     * @var string
     */
    private $xpath_value;
    public function set_xpath_field_value(string $xpath_value)
    {
        $this->xpath_value = $xpath_value;
    }
    public function is_valid() : bool
    {
        if (!isset($this->xpath_value)) {
            throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\ConditionalLogicNoValueException('Missing required values');
        }
        return empty($this->xpath_value);
    }
    public static function get_name() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_VALUE_TYPE_OPTION_EMPTY;
    }
}
