<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Field;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer;
use OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer\EmailSanitizer;
class InputEmailField extends \OctolizeShippingNoticesVendor\WPDesk\Forms\Field\BasicField
{
    public function get_type() : string
    {
        return 'email';
    }
    public function get_sanitizer() : \OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer
    {
        return new \OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer\EmailSanitizer();
    }
    public function get_template_name() : string
    {
        return 'input-text';
    }
}
