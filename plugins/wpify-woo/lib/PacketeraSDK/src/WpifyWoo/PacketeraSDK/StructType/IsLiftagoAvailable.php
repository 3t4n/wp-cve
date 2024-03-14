<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for IsLiftagoAvailable StructType
 * @subpackage Structs
 */
class IsLiftagoAvailable extends AbstractStructBase
{
    /**
     * The pickupAddress
     * @var \WpifyWoo\PacketeraSDK\StructType\Address|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\Address $pickupAddress = null;
    /**
     * The deliveryAddress
     * @var \WpifyWoo\PacketeraSDK\StructType\Address|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\Address $deliveryAddress = null;
    /**
     * Constructor method for IsLiftagoAvailable
     * @uses IsLiftagoAvailable::setPickupAddress()
     * @uses IsLiftagoAvailable::setDeliveryAddress()
     * @param \WpifyWoo\PacketeraSDK\StructType\Address $pickupAddress
     * @param \WpifyWoo\PacketeraSDK\StructType\Address $deliveryAddress
     */
    public function __construct(?\WpifyWoo\PacketeraSDK\StructType\Address $pickupAddress = null, ?\WpifyWoo\PacketeraSDK\StructType\Address $deliveryAddress = null)
    {
        $this
            ->setPickupAddress($pickupAddress)
            ->setDeliveryAddress($deliveryAddress);
    }
    /**
     * Get pickupAddress value
     * @return \WpifyWoo\PacketeraSDK\StructType\Address|null
     */
    public function getPickupAddress(): ?\WpifyWoo\PacketeraSDK\StructType\Address
    {
        return $this->pickupAddress;
    }
    /**
     * Set pickupAddress value
     * @param \WpifyWoo\PacketeraSDK\StructType\Address $pickupAddress
     * @return \WpifyWoo\PacketeraSDK\StructType\IsLiftagoAvailable
     */
    public function setPickupAddress(?\WpifyWoo\PacketeraSDK\StructType\Address $pickupAddress = null): self
    {
        $this->pickupAddress = $pickupAddress;
        
        return $this;
    }
    /**
     * Get deliveryAddress value
     * @return \WpifyWoo\PacketeraSDK\StructType\Address|null
     */
    public function getDeliveryAddress(): ?\WpifyWoo\PacketeraSDK\StructType\Address
    {
        return $this->deliveryAddress;
    }
    /**
     * Set deliveryAddress value
     * @param \WpifyWoo\PacketeraSDK\StructType\Address $deliveryAddress
     * @return \WpifyWoo\PacketeraSDK\StructType\IsLiftagoAvailable
     */
    public function setDeliveryAddress(?\WpifyWoo\PacketeraSDK\StructType\Address $deliveryAddress = null): self
    {
        $this->deliveryAddress = $deliveryAddress;
        
        return $this;
    }
}
