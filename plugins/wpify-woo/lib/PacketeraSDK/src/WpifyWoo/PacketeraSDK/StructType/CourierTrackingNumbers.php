<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CourierTrackingNumbers StructType
 * @subpackage Structs
 */
class CourierTrackingNumbers extends AbstractStructBase
{
    /**
     * The courierTrackingNumber
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var string[]
     */
    protected ?array $courierTrackingNumber = null;
    /**
     * Constructor method for CourierTrackingNumbers
     * @uses CourierTrackingNumbers::setCourierTrackingNumber()
     * @param string[] $courierTrackingNumber
     */
    public function __construct(?array $courierTrackingNumber = null)
    {
        $this
            ->setCourierTrackingNumber($courierTrackingNumber);
    }
    /**
     * Get courierTrackingNumber value
     * @return string[]
     */
    public function getCourierTrackingNumber(): ?array
    {
        return $this->courierTrackingNumber;
    }
    /**
     * This method is responsible for validating the values passed to the setCourierTrackingNumber method
     * This method is willingly generated in order to preserve the one-line inline validation within the setCourierTrackingNumber method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateCourierTrackingNumberForArrayConstraintsFromSetCourierTrackingNumber(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $courierTrackingNumbersCourierTrackingNumberItem) {
            // validation for constraint: itemType
            if (!is_string($courierTrackingNumbersCourierTrackingNumberItem)) {
                $invalidValues[] = is_object($courierTrackingNumbersCourierTrackingNumberItem) ? get_class($courierTrackingNumbersCourierTrackingNumberItem) : sprintf('%s(%s)', gettype($courierTrackingNumbersCourierTrackingNumberItem), var_export($courierTrackingNumbersCourierTrackingNumberItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The courierTrackingNumber property can only contain items of type string, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set courierTrackingNumber value
     * @throws InvalidArgumentException
     * @param string[] $courierTrackingNumber
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierTrackingNumbers
     */
    public function setCourierTrackingNumber(?array $courierTrackingNumber = null): self
    {
        // validation for constraint: array
        if ('' !== ($courierTrackingNumberArrayErrorMessage = self::validateCourierTrackingNumberForArrayConstraintsFromSetCourierTrackingNumber($courierTrackingNumber))) {
            throw new InvalidArgumentException($courierTrackingNumberArrayErrorMessage, __LINE__);
        }
        $this->courierTrackingNumber = $courierTrackingNumber;
        
        return $this;
    }
    /**
     * Add item to courierTrackingNumber value
     * @throws InvalidArgumentException
     * @param string $item
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierTrackingNumbers
     */
    public function addToCourierTrackingNumber(string $item): self
    {
        // validation for constraint: itemType
        if (!is_string($item)) {
            throw new InvalidArgumentException(sprintf('The courierTrackingNumber property can only contain items of type string, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->courierTrackingNumber[] = $item;
        
        return $this;
    }
}
