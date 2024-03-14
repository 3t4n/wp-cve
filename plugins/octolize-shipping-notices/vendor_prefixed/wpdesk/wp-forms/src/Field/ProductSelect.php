<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Field;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer\ProductSelectSerializer;
use OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer;
class ProductSelect extends \OctolizeShippingNoticesVendor\WPDesk\Forms\Field\SelectField
{
    public function __construct()
    {
        $this->set_multiple();
    }
    public function has_serializer() : bool
    {
        return \true;
    }
    public function get_serializer() : \OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer
    {
        return new \OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer\ProductSelectSerializer();
    }
    public function get_template_name() : string
    {
        return 'product-select';
    }
}
