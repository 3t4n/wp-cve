<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Serializer;

use DropshippingXmlFreeVendor\WPDesk\Forms\Serializer;
class JsonSerializer implements \DropshippingXmlFreeVendor\WPDesk\Forms\Serializer
{
    public function serialize($value)
    {
        return \json_encode($value);
    }
    public function unserialize($value)
    {
        return \json_decode($value, \true);
    }
}
