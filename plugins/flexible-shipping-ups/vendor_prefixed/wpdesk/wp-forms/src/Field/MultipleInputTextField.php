<?php

namespace UpsFreeVendor\WPDesk\Forms\Field;

class MultipleInputTextField extends \UpsFreeVendor\WPDesk\Forms\Field\InputTextField
{
    public function get_template_name() : string
    {
        return 'input-text-multiple';
    }
}
