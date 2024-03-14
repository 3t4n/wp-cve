<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Form\Abstraction;

/**
 * Interface FormIdentity, add get_id method to form.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\View
 */
interface FormIdentity
{
    public static function get_id() : string;
}
