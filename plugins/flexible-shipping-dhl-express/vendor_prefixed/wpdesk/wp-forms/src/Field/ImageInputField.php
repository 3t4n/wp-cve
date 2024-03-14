<?php

namespace DhlVendor\WPDesk\Forms\Field;

class ImageInputField extends \DhlVendor\WPDesk\Forms\Field\BasicField
{
    public function get_template_name() : string
    {
        return 'input-image';
    }
}
