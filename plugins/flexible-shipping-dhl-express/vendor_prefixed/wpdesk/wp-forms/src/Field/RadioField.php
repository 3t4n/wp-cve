<?php

namespace DhlVendor\WPDesk\Forms\Field;

class RadioField extends \DhlVendor\WPDesk\Forms\Field\BasicField
{
    public function get_type() : string
    {
        return 'radio';
    }
    public function get_template_name() : string
    {
        return 'input-radio';
    }
}
