<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CurrentStatusRecord StructType
 * @subpackage Structs
 */
class CurrentStatusRecord extends StatusRecord
{
    /**
     * The isReturning
     * @var bool|null
     */
    protected ?bool $isReturning = null;
    /**
     * The storedUntil
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $storedUntil = null;
    /**
     * The carrierId
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $carrierId = null;
    /**
     * The carrierName
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $carrierName = null;
    /**
     * Constructor method for CurrentStatusRecord
     * @uses CurrentStatusRecord::setIsReturning()
     * @uses CurrentStatusRecord::setStoredUntil()
     * @uses CurrentStatusRecord::setCarrierId()
     * @uses CurrentStatusRecord::setCarrierName()
     * @param bool $isReturning
     * @param string $storedUntil
     * @param string $carrierId
     * @param string $carrierName
     */
    public function __construct(?bool $isReturning = null, ?string $storedUntil = null, ?string $carrierId = null, ?string $carrierName = null)
    {
        $this
            ->setIsReturning($isReturning)
            ->setStoredUntil($storedUntil)
            ->setCarrierId($carrierId)
            ->setCarrierName($carrierName);
    }
    /**
     * Get isReturning value
     * @return bool|null
     */
    public function getIsReturning(): ?bool
    {
        return $this->isReturning;
    }
    /**
     * Set isReturning value
     * @param bool $isReturning
     * @return \WpifyWoo\PacketeraSDK\StructType\CurrentStatusRecord
     */
    public function setIsReturning(?bool $isReturning = null): self
    {
        // validation for constraint: boolean
        if (!is_null($isReturning) && !is_bool($isReturning)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($isReturning, true), gettype($isReturning)), __LINE__);
        }
        $this->isReturning = $isReturning;
        
        return $this;
    }
    /**
     * Get storedUntil value
     * @return string|null
     */
    public function getStoredUntil(): ?string
    {
        return $this->storedUntil;
    }
    /**
     * Set storedUntil value
     * @param string $storedUntil
     * @return \WpifyWoo\PacketeraSDK\StructType\CurrentStatusRecord
     */
    public function setStoredUntil(?string $storedUntil = null): self
    {
        // validation for constraint: string
        if (!is_null($storedUntil) && !is_string($storedUntil)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($storedUntil, true), gettype($storedUntil)), __LINE__);
        }
        $this->storedUntil = $storedUntil;
        
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
     * @return \WpifyWoo\PacketeraSDK\StructType\CurrentStatusRecord
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
     * @return \WpifyWoo\PacketeraSDK\StructType\CurrentStatusRecord
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
