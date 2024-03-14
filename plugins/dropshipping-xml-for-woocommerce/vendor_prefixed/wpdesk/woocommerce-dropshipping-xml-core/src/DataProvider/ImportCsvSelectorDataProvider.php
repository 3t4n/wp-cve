<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportCsvSelectorForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Provider\Abstraction\DataProvider;
/**
 * Class ImportCsvSelectorDataProvider, import csv selector data provider.
 * @package WPDesk\Library\DropshippingXmlCore\DataProvider
 */
class ImportCsvSelectorDataProvider extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\DropshippingDataProvider
{
    /**
     * @see DataProvider::get_id()
     */
    public static function get_id() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportCsvSelectorForm::get_id();
    }
    protected function get_identity() : string
    {
        return self::get_id();
    }
}
