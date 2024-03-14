<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CourierNumbers StructType
 * @subpackage Structs
 */
class CourierNumbers extends AbstractStructBase
{
    /**
     * The courierNumber
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var string[]
     */
    protected ?array $courierNumber = null;
    /**
     * Constructor method for CourierNumbers
     * @uses CourierNumbers::setCourierNumber()
     * @param string[] $courierNumber
     */
    public function __construct(?array $courierNumber = null)
    {
        $this
            ->setCourierNumber($courierNumber);
    }
    /**
     * Get courierNumber value
     * @return string[]
     */
    public function getCourierNumber(): ?array
    {
        return $this->courierNumber;
    }
    /**
     * This method is responsible for validating the values passed to the setCourierNumber method
     * This method is willingly generated in order to preserve the one-line inline validation within the setCourierNumber method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateCourierNumberForArrayConstraintsFromSetCourierNumber(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $courierNumbersCourierNumberItem) {
            // validation for constraint: itemType
            if (!is_string($courierNumbersCourierNumberItem)) {
                $invalidValues[] = is_object($courierNumbersCourierNumberItem) ? get_class($courierNumbersCourierNumberItem) : sprintf('%s(%s)', gettype($courierNumbersCourierNumberItem), var_export($courierNumbersCourierNumberItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The courierNumber property can only contain items of type string, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set courierNumber value
     * @throws InvalidArgumentException
     * @param string[] $courierNumber
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierNumbers
     */
    public function setCourierNumber(?array $courierNumber = null): self
    {
        // validation for constraint: array
        if ('' !== ($courierNumberArrayErrorMessage = self::validateCourierNumberForArrayConstraintsFromSetCourierNumber($courierNumber))) {
            throw new InvalidArgumentException($courierNumberArrayErrorMessage, __LINE__);
        }
        $this->courierNumber = $courierNumber;
        
        return $this;
    }
    /**
     * Add item to courierNumber value
     * @throws InvalidArgumentException
     * @param string $item
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierNumbers
     */
    public function addToCourierNumber(string $item): self
    {
        // validation for constraint: itemType
        if (!is_string($item)) {
            throw new InvalidArgumentException(sprintf('The courierNumber property can only contain items of type string, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->courierNumber[] = $item;
        
        return $this;
    }
}
