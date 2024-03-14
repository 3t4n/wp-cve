<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportSidebarForm;
/**
 * Class ImportSidebarDataProvider, import options data provider.
 * @package WPDesk\Library\DropshippingXmlCore\DataProvider
 */
class ImportSidebarDataProvider extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\DropshippingDataProvider
{
    /**
     * @see DataProvider::get_id()
     */
    public static function get_id() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportSidebarForm::get_id();
    }
    protected function get_identity() : string
    {
        return self::get_id();
    }
}
