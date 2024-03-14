<?php

namespace UpsFreeVendor\WPDesk\Forms\Field;

use UpsFreeVendor\WPDesk\Forms\Serializer;
use UpsFreeVendor\WPDesk\Forms\Serializer\JsonSerializer;
class TimepickerField extends \UpsFreeVendor\WPDesk\Forms\Field\BasicField
{
    public function get_type() : string
    {
        return 'time';
    }
    public function has_serializer() : bool
    {
        return \true;
    }
    public function get_serializer() : \UpsFreeVendor\WPDesk\Forms\Serializer
    {
        return new \UpsFreeVendor\WPDesk\Forms\Serializer\JsonSerializer();
    }
    public function get_template_name() : string
    {
        return 'timepicker';
    }
}
