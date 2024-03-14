<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for AttributeCollection StructType
 * @subpackage Structs
 */
class AttributeCollection extends AbstractStructBase
{
    /**
     * The attribute
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\Attribute[]
     */
    protected ?array $attribute = null;
    /**
     * Constructor method for AttributeCollection
     * @uses AttributeCollection::setAttribute()
     * @param \WpifyWoo\PacketeraSDK\StructType\Attribute[] $attribute
     */
    public function __construct(?array $attribute = null)
    {
        $this
            ->setAttribute($attribute);
    }
    /**
     * Get attribute value
     * @return \WpifyWoo\PacketeraSDK\StructType\Attribute[]
     */
    public function getAttribute(): ?array
    {
        return $this->attribute;
    }
    /**
     * This method is responsible for validating the values passed to the setAttribute method
     * This method is willingly generated in order to preserve the one-line inline validation within the setAttribute method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateAttributeForArrayConstraintsFromSetAttribute(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $attributeCollectionAttributeItem) {
            // validation for constraint: itemType
            if (!$attributeCollectionAttributeItem instanceof \WpifyWoo\PacketeraSDK\StructType\Attribute) {
                $invalidValues[] = is_object($attributeCollectionAttributeItem) ? get_class($attributeCollectionAttributeItem) : sprintf('%s(%s)', gettype($attributeCollectionAttributeItem), var_export($attributeCollectionAttributeItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The attribute property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\Attribute, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set attribute value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\Attribute[] $attribute
     * @return \WpifyWoo\PacketeraSDK\StructType\AttributeCollection
     */
    public function setAttribute(?array $attribute = null): self
    {
        // validation for constraint: array
        if ('' !== ($attributeArrayErrorMessage = self::validateAttributeForArrayConstraintsFromSetAttribute($attribute))) {
            throw new InvalidArgumentException($attributeArrayErrorMessage, __LINE__);
        }
        $this->attribute = $attribute;
        
        return $this;
    }
    /**
     * Add item to attribute value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\Attribute $item
     * @return \WpifyWoo\PacketeraSDK\StructType\AttributeCollection
     */
    public function addToAttribute(\WpifyWoo\PacketeraSDK\StructType\Attribute $item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \WpifyWoo\PacketeraSDK\StructType\Attribute) {
            throw new InvalidArgumentException(sprintf('The attribute property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\Attribute, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->attribute[] = $item;
        
        return $this;
    }
}
