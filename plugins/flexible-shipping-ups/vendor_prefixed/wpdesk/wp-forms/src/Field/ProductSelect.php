<?php

namespace UpsFreeVendor\WPDesk\Forms\Field;

use UpsFreeVendor\WPDesk\Forms\Serializer\ProductSelectSerializer;
use UpsFreeVendor\WPDesk\Forms\Serializer;
class ProductSelect extends \UpsFreeVendor\WPDesk\Forms\Field\SelectField
{
    public function __construct()
    {
        $this->set_multiple();
    }
    public function has_serializer() : bool
    {
        return \true;
    }
    public function get_serializer() : \UpsFreeVendor\WPDesk\Forms\Serializer
    {
        return new \UpsFreeVendor\WPDesk\Forms\Serializer\ProductSelectSerializer();
    }
    public function get_template_name() : string
    {
        return 'product-select';
    }
}
