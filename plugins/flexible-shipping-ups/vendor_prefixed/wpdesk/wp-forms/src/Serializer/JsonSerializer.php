<?php

namespace UpsFreeVendor\WPDesk\Forms\Serializer;

use UpsFreeVendor\WPDesk\Forms\Serializer;
class JsonSerializer implements \UpsFreeVendor\WPDesk\Forms\Serializer
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
