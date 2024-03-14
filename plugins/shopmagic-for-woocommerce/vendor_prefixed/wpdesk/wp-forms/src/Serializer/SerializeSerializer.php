<?php

namespace ShopMagicVendor\WPDesk\Forms\Serializer;

use ShopMagicVendor\WPDesk\Forms\Serializer;
class SerializeSerializer implements Serializer
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
