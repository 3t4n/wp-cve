<?php

declare (strict_types=1);
namespace ShopMagicVendor\WPDesk\Migrations\Version;

class AlphabeticalComparator implements Comparator
{
    public function compare(Version $a, Version $b) : int
    {
        return \strcmp((string) $a, (string) $b);
    }
}
