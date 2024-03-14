<?php

namespace UpsFreeVendor\WPDesk\Forms\Field;

class WPEditorField extends \UpsFreeVendor\WPDesk\Forms\Field\BasicField
{
    public function get_template_name() : string
    {
        return 'wp-editor';
    }
}
