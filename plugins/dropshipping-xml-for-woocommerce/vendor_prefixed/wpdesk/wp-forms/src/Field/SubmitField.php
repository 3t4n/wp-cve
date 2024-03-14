<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Field;

class SubmitField extends \DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoValueField
{
    public function get_template_name()
    {
        return 'input-submit';
    }
    public function get_type()
    {
        return 'submit';
    }
    public function should_override_form_template()
    {
        return \true;
    }
}
