<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Metadata\Driver;

use WPPayVendor\Doctrine\ORM\Mapping\ClassMetadataInfo;
use WPPayVendor\Doctrine\Persistence\Mapping\ClassMetadata as DoctrineClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
/**
 * This class decorates any other driver. If the inner driver does not provide a
 * a property type, the decorator will guess based on Doctrine 2 metadata.
 */
class DoctrineTypeDriver extends \WPPayVendor\JMS\Serializer\Metadata\Driver\AbstractDoctrineTypeDriver
{
    /**
     * @param ClassMetadataInfo $doctrineMetadata
     * @param ClassMetadata $classMetadata
     */
    protected function setDiscriminator(\WPPayVendor\Doctrine\Persistence\Mapping\ClassMetadata $doctrineMetadata, \WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $classMetadata) : void
    {
        if (empty($classMetadata->discriminatorMap) && !$classMetadata->discriminatorDisabled && !empty($doctrineMetadata->discriminatorMap) && $doctrineMetadata->isRootEntity()) {
            $classMetadata->setDiscriminator($doctrineMetadata->discriminatorColumn['name'], $doctrineMetadata->discriminatorMap);
        }
    }
    protected function setPropertyType(\WPPayVendor\Doctrine\Persistence\Mapping\ClassMetadata $doctrineMetadata, \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $propertyMetadata) : void
    {
        $propertyName = $propertyMetadata->name;
        if ($doctrineMetadata->hasField($propertyName) && ($typeOfFiled = $doctrineMetadata->getTypeOfField($propertyName)) && ($fieldType = $this->normalizeFieldType($typeOfFiled))) {
            $propertyMetadata->setType($this->typeParser->parse($fieldType));
        } elseif ($doctrineMetadata->hasAssociation($propertyName)) {
            $targetEntity = $doctrineMetadata->getAssociationTargetClass($propertyName);
            if (null === ($targetMetadata = $this->tryLoadingDoctrineMetadata($targetEntity))) {
                return;
            }
            // For inheritance schemes, we cannot add any type as we would only add the super-type of the hierarchy.
            // On serialization, this would lead to only the supertype being serialized, and properties of subtypes
            // being ignored.
            if ($targetMetadata instanceof \WPPayVendor\Doctrine\ORM\Mapping\ClassMetadataInfo && !$targetMetadata->isInheritanceTypeNone()) {
                return;
            }
            if (!$doctrineMetadata->isSingleValuedAssociation($propertyName)) {
                $targetEntity = \sprintf('ArrayCollection<%s>', $targetEntity);
            }
            $propertyMetadata->setType($this->typeParser->parse($targetEntity));
        }
    }
}
