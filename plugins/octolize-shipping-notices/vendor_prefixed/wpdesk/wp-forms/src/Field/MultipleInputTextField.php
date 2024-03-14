<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Field;

class MultipleInputTextField extends \OctolizeShippingNoticesVendor\WPDesk\Forms\Field\InputTextField
{
    public function get_template_name() : string
    {
        return 'input-text-multiple';
    }
}
