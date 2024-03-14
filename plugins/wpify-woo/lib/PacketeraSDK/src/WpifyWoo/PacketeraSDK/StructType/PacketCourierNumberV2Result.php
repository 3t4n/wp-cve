<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for packetCourierNumberV2Result StructType
 * @subpackage Structs
 */
class PacketCourierNumberV2Result extends AbstractStructBase
{
    /**
     * The courierNumber
     * @var string|null
     */
    protected ?string $courierNumber = null;
    /**
     * The carrierId
     * @var string|null
     */
    protected ?string $carrierId = null;
    /**
     * The carrierName
     * @var string|null
     */
    protected ?string $carrierName = null;
    /**
     * Constructor method for packetCourierNumberV2Result
     * @uses PacketCourierNumberV2Result::setCourierNumber()
     * @uses PacketCourierNumberV2Result::setCarrierId()
     * @uses PacketCourierNumberV2Result::setCarrierName()
     * @param string $courierNumber
     * @param string $carrierId
     * @param string $carrierName
     */
    public function __construct(?string $courierNumber = null, ?string $carrierId = null, ?string $carrierName = null)
    {
        $this
            ->setCourierNumber($courierNumber)
            ->setCarrierId($carrierId)
            ->setCarrierName($carrierName);
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketCourierNumberV2Result
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
    /**
     * Get carrierId value
     * @return string|null
     */
    public function getCarrierId(): ?string
    {
        return $this->carrierId;
    }
    /**
     * Set carrierId value
     * @param string $carrierId
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketCourierNumberV2Result
     */
    public function setCarrierId(?string $carrierId = null): self
    {
        // validation for constraint: string
        if (!is_null($carrierId) && !is_string($carrierId)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($carrierId, true), gettype($carrierId)), __LINE__);
        }
        $this->carrierId = $carrierId;
        
        return $this;
    }
    /**
     * Get carrierName value
     * @return string|null
     */
    public function getCarrierName(): ?string
    {
        return $this->carrierName;
    }
    /**
     * Set carrierName value
     * @param string $carrierName
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketCourierNumberV2Result
     */
    public function setCarrierName(?string $carrierName = null): self
    {
        // validation for constraint: string
        if (!is_null($carrierName) && !is_string($carrierName)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($carrierName, true), gettype($carrierName)), __LINE__);
        }
        $this->carrierName = $carrierName;
        
        return $this;
    }
}
