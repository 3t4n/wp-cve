<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector;

class NoopWordInflector implements \WPPayVendor\Doctrine\Inflector\WordInflector
{
    public function inflect(string $word) : string
    {
        return $word;
    }
}
