<?php

namespace UpsFreeVendor\WPDesk\Forms;

interface Sanitizer
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function sanitize($value);
}
