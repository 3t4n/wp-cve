<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CourierBarcodes StructType
 * @subpackage Structs
 */
class CourierBarcodes extends AbstractStructBase
{
    /**
     * The courierBarcode
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var string[]
     */
    protected ?array $courierBarcode = null;
    /**
     * Constructor method for CourierBarcodes
     * @uses CourierBarcodes::setCourierBarcode()
     * @param string[] $courierBarcode
     */
    public function __construct(?array $courierBarcode = null)
    {
        $this
            ->setCourierBarcode($courierBarcode);
    }
    /**
     * Get courierBarcode value
     * @return string[]
     */
    public function getCourierBarcode(): ?array
    {
        return $this->courierBarcode;
    }
    /**
     * This method is responsible for validating the values passed to the setCourierBarcode method
     * This method is willingly generated in order to preserve the one-line inline validation within the setCourierBarcode method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateCourierBarcodeForArrayConstraintsFromSetCourierBarcode(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $courierBarcodesCourierBarcodeItem) {
            // validation for constraint: itemType
            if (!is_string($courierBarcodesCourierBarcodeItem)) {
                $invalidValues[] = is_object($courierBarcodesCourierBarcodeItem) ? get_class($courierBarcodesCourierBarcodeItem) : sprintf('%s(%s)', gettype($courierBarcodesCourierBarcodeItem), var_export($courierBarcodesCourierBarcodeItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The courierBarcode property can only contain items of type string, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set courierBarcode value
     * @throws InvalidArgumentException
     * @param string[] $courierBarcode
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierBarcodes
     */
    public function setCourierBarcode(?array $courierBarcode = null): self
    {
        // validation for constraint: array
        if ('' !== ($courierBarcodeArrayErrorMessage = self::validateCourierBarcodeForArrayConstraintsFromSetCourierBarcode($courierBarcode))) {
            throw new InvalidArgumentException($courierBarcodeArrayErrorMessage, __LINE__);
        }
        $this->courierBarcode = $courierBarcode;
        
        return $this;
    }
    /**
     * Add item to courierBarcode value
     * @throws InvalidArgumentException
     * @param string $item
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierBarcodes
     */
    public function addToCourierBarcode(string $item): self
    {
        // validation for constraint: itemType
        if (!is_string($item)) {
            throw new InvalidArgumentException(sprintf('The courierBarcode property can only contain items of type string, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->courierBarcode[] = $item;
        
        return $this;
    }
}
