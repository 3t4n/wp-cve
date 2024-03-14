<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Handler;

use WPPayVendor\JMS\Serializer\GraphNavigatorInterface;
use WPPayVendor\JMS\Serializer\Metadata\StaticPropertyMetadata;
use WPPayVendor\JMS\Serializer\SerializationContext;
use WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface;
/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
final class StdClassHandler implements \WPPayVendor\JMS\Serializer\Handler\SubscribingHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = [];
        $formats = ['json', 'xml'];
        foreach ($formats as $format) {
            $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'type' => \stdClass::class, 'format' => $format, 'method' => 'serializeStdClass'];
        }
        return $methods;
    }
    /**
     * @return mixed
     */
    public function serializeStdClass(\WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface $visitor, \stdClass $stdClass, array $type, \WPPayVendor\JMS\Serializer\SerializationContext $context)
    {
        $classMetadata = $context->getMetadataFactory()->getMetadataForClass('stdClass');
        $visitor->startVisitingObject($classMetadata, $stdClass, ['name' => 'stdClass']);
        foreach ((array) $stdClass as $name => $value) {
            $metadata = new \WPPayVendor\JMS\Serializer\Metadata\StaticPropertyMetadata('stdClass', $name, $value);
            $visitor->visitProperty($metadata, $value);
        }
        return $visitor->endVisitingObject($classMetadata, $stdClass, ['name' => 'stdClass']);
    }
}
