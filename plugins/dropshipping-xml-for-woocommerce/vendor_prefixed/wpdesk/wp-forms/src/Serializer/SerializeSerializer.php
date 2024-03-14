<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Serializer;

use DropshippingXmlFreeVendor\WPDesk\Forms\Serializer;
class SerializeSerializer implements \DropshippingXmlFreeVendor\WPDesk\Forms\Serializer
{
    public function serialize($value)
    {
        return \serialize($value);
    }
    public function unserialize($value)
    {
        return \unserialize($value);
    }
}
