<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Exclusion;

use WPPayVendor\JMS\Serializer\Context;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
final class VersionExclusionStrategy implements \WPPayVendor\JMS\Serializer\Exclusion\ExclusionStrategyInterface
{
    /**
     * @var string
     */
    private $version;
    public function __construct(string $version)
    {
        $this->version = $version;
    }
    public function shouldSkipClass(\WPPayVendor\JMS\Serializer\Metadata\ClassMetadata $metadata, \WPPayVendor\JMS\Serializer\Context $navigatorContext) : bool
    {
        return \false;
    }
    public function shouldSkipProperty(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $property, \WPPayVendor\JMS\Serializer\Context $navigatorContext) : bool
    {
        if (null !== ($version = $property->sinceVersion) && \version_compare($this->version, $version, '<')) {
            return \true;
        }
        return null !== ($version = $property->untilVersion) && \version_compare($this->version, $version, '>');
    }
}
