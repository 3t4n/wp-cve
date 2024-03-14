<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ExternalStatusRecord StructType
 * @subpackage Structs
 */
class ExternalStatusRecord extends AbstractStructBase
{
    /**
     * The dateTime
     * @var string|null
     */
    protected ?string $dateTime = null;
    /**
     * The carrierClass
     * @var string|null
     */
    protected ?string $carrierClass = null;
    /**
     * The statusCode
     * @var string|null
     */
    protected ?string $statusCode = null;
    /**
     * The externalStatusName
     * @var string|null
     */
    protected ?string $externalStatusName = null;
    /**
     * The externalNote
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $externalNote = null;
    /**
     * The externalTrackingCode
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $externalTrackingCode = null;
    /**
     * Constructor method for ExternalStatusRecord
     * @uses ExternalStatusRecord::setDateTime()
     * @uses ExternalStatusRecord::setCarrierClass()
     * @uses ExternalStatusRecord::setStatusCode()
     * @uses ExternalStatusRecord::setExternalStatusName()
     * @uses ExternalStatusRecord::setExternalNote()
     * @uses ExternalStatusRecord::setExternalTrackingCode()
     * @param string $dateTime
     * @param string $carrierClass
     * @param string $statusCode
     * @param string $externalStatusName
     * @param string $externalNote
     * @param string $externalTrackingCode
     */
    public function __construct(?string $dateTime = null, ?string $carrierClass = null, ?string $statusCode = null, ?string $externalStatusName = null, ?string $externalNote = null, ?string $externalTrackingCode = null)
    {
        $this
            ->setDateTime($dateTime)
            ->setCarrierClass($carrierClass)
            ->setStatusCode($statusCode)
            ->setExternalStatusName($externalStatusName)
            ->setExternalNote($externalNote)
            ->setExternalTrackingCode($externalTrackingCode);
    }
    /**
     * Get dateTime value
     * @return string|null
     */
    public function getDateTime(): ?string
    {
        return $this->dateTime;
    }
    /**
     * Set dateTime value
     * @param string $dateTime
     * @return \WpifyWoo\PacketeraSDK\StructType\ExternalStatusRecord
     */
    public function setDateTime(?string $dateTime = null): self
    {
        // validation for constraint: string
        if (!is_null($dateTime) && !is_string($dateTime)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($dateTime, true), gettype($dateTime)), __LINE__);
        }
        $this->dateTime = $dateTime;
        
        return $this;
    }
    /**
     * Get carrierClass value
     * @return string|null
     */
    public function getCarrierClass(): ?string
    {
        return $this->carrierClass;
    }
    /**
     * Set carrierClass value
     * @param string $carrierClass
     * @return \WpifyWoo\PacketeraSDK\StructType\ExternalStatusRecord
     */
    public function setCarrierClass(?string $carrierClass = null): self
    {
        // validation for constraint: string
        if (!is_null($carrierClass) && !is_string($carrierClass)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($carrierClass, true), gettype($carrierClass)), __LINE__);
        }
        $this->carrierClass = $carrierClass;
        
        return $this;
    }
    /**
     * Get statusCode value
     * @return string|null
     */
    public function getStatusCode(): ?string
    {
        return $this->statusCode;
    }
    /**
     * Set statusCode value
     * @param string $statusCode
     * @return \WpifyWoo\PacketeraSDK\StructType\ExternalStatusRecord
     */
    public function setStatusCode(?string $statusCode = null): self
    {
        // validation for constraint: string
        if (!is_null($statusCode) && !is_string($statusCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($statusCode, true), gettype($statusCode)), __LINE__);
        }
        $this->statusCode = $statusCode;
        
        return $this;
    }
    /**
     * Get externalStatusName value
     * @return string|null
     */
    public function getExternalStatusName(): ?string
    {
        return $this->externalStatusName;
    }
    /**
     * Set externalStatusName value
     * @param string $externalStatusName
     * @return \WpifyWoo\PacketeraSDK\StructType\ExternalStatusRecord
     */
    public function setExternalStatusName(?string $externalStatusName = null): self
    {
        // validation for constraint: string
        if (!is_null($externalStatusName) && !is_string($externalStatusName)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($externalStatusName, true), gettype($externalStatusName)), __LINE__);
        }
        $this->externalStatusName = $externalStatusName;
        
        return $this;
    }
    /**
     * Get externalNote value
     * @return string|null
     */
    public function getExternalNote(): ?string
    {
        return $this->externalNote;
    }
    /**
     * Set externalNote value
     * @param string $externalNote
     * @return \WpifyWoo\PacketeraSDK\StructType\ExternalStatusRecord
     */
    public function setExternalNote(?string $externalNote = null): self
    {
        // validation for constraint: string
        if (!is_null($externalNote) && !is_string($externalNote)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($externalNote, true), gettype($externalNote)), __LINE__);
        }
        $this->externalNote = $externalNote;
        
        return $this;
    }
    /**
     * Get externalTrackingCode value
     * @return string|null
     */
    public function getExternalTrackingCode(): ?string
    {
        return $this->externalTrackingCode;
    }
    /**
     * Set externalTrackingCode value
     * @param string $externalTrackingCode
     * @return \WpifyWoo\PacketeraSDK\StructType\ExternalStatusRecord
     */
    public function setExternalTrackingCode(?string $externalTrackingCode = null): self
    {
        // validation for constraint: string
        if (!is_null($externalTrackingCode) && !is_string($externalTrackingCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($externalTrackingCode, true), gettype($externalTrackingCode)), __LINE__);
        }
        $this->externalTrackingCode = $externalTrackingCode;
        
        return $this;
    }
}
