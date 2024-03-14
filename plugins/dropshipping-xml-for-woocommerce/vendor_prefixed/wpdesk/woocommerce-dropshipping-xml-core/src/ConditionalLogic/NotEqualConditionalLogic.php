<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogic;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent;
/**
 * class EmptyConditionalLogic
 * @package WPDesk\Library\DropshippingXmlCore\ConditionalLogic
 */
class NotEqualConditionalLogic extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\EqualConditionalLogic
{
    public function is_valid() : bool
    {
        return !parent::is_valid();
    }
    public function get_value_field() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_NOT_EQUAL_VALUE;
    }
    public static function get_name() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_VALUE_TYPE_OPTION_NOT_EQUAL;
    }
}
