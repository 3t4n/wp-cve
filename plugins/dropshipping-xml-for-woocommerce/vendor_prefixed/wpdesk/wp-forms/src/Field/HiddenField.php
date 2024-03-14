<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Field;

use DropshippingXmlFreeVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
class HiddenField extends \DropshippingXmlFreeVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
        $this->set_attribute('type', 'hidden');
    }
    public function get_sanitizer()
    {
        return new \DropshippingXmlFreeVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer();
    }
    public function get_template_name()
    {
        return 'input-hidden';
    }
}
