<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component;

use DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField;
/**
 * Class ConditionalLogicComponent, conditional logic form fields component.
 * @package WPDesk\Library\DropshippingXmlCore\Form\Fields\Component
 */
class ConditionalLogicComponent extends \DropshippingXmlFreeVendor\WPDesk\Forms\Field\BasicField
{
    const FIELD_IMPORT = 'import';
    const FIELD_IMPORT_OPTION_ALLOW = 'allow';
    const FIELD_IMPORT_OPTION_DISALLOW = 'disallow';
    const FIELD_XPATH = 'xpath';
    const FIELD_VALUE_TYPE = 'value_type';
    const FIELD_VALUE_TYPE_OPTION_HIGHER = 'higher';
    const FIELD_VALUE_TYPE_OPTION_LOWER = 'lower';
    const FIELD_VALUE_TYPE_OPTION_EQUAL = 'equal';
    const FIELD_VALUE_TYPE_OPTION_NOT_EQUAL = 'not_equal';
    const FIELD_VALUE_TYPE_OPTION_EMPTY = 'empty';
    const FIELD_VALUE_TYPE_OPTION_NOT_EMPTY = 'not_empty';
    const FIELD_VALUE_TYPE_OPTION_CONTAINS = 'contains';
    const FIELD_VALUE_TYPE_OPTION_NOT_CONTAINS = 'not_contains';
    const FIELD_EQUAL_VALUE = 'equal';
    const FIELD_NOT_EQUAL_VALUE = 'not_equal';
    const FIELD_HIGHER_VALUE = 'higher';
    const FIELD_LOWER_VALUE = 'lower';
    const FIELD_EMPTY_VALUE = 'empty';
    const FIELD_NOT_EMPTY_VALUE = 'not_empty';
    const FIELD_CONTAINS_VALUE = 'contains';
    const FIELD_NOT_CONTAINS_VALUE = 'not_contains';
    /**
     * 
     * @var array
     */
    private $items;
    public function __construct()
    {
        parent::__construct();
        $this->attributes['multiple'] = \true;
    }
    public function get_items() : array
    {
        if (!isset($this->items)) {
            $this->items = [(new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_name(self::FIELD_IMPORT)->add_class('select short width-100')->set_attribute('type', 'select')->set_options($this->get_import_options()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->add_class('width-100')->set_label(\__('product if', 'dropshipping-xml-for-woocommerce'))->set_name(self::FIELD_XPATH)->set_placeholder(\__('Xpath to field', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('field value is', 'dropshipping-xml-for-woocommerce'))->set_name(self::FIELD_VALUE_TYPE)->add_class('select short conditional-value-type width-100')->set_attribute('type', 'select')->set_options($this->get_value_types()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->add_class('width-100')->set_name(self::FIELD_EQUAL_VALUE)->set_attribute('data-value', self::FIELD_VALUE_TYPE_OPTION_EQUAL)->set_placeholder(\__('String, numeric value or xpath', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->add_class('width-100')->set_name(self::FIELD_NOT_EQUAL_VALUE)->set_attribute('data-value', self::FIELD_VALUE_TYPE_OPTION_NOT_EQUAL)->set_placeholder(\__('String, numeric value or xpath', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->add_class('width-100')->set_name(self::FIELD_HIGHER_VALUE)->set_attribute('data-value', self::FIELD_VALUE_TYPE_OPTION_HIGHER)->set_placeholder(\__('Numeric value or xpath', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->add_class('width-100')->set_name(self::FIELD_LOWER_VALUE)->set_attribute('data-value', self::FIELD_VALUE_TYPE_OPTION_LOWER)->set_placeholder(\__('Numeric value or xpath', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->add_class('width-100')->set_name(self::FIELD_CONTAINS_VALUE)->set_attribute('data-value', self::FIELD_VALUE_TYPE_OPTION_CONTAINS)->set_placeholder(\__('String, numeric value or xpath', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->add_class('width-100')->set_name(self::FIELD_NOT_CONTAINS_VALUE)->set_attribute('data-value', self::FIELD_VALUE_TYPE_OPTION_NOT_CONTAINS)->set_placeholder(\__('String, numeric value or xpath', 'dropshipping-xml-for-woocommerce'))];
        }
        return $this->items;
    }
    public function get_template_name() : string
    {
        return 'conditional-logic-component';
    }
    private function get_value_types() : array
    {
        return [self::FIELD_VALUE_TYPE_OPTION_EMPTY => \__('Empty', 'dropshipping-xml-for-woocommerce'), self::FIELD_VALUE_TYPE_OPTION_NOT_EMPTY => \__('Not empty', 'dropshipping-xml-for-woocommerce'), self::FIELD_VALUE_TYPE_OPTION_HIGHER => \__('Higher than', 'dropshipping-xml-for-woocommerce'), self::FIELD_VALUE_TYPE_OPTION_LOWER => \__('Lower than', 'dropshipping-xml-for-woocommerce'), self::FIELD_VALUE_TYPE_OPTION_EQUAL => \__('Equal', 'dropshipping-xml-for-woocommerce'), self::FIELD_VALUE_TYPE_OPTION_NOT_EQUAL => \__('Not equal', 'dropshipping-xml-for-woocommerce'), self::FIELD_VALUE_TYPE_OPTION_CONTAINS => \__('Contains', 'dropshipping-xml-for-woocommerce'), self::FIELD_VALUE_TYPE_OPTION_NOT_CONTAINS => \__('Not contains', 'dropshipping-xml-for-woocommerce')];
    }
    private function get_import_options() : array
    {
        return [self::FIELD_IMPORT_OPTION_ALLOW => \__('Import', 'dropshipping-xml-for-woocommerce'), self::FIELD_IMPORT_OPTION_DISALLOW => \__('Don\'t import', 'dropshipping-xml-for-woocommerce')];
    }
}
