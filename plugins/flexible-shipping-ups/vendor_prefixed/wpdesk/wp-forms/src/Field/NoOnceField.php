<?php

namespace UpsFreeVendor\WPDesk\Forms\Field;

use UpsFreeVendor\WPDesk\Forms\Validator;
use UpsFreeVendor\WPDesk\Forms\Validator\NonceValidator;
class NoOnceField extends \UpsFreeVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct(string $action_name)
    {
        $this->meta['action'] = $action_name;
    }
    public function get_validator() : \UpsFreeVendor\WPDesk\Forms\Validator
    {
        return new \UpsFreeVendor\WPDesk\Forms\Validator\NonceValidator($this->get_meta_value('action'));
    }
    public function get_template_name() : string
    {
        return 'noonce';
    }
}
