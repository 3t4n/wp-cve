<?php

declare (strict_types=1);
namespace WPPayVendor\Metadata;

/**
 * Represents the metadata for the entire class hierarchy.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ClassHierarchyMetadata
{
    /**
     * @var ClassMetadata[]
     */
    public $classMetadata = [];
    public function addClassMetadata(\WPPayVendor\Metadata\ClassMetadata $metadata) : void
    {
        $this->classMetadata[$metadata->name] = $metadata;
    }
    public function getRootClassMetadata() : ?\WPPayVendor\Metadata\ClassMetadata
    {
        return \reset($this->classMetadata);
    }
    public function getOutsideClassMetadata() : ?\WPPayVendor\Metadata\ClassMetadata
    {
        return \end($this->classMetadata);
    }
    public function isFresh(int $timestamp) : bool
    {
        foreach ($this->classMetadata as $metadata) {
            if (!$metadata->isFresh($timestamp)) {
                return \false;
            }
        }
        return \true;
    }
}
