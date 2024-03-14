<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Itn\ValueObject;

use WPPayVendor\BlueMedia\Common\ValueObject\AbstractValueObject;
use WPPayVendor\BlueMedia\Hash\HashableInterface;
use WPPayVendor\JMS\Serializer\Annotation\Type;
use WPPayVendor\JMS\Serializer\Annotation\AccessorOrder;
use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
/**
 * @AccessorOrder("custom",
 *     custom = {
 *      "serviceID",
 *      "orderID",
 *      "remoteID",
 *      "amount",
 *      "currency",
 *      "gatewayID",
 *      "paymentDate",
 *      "paymentStatus",
 *      "paymentStatusDetails",
 *      "hash"
 * })
 */
final class Itn extends \WPPayVendor\BlueMedia\Common\ValueObject\AbstractValueObject implements \WPPayVendor\BlueMedia\Serializer\SerializableInterface, \WPPayVendor\BlueMedia\Hash\HashableInterface
{
    /**
     * Transaction service id.
     *
     * @var string
     * @Type("string")
     */
    protected $serviceID;
    /**
     * Transaction order id.
     *
     * @var string
     * @Type("string")
     */
    protected $orderID;
    /**
     * Remote order id.
     *
     * @var string
     * @Type("string")
     */
    protected $remoteID;
    /**
     * Transaction amount.
     *
     * @var string
     * @Type("string")
     */
    protected $amount;
    /**
     * Transaction currency.
     *
     * @var string
     * @Type("string")
     */
    protected $currency;
    /**
     * Transaction gateway id.
     *
     * @var int
     * @Type("int")
     */
    protected $gatewayID;
    /**
     * Payment date. YYYYMMDDhhmmss
     *
     * @var string
     * @Type("string")
     */
    protected $paymentDate;
    /**
     * Payment status.
     *
     * @var string
     * @Type("string")
     */
    protected $paymentStatus;
    /**
     * Payment status details.
     *
     * @var string
     * @Type("string")
     */
    protected $paymentStatusDetails;
    /**
     * Itn hash.
     *
     * @var string
     * @Type("string")
     */
    protected $hash;
    /**
     * @param string $serviceID
     * @return Itn
     */
    public function setServiceID(string $serviceID) : \WPPayVendor\BlueMedia\Itn\ValueObject\Itn
    {
        $this->serviceID = $serviceID;
        return $this;
    }
    /**
     * @param string $hash
     * @return Itn
     */
    public function setHash(string $hash) : \WPPayVendor\BlueMedia\Itn\ValueObject\Itn
    {
        $this->hash = $hash;
        return $this;
    }
    /**
     * @return string
     */
    public function getHash() : string
    {
        return \trim($this->hash);
    }
    public function isHashPresent() : bool
    {
        return $this->hash !== null;
    }
    /**
     * @return string
     */
    public function getOrderID() : string
    {
        return $this->orderID;
    }
    /**
     * @return string
     */
    public function getRemoteID() : string
    {
        return $this->remoteID;
    }
    /**
     * @return string
     */
    public function getAmount() : string
    {
        return $this->amount;
    }
    /**
     * @return string
     */
    public function getCurrency() : string
    {
        return $this->currency;
    }
    /**
     * @return string
     */
    public function getPaymentDate() : string
    {
        return $this->paymentDate;
    }
    /**
     * @return string
     */
    public function getPaymentStatus() : string
    {
        return $this->paymentStatus;
    }
    /**
     * @return string
     */
    public function getPaymentStatusDetails() : ?string
    {
        return $this->paymentStatusDetails;
    }
}
