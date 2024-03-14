<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Connector\Abstraction;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
/**
 * Class Client, abstraction layer for connector client.
 * @package WPDesk\Library\DropshippingXmlCore\Connector\Client
 */
interface Connector
{
    public function get_content() : string;
    public function get_file(string $destination) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
}
