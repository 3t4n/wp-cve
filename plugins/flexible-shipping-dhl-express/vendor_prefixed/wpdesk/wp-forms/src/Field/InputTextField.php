<?php

namespace DhlVendor\WPDesk\Forms\Field;

use DhlVendor\WPDesk\Forms\Sanitizer;
use DhlVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
class InputTextField extends \DhlVendor\WPDesk\Forms\Field\BasicField
{
    public function get_sanitizer() : \DhlVendor\WPDesk\Forms\Sanitizer
    {
        return new \DhlVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer();
    }
    public function get_template_name() : string
    {
        return 'input-text';
    }
}
