<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\EventDispatcher\Subscriber;

use WPPayVendor\Doctrine\Common\Persistence\Proxy as LegacyProxy;
use WPPayVendor\Doctrine\ODM\MongoDB\PersistentCollection as MongoDBPersistentCollection;
use WPPayVendor\Doctrine\ODM\PHPCR\PersistentCollection as PHPCRPersistentCollection;
use WPPayVendor\Doctrine\ORM\PersistentCollection;
use WPPayVendor\Doctrine\Persistence\Proxy;
use WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use WPPayVendor\JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use WPPayVendor\JMS\Serializer\EventDispatcher\PreSerializeEvent;
use WPPayVendor\ProxyManager\Proxy\LazyLoadingInterface;
final class DoctrineProxySubscriber implements \WPPayVendor\JMS\Serializer\EventDispatcher\EventSubscriberInterface
{
    /**
     * @var bool
     */
    private $skipVirtualTypeInit;
    /**
     * @var bool
     */
    private $initializeExcluded;
    public function __construct(bool $skipVirtualTypeInit = \true, bool $initializeExcluded = \false)
    {
        $this->skipVirtualTypeInit = $skipVirtualTypeInit;
        $this->initializeExcluded = $initializeExcluded;
    }
    public function onPreSerialize(\WPPayVendor\JMS\Serializer\EventDispatcher\PreSerializeEvent $event) : void
    {
        $object = $event->getObject();
        $type = $event->getType();
        // If the set type name is not an actual class, but a faked type for which a custom handler exists, we do not
        // modify it with this subscriber. Also, we forgo autoloading here as an instance of this type is already created,
        // so it must be loaded if its a real class.
        $virtualType = !\class_exists($type['name'], \false);
        if ($object instanceof \WPPayVendor\Doctrine\ORM\PersistentCollection || $object instanceof \WPPayVendor\Doctrine\ODM\MongoDB\PersistentCollection || $object instanceof \WPPayVendor\Doctrine\ODM\PHPCR\PersistentCollection) {
            if (!$virtualType) {
                $event->setType('ArrayCollection');
            }
            return;
        }
        if ($this->skipVirtualTypeInit && $virtualType || !$object instanceof \WPPayVendor\Doctrine\Persistence\Proxy && !$object instanceof \WPPayVendor\ProxyManager\Proxy\LazyLoadingInterface) {
            return;
        }
        // do not initialize the proxy if is going to be excluded by-class by some exclusion strategy
        if (\false === $this->initializeExcluded && !$virtualType) {
            $context = $event->getContext();
            $exclusionStrategy = $context->getExclusionStrategy();
            $metadata = $context->getMetadataFactory()->getMetadataForClass(\get_parent_class($object));
            if (null !== $metadata && null !== $exclusionStrategy && $exclusionStrategy->shouldSkipClass($metadata, $context)) {
                return;
            }
        }
        if ($object instanceof \WPPayVendor\ProxyManager\Proxy\LazyLoadingInterface) {
            $object->initializeProxy();
        } else {
            $object->__load();
        }
        if (!$virtualType) {
            $event->setType(\get_parent_class($object), $type['params']);
        }
    }
    public function onPreSerializeTypedProxy(\WPPayVendor\JMS\Serializer\EventDispatcher\PreSerializeEvent $event, string $eventName, string $class, string $format, \WPPayVendor\JMS\Serializer\EventDispatcher\EventDispatcherInterface $dispatcher) : void
    {
        $type = $event->getType();
        // is a virtual type? then there is no need to change the event name
        if (!\class_exists($type['name'], \false)) {
            return;
        }
        $object = $event->getObject();
        if ($object instanceof \WPPayVendor\Doctrine\Persistence\Proxy) {
            $parentClassName = \get_parent_class($object);
            // check if this is already a re-dispatch
            if (\strtolower($class) !== \strtolower($parentClassName)) {
                $event->stopPropagation();
                $newEvent = new \WPPayVendor\JMS\Serializer\EventDispatcher\PreSerializeEvent($event->getContext(), $object, ['name' => $parentClassName, 'params' => $type['params']]);
                $dispatcher->dispatch($eventName, $parentClassName, $format, $newEvent);
                // update the type in case some listener changed it
                $newType = $newEvent->getType();
                $event->setType($newType['name'], $newType['params']);
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [['event' => 'serializer.pre_serialize', 'method' => 'onPreSerializeTypedProxy', 'interface' => \WPPayVendor\Doctrine\Persistence\Proxy::class], ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerializeTypedProxy', 'interface' => \WPPayVendor\Doctrine\Common\Persistence\Proxy::class], ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize', 'interface' => \WPPayVendor\Doctrine\ORM\PersistentCollection::class], ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize', 'interface' => \WPPayVendor\Doctrine\ODM\MongoDB\PersistentCollection::class], ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize', 'interface' => \WPPayVendor\Doctrine\ODM\PHPCR\PersistentCollection::class], ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize', 'interface' => \WPPayVendor\Doctrine\Persistence\Proxy::class], ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize', 'interface' => \WPPayVendor\Doctrine\Common\Persistence\Proxy::class], ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize', 'interface' => \WPPayVendor\ProxyManager\Proxy\LazyLoadingInterface::class]];
    }
}
