<?php

namespace ShopMagicVendor\WPDesk\Forms\Field;

use ShopMagicVendor\WPDesk\Forms\Serializer\ProductSelectSerializer;
use ShopMagicVendor\WPDesk\Forms\Serializer;
class ProductSelect extends SelectField
{
    public function __construct()
    {
        $this->set_multiple();
    }
    public function has_serializer() : bool
    {
        return \true;
    }
    public function get_serializer() : Serializer
    {
        return new ProductSelectSerializer();
    }
    public function get_template_name() : string
    {
        return 'product-select';
    }
}
