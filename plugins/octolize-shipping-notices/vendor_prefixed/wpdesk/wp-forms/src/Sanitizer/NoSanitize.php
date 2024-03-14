<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer;
class NoSanitize implements \OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer
{
    public function sanitize($value)
    {
        return $value;
    }
}
