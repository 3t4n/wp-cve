<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for Item StructType
 * @subpackage Structs
 */
class Item extends AbstractStructBase
{
    /**
     * The attributes
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\AttributeCollection|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\AttributeCollection $attributes = null;
    /**
     * Constructor method for Item
     * @uses Item::setAttributes()
     * @param \WpifyWoo\PacketeraSDK\StructType\AttributeCollection $attributes
     */
    public function __construct(?\WpifyWoo\PacketeraSDK\StructType\AttributeCollection $attributes = null)
    {
        $this
            ->setAttributes($attributes);
    }
    /**
     * Get attributes value
     * @return \WpifyWoo\PacketeraSDK\StructType\AttributeCollection|null
     */
    public function getAttributes(): ?\WpifyWoo\PacketeraSDK\StructType\AttributeCollection
    {
        return $this->attributes;
    }
    /**
     * Set attributes value
     * @param \WpifyWoo\PacketeraSDK\StructType\AttributeCollection $attributes
     * @return \WpifyWoo\PacketeraSDK\StructType\Item
     */
    public function setAttributes(?\WpifyWoo\PacketeraSDK\StructType\AttributeCollection $attributes = null): self
    {
        $this->attributes = $attributes;
        
        return $this;
    }
}
