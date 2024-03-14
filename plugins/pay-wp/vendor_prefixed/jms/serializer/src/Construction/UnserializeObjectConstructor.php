<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Construction;

use WPPayVendor\Doctrine\Instantiator\Instantiator;
use WPPayVendor\JMS\Serializer\DeserializationContext;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface;
final class UnserializeObjectConstructor implements \WPPayVendor\JMS\Serializer\Construction\ObjectConstructorInterface
{
    /** @var Instantiator */
    private $instantiator;
    /**
     * {@inheritdoc}
     */
    public function construct(\WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface $visitor, \WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata, $data, array $type, \WPPayVendor\JMS\Serializer\DeserializationContext $context) : ?object
    {
        return $this->getInstantiator()->instantiate($metadata->name);
    }
    private function getInstantiator() : \WPPayVendor\Doctrine\Instantiator\Instantiator
    {
        if (null === $this->instantiator) {
            $this->instantiator = new \WPPayVendor\Doctrine\Instantiator\Instantiator();
        }
        return $this->instantiator;
    }
}
