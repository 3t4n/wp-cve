<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CustomsDeclarationItems StructType
 * @subpackage Structs
 */
class CustomsDeclarationItems extends AbstractStructBase
{
    /**
     * The item
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 1
     * @var \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem[]
     */
    protected array $item;
    /**
     * Constructor method for CustomsDeclarationItems
     * @uses CustomsDeclarationItems::setItem()
     * @param \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem[] $item
     */
    public function __construct(array $item)
    {
        $this
            ->setItem($item);
    }
    /**
     * Get item value
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem[]
     */
    public function getItem(): array
    {
        return $this->item;
    }
    /**
     * This method is responsible for validating the values passed to the setItem method
     * This method is willingly generated in order to preserve the one-line inline validation within the setItem method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateItemForArrayConstraintsFromSetItem(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $customsDeclarationItemsItemItem) {
            // validation for constraint: itemType
            if (!$customsDeclarationItemsItemItem instanceof \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem) {
                $invalidValues[] = is_object($customsDeclarationItemsItemItem) ? get_class($customsDeclarationItemsItemItem) : sprintf('%s(%s)', gettype($customsDeclarationItemsItemItem), var_export($customsDeclarationItemsItemItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The item property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set item value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem[] $item
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItems
     */
    public function setItem(array $item): self
    {
        // validation for constraint: array
        if ('' !== ($itemArrayErrorMessage = self::validateItemForArrayConstraintsFromSetItem($item))) {
            throw new InvalidArgumentException($itemArrayErrorMessage, __LINE__);
        }
        $this->item = $item;
        
        return $this;
    }
    /**
     * Add item to item value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem $item
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItems
     */
    public function addToItem(\WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem $item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem) {
            throw new InvalidArgumentException(sprintf('The item property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->item[] = $item;
        
        return $this;
    }
}
