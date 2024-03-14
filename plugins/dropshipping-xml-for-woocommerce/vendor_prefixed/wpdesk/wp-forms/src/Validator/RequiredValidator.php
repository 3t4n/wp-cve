<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Validator;

use DropshippingXmlFreeVendor\WPDesk\Forms\Validator;
class RequiredValidator implements \DropshippingXmlFreeVendor\WPDesk\Forms\Validator
{
    public function is_valid($value)
    {
        return $value !== null;
    }
    public function get_messages()
    {
        return [];
    }
}
