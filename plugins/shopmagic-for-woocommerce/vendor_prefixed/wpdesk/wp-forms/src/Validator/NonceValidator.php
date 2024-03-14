<?php

namespace ShopMagicVendor\WPDesk\Forms\Validator;

use ShopMagicVendor\WPDesk\Forms\Validator;
class NonceValidator implements Validator
{
    /** @var string */
    private $action;
    /** @param string $action */
    public function __construct($action)
    {
        $this->action = $action;
    }
    public function is_valid($value) : bool
    {
        return (bool) wp_verify_nonce($value, $this->action);
    }
    public function get_messages() : array
    {
        return [];
    }
}
