<?php

namespace ShopMagicVendor\WPDesk\Forms\Field;

class ButtonField extends NoValueField
{
    public function get_template_name() : string
    {
        return 'button';
    }
    public function get_type() : string
    {
        return 'button';
    }
}
