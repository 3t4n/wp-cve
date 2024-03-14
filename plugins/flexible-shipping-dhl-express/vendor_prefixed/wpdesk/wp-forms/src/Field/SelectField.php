<?php

namespace DhlVendor\WPDesk\Forms\Field;

use DhlVendor\WPDesk\Forms\Field;
class SelectField extends \DhlVendor\WPDesk\Forms\Field\BasicField
{
    public function get_type() : string
    {
        return 'select';
    }
    public function get_template_name() : string
    {
        return 'select';
    }
    /** @param string[] $options */
    public function set_options(array $options) : \DhlVendor\WPDesk\Forms\Field
    {
        $this->meta['possible_values'] = $options;
        return $this;
    }
    public function set_multiple() : \DhlVendor\WPDesk\Forms\Field
    {
        $this->attributes['multiple'] = 'multiple';
        return $this;
    }
}
