<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for attributes StructType
 * @subpackage Structs
 */
class Attributes extends AbstractStructBase
{
    /**
     * The fault
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 1
     * @var \WpifyWoo\PacketeraSDK\StructType\AttributeFault[]
     */
    protected array $fault;
    /**
     * Constructor method for attributes
     * @uses Attributes::setFault()
     * @param \WpifyWoo\PacketeraSDK\StructType\AttributeFault[] $fault
     */
    public function __construct(array $fault)
    {
        $this
            ->setFault($fault);
    }
    /**
     * Get fault value
     * @return \WpifyWoo\PacketeraSDK\StructType\AttributeFault[]
     */
    public function getFault(): array
    {
        return $this->fault;
    }
    /**
     * This method is responsible for validating the values passed to the setFault method
     * This method is willingly generated in order to preserve the one-line inline validation within the setFault method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateFaultForArrayConstraintsFromSetFault(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $attributesFaultItem) {
            // validation for constraint: itemType
            if (!$attributesFaultItem instanceof \WpifyWoo\PacketeraSDK\StructType\AttributeFault) {
                $invalidValues[] = is_object($attributesFaultItem) ? get_class($attributesFaultItem) : sprintf('%s(%s)', gettype($attributesFaultItem), var_export($attributesFaultItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The fault property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\AttributeFault, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set fault value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\AttributeFault[] $fault
     * @return \WpifyWoo\PacketeraSDK\StructType\Attributes
     */
    public function setFault(array $fault): self
    {
        // validation for constraint: array
        if ('' !== ($faultArrayErrorMessage = self::validateFaultForArrayConstraintsFromSetFault($fault))) {
            throw new InvalidArgumentException($faultArrayErrorMessage, __LINE__);
        }
        $this->fault = $fault;
        
        return $this;
    }
    /**
     * Add item to fault value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\AttributeFault $item
     * @return \WpifyWoo\PacketeraSDK\StructType\Attributes
     */
    public function addToFault(\WpifyWoo\PacketeraSDK\StructType\AttributeFault $item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \WpifyWoo\PacketeraSDK\StructType\AttributeFault) {
            throw new InvalidArgumentException(sprintf('The fault property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\AttributeFault, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->fault[] = $item;
        
        return $this;
    }
}
