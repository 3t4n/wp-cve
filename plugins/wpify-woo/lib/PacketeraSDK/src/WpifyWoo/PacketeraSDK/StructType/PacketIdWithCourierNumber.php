<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketIdWithCourierNumber StructType
 * @subpackage Structs
 */
class PacketIdWithCourierNumber extends AbstractStructBase
{
    /**
     * The packetId
     * @var string|null
     */
    protected ?string $packetId = null;
    /**
     * The courierNumber
     * @var string|null
     */
    protected ?string $courierNumber = null;
    /**
     * Constructor method for PacketIdWithCourierNumber
     * @uses PacketIdWithCourierNumber::setPacketId()
     * @uses PacketIdWithCourierNumber::setCourierNumber()
     * @param string $packetId
     * @param string $courierNumber
     */
    public function __construct(?string $packetId = null, ?string $courierNumber = null)
    {
        $this
            ->setPacketId($packetId)
            ->setCourierNumber($courierNumber);
    }
    /**
     * Get packetId value
     * @return string|null
     */
    public function getPacketId(): ?string
    {
        return $this->packetId;
    }
    /**
     * Set packetId value
     * @param string $packetId
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber
     */
    public function setPacketId(?string $packetId = null): self
    {
        // validation for constraint: string
        if (!is_null($packetId) && !is_string($packetId)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($packetId, true), gettype($packetId)), __LINE__);
        }
        $this->packetId = $packetId;
        
        return $this;
    }
    /**
     * Get courierNumber value
     * @return string|null
     */
    public function getCourierNumber(): ?string
    {
        return $this->courierNumber;
    }
    /**
     * Set courierNumber value
     * @param string $courierNumber
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdWithCourierNumber
     */
    public function setCourierNumber(?string $courierNumber = null): self
    {
        // validation for constraint: string
        if (!is_null($courierNumber) && !is_string($courierNumber)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($courierNumber, true), gettype($courierNumber)), __LINE__);
        }
        $this->courierNumber = $courierNumber;
        
        return $this;
    }
}
