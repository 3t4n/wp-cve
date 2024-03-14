<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer;
class EmailSanitizer implements \OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer
{
    public function sanitize($value) : string
    {
        return \sanitize_email($value);
    }
}
