<?php

namespace UpsFreeVendor\WPDesk\Forms\Field;

class TextAreaField extends \UpsFreeVendor\WPDesk\Forms\Field\BasicField
{
    public function get_type() : string
    {
        return 'textarea';
    }
    public function get_template_name() : string
    {
        return 'textarea';
    }
}
