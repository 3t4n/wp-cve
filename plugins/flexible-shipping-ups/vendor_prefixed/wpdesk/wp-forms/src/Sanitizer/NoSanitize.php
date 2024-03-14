<?php

namespace UpsFreeVendor\WPDesk\Forms\Sanitizer;

use UpsFreeVendor\WPDesk\Forms\Sanitizer;
class NoSanitize implements \UpsFreeVendor\WPDesk\Forms\Sanitizer
{
    public function sanitize($value)
    {
        return $value;
    }
}
