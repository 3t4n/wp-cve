<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketAttributesFault StructType
 * Meta information extracted from the WSDL
 * - type: tns:PacketAttributesFault
 * @subpackage Structs
 */
class PacketAttributesFault extends AbstractStructBase
{
    /**
     * The attributes
     * @var \WpifyWoo\PacketeraSDK\StructType\Attributes|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\Attributes $attributes = null;
    /**
     * Constructor method for PacketAttributesFault
     * @uses PacketAttributesFault::setAttributes()
     * @param \WpifyWoo\PacketeraSDK\StructType\Attributes $attributes
     */
    public function __construct(?\WpifyWoo\PacketeraSDK\StructType\Attributes $attributes = null)
    {
        $this
            ->setAttributes($attributes);
    }
    /**
     * Get attributes value
     * @return \WpifyWoo\PacketeraSDK\StructType\Attributes|null
     */
    public function getAttributes(): ?\WpifyWoo\PacketeraSDK\StructType\Attributes
    {
        return $this->attributes;
    }
    /**
     * Set attributes value
     * @param \WpifyWoo\PacketeraSDK\StructType\Attributes $attributes
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault
     */
    public function setAttributes(?\WpifyWoo\PacketeraSDK\StructType\Attributes $attributes = null): self
    {
        $this->attributes = $attributes;
        
        return $this;
    }
}
