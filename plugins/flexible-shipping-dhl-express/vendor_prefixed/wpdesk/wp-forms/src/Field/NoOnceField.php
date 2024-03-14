<?php

namespace DhlVendor\WPDesk\Forms\Field;

use DhlVendor\WPDesk\Forms\Validator;
use DhlVendor\WPDesk\Forms\Validator\NonceValidator;
class NoOnceField extends \DhlVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct(string $action_name)
    {
        $this->meta['action'] = $action_name;
    }
    public function get_validator() : \DhlVendor\WPDesk\Forms\Validator
    {
        return new \DhlVendor\WPDesk\Forms\Validator\NonceValidator($this->get_meta_value('action'));
    }
    public function get_template_name() : string
    {
        return 'noonce';
    }
}
