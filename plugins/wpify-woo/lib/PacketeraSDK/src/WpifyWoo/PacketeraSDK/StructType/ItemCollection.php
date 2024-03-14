<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ItemCollection StructType
 * @subpackage Structs
 */
class ItemCollection extends AbstractStructBase
{
    /**
     * The item
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\Item[]
     */
    protected ?array $item = null;
    /**
     * Constructor method for ItemCollection
     * @uses ItemCollection::setItem()
     * @param \WpifyWoo\PacketeraSDK\StructType\Item[] $item
     */
    public function __construct(?array $item = null)
    {
        $this
            ->setItem($item);
    }
    /**
     * Get item value
     * @return \WpifyWoo\PacketeraSDK\StructType\Item[]
     */
    public function getItem(): ?array
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
        foreach ($values as $itemCollectionItemItem) {
            // validation for constraint: itemType
            if (!$itemCollectionItemItem instanceof \WpifyWoo\PacketeraSDK\StructType\Item) {
                $invalidValues[] = is_object($itemCollectionItemItem) ? get_class($itemCollectionItemItem) : sprintf('%s(%s)', gettype($itemCollectionItemItem), var_export($itemCollectionItemItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The item property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\Item, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set item value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\Item[] $item
     * @return \WpifyWoo\PacketeraSDK\StructType\ItemCollection
     */
    public function setItem(?array $item = null): self
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
     * @param \WpifyWoo\PacketeraSDK\StructType\Item $item
     * @return \WpifyWoo\PacketeraSDK\StructType\ItemCollection
     */
    public function addToItem(\WpifyWoo\PacketeraSDK\StructType\Item $item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \WpifyWoo\PacketeraSDK\StructType\Item) {
            throw new InvalidArgumentException(sprintf('The item property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\Item, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->item[] = $item;
        
        return $this;
    }
}
