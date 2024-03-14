<?php

declare (strict_types=1);
namespace WPPayVendor\Metadata\Driver;

use WPPayVendor\Metadata\ClassMetadata;
interface DriverInterface
{
    public function loadMetadataForClass(\ReflectionClass $class) : ?\WPPayVendor\Metadata\ClassMetadata;
}
