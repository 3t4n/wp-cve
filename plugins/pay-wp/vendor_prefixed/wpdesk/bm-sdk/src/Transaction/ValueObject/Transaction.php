<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Transaction\ValueObject;

use WPPayVendor\BlueMedia\Hash\HashableInterface;
use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\BlueMedia\Common\ValueObject\AbstractValueObject;
use WPPayVendor\JMS\Serializer\Annotation\Type;
use WPPayVendor\JMS\Serializer\Annotation\AccessorOrder;
use DateTime;
/**
 * @AccessorOrder("custom",
 *     custom = {
 *      "serviceID",
 *      "orderID",
 *      "amount",
 *      "description",
 *      "gatewayID",
 *      "currency",
 *      "customerEmail",
 *      "customerNRB",
 *      "taxCountry",
 *      "customerIP",
 *      "title",
 *      "receiverName",
 *      "authorizationCode",
 *      "blikUIDKey",
 *      "blikUIDLabel",
 *      "blikAMKey",
 *      "validityTime",
 *      "linkValidityTime",
 *      "receiverNRB",
 *      "receiverAddress",
 *      "remoteID",
 *      "bankHref",
 *      "returnURL",
 *      "screenType",
 *      "defaultRegulationAcceptanceState",
 *      "defaultRegulationAcceptanceID",
 *      "defaultRegulationAcceptanceTime",
 *      "hash"
 * })
 */
class Transaction extends \WPPayVendor\BlueMedia\Common\ValueObject\AbstractValueObject implements \WPPayVendor\BlueMedia\Serializer\SerializableInterface, \WPPayVendor\BlueMedia\Hash\HashableInterface
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
     * Transaction amount.
     *
     * @var string
     * @Type("string")
     */
    protected $amount;
    /**
     * Transaction description.
     *
     * @var string
     * @Type("string")
     */
    protected $description;
    /**
     * Transaction gateway id.
     *
     * @var int
     * @Type("int")
     */
    protected $gatewayID;
    /**
     * @var DateTime
     * @Type("DateTime<'Y-m-d H:i:s'>")
     */
    protected $defaultRegulationAcceptanceTime;
    /**
     * @var string
     * @Type("string")
     */
    protected $defaultRegulationAcceptanceState;
    /**
     * @var string
     * @Type("string")
     */
    protected $defaultRegulationAcceptanceID;
    /**
     * Transaction currency.
     *
     * @var string
     * @Type("string")
     */
    protected $currency;
    /**
     * Transaction customer e-mail address.
     *
     * @var string
     * @Type("string")
     */
    protected $customerEmail;
    /**
     * Customer IP address.
     *
     * @var string
     * @Type("string")
     */
    protected $customerIP;
    /**
     * Transaction title.
     *
     * @var string
     * @Type("string")
     */
    protected $title;
    /**
     * Transaction validity time.
     *
     * @var DateTime
     * @Type("DateTime<'Y-m-d H:i:s'>")
     */
    protected $validityTime;
    /**
     * Transaction link validity time.
     *
     * @var DateTime
     * @Type("DateTime<'Y-m-d H:i:s'>")
     */
    protected $linkValidityTime;
    /**
     * Transaction authorization code.
     *
     * @var string
     * @Type("string")
     */
    protected $authorizationCode;
    /**
     * Screen tpe for payment authorization (only for card payment).
     *
     * @var string
     * @Type("string")
     */
    protected $screenType;
    /**
     * Transaction customer bank account number.
     *
     * @var string
     * @Type("string")
     */
    protected $customerNRB;
    /**
     * Transaction tax country.
     *
     * @var string
     * @Type("string")
     */
    protected $taxCountry;
    /**
     * Transaction receiver name.
     *
     * @var string
     * @Type("string")
     */
    protected $receiverName;
    /**
     * BLIK Alias UID key.
     *
     * @var string
     * @Type("string")
     */
    protected $blikUIDKey;
    /**
     * BLIK Alias UID label.
     *
     * @var string
     * @Type("string")
     */
    protected $blikUIDLabel;
    /**
     * BLIK banks mobile application key.
     *
     * @var string
     * @Type("string")
     */
    protected $blikAMKey;
    /**
     * Receiver bank account number.
     *
     * @var string
     * @Type("string")
     */
    protected $receiverNRB;
    /**
     * Receiver address.
     *
     * @var string
     * @Type("string")
     */
    protected $receiverAddress;
    /**
     * Remote order id.
     *
     * @var string
     * @Type("string")
     */
    protected $remoteID;
    /**
     * Transaction hash.
     *
     * @var string
     * @Type("string")
     */
    protected $hash;
    /**
     * Banks system URL.
     *
     * @var string
     * @Type("string")
     */
    protected $bankHref;
    /**
     * return address.
     *
     * @var string
     * @Type("string")
     */
    protected $returnURL;
    /**
     * @param string $serviceID
     * @return Transaction
     */
    public function setServiceId(string $serviceID) : \WPPayVendor\BlueMedia\Transaction\ValueObject\Transaction
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
     * @param string $hash
     * @return Transaction
     */
    public function setHash(string $hash) : \WPPayVendor\BlueMedia\Transaction\ValueObject\Transaction
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
    public function getReceiverNRB() : string
    {
        return $this->receiverNRB;
    }
    /**
     * @return string
     */
    public function getReceiverName() : string
    {
        return $this->receiverName;
    }
    /**
     * @return string
     */
    public function getReceiverAddress() : string
    {
        return $this->receiverAddress;
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
    public function getTitle() : string
    {
        return $this->title;
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
    public function getBankHref() : string
    {
        return $this->bankHref;
    }
    /**
     * @return string
     */
    public function getReturnURL() : string
    {
        return $this->returnURL;
    }
    /**
     * @return string
     */
    public function getBlikAMKey() : string
    {
        return $this->blikAMKey;
    }
}
