<?php

namespace DhlVendor\WPDesk\Forms\Field;

use DhlVendor\WPDesk\Forms\Serializer;
use DhlVendor\WPDesk\Forms\Serializer\JsonSerializer;
class TimepickerField extends \DhlVendor\WPDesk\Forms\Field\BasicField
{
    public function get_type() : string
    {
        return 'time';
    }
    public function has_serializer() : bool
    {
        return \true;
    }
    public function get_serializer() : \DhlVendor\WPDesk\Forms\Serializer
    {
        return new \DhlVendor\WPDesk\Forms\Serializer\JsonSerializer();
    }
    public function get_template_name() : string
    {
        return 'timepicker';
    }
}
