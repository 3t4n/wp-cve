<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File;

/**
 * Interface SeparatorInterface, separator interface for converter.
 * @package WPDesk\Library\DropshippingXmlCore\Converter\File
 */
interface SeparatorInterface
{
    public function set_separator(string $separator);
    public function get_separator() : string;
}
