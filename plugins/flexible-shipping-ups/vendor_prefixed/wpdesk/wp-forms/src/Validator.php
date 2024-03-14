<?php

namespace UpsFreeVendor\WPDesk\Forms;

interface Validator
{
    /** @param mixed $value */
    public function is_valid($value) : bool;
    /** @return string[] */
    public function get_messages() : array;
}
