<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Exclusion;

use WPPayVendor\JMS\Serializer\Context;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
/**
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
final class DepthExclusionStrategy implements \WPPayVendor\JMS\Serializer\Exclusion\ExclusionStrategyInterface
{
    public function shouldSkipClass(\WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata, \WPPayVendor\JMS\Serializer\Context $context) : bool
    {
        return $this->isTooDeep($context);
    }
    public function shouldSkipProperty(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $property, \WPPayVendor\JMS\Serializer\Context $context) : bool
    {
        return $this->isTooDeep($context);
    }
    private function isTooDeep(\WPPayVendor\JMS\Serializer\Context $context) : bool
    {
        $relativeDepth = 0;
        foreach ($context->getMetadataStack() as $metadata) {
            if (!$metadata instanceof \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata) {
                continue;
            }
            $relativeDepth++;
            if (0 === $metadata->maxDepth && $context->getMetadataStack()->top() === $metadata) {
                continue;
            }
            if (null !== $metadata->maxDepth && $relativeDepth > $metadata->maxDepth) {
                return \true;
            }
        }
        return \false;
    }
}
