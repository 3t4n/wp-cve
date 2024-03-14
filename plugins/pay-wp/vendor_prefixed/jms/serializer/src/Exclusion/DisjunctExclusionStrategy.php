<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Exclusion;

use WPPayVendor\JMS\Serializer\Context;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
/**
 * Disjunct Exclusion Strategy.
 *
 * This strategy is short-circuiting and will skip a class, or property as soon as one of the delegates skips it.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
final class DisjunctExclusionStrategy implements \WPPayVendor\JMS\Serializer\Exclusion\ExclusionStrategyInterface
{
    /**
     * @var ExclusionStrategyInterface[]
     */
    private $delegates;
    /**
     * @param ExclusionStrategyInterface[] $delegates
     */
    public function __construct(array $delegates = [])
    {
        $this->delegates = $delegates;
    }
    public function addStrategy(\WPPayVendor\JMS\Serializer\Exclusion\ExclusionStrategyInterface $strategy) : void
    {
        $this->delegates[] = $strategy;
    }
    /**
     * Whether the class should be skipped.
     */
    public function shouldSkipClass(\WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata, \WPPayVendor\JMS\Serializer\Context $context) : bool
    {
        foreach ($this->delegates as $delegate) {
            \assert($delegate instanceof \WPPayVendor\JMS\Serializer\Exclusion\ExclusionStrategyInterface);
            if ($delegate->shouldSkipClass($metadata, $context)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * Whether the property should be skipped.
     */
    public function shouldSkipProperty(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $property, \WPPayVendor\JMS\Serializer\Context $context) : bool
    {
        foreach ($this->delegates as $delegate) {
            \assert($delegate instanceof \WPPayVendor\JMS\Serializer\Exclusion\ExclusionStrategyInterface);
            if ($delegate->shouldSkipProperty($property, $context)) {
                return \true;
            }
        }
        return \false;
    }
}
