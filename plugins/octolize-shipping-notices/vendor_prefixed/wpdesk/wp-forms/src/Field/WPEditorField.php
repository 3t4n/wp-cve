<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Field;

class WPEditorField extends \OctolizeShippingNoticesVendor\WPDesk\Forms\Field\BasicField
{
    public function get_template_name() : string
    {
        return 'wp-editor';
    }
}
