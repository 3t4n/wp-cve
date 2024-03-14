<?php

namespace DhlVendor\WPDesk\Forms\Sanitizer;

use DhlVendor\WPDesk\Forms\Sanitizer;
class NoSanitize implements \DhlVendor\WPDesk\Forms\Sanitizer
{
    public function sanitize($value)
    {
        return $value;
    }
}
