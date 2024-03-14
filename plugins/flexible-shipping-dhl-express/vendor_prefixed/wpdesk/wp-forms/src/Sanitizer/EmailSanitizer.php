<?php

namespace DhlVendor\WPDesk\Forms\Sanitizer;

use DhlVendor\WPDesk\Forms\Sanitizer;
class EmailSanitizer implements \DhlVendor\WPDesk\Forms\Sanitizer
{
    public function sanitize($value) : string
    {
        return \sanitize_email($value);
    }
}
