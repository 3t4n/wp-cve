<?php

namespace DhlVendor\WPDesk\Forms\Field;

use DhlVendor\WPDesk\Forms\Sanitizer;
use DhlVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
class DatePickerField extends \DhlVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct()
    {
        $this->add_class('date-picker');
        $this->set_placeholder('YYYY-MM-DD');
    }
    public function get_sanitizer() : \DhlVendor\WPDesk\Forms\Sanitizer
    {
        return new \DhlVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer();
    }
    public function get_template_name() : string
    {
        return 'input-date-picker';
    }
}
