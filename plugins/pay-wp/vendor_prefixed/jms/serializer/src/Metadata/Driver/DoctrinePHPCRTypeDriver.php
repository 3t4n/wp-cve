<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Metadata\Driver;

use WPPayVendor\Doctrine\ODM\PHPCR\Mapping\ClassMetadata as PHPCRClassMetadata;
use WPPayVendor\Doctrine\Persistence\Mapping\ClassMetadata as DoctrineClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
/**
 * This class decorates any other driver. If the inner driver does not provide a
 * a property type, the decorator will guess based on Doctrine 2 metadata.
 */
class DoctrinePHPCRTypeDriver extends \WPPayVendor\JMS\Serializer\Metadata\Driver\AbstractDoctrineTypeDriver
{
    /**
     * @param PHPCRClassMetadata $doctrineMetadata
     * @param PropertyMetadata $propertyMetadata
     */
    protected function hideProperty(\WPPayVendor\Doctrine\Persistence\Mapping\ClassMetadata $doctrineMetadata, \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $propertyMetadata) : bool
    {
        return 'lazyPropertiesDefaults' === $propertyMetadata->name || $doctrineMetadata->parentMapping === $propertyMetadata->name || $doctrineMetadata->node === $propertyMetadata->name;
    }
    /**
     * @param PHPCRClassMetadata $doctrineMetadata
     * @param PropertyMetadata $propertyMetadata
     */
    protected function setPropertyType(\WPPayVendor\Doctrine\Persistence\Mapping\ClassMetadata $doctrineMetadata, \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $propertyMetadata) : void
    {
        $propertyName = $propertyMetadata->name;
        if ($doctrineMetadata->hasField($propertyName) && ($typeOfFiled = $doctrineMetadata->getTypeOfField($propertyName)) && ($fieldType = $this->normalizeFieldType($typeOfFiled))) {
            $field = $doctrineMetadata->getFieldMapping($propertyName);
            if (!empty($field['multivalue'])) {
                $fieldType = 'array';
            }
            $propertyMetadata->setType($this->typeParser->parse($fieldType));
        } elseif ($doctrineMetadata->hasAssociation($propertyName)) {
            try {
                $targetEntity = $doctrineMetadata->getAssociationTargetClass($propertyName);
            } catch (\Throwable $e) {
                return;
            }
            if (null === $this->tryLoadingDoctrineMetadata($targetEntity)) {
                return;
            }
            if (!$doctrineMetadata->isSingleValuedAssociation($propertyName)) {
                $targetEntity = \sprintf('ArrayCollection<%s>', $targetEntity);
            }
            $propertyMetadata->setType($this->typeParser->parse($targetEntity));
        }
    }
}
