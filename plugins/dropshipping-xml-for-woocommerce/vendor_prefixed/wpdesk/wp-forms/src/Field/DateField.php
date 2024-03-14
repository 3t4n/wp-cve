<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Field;

use DropshippingXmlFreeVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
class DateField extends \DropshippingXmlFreeVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct()
    {
        parent::__construct();
        $this->set_placeholder('YYYY-MM-DD');
    }
    public function get_type()
    {
        return 'date';
    }
    public function get_template_name()
    {
        return 'input-text';
    }
}
