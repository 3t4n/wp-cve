<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  MessageEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\MessageEntity;

use Payever\Sdk\Core\Helper\StringHelper;
use Payever\Sdk\Core\Http\MessageEntity\ChannelSetEntity;
use Payever\Sdk\Core\Http\MessageEntity\ResultEntity;

/**
 * This class represents Retrieve Payment Result Entity
 *
 * @method string               getId()
 * @method string               getStatus()
 * @method string               getColorState()
 * @method string               getMerchantName()
 * @method string               getCustomerName()
 * @method string               getCustomerEmail()
 * @method string               getPaymentType()
 * @method string               getLastAction()
 * @method \DateTime|false      getCreatedAt()
 * @method \DateTime|false      getUpdatedAt()
 * @method string               getChannel()
 * @method string               getReference()
 * @method float                getAmount()
 * @method string               getCurrency()
 * @method float                getPaymentFee()
 * @method float                getTotal()
 * @method AddressEntity        getAddress()
 * @method AddressEntity        getShippingAddress()
 * @method PaymentDetailsEntity getPaymentDetails()
 * @method array                getPaymentDetailsArray()
 * @method ChannelSetEntity     getChannelSet()
 * @method float                getDownPayment()
 * @method ShippingOptionEntity getShippingOption()
 * @method CartItemEntity[]     getItems()
 * @method self                 setId(string $id)
 * @method self                 setStatus(string $status)
 * @method self                 setColorState(string $colorState)
 * @method self                 setMerchantName(string $merchantName)
 * @method self                 setCustomerName(string $customerName)
 * @method self                 setCustomerEmail(string $customerEmail)
 * @method self                 setPaymentType(string $paymentType)
 * @method self                 setLastAction(string $lastAction)
 * @method self                 setChannel(string $channel)
 * @method self                 setReference(string $reference)
 * @method self                 setAmount(float $amount)
 * @method self                 setDownPayment(float $downPayment)
 * @method self                 setCurrency(string $currency)
 * @method self                 setFee(float $fee)
 * @method self                 setPaymentFee(float $paymentFee)
 * @method self                 setTotal(float $total)
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class RetrievePaymentResultEntity extends ResultEntity
{
    /** @var string $id */
    protected $id;

    /** @var string $status */
    protected $status;

    /** @var string $colorState */
    protected $colorState;

    /** @var string $merchantName */
    protected $merchantName;

    /** @var string $customerName */
    protected $customerName;

    /** @var string $customerName */
    protected $customerEmail;

    /** @var string $paymentType */
    protected $paymentType;

    /** @var string $lastAction */
    protected $lastAction;

    /** @var \DateTime|bool $createdAt */
    protected $createdAt;

    /** @var \DateTime|bool $updatedAt */
    protected $updatedAt;

    /** @var string $channel */
    protected $channel;

    /** @var string $reference */
    protected $reference;

    /** @var float $amount */
    protected $amount;

    /** @var float $downPayment */
    protected $downPayment;

    /** @var string $currency */
    protected $currency;

    /** @var float $fee */
    protected $fee;

    /** @var float $paymentFee */
    protected $paymentFee;

    /** @var float $total */
    protected $total;

    /** @var AddressEntity $address */
    protected $address;

    /** @var AddressEntity $shippingAddress */
    protected $shippingAddress;

    /** @var PaymentDetailsEntity $paymentDetails */
    protected $paymentDetails;

    /** @var array $paymentDetailsArray */
    protected $paymentDetailsArray;

    /** @var ChannelSetEntity */
    protected $channelSet;

    /** @var ShippingOptionEntity $shippingOption */
    protected $shippingOption;

    /** @var CartItemEntity[] */
    protected $items;

    /**
     * Sets Created At
     *
     * @param string $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        if ($createdAt) {
            $this->createdAt = date_create($createdAt);
        }

        return $this;
    }

    /**
     * Sets Updated At
     *
     * @param string $updatedAt
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        if ($updatedAt) {
            $this->updatedAt = date_create($updatedAt);
        }

        return $this;
    }

    /**
     * Sets Address
     *
     * @param array $address
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = new AddressEntity($address);

        return $this;
    }

    /**
     * Sets Shipping Address
     *
     * @param array $shippingAddress
     * @return self
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = new AddressEntity($shippingAddress);

        return $this;
    }

    /**
     * Sets Channel Set
     *
     * @param array $channelSet
     * @return self
     */
    public function setChannelSet($channelSet)
    {
        $this->channelSet = new ChannelSetEntity($channelSet);

        return $this;
    }

    /**
     * Sets Shipping Option
     *
     * @param array $shippingOption
     * @return self
     */
    public function setShippingOption($shippingOption)
    {
        $this->shippingOption = new ShippingOptionEntity($shippingOption);

        return $this;
    }

    /**
     * Sets Items
     *
     * @param array|string $items
     * @return self
     * @throws \Exception
     */
    public function setItems($items)
    {
        if (is_string($items)) {
            $items = StringHelper::jsonDecode($items);
        }

        if ($items && is_array($items)) {
            foreach ($items as $item) {
                $this->items[] = new CartItemEntity($item);
            }
        }

        return $this;
    }

    /**
     * Sets Payment Details
     *
     * @param array $paymentDetails
     * @return self
     */
    public function setPaymentDetails($paymentDetails)
    {
        $this->paymentDetails = new PaymentDetailsEntity($paymentDetails);

        return $this;
    }

    /**
     * @param \stdClass|array $details
     * @return self
     */
    public function setPaymentDetailsArray($details)
    {
        if ($details instanceof \stdClass) {
            $details = (array) $details;
        }
        $this->paymentDetailsArray = $details;

        return $this;
    }

    /**
     * Gets payment fee value
     *
     * @return float
     */
    public function getFee()
    {
        return $this->getPaymentFee();
    }
}
