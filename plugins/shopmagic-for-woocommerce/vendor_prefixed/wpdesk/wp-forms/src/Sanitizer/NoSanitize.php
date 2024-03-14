<?php

namespace ShopMagicVendor\WPDesk\Forms\Sanitizer;

use ShopMagicVendor\WPDesk\Forms\Sanitizer;
class NoSanitize implements Sanitizer
{
    public function sanitize($value)
    {
        return $value;
    }
}
