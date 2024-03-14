<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Validator;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Validator;
class NoValidateValidator implements \OctolizeShippingNoticesVendor\WPDesk\Forms\Validator
{
    public function is_valid($value) : bool
    {
        return \true;
    }
    public function get_messages() : array
    {
        return [];
    }
}
