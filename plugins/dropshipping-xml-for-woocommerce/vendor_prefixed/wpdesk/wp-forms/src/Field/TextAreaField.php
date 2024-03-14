<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Field;

class TextAreaField extends \DropshippingXmlFreeVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
    }
    public function get_template_name()
    {
        return 'textarea';
    }
}
