<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogic;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogicWithCompareValue;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\ConditionalLogicNoValueException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent;
/**
 * class EmptyConditionalLogic
 *
 * @package WPDesk\Library\DropshippingXmlCore\ConditionalLogic
 */
class ContainsConditionalLogic implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogicWithCompareValue
{
    /**
     *
     * @var string
     */
    private $xpath_value;
    /**
     *
     * @var string
     */
    private $compare_value;
    public function set_xpath_field_value(string $xpath_value)
    {
        $this->xpath_value = $xpath_value;
    }
    public function set_compare_value(string $compare_value)
    {
        $this->compare_value = $compare_value;
    }
    public function is_valid() : bool
    {
        if (!isset($this->xpath_value) || !isset($this->compare_value)) {
            throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\ConditionalLogicNoValueException('Missing required values');
        }
        return \strpos(\mb_strtolower($this->xpath_value), \mb_strtolower($this->compare_value)) !== \false;
    }
    public function get_value_field() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_CONTAINS_VALUE;
    }
    public static function get_name() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_VALUE_TYPE_OPTION_CONTAINS;
    }
}
