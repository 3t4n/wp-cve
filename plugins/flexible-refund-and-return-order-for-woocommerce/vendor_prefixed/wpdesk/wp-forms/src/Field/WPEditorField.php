<?php

namespace FRFreeVendor\WPDesk\Forms\Field;

class WPEditorField extends \FRFreeVendor\WPDesk\Forms\Field\BasicField
{
    public function get_template_name() : string
    {
        return 'wp-editor';
    }
}
