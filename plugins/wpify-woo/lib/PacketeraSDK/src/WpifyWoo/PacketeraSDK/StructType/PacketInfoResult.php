<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketInfoResult StructType
 * @subpackage Structs
 */
class PacketInfoResult extends AbstractStructBase
{
    /**
     * The branchId
     * @var int|null
     */
    protected ?int $branchId = null;
    /**
     * The invoicedWeightGrams
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var int|null
     */
    protected ?int $invoicedWeightGrams = null;
    /**
     * The courierInfo
     * @var \WpifyWoo\PacketeraSDK\StructType\CourierInfo|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\CourierInfo $courierInfo = null;
    /**
     * Constructor method for PacketInfoResult
     * @uses PacketInfoResult::setBranchId()
     * @uses PacketInfoResult::setInvoicedWeightGrams()
     * @uses PacketInfoResult::setCourierInfo()
     * @param int $branchId
     * @param int $invoicedWeightGrams
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierInfo $courierInfo
     */
    public function __construct(?int $branchId = null, ?int $invoicedWeightGrams = null, ?\WpifyWoo\PacketeraSDK\StructType\CourierInfo $courierInfo = null)
    {
        $this
            ->setBranchId($branchId)
            ->setInvoicedWeightGrams($invoicedWeightGrams)
            ->setCourierInfo($courierInfo);
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketInfoResult
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
     * Get invoicedWeightGrams value
     * @return int|null
     */
    public function getInvoicedWeightGrams(): ?int
    {
        return $this->invoicedWeightGrams;
    }
    /**
     * Set invoicedWeightGrams value
     * @param int $invoicedWeightGrams
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketInfoResult
     */
    public function setInvoicedWeightGrams(?int $invoicedWeightGrams = null): self
    {
        // validation for constraint: int
        if (!is_null($invoicedWeightGrams) && !(is_int($invoicedWeightGrams) || ctype_digit($invoicedWeightGrams))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($invoicedWeightGrams, true), gettype($invoicedWeightGrams)), __LINE__);
        }
        $this->invoicedWeightGrams = $invoicedWeightGrams;
        
        return $this;
    }
    /**
     * Get courierInfo value
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierInfo|null
     */
    public function getCourierInfo(): ?\WpifyWoo\PacketeraSDK\StructType\CourierInfo
    {
        return $this->courierInfo;
    }
    /**
     * Set courierInfo value
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierInfo $courierInfo
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketInfoResult
     */
    public function setCourierInfo(?\WpifyWoo\PacketeraSDK\StructType\CourierInfo $courierInfo = null): self
    {
        $this->courierInfo = $courierInfo;
        
        return $this;
    }
}
