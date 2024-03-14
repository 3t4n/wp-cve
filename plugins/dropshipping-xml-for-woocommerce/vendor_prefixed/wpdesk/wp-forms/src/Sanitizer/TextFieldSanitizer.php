<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Sanitizer;

use DropshippingXmlFreeVendor\WPDesk\Forms\Sanitizer;
class TextFieldSanitizer implements \DropshippingXmlFreeVendor\WPDesk\Forms\Sanitizer
{
    public function sanitize($value)
    {
        return \sanitize_text_field($value);
    }
}
