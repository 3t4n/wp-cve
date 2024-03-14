<?php

namespace WPDeskFIVendor\WPDesk\Forms\Field;

class ButtonField extends \WPDeskFIVendor\WPDesk\Forms\Field\NoValueField
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
