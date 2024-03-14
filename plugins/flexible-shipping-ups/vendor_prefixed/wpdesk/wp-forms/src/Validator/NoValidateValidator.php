<?php

namespace UpsFreeVendor\WPDesk\Forms\Validator;

use UpsFreeVendor\WPDesk\Forms\Validator;
class NoValidateValidator implements \UpsFreeVendor\WPDesk\Forms\Validator
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
