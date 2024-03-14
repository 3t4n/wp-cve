<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Type;

interface ParserInterface
{
    public function parse(string $type) : array;
}
