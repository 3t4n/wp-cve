<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for getConsignmentPasswordResult StructType
 * @subpackage Structs
 */
class GetConsignmentPasswordResult extends AbstractStructBase
{
    /**
     * The packetConsignerDetail
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var \WpifyWoo\PacketeraSDK\StructType\PacketConsignerDetail|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\PacketConsignerDetail $packetConsignerDetail = null;
    /**
     * The fault
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $fault = null;
    /**
     * The IncorrectApiPasswordFault
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\IncorrectApiPasswordFault|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\IncorrectApiPasswordFault $IncorrectApiPasswordFault = null;
    /**
     * The PacketIdFault
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\PacketIdFault|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\PacketIdFault $PacketIdFault = null;
    /**
     * The AccessDeniedFault
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\AccessDeniedFault|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\AccessDeniedFault $AccessDeniedFault = null;
    /**
     * The PacketAttributesFault
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault $PacketAttributesFault = null;
    /**
     * Constructor method for getConsignmentPasswordResult
     * @uses GetConsignmentPasswordResult::setPacketConsignerDetail()
     * @uses GetConsignmentPasswordResult::setFault()
     * @uses GetConsignmentPasswordResult::setIncorrectApiPasswordFault()
     * @uses GetConsignmentPasswordResult::setPacketIdFault()
     * @uses GetConsignmentPasswordResult::setAccessDeniedFault()
     * @uses GetConsignmentPasswordResult::setPacketAttributesFault()
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketConsignerDetail $packetConsignerDetail
     * @param string $fault
     * @param \WpifyWoo\PacketeraSDK\StructType\IncorrectApiPasswordFault $incorrectApiPasswordFault
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIdFault $packetIdFault
     * @param \WpifyWoo\PacketeraSDK\StructType\AccessDeniedFault $accessDeniedFault
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault $packetAttributesFault
     */
    public function __construct(?\WpifyWoo\PacketeraSDK\StructType\PacketConsignerDetail $packetConsignerDetail = null, ?string $fault = null, ?\WpifyWoo\PacketeraSDK\StructType\IncorrectApiPasswordFault $incorrectApiPasswordFault = null, ?\WpifyWoo\PacketeraSDK\StructType\PacketIdFault $packetIdFault = null, ?\WpifyWoo\PacketeraSDK\StructType\AccessDeniedFault $accessDeniedFault = null, ?\WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault $packetAttributesFault = null)
    {
        $this
            ->setPacketConsignerDetail($packetConsignerDetail)
            ->setFault($fault)
            ->setIncorrectApiPasswordFault($incorrectApiPasswordFault)
            ->setPacketIdFault($packetIdFault)
            ->setAccessDeniedFault($accessDeniedFault)
            ->setPacketAttributesFault($packetAttributesFault);
    }
    /**
     * Get packetConsignerDetail value
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketConsignerDetail|null
     */
    public function getPacketConsignerDetail(): ?\WpifyWoo\PacketeraSDK\StructType\PacketConsignerDetail
    {
        return $this->packetConsignerDetail;
    }
    /**
     * Set packetConsignerDetail value
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketConsignerDetail $packetConsignerDetail
     * @return \WpifyWoo\PacketeraSDK\StructType\GetConsignmentPasswordResult
     */
    public function setPacketConsignerDetail(?\WpifyWoo\PacketeraSDK\StructType\PacketConsignerDetail $packetConsignerDetail = null): self
    {
        $this->packetConsignerDetail = $packetConsignerDetail;
        
        return $this;
    }
    /**
     * Get fault value
     * @return string|null
     */
    public function getFault(): ?string
    {
        return $this->fault;
    }
    /**
     * Set fault value
     * @param string $fault
     * @return \WpifyWoo\PacketeraSDK\StructType\GetConsignmentPasswordResult
     */
    public function setFault(?string $fault = null): self
    {
        // validation for constraint: string
        if (!is_null($fault) && !is_string($fault)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($fault, true), gettype($fault)), __LINE__);
        }
        $this->fault = $fault;
        
        return $this;
    }
    /**
     * Get IncorrectApiPasswordFault value
     * @return \WpifyWoo\PacketeraSDK\StructType\IncorrectApiPasswordFault|null
     */
    public function getIncorrectApiPasswordFault(): ?\WpifyWoo\PacketeraSDK\StructType\IncorrectApiPasswordFault
    {
        return $this->IncorrectApiPasswordFault;
    }
    /**
     * Set IncorrectApiPasswordFault value
     * @param \WpifyWoo\PacketeraSDK\StructType\IncorrectApiPasswordFault $incorrectApiPasswordFault
     * @return \WpifyWoo\PacketeraSDK\StructType\GetConsignmentPasswordResult
     */
    public function setIncorrectApiPasswordFault(?\WpifyWoo\PacketeraSDK\StructType\IncorrectApiPasswordFault $incorrectApiPasswordFault = null): self
    {
        $this->IncorrectApiPasswordFault = $incorrectApiPasswordFault;
        
        return $this;
    }
    /**
     * Get PacketIdFault value
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdFault|null
     */
    public function getPacketIdFault(): ?\WpifyWoo\PacketeraSDK\StructType\PacketIdFault
    {
        return $this->PacketIdFault;
    }
    /**
     * Set PacketIdFault value
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketIdFault $packetIdFault
     * @return \WpifyWoo\PacketeraSDK\StructType\GetConsignmentPasswordResult
     */
    public function setPacketIdFault(?\WpifyWoo\PacketeraSDK\StructType\PacketIdFault $packetIdFault = null): self
    {
        $this->PacketIdFault = $packetIdFault;
        
        return $this;
    }
    /**
     * Get AccessDeniedFault value
     * @return \WpifyWoo\PacketeraSDK\StructType\AccessDeniedFault|null
     */
    public function getAccessDeniedFault(): ?\WpifyWoo\PacketeraSDK\StructType\AccessDeniedFault
    {
        return $this->AccessDeniedFault;
    }
    /**
     * Set AccessDeniedFault value
     * @param \WpifyWoo\PacketeraSDK\StructType\AccessDeniedFault $accessDeniedFault
     * @return \WpifyWoo\PacketeraSDK\StructType\GetConsignmentPasswordResult
     */
    public function setAccessDeniedFault(?\WpifyWoo\PacketeraSDK\StructType\AccessDeniedFault $accessDeniedFault = null): self
    {
        $this->AccessDeniedFault = $accessDeniedFault;
        
        return $this;
    }
    /**
     * Get PacketAttributesFault value
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault|null
     */
    public function getPacketAttributesFault(): ?\WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault
    {
        return $this->PacketAttributesFault;
    }
    /**
     * Set PacketAttributesFault value
     * @param \WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault $packetAttributesFault
     * @return \WpifyWoo\PacketeraSDK\StructType\GetConsignmentPasswordResult
     */
    public function setPacketAttributesFault(?\WpifyWoo\PacketeraSDK\StructType\PacketAttributesFault $packetAttributesFault = null): self
    {
        $this->PacketAttributesFault = $packetAttributesFault;
        
        return $this;
    }
}
