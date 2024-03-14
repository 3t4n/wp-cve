<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent;
/**
 * class NotEmptyConditionalLogic
 * @package WPDesk\Library\DropshippingXmlCore\ConditionalLogic
 */
class NotEmptyConditionalLogic extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\EmptyConditionalLogic
{
    public function is_valid() : bool
    {
        return !parent::is_valid();
    }
    public static function get_name() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_VALUE_TYPE_OPTION_NOT_EMPTY;
    }
}
