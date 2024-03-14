<?php

namespace UpsFreeVendor\WPDesk\Forms\Sanitizer;

use UpsFreeVendor\WPDesk\Forms\Sanitizer;
class EmailSanitizer implements \UpsFreeVendor\WPDesk\Forms\Sanitizer
{
    public function sanitize($value) : string
    {
        return \sanitize_email($value);
    }
}
