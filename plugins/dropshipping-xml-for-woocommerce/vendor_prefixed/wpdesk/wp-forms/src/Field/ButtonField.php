<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Field;

class ButtonField extends \DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoValueField
{
    public function get_template_name()
    {
        return 'button';
    }
    public function get_type()
    {
        return 'button';
    }
}
