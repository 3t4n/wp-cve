<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Naming;

use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
/**
 * Naming strategy which uses an annotation to translate the property name.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
final class SerializedNameAnnotationStrategy implements \WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface
{
    /**
     * @var PropertyNamingStrategyInterface
     */
    private $delegate;
    public function __construct(\WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface $namingStrategy)
    {
        $this->delegate = $namingStrategy;
    }
    public function translateName(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $property) : string
    {
        if (null !== ($name = $property->serializedName)) {
            return $name;
        }
        return $this->delegate->translateName($property);
    }
}
