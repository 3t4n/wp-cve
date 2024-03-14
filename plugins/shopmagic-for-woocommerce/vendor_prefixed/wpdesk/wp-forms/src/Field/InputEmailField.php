<?php

namespace ShopMagicVendor\WPDesk\Forms\Field;

use ShopMagicVendor\WPDesk\Forms\Sanitizer;
use ShopMagicVendor\WPDesk\Forms\Sanitizer\EmailSanitizer;
class InputEmailField extends BasicField
{
    public function get_type() : string
    {
        return 'email';
    }
    public function get_sanitizer() : Sanitizer
    {
        return new EmailSanitizer();
    }
    public function get_template_name() : string
    {
        return 'input-text';
    }
}
