<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Naming;

use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
/**
 * Generic naming strategy which translates a camel-cased property name.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
final class CamelCaseNamingStrategy implements \WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface
{
    /**
     * @var string
     */
    private $separator;
    /**
     * @var bool
     */
    private $lowerCase;
    public function __construct(string $separator = '_', bool $lowerCase = \true)
    {
        $this->separator = $separator;
        $this->lowerCase = $lowerCase;
    }
    public function translateName(\WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata $property) : string
    {
        $name = \preg_replace('/[A-Z]+/', $this->separator . '\\0', $property->name);
        if ($this->lowerCase) {
            return \strtolower($name);
        }
        return \ucfirst($name);
    }
}
