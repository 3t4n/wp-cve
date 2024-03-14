<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector;

interface WordInflector
{
    public function inflect(string $word) : string;
}
