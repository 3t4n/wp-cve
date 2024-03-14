<?php

namespace ShopMagicVendor\WPDesk\Forms\Field;

class MultipleInputTextField extends InputTextField
{
    public function get_template_name() : string
    {
        return 'input-text-multiple';
    }
}
