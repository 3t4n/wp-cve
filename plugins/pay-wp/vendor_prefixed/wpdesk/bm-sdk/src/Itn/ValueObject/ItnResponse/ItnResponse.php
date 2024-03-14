<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Itn\ValueObject\ItnResponse;

use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\BlueMedia\Serializer\Serializer;
use WPPayVendor\JMS\Serializer\Annotation\AccessorOrder;
use WPPayVendor\JMS\Serializer\Annotation\XmlRoot;
use WPPayVendor\JMS\Serializer\Annotation\Type;
/**
 * @XmlRoot("confirmationList")
 *
 * @AccessorOrder("custom",
 *     custom = {
 *      "serviceID",
 *      "transactionsConfirmations",
 *      "hash"
 * })
 */
class ItnResponse implements \WPPayVendor\BlueMedia\Serializer\SerializableInterface
{
    /**
     * @var string
     * @Type("string")
     */
    private $serviceID;
    /**
     * @var TransactionsConfirmations
     * @Type("WPPayVendor\BlueMedia\Itn\ValueObject\ItnResponse\TransactionsConfirmations")
     */
    private $transactionsConfirmations;
    /**
     * @var string
     * @Type("string")
     */
    private $hash;
    /**
     * @return string
     */
    public function getServiceID() : string
    {
        return $this->serviceID;
    }
    /**
     * @return TransactionsConfirmations
     */
    public function getTransactionsConfirmations() : \WPPayVendor\BlueMedia\Itn\ValueObject\ItnResponse\TransactionsConfirmations
    {
        return $this->transactionsConfirmations;
    }
    /**
     * @return string
     */
    public function getHash() : string
    {
        return $this->hash;
    }
    public function toXml() : string
    {
        return (new \WPPayVendor\BlueMedia\Serializer\Serializer())->toXml($this);
    }
}
