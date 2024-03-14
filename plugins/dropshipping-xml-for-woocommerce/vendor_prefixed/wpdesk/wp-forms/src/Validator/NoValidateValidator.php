<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Validator;

use DropshippingXmlFreeVendor\WPDesk\Forms\Validator;
class NoValidateValidator implements \DropshippingXmlFreeVendor\WPDesk\Forms\Validator
{
    public function is_valid($value)
    {
        return \true;
    }
    public function get_messages()
    {
        return [];
    }
}
