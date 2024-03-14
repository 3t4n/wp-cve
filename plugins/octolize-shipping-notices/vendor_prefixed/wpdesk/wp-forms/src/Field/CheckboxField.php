<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Field;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Field;
class CheckboxField extends \OctolizeShippingNoticesVendor\WPDesk\Forms\Field\BasicField
{
    const VALUE_TRUE = 'yes';
    const VALUE_FALSE = 'no';
    public function get_type() : string
    {
        return 'checkbox';
    }
    public function get_template_name() : string
    {
        return 'input-checkbox';
    }
    public function get_sublabel() : string
    {
        return $this->meta['sublabel'];
    }
    public function set_sublabel(string $value) : \OctolizeShippingNoticesVendor\WPDesk\Forms\Field
    {
        $this->meta['sublabel'] = $value;
        return $this;
    }
    public function has_sublabel() : bool
    {
        return isset($this->meta['sublabel']);
    }
}
