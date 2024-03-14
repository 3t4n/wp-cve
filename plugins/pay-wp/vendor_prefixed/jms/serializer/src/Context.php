<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer;

use WPPayVendor\JMS\Serializer\Exception\LogicException;
use WPPayVendor\JMS\Serializer\Exception\RuntimeException;
use WPPayVendor\JMS\Serializer\Exclusion\DepthExclusionStrategy;
use WPPayVendor\JMS\Serializer\Exclusion\DisjunctExclusionStrategy;
use WPPayVendor\JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use WPPayVendor\JMS\Serializer\Exclusion\GroupsExclusionStrategy;
use WPPayVendor\JMS\Serializer\Exclusion\VersionExclusionStrategy;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
use WPPayVendor\Metadata\MetadataFactoryInterface;
abstract class Context
{
    /**
     * @var array
     */
    private $attributes = [];
    /**
     * @var string
     */
    private $format;
    /**
     * @var VisitorInterface
     */
    private $visitor;
    /**
     * @var GraphNavigatorInterface
     */
    private $navigator;
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;
    /** @var ExclusionStrategyInterface */
    private $exclusionStrategy;
    /**
     * @var bool
     */
    private $initialized = \false;
    /** @var \SplStack */
    private $metadataStack;
    public function __construct()
    {
        $this->metadataStack = new \SplStack();
    }
    public function initialize(string $format, \WPPayVendor\JMS\Serializer\VisitorInterface $visitor, \WPPayVendor\JMS\Serializer\GraphNavigatorInterface $navigator, \WPPayVendor\Metadata\MetadataFactoryInterface $factory) : void
    {
        if ($this->initialized) {
            throw new \WPPayVendor\JMS\Serializer\Exception\LogicException('This context was already initialized, and cannot be re-used.');
        }
        $this->format = $format;
        $this->visitor = $visitor;
        $this->navigator = $navigator;
        $this->metadataFactory = $factory;
        $this->metadataStack = new \SplStack();
        if (isset($this->attributes['groups'])) {
            $this->addExclusionStrategy(new \WPPayVendor\JMS\Serializer\Exclusion\GroupsExclusionStrategy($this->attributes['groups']));
        }
        if (isset($this->attributes['version'])) {
            $this->addExclusionStrategy(new \WPPayVendor\JMS\Serializer\Exclusion\VersionExclusionStrategy($this->attributes['version']));
        }
        if (!empty($this->attributes['max_depth_checks'])) {
            $this->addExclusionStrategy(new \WPPayVendor\JMS\Serializer\Exclusion\DepthExclusionStrategy());
        }
        $this->initialized = \true;
    }
    public function getMetadataFactory() : \WPPayVendor\Metadata\MetadataFactoryInterface
    {
        return $this->metadataFactory;
    }
    public function getVisitor() : \WPPayVendor\JMS\Serializer\VisitorInterface
    {
        return $this->visitor;
    }
    public function getNavigator() : \WPPayVendor\JMS\Serializer\GraphNavigatorInterface
    {
        return $this->navigator;
    }
    public function getExclusionStrategy() : ?\WPPayVendor\JMS\Serializer\Exclusion\ExclusionStrategyInterface
    {
        return $this->exclusionStrategy;
    }
    /**
     * @return mixed
     */
    public function getAttribute(string $key)
    {
        return $this->attributes[$key];
    }
    public function hasAttribute(string $key) : bool
    {
        return isset($this->attributes[$key]);
    }
    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setAttribute(string $key, $value) : self
    {
        $this->assertMutable();
        $this->attributes[$key] = $value;
        return $this;
    }
    protected final function assertMutable() : void
    {
        if (!$this->initialized) {
            return;
        }
        throw new \WPPayVendor\JMS\Serializer\Exception\LogicException('This context was already initialized and is immutable; you cannot modify it anymore.');
    }
    /**
     * @return $this
     */
    public function addExclusionStrategy(\WPPayVendor\JMS\Serializer\Exclusion\ExclusionStrategyInterface $strategy) : self
    {
        $this->assertMutable();
        if (null === $this->exclusionStrategy) {
            $this->exclusionStrategy = $strategy;
            return $this;
        }
        if ($this->exclusionStrategy instanceof \WPPayVendor\JMS\Serializer\Exclusion\DisjunctExclusionStrategy) {
            $this->exclusionStrategy->addStrategy($strategy);
            return $this;
        }
        $this->exclusionStrategy = new \WPPayVendor\JMS\Serializer\Exclusion\DisjunctExclusionStrategy([$this->exclusionStrategy, $strategy]);
        return $this;
    }
    /**
     * @return $this
     */
    public function setVersion(string $version) : self
    {
        $this->attributes['version'] = $version;
        return $this;
    }
    /**
     * @param array|string $groups
     *
     * @return $this
     */
    public function setGroups($groups) : self
    {
        if (empty($groups)) {
            throw new \WPPayVendor\JMS\Serializer\Exception\LogicException('The groups must not be empty.');
        }
        $this->attributes['groups'] = (array) $groups;
        return $this;
    }
    /**
     * @return $this
     */
    public function enableMaxDepthChecks() : self
    {
        $this->attributes['max_depth_checks'] = \true;
        return $this;
    }
    public function getFormat() : string
    {
        return $this->format;
    }
    public function pushClassMetadata(\WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata) : void
    {
        $this->metadataStack->push($metadata);
    }
    public function pushPropertyMetadata(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $metadata) : void
    {
        $this->metadataStack->push($metadata);
    }
    public function popPropertyMetadata() : void
    {
        $metadata = $this->metadataStack->pop();
        if (!$metadata instanceof \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata) {
            throw new \WPPayVendor\JMS\Serializer\Exception\RuntimeException('Context metadataStack not working well');
        }
    }
    public function popClassMetadata() : void
    {
        $metadata = $this->metadataStack->pop();
        if (!$metadata instanceof \WPPayVendor\JMS\Serializer\Metadata\ClassMetadata) {
            throw new \WPPayVendor\JMS\Serializer\Exception\RuntimeException('Context metadataStack not working well');
        }
    }
    public function getMetadataStack() : \SplStack
    {
        return $this->metadataStack;
    }
    /**
     * @return array
     */
    public function getCurrentPath() : array
    {
        if (!$this->metadataStack) {
            return [];
        }
        $paths = [];
        foreach ($this->metadataStack as $metadata) {
            if ($metadata instanceof \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata) {
                \array_unshift($paths, $metadata->name);
            }
        }
        return $paths;
    }
    public abstract function getDepth() : int;
    public abstract function getDirection() : int;
    public function close() : void
    {
        unset($this->visitor, $this->navigator);
    }
}
