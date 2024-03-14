<?php

namespace DhlVendor\WPDesk\Forms\Validator;

use DhlVendor\WPDesk\Forms\Validator;
class RequiredValidator implements \DhlVendor\WPDesk\Forms\Validator
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
