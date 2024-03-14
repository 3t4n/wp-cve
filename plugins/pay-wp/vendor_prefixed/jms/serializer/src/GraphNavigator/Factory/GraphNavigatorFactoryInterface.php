<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\GraphNavigator\Factory;

use WPPayVendor\JMS\Serializer\GraphNavigatorInterface;
interface GraphNavigatorFactoryInterface
{
    public function getGraphNavigator() : \WPPayVendor\JMS\Serializer\GraphNavigatorInterface;
}
