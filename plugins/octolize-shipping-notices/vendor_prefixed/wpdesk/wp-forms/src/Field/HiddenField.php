<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Field;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer;
use OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
class HiddenField extends \OctolizeShippingNoticesVendor\WPDesk\Forms\Field\BasicField
{
    public function get_type() : string
    {
        return 'hidden';
    }
    public function get_sanitizer() : \OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer
    {
        return new \OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer();
    }
    public function get_template_name() : string
    {
        return 'input-hidden';
    }
}
