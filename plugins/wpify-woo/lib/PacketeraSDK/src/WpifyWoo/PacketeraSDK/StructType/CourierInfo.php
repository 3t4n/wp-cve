<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CourierInfo StructType
 * @subpackage Structs
 */
class CourierInfo extends AbstractStructBase
{
    /**
     * The courierInfoItem
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem[]
     */
    protected ?array $courierInfoItem = null;
    /**
     * Constructor method for CourierInfo
     * @uses CourierInfo::setCourierInfoItem()
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem[] $courierInfoItem
     */
    public function __construct(?array $courierInfoItem = null)
    {
        $this
            ->setCourierInfoItem($courierInfoItem);
    }
    /**
     * Get courierInfoItem value
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem[]
     */
    public function getCourierInfoItem(): ?array
    {
        return $this->courierInfoItem;
    }
    /**
     * This method is responsible for validating the values passed to the setCourierInfoItem method
     * This method is willingly generated in order to preserve the one-line inline validation within the setCourierInfoItem method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateCourierInfoItemForArrayConstraintsFromSetCourierInfoItem(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $courierInfoCourierInfoItemItem) {
            // validation for constraint: itemType
            if (!$courierInfoCourierInfoItemItem instanceof \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem) {
                $invalidValues[] = is_object($courierInfoCourierInfoItemItem) ? get_class($courierInfoCourierInfoItemItem) : sprintf('%s(%s)', gettype($courierInfoCourierInfoItemItem), var_export($courierInfoCourierInfoItemItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The courierInfoItem property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set courierInfoItem value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem[] $courierInfoItem
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierInfo
     */
    public function setCourierInfoItem(?array $courierInfoItem = null): self
    {
        // validation for constraint: array
        if ('' !== ($courierInfoItemArrayErrorMessage = self::validateCourierInfoItemForArrayConstraintsFromSetCourierInfoItem($courierInfoItem))) {
            throw new InvalidArgumentException($courierInfoItemArrayErrorMessage, __LINE__);
        }
        $this->courierInfoItem = $courierInfoItem;
        
        return $this;
    }
    /**
     * Add item to courierInfoItem value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem $item
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierInfo
     */
    public function addToCourierInfoItem(\WpifyWoo\PacketeraSDK\StructType\CourierInfoItem $item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem) {
            throw new InvalidArgumentException(sprintf('The courierInfoItem property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->courierInfoItem[] = $item;
        
        return $this;
    }
}
