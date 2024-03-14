<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer;

use OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer;
class JsonSerializer implements \OctolizeShippingNoticesVendor\WPDesk\Forms\Serializer
{
    public function serialize($value) : string
    {
        return (string) \json_encode($value);
    }
    public function unserialize(string $value)
    {
        return \json_decode($value, \true);
    }
}
