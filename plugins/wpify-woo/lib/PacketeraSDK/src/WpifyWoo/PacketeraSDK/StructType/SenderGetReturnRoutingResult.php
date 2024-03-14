<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for SenderGetReturnRoutingResult StructType
 * @subpackage Structs
 */
class SenderGetReturnRoutingResult extends AbstractStructBase
{
    /**
     * The routingSegment
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 1
     * @var string[]
     */
    protected array $routingSegment;
    /**
     * Constructor method for SenderGetReturnRoutingResult
     * @uses SenderGetReturnRoutingResult::setRoutingSegment()
     * @param string[] $routingSegment
     */
    public function __construct(array $routingSegment)
    {
        $this
            ->setRoutingSegment($routingSegment);
    }
    /**
     * Get routingSegment value
     * @return string[]
     */
    public function getRoutingSegment(): array
    {
        return $this->routingSegment;
    }
    /**
     * This method is responsible for validating the values passed to the setRoutingSegment method
     * This method is willingly generated in order to preserve the one-line inline validation within the setRoutingSegment method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateRoutingSegmentForArrayConstraintsFromSetRoutingSegment(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $senderGetReturnRoutingResultRoutingSegmentItem) {
            // validation for constraint: itemType
            if (!is_string($senderGetReturnRoutingResultRoutingSegmentItem)) {
                $invalidValues[] = is_object($senderGetReturnRoutingResultRoutingSegmentItem) ? get_class($senderGetReturnRoutingResultRoutingSegmentItem) : sprintf('%s(%s)', gettype($senderGetReturnRoutingResultRoutingSegmentItem), var_export($senderGetReturnRoutingResultRoutingSegmentItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The routingSegment property can only contain items of type string, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set routingSegment value
     * @throws InvalidArgumentException
     * @param string[] $routingSegment
     * @return \WpifyWoo\PacketeraSDK\StructType\SenderGetReturnRoutingResult
     */
    public function setRoutingSegment(array $routingSegment): self
    {
        // validation for constraint: array
        if ('' !== ($routingSegmentArrayErrorMessage = self::validateRoutingSegmentForArrayConstraintsFromSetRoutingSegment($routingSegment))) {
            throw new InvalidArgumentException($routingSegmentArrayErrorMessage, __LINE__);
        }
        $this->routingSegment = $routingSegment;
        
        return $this;
    }
    /**
     * Add item to routingSegment value
     * @throws InvalidArgumentException
     * @param string $item
     * @return \WpifyWoo\PacketeraSDK\StructType\SenderGetReturnRoutingResult
     */
    public function addToRoutingSegment(string $item): self
    {
        // validation for constraint: itemType
        if (!is_string($item)) {
            throw new InvalidArgumentException(sprintf('The routingSegment property can only contain items of type string, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->routingSegment[] = $item;
        
        return $this;
    }
}
