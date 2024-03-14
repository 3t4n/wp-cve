<?php

namespace UpsFreeVendor\WPDesk\Forms\Field;

use UpsFreeVendor\WPDesk\Forms\Sanitizer;
use UpsFreeVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
class InputTextField extends \UpsFreeVendor\WPDesk\Forms\Field\BasicField
{
    public function get_sanitizer() : \UpsFreeVendor\WPDesk\Forms\Sanitizer
    {
        return new \UpsFreeVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer();
    }
    public function get_template_name() : string
    {
        return 'input-text';
    }
}
