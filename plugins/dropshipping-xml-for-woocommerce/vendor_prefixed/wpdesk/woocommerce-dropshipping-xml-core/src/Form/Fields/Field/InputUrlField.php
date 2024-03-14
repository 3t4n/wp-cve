<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Field;

use DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Field\Sanitizer\UrlFieldSanitizer;
class InputUrlField extends \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField
{
    public function get_sanitizer()
    {
        return new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Field\Sanitizer\UrlFieldSanitizer();
    }
}
