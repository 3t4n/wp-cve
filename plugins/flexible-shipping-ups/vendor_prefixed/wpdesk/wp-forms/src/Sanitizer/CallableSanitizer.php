<?php

namespace UpsFreeVendor\WPDesk\Forms\Sanitizer;

use UpsFreeVendor\WPDesk\Forms\Sanitizer;
class CallableSanitizer implements \UpsFreeVendor\WPDesk\Forms\Sanitizer
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
