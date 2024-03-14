<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Validator;

use DropshippingXmlFreeVendor\WPDesk\Forms\Validator;
class NonceValidator implements \DropshippingXmlFreeVendor\WPDesk\Forms\Validator
{
    private $action;
    public function __construct($action)
    {
        $this->action = $action;
    }
    public function is_valid($value)
    {
        $valid = \wp_verify_nonce($value, $this->action);
        return $valid;
    }
    public function get_messages()
    {
        return [];
    }
}
