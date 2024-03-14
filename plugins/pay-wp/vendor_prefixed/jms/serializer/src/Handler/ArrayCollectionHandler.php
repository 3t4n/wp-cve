<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Handler;

use WPPayVendor\Doctrine\Common\Collections\ArrayCollection;
use WPPayVendor\Doctrine\Common\Collections\Collection;
use WPPayVendor\Doctrine\ODM\MongoDB\PersistentCollection as MongoPersistentCollection;
use WPPayVendor\Doctrine\ODM\PHPCR\PersistentCollection as PhpcrPersistentCollection;
use WPPayVendor\Doctrine\ORM\PersistentCollection as OrmPersistentCollection;
use WPPayVendor\Doctrine\Persistence\ManagerRegistry;
use WPPayVendor\JMS\Serializer\DeserializationContext;
use WPPayVendor\JMS\Serializer\GraphNavigatorInterface;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
use WPPayVendor\JMS\Serializer\SerializationContext;
use WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface;
use WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface;
final class ArrayCollectionHandler implements \WPPayVendor\JMS\Serializer\Handler\SubscribingHandlerInterface
{
    public const COLLECTION_TYPES = ['ArrayCollection', \WPPayVendor\Doctrine\Common\Collections\ArrayCollection::class, \WPPayVendor\Doctrine\ORM\PersistentCollection::class, \WPPayVendor\Doctrine\ODM\MongoDB\PersistentCollection::class, \WPPayVendor\Doctrine\ODM\PHPCR\PersistentCollection::class];
    /**
     * @var bool
     */
    private $initializeExcluded;
    /**
     * @var ManagerRegistry|null
     */
    private $managerRegistry;
    public function __construct(bool $initializeExcluded = \true, ?\WPPayVendor\Doctrine\Persistence\ManagerRegistry $managerRegistry = null)
    {
        $this->initializeExcluded = $initializeExcluded;
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = [];
        $formats = ['json', 'xml'];
        foreach (self::COLLECTION_TYPES as $type) {
            foreach ($formats as $format) {
                $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'type' => $type, 'format' => $format, 'method' => 'serializeCollection'];
                $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_DESERIALIZATION, 'type' => $type, 'format' => $format, 'method' => 'deserializeCollection'];
            }
        }
        return $methods;
    }
    /**
     * @return array|\ArrayObject
     */
    public function serializeCollection(\WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface $visitor, \WPPayVendor\Doctrine\Common\Collections\Collection $collection, array $type, \WPPayVendor\JMS\Serializer\SerializationContext $context)
    {
        // We change the base type, and pass through possible parameters.
        $type['name'] = 'array';
        $context->stopVisiting($collection);
        if (\false === $this->initializeExcluded) {
            $exclusionStrategy = $context->getExclusionStrategy();
            if (null !== $exclusionStrategy && $exclusionStrategy->shouldSkipClass($context->getMetadataFactory()->getMetadataForClass(\get_class($collection)), $context)) {
                $context->startVisiting($collection);
                return $visitor->visitArray([], $type);
            }
        }
        $result = $visitor->visitArray($collection->toArray(), $type);
        $context->startVisiting($collection);
        return $result;
    }
    /**
     * @param mixed $data
     */
    public function deserializeCollection(\WPPayVendor\JMS\Serializer\Visitor\DeserializationVisitorInterface $visitor, $data, array $type, \WPPayVendor\JMS\Serializer\DeserializationContext $context) : \WPPayVendor\Doctrine\Common\Collections\Collection
    {
        // See above.
        $type['name'] = 'array';
        $elements = new \WPPayVendor\Doctrine\Common\Collections\ArrayCollection($visitor->visitArray($data, $type));
        if (null === $this->managerRegistry) {
            return $elements;
        }
        $propertyMetadata = $context->getMetadataStack()->top();
        if (!$propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata) {
            return $elements;
        }
        $objectManager = $this->managerRegistry->getManagerForClass($propertyMetadata->class);
        if (null === $objectManager) {
            return $elements;
        }
        $classMetadata = $objectManager->getClassMetadata($propertyMetadata->class);
        $currentObject = $visitor->getCurrentObject();
        if (\array_key_exists('name', $propertyMetadata->type) && \in_array($propertyMetadata->type['name'], self::COLLECTION_TYPES) && $classMetadata->isCollectionValuedAssociation($propertyMetadata->name)) {
            $existingCollection = $classMetadata->getFieldValue($currentObject, $propertyMetadata->name);
            if (!$existingCollection instanceof \WPPayVendor\Doctrine\ORM\PersistentCollection) {
                return $elements;
            }
            foreach ($elements as $element) {
                if (!$existingCollection->contains($element)) {
                    $existingCollection->add($element);
                }
            }
            foreach ($existingCollection as $collectionElement) {
                if (!$elements->contains($collectionElement)) {
                    $existingCollection->removeElement($collectionElement);
                }
            }
            return $existingCollection;
        }
        return $elements;
    }
}
