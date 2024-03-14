<?php

namespace UpsFreeVendor\WPDesk\Forms\Field;

use UpsFreeVendor\WPDesk\Forms\Sanitizer;
use UpsFreeVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
class DatePickerField extends \UpsFreeVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct()
    {
        $this->add_class('date-picker');
        $this->set_placeholder('YYYY-MM-DD');
    }
    public function get_sanitizer() : \UpsFreeVendor\WPDesk\Forms\Sanitizer
    {
        return new \UpsFreeVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer();
    }
    public function get_template_name() : string
    {
        return 'input-date-picker';
    }
}
