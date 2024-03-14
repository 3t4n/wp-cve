<?php

declare (strict_types=1);
namespace WPPayVendor\Metadata;

interface MergeableInterface
{
    public function merge(\WPPayVendor\Metadata\MergeableInterface $object) : void;
}
