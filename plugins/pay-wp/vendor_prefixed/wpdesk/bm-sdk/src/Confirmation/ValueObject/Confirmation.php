<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Confirmation\ValueObject;

use WPPayVendor\BlueMedia\Hash\HashableInterface;
use WPPayVendor\JMS\Serializer\Annotation\Type;
use WPPayVendor\JMS\Serializer\Annotation\AccessorOrder;
use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\BlueMedia\Common\ValueObject\AbstractValueObject;
/**
 * @AccessorOrder("custom",
 *     custom = {
 *      "serviceID",
 *      "orderID",
 *      "hash"
 * })
 */
class Confirmation extends \WPPayVendor\BlueMedia\Common\ValueObject\AbstractValueObject implements \WPPayVendor\BlueMedia\Serializer\SerializableInterface, \WPPayVendor\BlueMedia\Hash\HashableInterface
{
    /**
     * @var string
     * @Type("string")
     */
    private $ServiceID;
    /**
     * @var string
     * @Type("string")
     */
    private $OrderID;
    /**
     * Transaction hash.
     *
     * @var string
     * @Type("string")
     */
    private $Hash;
    /**
     * @return string
     */
    public function getServiceID() : string
    {
        return $this->ServiceID;
    }
    /**
     * @return string
     */
    public function getOrderID() : string
    {
        return $this->OrderID;
    }
    /**
     * @return string
     */
    public function getHash() : string
    {
        return $this->Hash;
    }
    public function isHashPresent() : bool
    {
        return $this->Hash !== null;
    }
}
