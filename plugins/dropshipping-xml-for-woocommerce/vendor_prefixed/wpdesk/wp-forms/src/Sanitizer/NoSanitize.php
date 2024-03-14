<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Sanitizer;

use DropshippingXmlFreeVendor\WPDesk\Forms\Sanitizer;
class NoSanitize implements \DropshippingXmlFreeVendor\WPDesk\Forms\Sanitizer
{
    public function sanitize($value)
    {
        return $value;
    }
}
