<?php

namespace DropshippingXmlFreeVendor\WPDesk\Forms\Serializer;

use DropshippingXmlFreeVendor\WPDesk\Forms\Serializer;
class NoSerialize implements \DropshippingXmlFreeVendor\WPDesk\Forms\Serializer
{
    public function serialize($value)
    {
        return $value;
    }
    public function unserialize($value)
    {
        return $value;
    }
}
