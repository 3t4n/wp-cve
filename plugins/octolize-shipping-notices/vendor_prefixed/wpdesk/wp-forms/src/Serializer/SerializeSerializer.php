<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer;
class SerializeSerializer implements \OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer
{
    public function serialize($value) : string
    {
        return \serialize($value);
    }
    public function unserialize(string $value)
    {
        return \unserialize($value);
    }
}
