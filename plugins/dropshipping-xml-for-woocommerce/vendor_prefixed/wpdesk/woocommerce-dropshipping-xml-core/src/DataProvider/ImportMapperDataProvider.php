<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportMapperForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Provider\Abstraction\DataProvider;
/**
 * Class ImportMapperDataProvider, import mapper data provider.
 * @package WPDesk\Library\DropshippingXmlCore\DataProvider
 */
class ImportMapperDataProvider extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\DropshippingDataProvider
{
    /**
     * @see DataProvider::get_id()
     */
    public static function get_id() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportMapperForm::get_id();
    }
    protected function get_identity() : string
    {
        return self::get_id();
    }
}
