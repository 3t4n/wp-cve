<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketCollection StructType
 * @subpackage Structs
 */
class PacketCollection extends AbstractStructBase
{
    /**
     * The packet
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail[]
     */
    protected ?array $packet = null;
    /**
     * Constructor method for PacketCollection
     * @uses PacketCollection::setPacket()
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail[] $packet
     */
    public function __construct(?array $packet = null)
    {
        $this
            ->setPacket($packet);
    }
    /**
     * Get packet value
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail[]
     */
    public function getPacket(): ?array
    {
        return $this->packet;
    }
    /**
     * This method is responsible for validating the values passed to the setPacket method
     * This method is willingly generated in order to preserve the one-line inline validation within the setPacket method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validatePacketForArrayConstraintsFromSetPacket(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $packetCollectionPacketItem) {
            // validation for constraint: itemType
            if (!$packetCollectionPacketItem instanceof \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail) {
                $invalidValues[] = is_object($packetCollectionPacketItem) ? get_class($packetCollectionPacketItem) : sprintf('%s(%s)', gettype($packetCollectionPacketItem), var_export($packetCollectionPacketItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The packet property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set packet value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail[] $packet
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketCollection
     */
    public function setPacket(?array $packet = null): self
    {
        // validation for constraint: array
        if ('' !== ($packetArrayErrorMessage = self::validatePacketForArrayConstraintsFromSetPacket($packet))) {
            throw new InvalidArgumentException($packetArrayErrorMessage, __LINE__);
        }
        $this->packet = $packet;
        
        return $this;
    }
    /**
     * Add item to packet value
     * @throws InvalidArgumentException
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail $item
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketCollection
     */
    public function addToPacket(\WpifyWoo\PacketeraSDK\StructType\PacketIdDetail $item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail) {
            throw new InvalidArgumentException(sprintf('The packet property can only contain items of type \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->packet[] = $item;
        
        return $this;
    }
}
