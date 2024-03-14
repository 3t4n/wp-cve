<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketsAttributes StructType
 * @subpackage Structs
 */
class PacketsAttributes extends AbstractStructBase
{
    /**
     * The attributes
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 1
     * @var \WpifyWoo\PacketeraSDK\StructType\PacketAttributes[]
     */
    protected array $attributes;
    /**
     * Constructor method for PacketsAttributes
     * @uses PacketsAttributes::setAttributes()
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketAttributes[] $attributes
     */
    public function __construct(array $attributes)
    {
        $this
            ->setAttributes($attributes);
    }
    /**
     * Get attributes value
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
    /**
     * This method is responsible for validating the values passed to the setAttributes method
     * This method is willingly generated in order to preserve the one-line inline validation within the setAttributes method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateAttributesForArrayConstraintsFromSetAttributes(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $packetsAttributesAttributesItem) {
            // validation for constraint: itemType
            if (!$packetsAttributesAttributesItem instanceof \WpifyWoo\PacketeraSDK\StructType\PacketAttributes) {
                $invalidValues[] = is_object($packetsAttributesAttributesItem) ? get_class($packetsAttributesAttributesItem) : sprintf('%s(%s)', gettype($packetsAttributesAttributesItem), var_export($packetsAttributesAttributesItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The attributes property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\PacketAttributes, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set attributes value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketAttributes[] $attributes
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketsAttributes
     */
    public function setAttributes(array $attributes): self
    {
        // validation for constraint: array
        if ('' !== ($attributesArrayErrorMessage = self::validateAttributesForArrayConstraintsFromSetAttributes($attributes))) {
            throw new InvalidArgumentException($attributesArrayErrorMessage, __LINE__);
        }
        $this->attributes = $attributes;
        
        return $this;
    }
    /**
     * Add item to attributes value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketAttributes $item
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketsAttributes
     */
    public function addToAttributes(\WpifyWoo\PacketeraSDK\StructType\PacketAttributes $item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \WpifyWoo\PacketeraSDK\StructType\PacketAttributes) {
            throw new InvalidArgumentException(sprintf('The attributes property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\PacketAttributes, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->attributes[] = $item;
        
        return $this;
    }
}
