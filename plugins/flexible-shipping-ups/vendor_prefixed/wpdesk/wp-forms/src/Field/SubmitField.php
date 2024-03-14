<?php

namespace UpsFreeVendor\WPDesk\Forms\Field;

class SubmitField extends \UpsFreeVendor\WPDesk\Forms\Field\NoValueField
{
    public function get_template_name() : string
    {
        return 'input-submit';
    }
    public function get_type() : string
    {
        return 'submit';
    }
    public function should_override_form_template() : bool
    {
        return \true;
    }
}
