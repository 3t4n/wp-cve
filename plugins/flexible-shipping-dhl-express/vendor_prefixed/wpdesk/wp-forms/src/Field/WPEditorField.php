<?php

namespace DhlVendor\WPDesk\Forms\Field;

class WPEditorField extends \DhlVendor\WPDesk\Forms\Field\BasicField
{
    public function get_template_name() : string
    {
        return 'wp-editor';
    }
}
