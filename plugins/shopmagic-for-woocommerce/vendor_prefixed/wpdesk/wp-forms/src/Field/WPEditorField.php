<?php

namespace ShopMagicVendor\WPDesk\Forms\Field;

class WPEditorField extends BasicField
{
    public function get_template_name() : string
    {
        return 'wp-editor';
    }
}
