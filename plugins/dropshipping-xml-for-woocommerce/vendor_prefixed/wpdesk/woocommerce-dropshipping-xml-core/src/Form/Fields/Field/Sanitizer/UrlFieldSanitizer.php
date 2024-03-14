<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Field\Sanitizer;

use DropshippingXmlFreeVendor\WPDesk\Forms\Sanitizer;
use InvalidArgumentException;
class UrlFieldSanitizer implements \DropshippingXmlFreeVendor\WPDesk\Forms\Sanitizer
{
    public function sanitize($value)
    {
        $value = \esc_url_raw($value);
        if (!\filter_var($value, \FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Error, file url is not valid http address');
        }
        return $value;
    }
}
