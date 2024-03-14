<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\EventDispatcher\Subscriber;

use WPPayVendor\JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use WPPayVendor\JMS\Serializer\EventDispatcher\PreSerializeEvent;
final class EnumSubscriber implements \WPPayVendor\JMS\Serializer\EventDispatcher\EventSubscriberInterface
{
    public function onPreSerializeEnum(\WPPayVendor\JMS\Serializer\EventDispatcher\PreSerializeEvent $event) : void
    {
        $type = $event->getType();
        if (isset($type['name']) && ('enum' === $type['name'] || !\is_a($type['name'], \UnitEnum::class, \true))) {
            return;
        }
        $object = $event->getObject();
        $params = [\get_class($object), $object instanceof \BackedEnum ? 'value' : 'name'];
        $event->setType('enum', $params);
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [['event' => 'serializer.pre_serialize', 'method' => 'onPreSerializeEnum', 'interface' => \UnitEnum::class]];
    }
}
