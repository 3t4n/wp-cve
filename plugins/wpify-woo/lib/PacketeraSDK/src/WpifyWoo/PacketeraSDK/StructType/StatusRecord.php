<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for StatusRecord StructType
 * @subpackage Structs
 */
class StatusRecord extends AbstractStructBase
{
    /**
     * The dateTime
     * @var string|null
     */
    protected ?string $dateTime = null;
    /**
     * The statusCode
     * @var int|null
     */
    protected ?int $statusCode = null;
    /**
     * The codeText
     * @var string|null
     */
    protected ?string $codeText = null;
    /**
     * The statusText
     * @var string|null
     */
    protected ?string $statusText = null;
    /**
     * The branchId
     * @var int|null
     */
    protected ?int $branchId = null;
    /**
     * The destinationBranchId
     * @var int|null
     */
    protected ?int $destinationBranchId = null;
    /**
     * The externalTrackingCode
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $externalTrackingCode = null;
    /**
     * Constructor method for StatusRecord
     * @uses StatusRecord::setDateTime()
     * @uses StatusRecord::setStatusCode()
     * @uses StatusRecord::setCodeText()
     * @uses StatusRecord::setStatusText()
     * @uses StatusRecord::setBranchId()
     * @uses StatusRecord::setDestinationBranchId()
     * @uses StatusRecord::setExternalTrackingCode()
     * @param string $dateTime
     * @param int $statusCode
     * @param string $codeText
     * @param string $statusText
     * @param int $branchId
     * @param int $destinationBranchId
     * @param string $externalTrackingCode
     */
    public function __construct(?string $dateTime = null, ?int $statusCode = null, ?string $codeText = null, ?string $statusText = null, ?int $branchId = null, ?int $destinationBranchId = null, ?string $externalTrackingCode = null)
    {
        $this
            ->setDateTime($dateTime)
            ->setStatusCode($statusCode)
            ->setCodeText($codeText)
            ->setStatusText($statusText)
            ->setBranchId($branchId)
            ->setDestinationBranchId($destinationBranchId)
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
     * @return \WpifyWoo\PacketeraSDK\StructType\StatusRecord
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
     * Get statusCode value
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
    /**
     * Set statusCode value
     * @param int $statusCode
     * @return \WpifyWoo\PacketeraSDK\StructType\StatusRecord
     */
    public function setStatusCode(?int $statusCode = null): self
    {
        // validation for constraint: int
        if (!is_null($statusCode) && !(is_int($statusCode) || ctype_digit($statusCode))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($statusCode, true), gettype($statusCode)), __LINE__);
        }
        $this->statusCode = $statusCode;
        
        return $this;
    }
    /**
     * Get codeText value
     * @return string|null
     */
    public function getCodeText(): ?string
    {
        return $this->codeText;
    }
    /**
     * Set codeText value
     * @param string $codeText
     * @return \WpifyWoo\PacketeraSDK\StructType\StatusRecord
     */
    public function setCodeText(?string $codeText = null): self
    {
        // validation for constraint: string
        if (!is_null($codeText) && !is_string($codeText)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($codeText, true), gettype($codeText)), __LINE__);
        }
        $this->codeText = $codeText;
        
        return $this;
    }
    /**
     * Get statusText value
     * @return string|null
     */
    public function getStatusText(): ?string
    {
        return $this->statusText;
    }
    /**
     * Set statusText value
     * @param string $statusText
     * @return \WpifyWoo\PacketeraSDK\StructType\StatusRecord
     */
    public function setStatusText(?string $statusText = null): self
    {
        // validation for constraint: string
        if (!is_null($statusText) && !is_string($statusText)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($statusText, true), gettype($statusText)), __LINE__);
        }
        $this->statusText = $statusText;
        
        return $this;
    }
    /**
     * Get branchId value
     * @return int|null
     */
    public function getBranchId(): ?int
    {
        return $this->branchId;
    }
    /**
     * Set branchId value
     * @param int $branchId
     * @return \WpifyWoo\PacketeraSDK\StructType\StatusRecord
     */
    public function setBranchId(?int $branchId = null): self
    {
        // validation for constraint: int
        if (!is_null($branchId) && !(is_int($branchId) || ctype_digit($branchId))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($branchId, true), gettype($branchId)), __LINE__);
        }
        $this->branchId = $branchId;
        
        return $this;
    }
    /**
     * Get destinationBranchId value
     * @return int|null
     */
    public function getDestinationBranchId(): ?int
    {
        return $this->destinationBranchId;
    }
    /**
     * Set destinationBranchId value
     * @param int $destinationBranchId
     * @return \WpifyWoo\PacketeraSDK\StructType\StatusRecord
     */
    public function setDestinationBranchId(?int $destinationBranchId = null): self
    {
        // validation for constraint: int
        if (!is_null($destinationBranchId) && !(is_int($destinationBranchId) || ctype_digit($destinationBranchId))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($destinationBranchId, true), gettype($destinationBranchId)), __LINE__);
        }
        $this->destinationBranchId = $destinationBranchId;
        
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
     * @return \WpifyWoo\PacketeraSDK\StructType\StatusRecord
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
