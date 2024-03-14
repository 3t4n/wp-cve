<?php

namespace DhlVendor\WPDesk\Forms\Serializer;

use DhlVendor\WPDesk\Forms\Serializer;
class JsonSerializer implements \DhlVendor\WPDesk\Forms\Serializer
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
