<?php

namespace DhlVendor\WPDesk\Forms\Serializer;

use DhlVendor\WPDesk\Forms\Serializer;
class SerializeSerializer implements \DhlVendor\WPDesk\Forms\Serializer
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
