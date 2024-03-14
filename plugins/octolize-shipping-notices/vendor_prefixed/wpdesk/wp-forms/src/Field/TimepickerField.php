<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Field;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer;
use OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer\JsonSerializer;
class TimepickerField extends \OctolizeShippingNoticesVendor\WPDesk\Forms\Field\BasicField
{
    public function get_type() : string
    {
        return 'time';
    }
    public function has_serializer() : bool
    {
        return \true;
    }
    public function get_serializer() : \OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer
    {
        return new \OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer\JsonSerializer();
    }
    public function get_template_name() : string
    {
        return 'timepicker';
    }
}
