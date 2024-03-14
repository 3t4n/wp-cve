<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields;

use DropshippingXmlFreeVendor\WPDesk\Forms\Field\HiddenField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputNumberField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoOnceField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\SubmitField;
use DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider;
/**
 * Class ImportXmlSelectorFormFields, import xml selector form fields.
 * @package WPDesk\Library\DropshippingXmlCore\Form\Fields
 */
class ImportXmlSelectorFormFields implements \DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider
{
    const NODE_ELEMENT = 'node_element';
    const NODE_ELEMENT_ID = 'dropshipping-node-element';
    const ITEM_NUMBER = 'item_number';
    const ITEM_NUMBER_ID = 'dropshipping-item-nr';
    const SUBMIT_NEXT_STEP = 'next_step';
    const NONCE_ACTION = 'import_xml_selector_action';
    const NONCE_NAME = 'import_xml_selector_nonce';
    /**
     * @see FieldProvider::get_fields()
     */
    public function get_fields()
    {
        $beacon = $this->get_beacon_translations();
        return [(new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputNumberField())->add_class('input-text regular-input padding-xs hs-beacon-search')->set_name(self::ITEM_NUMBER)->set_default_value(1)->set_attribute('data-beacon_search', $beacon['preview'])->set_attribute('id', self::ITEM_NUMBER_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\HiddenField())->set_name(self::NODE_ELEMENT)->set_attribute('id', self::NODE_ELEMENT_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SubmitField())->set_name(self::SUBMIT_NEXT_STEP)->set_label(\__('Go to the next step &rarr;', 'dropshipping-xml-for-woocommerce'))->add_class('button button-primary button-hero')->set_attribute('id', self::SUBMIT_NEXT_STEP), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoOnceField(self::NONCE_ACTION))->set_name(self::NONCE_NAME)];
    }
    private function get_beacon_translations() : array
    {
        return ['preview' => \__('Step 2/4 - File preview', 'dropshipping-xml-for-woocommerce')];
    }
}
