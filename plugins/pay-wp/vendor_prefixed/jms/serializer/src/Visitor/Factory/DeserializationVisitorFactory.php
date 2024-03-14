<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Visitor\Factory;

use WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface;
/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
interface DeserializationVisitorFactory
{
    public function getVisitor() : \WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface;
}
