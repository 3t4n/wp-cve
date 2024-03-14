<?php

namespace DhlVendor\WPDesk\Forms\Field;

use DhlVendor\WPDesk\Forms\Serializer\ProductSelectSerializer;
use DhlVendor\WPDesk\Forms\Serializer;
class ProductSelect extends \DhlVendor\WPDesk\Forms\Field\SelectField
{
    public function __construct()
    {
        $this->set_multiple();
    }
    public function has_serializer() : bool
    {
        return \true;
    }
    public function get_serializer() : \DhlVendor\WPDesk\Forms\Serializer
    {
        return new \DhlVendor\WPDesk\Forms\Serializer\ProductSelectSerializer();
    }
    public function get_template_name() : string
    {
        return 'product-select';
    }
}
