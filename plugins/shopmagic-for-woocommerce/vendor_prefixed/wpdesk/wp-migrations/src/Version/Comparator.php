<?php

declare (strict_types=1);
namespace ShopMagicVendor\WPDesk\Migrations\Version;

interface Comparator
{
    public function compare(Version $a, Version $b) : int;
}
