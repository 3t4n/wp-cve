<?php

declare (strict_types=1);
namespace WPPayVendor\Metadata;

class MergeableClassMetadata extends \WPPayVendor\Metadata\ClassMetadata implements \WPPayVendor\Metadata\MergeableInterface
{
    public function merge(\WPPayVendor\Metadata\MergeableInterface $object) : void
    {
        if (!$object instanceof \WPPayVendor\Metadata\MergeableClassMetadata) {
            throw new \InvalidArgumentException('$object must be an instance of MergeableClassMetadata.');
        }
        $this->name = $object->name;
        $this->methodMetadata = \array_merge($this->methodMetadata, $object->methodMetadata);
        $this->propertyMetadata = \array_merge($this->propertyMetadata, $object->propertyMetadata);
        $this->fileResources = \array_merge($this->fileResources, $object->fileResources);
        if ($object->createdAt < $this->createdAt) {
            $this->createdAt = $object->createdAt;
        }
    }
}
