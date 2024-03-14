<?php

namespace UpsFreeVendor\WPDesk\Forms\Validator;

use UpsFreeVendor\WPDesk\Forms\Validator;
class RequiredValidator implements \UpsFreeVendor\WPDesk\Forms\Validator
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
