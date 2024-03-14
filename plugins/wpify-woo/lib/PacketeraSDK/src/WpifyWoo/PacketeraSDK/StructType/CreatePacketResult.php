<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CreatePacketResult StructType
 * @subpackage Structs
 */
class CreatePacketResult extends AbstractStructBase
{
    /**
     * The createdPacket
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\PacketIdDetail $createdPacket = null;
    /**
     * The fault
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $fault = null;
    /**
     * The packetAttributesFault
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault $packetAttributesFault = null;
    /**
     * The dispatchOrderUnknownCodeFault
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\DispatchOrderUnknownCodeFault|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\DispatchOrderUnknownCodeFault $dispatchOrderUnknownCodeFault = null;
    /**
     * Constructor method for CreatePacketResult
     * @uses CreatePacketResult::setCreatedPacket()
     * @uses CreatePacketResult::setFault()
     * @uses CreatePacketResult::setPacketAttributesFault()
     * @uses CreatePacketResult::setDispatchOrderUnknownCodeFault()
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail $createdPacket
     * @param string $fault
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault $packetAttributesFault
     * @param \WpifyWoo\PacketeraSDK\StructType\DispatchOrderUnknownCodeFault $dispatchOrderUnknownCodeFault
     */
    public function __construct(?\WpifyWoo\PacketeraSDK\StructType\PacketIdDetail $createdPacket = null, ?string $fault = null, ?\WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault $packetAttributesFault = null, ?\WpifyWoo\PacketeraSDK\StructType\DispatchOrderUnknownCodeFault $dispatchOrderUnknownCodeFault = null)
    {
        $this
            ->setCreatedPacket($createdPacket)
            ->setFault($fault)
            ->setPacketAttributesFault($packetAttributesFault)
            ->setDispatchOrderUnknownCodeFault($dispatchOrderUnknownCodeFault);
    }
    /**
     * Get createdPacket value
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail|null
     */
    public function getCreatedPacket(): ?\WpifyWoo\PacketeraSDK\StructType\PacketIdDetail
    {
        return $this->createdPacket;
    }
    /**
     * Set createdPacket value
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail $createdPacket
     * @return \WpifyWoo\PacketeraSDK\StructType\CreatePacketResult
     */
    public function setCreatedPacket(?\WpifyWoo\PacketeraSDK\StructType\PacketIdDetail $createdPacket = null): self
    {
        $this->createdPacket = $createdPacket;
        
        return $this;
    }
    /**
     * Get fault value
     * @return string|null
     */
    public function getFault(): ?string
    {
        return $this->fault;
    }
    /**
     * Set fault value
     * @param string $fault
     * @return \WpifyWoo\PacketeraSDK\StructType\CreatePacketResult
     */
    public function setFault(?string $fault = null): self
    {
        // validation for constraint: string
        if (!is_null($fault) && !is_string($fault)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($fault, true), gettype($fault)), __LINE__);
        }
        $this->fault = $fault;
        
        return $this;
    }
    /**
     * Get packetAttributesFault value
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault|null
     */
    public function getPacketAttributesFault(): ?\WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault
    {
        return $this->packetAttributesFault;
    }
    /**
     * Set packetAttributesFault value
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault $packetAttributesFault
     * @return \WpifyWoo\PacketeraSDK\StructType\CreatePacketResult
     */
    public function setPacketAttributesFault(?\WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault $packetAttributesFault = null): self
    {
        $this->packetAttributesFault = $packetAttributesFault;
        
        return $this;
    }
    /**
     * Get dispatchOrderUnknownCodeFault value
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrderUnknownCodeFault|null
     */
    public function getDispatchOrderUnknownCodeFault(): ?\WpifyWoo\PacketeraSDK\StructType\DispatchOrderUnknownCodeFault
    {
        return $this->dispatchOrderUnknownCodeFault;
    }
    /**
     * Set dispatchOrderUnknownCodeFault value
     * @param \WpifyWoo\PacketeraSDK\StructType\DispatchOrderUnknownCodeFault $dispatchOrderUnknownCodeFault
     * @return \WpifyWoo\PacketeraSDK\StructType\CreatePacketResult
     */
    public function setDispatchOrderUnknownCodeFault(?\WpifyWoo\PacketeraSDK\StructType\DispatchOrderUnknownCodeFault $dispatchOrderUnknownCodeFault = null): self
    {
        $this->dispatchOrderUnknownCodeFault = $dispatchOrderUnknownCodeFault;
        
        return $this;
    }
}
