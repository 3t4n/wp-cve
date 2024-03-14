<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields;

use DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoOnceField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\SubmitField;
use DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider;
/**
 * Class ImportManagerFormFields, import manager form fields.
 * @package WPDesk\Library\DropshippingXmlCore\Form\Fields
 */
class ImportManagerFormFields implements \DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider
{
    const SELECT_MANAGE = 'manage';
    const SELECT_MANAGE_BOTTOM = 'manage2';
    const SELECT_MANAGE_OPTION_BULK = 'none';
    const SELECT_MANAGE_OPTION_REMOVE = 'remove';
    const SUBMIT_MANAGE_APPLY = 'apply';
    const SUBMIT_MANAGE_APPLY_BOTTOM = 'apply2';
    const NONCE_ACTION = 'import_manager_action';
    const NONCE_NAME = 'import_manager_nonce';
    /**
     * @see FieldProvider::get_fields()
     */
    public function get_fields()
    {
        $beacon = $this->get_beacon_translations();
        return [(new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_name(self::SELECT_MANAGE)->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['import'])->set_options($this->get_manage_select_options()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SubmitField())->set_name(self::SUBMIT_MANAGE_APPLY)->set_label(\__('Apply', 'dropshipping-xml-for-woocommerce'))->add_class('button action')->set_attribute('id', self::SUBMIT_MANAGE_APPLY), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_name(self::SELECT_MANAGE_BOTTOM)->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['import'])->set_options($this->get_manage_select_options()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SubmitField())->set_name(self::SUBMIT_MANAGE_APPLY_BOTTOM)->set_label(\__('Apply', 'dropshipping-xml-for-woocommerce'))->add_class('button action')->set_attribute('id', self::SUBMIT_MANAGE_APPLY_BOTTOM), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoOnceField(self::NONCE_ACTION))->set_name(self::NONCE_NAME)];
    }
    private function get_manage_select_options() : array
    {
        return [self::SELECT_MANAGE_OPTION_BULK => \__('Bulk', 'dropshipping-xml-for-woocommerce'), self::SELECT_MANAGE_OPTION_REMOVE => \__('Remove', 'dropshipping-xml-for-woocommerce')];
    }
    private function get_beacon_translations() : array
    {
        return ['import' => \__('Import manager', 'dropshipping-xml-for-woocommerce')];
    }
}
