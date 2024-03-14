<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields;

use DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField;
use DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider;
/**
 * Class ImportSidebarFormFields, import file form fields.
 * @package WPDesk\Library\DropshippingXmlCore\Form\Fields
 */
class ImportSidebarFormFields implements \DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider
{
    const IMPORT_NAME = 'import_name';
    /**
     * @see FieldProvider::get_fields()
     */
    public function get_fields()
    {
        return [(new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::IMPORT_NAME)->set_placeholder(\__('Import name', 'dropshipping-xml-for-woocommerce'))->add_class('width-100')];
    }
}
