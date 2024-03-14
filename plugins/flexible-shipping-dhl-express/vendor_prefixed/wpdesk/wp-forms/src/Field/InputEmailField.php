<?php

namespace DhlVendor\WPDesk\Forms\Field;

use DhlVendor\WPDesk\Forms\Sanitizer;
use DhlVendor\WPDesk\Forms\Sanitizer\EmailSanitizer;
class InputEmailField extends \DhlVendor\WPDesk\Forms\Field\BasicField
{
    public function get_type() : string
    {
        return 'email';
    }
    public function get_sanitizer() : \DhlVendor\WPDesk\Forms\Sanitizer
    {
        return new \DhlVendor\WPDesk\Forms\Sanitizer\EmailSanitizer();
    }
    public function get_template_name() : string
    {
        return 'input-text';
    }
}
