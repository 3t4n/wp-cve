<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer;
class TextFieldSanitizer implements \OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer
{
    /** @return string|string[] */
    public function sanitize($value)
    {
        if (\is_array($value)) {
            return \array_map('sanitize_text_field', $value);
        }
        return \sanitize_text_field($value);
    }
}
