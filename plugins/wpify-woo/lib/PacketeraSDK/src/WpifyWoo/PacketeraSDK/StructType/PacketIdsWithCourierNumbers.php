<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketIdsWithCourierNumbers StructType
 * @subpackage Structs
 */
class PacketIdsWithCourierNumbers extends AbstractStructBase
{
    /**
     * The packetIdWithCourierNumber
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 1
     * @var \WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber[]
     */
    protected array $packetIdWithCourierNumber;
    /**
     * Constructor method for PacketIdsWithCourierNumbers
     * @uses PacketIdsWithCourierNumbers::setPacketIdWithCourierNumber()
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber[] $packetIdWithCourierNumber
     */
    public function __construct(array $packetIdWithCourierNumber)
    {
        $this
            ->setPacketIdWithCourierNumber($packetIdWithCourierNumber);
    }
    /**
     * Get packetIdWithCourierNumber value
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber[]
     */
    public function getPacketIdWithCourierNumber(): array
    {
        return $this->packetIdWithCourierNumber;
    }
    /**
     * This method is responsible for validating the values passed to the setPacketIdWithCourierNumber method
     * This method is willingly generated in order to preserve the one-line inline validation within the setPacketIdWithCourierNumber method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validatePacketIdWithCourierNumberForArrayConstraintsFromSetPacketIdWithCourierNumber(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $packetIdsWithCourierNumbersPacketIdWithCourierNumberItem) {
            // validation for constraint: itemType
            if (!$packetIdsWithCourierNumbersPacketIdWithCourierNumberItem instanceof \WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber) {
                $invalidValues[] = is_object($packetIdsWithCourierNumbersPacketIdWithCourierNumberItem) ? get_class($packetIdsWithCourierNumbersPacketIdWithCourierNumberItem) : sprintf('%s(%s)', gettype($packetIdsWithCourierNumbersPacketIdWithCourierNumberItem), var_export($packetIdsWithCourierNumbersPacketIdWithCourierNumberItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The packetIdWithCourierNumber property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set packetIdWithCourierNumber value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber[] $packetIdWithCourierNumber
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdsWithCourierNumbers
     */
    public function setPacketIdWithCourierNumber(array $packetIdWithCourierNumber): self
    {
        // validation for constraint: array
        if ('' !== ($packetIdWithCourierNumberArrayErrorMessage = self::validatePacketIdWithCourierNumberForArrayConstraintsFromSetPacketIdWithCourierNumber($packetIdWithCourierNumber))) {
            throw new InvalidArgumentException($packetIdWithCourierNumberArrayErrorMessage, __LINE__);
        }
        $this->packetIdWithCourierNumber = $packetIdWithCourierNumber;
        
        return $this;
    }
    /**
     * Add item to packetIdWithCourierNumber value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber $item
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdsWithCourierNumbers
     */
    public function addToPacketIdWithCourierNumber(\WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber $item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber) {
            throw new InvalidArgumentException(sprintf('The packetIdWithCourierNumber property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->packetIdWithCourierNumber[] = $item;
        
        return $this;
    }
}
