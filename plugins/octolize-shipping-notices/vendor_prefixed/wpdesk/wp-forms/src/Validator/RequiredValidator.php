<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Validator;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Validator;
class RequiredValidator implements \OctolizeShippingNoticesVendor\WPDesk\Forms\Validator
{
    public function is_valid($value) : bool
    {
        return $value !== null;
    }
    public function get_messages() : array
    {
        return [];
    }
}
