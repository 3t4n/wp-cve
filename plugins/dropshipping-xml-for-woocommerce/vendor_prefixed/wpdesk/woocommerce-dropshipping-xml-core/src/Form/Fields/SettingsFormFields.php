<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields;

use DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputNumberField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoOnceField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\SubmitField;
use DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider;
/**
 * Class SettingsFormFields, settings form fields.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Form\Fields
 */
class SettingsFormFields implements \DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider
{
    const INPUT_TEXT_BATCH = 'products_in_batch';
    const SUBMIT_SAVE = 'save';
    const NONCE_ACTION = 'settings_action';
    const NONCE_NAME = 'settings_nonce';
    const DEFAULT_IN_BATCH = 30;
    /**
     * @see FieldProvider::get_fields()
     */
    public function get_fields()
    {
        return [(new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputNumberField())->set_label(\__('Products in one batch', 'dropshipping-xml-for-woocommerce'))->set_description(\__('Number of products imported in batch. <b>Read more in the</b> <a href="https://wpde.sk/dropshipping-settings" class="docs-url" target="_blank" >plugin documentation &rarr;</a>', 'dropshipping-xml-for-woocommerce'))->set_default_value(self::DEFAULT_IN_BATCH)->set_name(self::INPUT_TEXT_BATCH), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SubmitField())->set_name(self::SUBMIT_SAVE)->set_label(\__('Save settings', 'dropshipping-xml-for-woocommerce'))->add_class('button button-primary')->set_attribute('id', 'save'), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoOnceField(self::NONCE_ACTION))->set_name(self::NONCE_NAME)];
    }
}
