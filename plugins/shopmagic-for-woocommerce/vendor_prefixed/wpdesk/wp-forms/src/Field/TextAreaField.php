<?php

namespace ShopMagicVendor\WPDesk\Forms\Field;

class TextAreaField extends BasicField
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
