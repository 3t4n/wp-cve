<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CourierInfoItem StructType
 * @subpackage Structs
 */
class CourierInfoItem extends AbstractStructBase
{
    /**
     * The courierId
     * @var int|null
     */
    protected ?int $courierId = null;
    /**
     * The courierName
     * @var string|null
     */
    protected ?string $courierName = null;
    /**
     * The courierNumbers
     * @var \WpifyWoo\PacketeraSDK\StructType\CourierNumbers|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\CourierNumbers $courierNumbers = null;
    /**
     * The courierBarcodes
     * @var \WpifyWoo\PacketeraSDK\StructType\CourierBarcodes|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\CourierBarcodes $courierBarcodes = null;
    /**
     * The courierTrackingNumbers
     * @var \WpifyWoo\PacketeraSDK\StructType\CourierTrackingNumbers|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\CourierTrackingNumbers $courierTrackingNumbers = null;
    /**
     * The courierTrackingUrls
     * @var \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrls|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrls $courierTrackingUrls = null;
    /**
     * Constructor method for CourierInfoItem
     * @uses CourierInfoItem::setCourierId()
     * @uses CourierInfoItem::setCourierName()
     * @uses CourierInfoItem::setCourierNumbers()
     * @uses CourierInfoItem::setCourierBarcodes()
     * @uses CourierInfoItem::setCourierTrackingNumbers()
     * @uses CourierInfoItem::setCourierTrackingUrls()
     * @param int $courierId
     * @param string $courierName
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierNumbers $courierNumbers
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierBarcodes $courierBarcodes
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierTrackingNumbers $courierTrackingNumbers
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrls $courierTrackingUrls
     */
    public function __construct(?int $courierId = null, ?string $courierName = null, ?\WpifyWoo\PacketeraSDK\StructType\CourierNumbers $courierNumbers = null, ?\WpifyWoo\PacketeraSDK\StructType\CourierBarcodes $courierBarcodes = null, ?\WpifyWoo\PacketeraSDK\StructType\CourierTrackingNumbers $courierTrackingNumbers = null, ?\WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrls $courierTrackingUrls = null)
    {
        $this
            ->setCourierId($courierId)
            ->setCourierName($courierName)
            ->setCourierNumbers($courierNumbers)
            ->setCourierBarcodes($courierBarcodes)
            ->setCourierTrackingNumbers($courierTrackingNumbers)
            ->setCourierTrackingUrls($courierTrackingUrls);
    }
    /**
     * Get courierId value
     * @return int|null
     */
    public function getCourierId(): ?int
    {
        return $this->courierId;
    }
    /**
     * Set courierId value
     * @param int $courierId
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem
     */
    public function setCourierId(?int $courierId = null): self
    {
        // validation for constraint: int
        if (!is_null($courierId) && !(is_int($courierId) || ctype_digit($courierId))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($courierId, true), gettype($courierId)), __LINE__);
        }
        $this->courierId = $courierId;
        
        return $this;
    }
    /**
     * Get courierName value
     * @return string|null
     */
    public function getCourierName(): ?string
    {
        return $this->courierName;
    }
    /**
     * Set courierName value
     * @param string $courierName
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem
     */
    public function setCourierName(?string $courierName = null): self
    {
        // validation for constraint: string
        if (!is_null($courierName) && !is_string($courierName)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($courierName, true), gettype($courierName)), __LINE__);
        }
        $this->courierName = $courierName;
        
        return $this;
    }
    /**
     * Get courierNumbers value
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierNumbers|null
     */
    public function getCourierNumbers(): ?\WpifyWoo\PacketeraSDK\StructType\CourierNumbers
    {
        return $this->courierNumbers;
    }
    /**
     * Set courierNumbers value
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierNumbers $courierNumbers
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem
     */
    public function setCourierNumbers(?\WpifyWoo\PacketeraSDK\StructType\CourierNumbers $courierNumbers = null): self
    {
        $this->courierNumbers = $courierNumbers;
        
        return $this;
    }
    /**
     * Get courierBarcodes value
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierBarcodes|null
     */
    public function getCourierBarcodes(): ?\WpifyWoo\PacketeraSDK\StructType\CourierBarcodes
    {
        return $this->courierBarcodes;
    }
    /**
     * Set courierBarcodes value
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierBarcodes $courierBarcodes
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem
     */
    public function setCourierBarcodes(?\WpifyWoo\PacketeraSDK\StructType\CourierBarcodes $courierBarcodes = null): self
    {
        $this->courierBarcodes = $courierBarcodes;
        
        return $this;
    }
    /**
     * Get courierTrackingNumbers value
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierTrackingNumbers|null
     */
    public function getCourierTrackingNumbers(): ?\WpifyWoo\PacketeraSDK\StructType\CourierTrackingNumbers
    {
        return $this->courierTrackingNumbers;
    }
    /**
     * Set courierTrackingNumbers value
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierTrackingNumbers $courierTrackingNumbers
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem
     */
    public function setCourierTrackingNumbers(?\WpifyWoo\PacketeraSDK\StructType\CourierTrackingNumbers $courierTrackingNumbers = null): self
    {
        $this->courierTrackingNumbers = $courierTrackingNumbers;
        
        return $this;
    }
    /**
     * Get courierTrackingUrls value
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrls|null
     */
    public function getCourierTrackingUrls(): ?\WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrls
    {
        return $this->courierTrackingUrls;
    }
    /**
     * Set courierTrackingUrls value
     * @param \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrls $courierTrackingUrls
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierInfoItem
     */
    public function setCourierTrackingUrls(?\WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrls $courierTrackingUrls = null): self
    {
        $this->courierTrackingUrls = $courierTrackingUrls;
        
        return $this;
    }
}
