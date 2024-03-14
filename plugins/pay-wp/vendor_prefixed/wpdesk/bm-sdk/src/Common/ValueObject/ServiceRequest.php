<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Common\ValueObject;

use WPPayVendor\JMS\Serializer\Annotation\AccessorOrder;
use WPPayVendor\JMS\Serializer\Annotation\Type;
/**
 * @AccessorOrder("custom",
 *     custom = {
 *      "serviceID",
 *      "messageID",
 *      "hash"
 * })
 */
class ServiceRequest extends \WPPayVendor\BlueMedia\Common\ValueObject\AbstractValueObject
{
    /**
     * @var string
     * @Type("string")
     */
    protected $serviceID;
    /**
     * @var string
     * @Type("string")
     */
    protected $messageID;
    /**
     * @var string
     * @Type("string")
     */
    protected $hash;
    /**
     * @param string $serviceID
     * @return self
     */
    public function setServiceID(string $serviceID) : self
    {
        $this->serviceID = $serviceID;
        return $this;
    }
    /**
     * @return string
     */
    public function getServiceID() : string
    {
        return $this->serviceID;
    }
    /**
     * @return string
     */
    public function getMessageID() : string
    {
        return $this->messageID;
    }
    /**
     * @param string $hash
     * @return self
     */
    public function setHash(string $hash) : self
    {
        $this->hash = $hash;
        return $this;
    }
    /**
     * @return string
     */
    public function getHash() : string
    {
        return $this->hash;
    }
}
