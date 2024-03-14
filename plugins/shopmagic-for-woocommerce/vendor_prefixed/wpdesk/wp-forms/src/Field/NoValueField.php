<?php

namespace ShopMagicVendor\WPDesk\Forms\Field;

/**
 * Base class for Fields that can show itself on form but cannot process any value.
 *
 * @package WPDesk\Forms
 */
abstract class NoValueField extends BasicField
{
    public function __construct()
    {
        $this->set_name('');
    }
}
