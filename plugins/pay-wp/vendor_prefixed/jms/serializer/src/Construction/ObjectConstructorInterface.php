<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Construction;

use WPPayVendor\JMS\Serializer\DeserializationContext;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface;
/**
 * Implementations of this interface construct new objects during deserialization.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface ObjectConstructorInterface
{
    /**
     * Constructs a new object.
     *
     * Implementations could for example create a new object calling "new", use
     * "unserialize" techniques, reflection, or other means.
     *
     * @param mixed $data
     * @param array $type ["name" => string, "params" => array]
     */
    public function construct(\WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface $visitor, \WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata, $data, array $type, \WPPayVendor\JMS\Serializer\DeserializationContext $context) : ?object;
}
