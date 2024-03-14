<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Field;

class Paragraph extends \OctolizeShippingNoticesVendor\WPDesk\Forms\Field\NoValueField
{
    public function get_template_name() : string
    {
        return 'paragraph';
    }
    public function should_override_form_template() : bool
    {
        return \true;
    }
}
