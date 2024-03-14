<?php

namespace DhlVendor\WPDesk\Forms\Validator;

use DhlVendor\WPDesk\Forms\Validator;
class NoValidateValidator implements \DhlVendor\WPDesk\Forms\Validator
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
