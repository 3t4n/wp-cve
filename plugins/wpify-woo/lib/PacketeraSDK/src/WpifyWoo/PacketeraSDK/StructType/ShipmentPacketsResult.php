<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ShipmentPacketsResult StructType
 * @subpackage Structs
 */
class ShipmentPacketsResult extends AbstractStructBase
{
    /**
     * The packets
     * @var \WpifyWoo\PacketeraSDK\StructType\PacketCollection|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\PacketCollection $packets = null;
    /**
     * Constructor method for ShipmentPacketsResult
     * @uses ShipmentPacketsResult::setPackets()
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketCollection $packets
     */
    public function __construct(?\WpifyWoo\PacketeraSDK\StructType\PacketCollection $packets = null)
    {
        $this
            ->setPackets($packets);
    }
    /**
     * Get packets value
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketCollection|null
     */
    public function getPackets(): ?\WpifyWoo\PacketeraSDK\StructType\PacketCollection
    {
        return $this->packets;
    }
    /**
     * Set packets value
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketCollection $packets
     * @return \WpifyWoo\PacketeraSDK\StructType\ShipmentPacketsResult
     */
    public function setPackets(?\WpifyWoo\PacketeraSDK\StructType\PacketCollection $packets = null): self
    {
        $this->packets = $packets;
        
        return $this;
    }
}
