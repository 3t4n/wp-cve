<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Builder;

use WPPayVendor\Doctrine\Common\Annotations\Reader;
use WPPayVendor\Metadata\Driver\DriverInterface;
interface DriverFactoryInterface
{
    public function createDriver(array $metadataDirs, ?\WPPayVendor\Doctrine\Common\Annotations\Reader $annotationReader = null) : \WPPayVendor\Metadata\Driver\DriverInterface;
}
