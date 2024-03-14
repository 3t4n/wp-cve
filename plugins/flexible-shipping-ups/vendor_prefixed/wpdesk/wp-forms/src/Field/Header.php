<?php

namespace UpsFreeVendor\WPDesk\Forms\Field;

use UpsFreeVendor\WPDesk\Forms\Field;
class Header extends \UpsFreeVendor\WPDesk\Forms\Field\NoValueField
{
    public function __construct()
    {
        parent::__construct();
        $this->meta['header_size'] = '';
    }
    public function get_template_name() : string
    {
        return 'header';
    }
    public function should_override_form_template() : bool
    {
        return \true;
    }
    public function set_header_size(int $value) : \UpsFreeVendor\WPDesk\Forms\Field
    {
        $this->meta['header_size'] = $value;
        return $this;
    }
}
