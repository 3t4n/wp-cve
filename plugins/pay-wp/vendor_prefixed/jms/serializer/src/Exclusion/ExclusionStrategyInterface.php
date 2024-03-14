<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Exclusion;

use WPPayVendor\JMS\Serializer\Context;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
/**
 * Interface for exclusion strategies.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface ExclusionStrategyInterface
{
    /**
     * Whether the class should be skipped.
     */
    public function shouldSkipClass(\WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata, \WPPayVendor\JMS\Serializer\Context $context) : bool;
    /**
     * Whether the property should be skipped.
     */
    public function shouldSkipProperty(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $property, \WPPayVendor\JMS\Serializer\Context $context) : bool;
}
