<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Field;

class MultipleInputTextField extends \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField
{
    /**
     * @return string
     */
    public function get_template_name()
    {
        return 'input-text-multiple';
    }
}
