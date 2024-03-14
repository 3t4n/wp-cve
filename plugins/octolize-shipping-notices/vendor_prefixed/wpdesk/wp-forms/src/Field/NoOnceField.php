<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Field;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Validator;
use OctolizeShippingNoticesVendor\WPDesk\Forms\Validator\NonceValidator;
class NoOnceField extends \OctolizeShippingNoticesVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct(string $action_name)
    {
        $this->meta['action'] = $action_name;
    }
    public function get_validator() : \OctolizeShippingNoticesVendor\WPDesk\Forms\Validator
    {
        return new \OctolizeShippingNoticesVendor\WPDesk\Forms\Validator\NonceValidator($this->get_meta_value('action'));
    }
    public function get_template_name() : string
    {
        return 'noonce';
    }
}
