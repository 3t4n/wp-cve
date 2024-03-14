<?php

namespace UpsFreeVendor\WPDesk\Forms\Serializer;

use UpsFreeVendor\WPDesk\Forms\Serializer;
class SerializeSerializer implements \UpsFreeVendor\WPDesk\Forms\Serializer
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
