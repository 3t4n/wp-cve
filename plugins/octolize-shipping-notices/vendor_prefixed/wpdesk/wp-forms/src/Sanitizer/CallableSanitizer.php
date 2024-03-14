<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer;
class CallableSanitizer implements \OctolizeShippingNoticesVendor\WPDesk\Forms\Sanitizer
{
    /** @var callable */
    private $callable;
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }
    public function sanitize($value) : string
    {
        return \call_user_func($this->callable, $value);
    }
}
