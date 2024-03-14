<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Field;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer;
use OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
class DatePickerField extends \OctolizeShippingNoticesVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct()
    {
        $this->add_class('date-picker');
        $this->set_placeholder('YYYY-MM-DD');
    }
    public function get_sanitizer() : \OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer
    {
        return new \OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer();
    }
    public function get_template_name() : string
    {
        return 'input-date-picker';
    }
}
