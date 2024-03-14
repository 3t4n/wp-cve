<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketIdsFault StructType
 * Meta information extracted from the WSDL
 * - type: tns:PacketIdsFault
 * @subpackage Structs
 */
class PacketIdsFault extends AbstractStructBase
{
    /**
     * The ids
     * @var \WpifyWoo\PacketeraSDK\StructType\Ids|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\Ids $ids = null;
    /**
     * Constructor method for PacketIdsFault
     * @uses PacketIdsFault::setIds()
     * @param \WpifyWoo\PacketeraSDK\StructType\Ids $ids
     */
    public function __construct(?\WpifyWoo\PacketeraSDK\StructType\Ids $ids = null)
    {
        $this
            ->setIds($ids);
    }
    /**
     * Get ids value
     * @return \WpifyWoo\PacketeraSDK\StructType\Ids|null
     */
    public function getIds(): ?\WpifyWoo\PacketeraSDK\StructType\Ids
    {
        return $this->ids;
    }
    /**
     * Set ids value
     * @param \WpifyWoo\PacketeraSDK\StructType\Ids $ids
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdsFault
     */
    public function setIds(?\WpifyWoo\PacketeraSDK\StructType\Ids $ids = null): self
    {
        $this->ids = $ids;
        
        return $this;
    }
}
