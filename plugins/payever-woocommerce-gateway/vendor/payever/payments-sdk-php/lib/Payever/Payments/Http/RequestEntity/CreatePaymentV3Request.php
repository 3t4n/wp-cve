<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  RequestEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2021 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\RequestEntity;

use Payever\Sdk\Core\Http\RequestEntity;
use Payever\Sdk\Payments\Http\MessageEntity\AttributesEntity;
use Payever\Sdk\Payments\Http\MessageEntity\CustomerAddressV3Entity;
use Payever\Sdk\Payments\Http\MessageEntity\CompanyEntity;
use Payever\Sdk\Payments\Http\MessageEntity\CustomerEntity;
use Payever\Sdk\Payments\Http\MessageEntity\OptionsEntity;
use Payever\Sdk\Payments\Http\MessageEntity\PurchaseEntity;
use Payever\Sdk\Payments\Http\MessageEntity\SellerEntity;
use Payever\Sdk\Payments\Http\MessageEntity\ShippingOptionEntity;
use Payever\Sdk\Payments\Http\MessageEntity\SplitItemEntity;
use Payever\Sdk\Payments\Http\MessageEntity\CartItemV3Entity;
use Payever\Sdk\Payments\Http\MessageEntity\ChannelEntity;
use Payever\Sdk\Payments\Http\MessageEntity\PaymentDataEntity;
use Payever\Sdk\Payments\Http\MessageEntity\UrlsEntity;
use Payever\Sdk\Payments\Http\MessageEntity\VerifyEntity;

/**
 * This class represents Create Payment RequestInterface Entity
 *
 * @method ChannelEntity           getChannel()
 * @method PurchaseEntity          getPurchase()
 * @method CustomerEntity          getCustomer()
 * @method CompanyEntity           getCompany()
 * @method CustomerAddressV3Entity getBillingAddress()
 * @method CustomerAddressV3Entity getShippingAddress()
 * @method ShippingOptionEntity    getShippingOption()
 * @method CartItemEntity[]        getCart()
 * @method SplitItemEntity[]       getSplits()
 * @method UrlsEntity              getUrls()
 * @method OptionsEntity           getOptions()
 * @method VerifyEntity            getVerify()
 * @method SellerEntity            getSeller()
 * @method AttributesEntity        getAttributes()
 * @method string                  getReference()
 * @method string                  getReferenceExtra()
 * @method string|null             getPaymentVariantId()
 * @method string|null             getPaymentMethod()
 * @method array                   getPaymentMethods()
 * @method string                  getLocale()
 * @method string                  getXFrameHost()
 * @method string                  getPluginVersion()
 * @method string                  getClientIp()
 * @method \DateTime|null          getExpiresAt()
 * @method PaymentDataEntity|null  getPaymentData()
 * @method self                    setReference(string $id)
 * @method self                    setReferenceExtra(string $id)
 * @method self                    setPaymentVariantId(string|null $variantId)
 * @method self                    setPaymentMethod(string $paymentMethod)
 * @method self                    setPaymentMethods(array $paymentMethods)
 * @method self                    setLocale(string $locale)
 * @method self                    setXFrameHost(string $host)
 * @method self                    setPluginVersion(string $version)
 * @method self                    setClientIp(string $ip)
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class CreatePaymentV3Request extends RequestEntity
{
    /**
     * @var ChannelEntity
     */
    protected $channel;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var PurchaseEntity
     */
    protected $purchase;

    /**
     * @var CustomerEntity
     */
    protected $customer;

    /**
     * @var CompanyEntity
     */
    protected $company;

    /**
     * @var ShippingOptionEntity
     */
    protected $shippingOption;

    /**
     * @var CustomerAddressV3Entity
     */
    protected $shippingAddress;

    /**
     * @var CustomerAddressV3Entity
     */
    protected $billingAddress;

    /**
     * @var AttributesEntity
     */
    protected $attributes;

    /**
     * @var UrlsEntity
     */
    protected $urls;

    /**
     * @var VerifyEntity
     */
    protected $verify;

    /**
     * @var SellerEntity
     */
    protected $seller;

    /**
     * @var OptionsEntity
     */
    protected $options;

    /**
     * @var string
     */
    protected $xFrameHost;

    /**
     * @var string
     */
    protected $pluginVersion;

    /**
     * @var string
     */
    protected $clientIp;

    /**
     * @var \DateTime|null
     */
    protected $expiresAt;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string
     */
    protected $referenceExtra;

    /**
     * @var CartItemEntity[]
     */
    protected $cart;

    /**
     * @var SplitItemEntity[]
     */
    protected $splits;

    /**
     * @var PaymentDataEntity
     */
    protected $paymentData;

    /**
     * @var string|null
     */
    protected $paymentMethod;

    /**
     * @var array
     */
    protected $paymentMethods;

    /**
     * @var string
     */
    protected $paymentVariantId;

    /**
     * {@inheritdoc}
     */
    public function getRequired()
    {
        return [
            'channel',
            'reference',
            'purchase',
            'customer',
            'billing_address',
            'urls',
        ];
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function isValid()
    {
        if (is_array($this->cart)) {
            foreach ($this->cart as $item) {
                if (!$item instanceof CartItemV3Entity || !$item->isValid()) {
                    return false;
                }
            }
        }

        return parent::isValid() &&
            is_array($this->cart) &&
            !empty($this->cart) &&
            (!$this->expiresAt || $this->expiresAt instanceof \DateTime);
    }

    /**
     * Sets Purchase
     *
     * @param array|string $purchase
     *
     * @return $this
     */
    public function setPurchase($purchase)
    {
        if (!$purchase) {
            return $this;
        }

        if (is_string($purchase)) {
            $purchase = json_decode($purchase);
        }

        if (!is_array($purchase) && !is_object($purchase)) {
            return $this;
        }

        $this->purchase = new PurchaseEntity($purchase);

        return $this;
    }

    /**
     * Sets Customer
     *
     * @param CustomerEntity|array|string $customer
     *
     * @return $this
     */
    public function setCustomer($customer)
    {
        if (!$customer) {
            return $this;
        }

        if (is_string($customer)) {
            $customer = json_decode($customer);
        }

        if (!is_array($customer) && !is_object($customer)) {
            return $this;
        }

        $this->customer = new CustomerEntity($customer);

        return $this;
    }

    /**
     * Sets Company
     *
     * @param CompanyEntity|array|string $company
     *
     * @return $this
     */
    public function setCompany($company)
    {
        if (!$company) {
            return $this;
        }

        if (is_string($company)) {
            $company = json_decode($company);
        }

        if (!is_array($company) && !is_object($company)) {
            return $this;
        }

        $this->company = new CompanyEntity($company);

        return $this;
    }

    /**
     * Sets Shipping option
     *
     * @param ShippingOptionEntity|array|string $shipping
     *
     * @return $this
     */
    public function setShippingOption($shipping)
    {
        if (!$shipping) {
            return $this;
        }

        if (is_string($shipping)) {
            $shipping = json_decode($shipping);
        }

        if (!is_array($shipping) && !is_object($shipping)) {
            return $this;
        }

        $this->shippingOption = new ShippingOptionEntity($shipping);

        return $this;
    }

    /**
     * Sets Cart
     *
     * @param CartItemV3Entity[]|array|string $cart
     *
     * @return $this
     */
    public function setCart($cart)
    {
        if (!$cart) {
            return $this;
        }

        if (is_string($cart)) {
            $cart = json_decode($cart);
        }

        if (!is_array($cart)) {
            return $this;
        }

        $this->cart = [];

        foreach ($cart as $item) {
            $this->cart[] = new CartItemV3Entity($item);
        }

        return $this;
    }

    /**
     * Sets Splits
     * Routing of accounts and amount to split payment.
     *
     * @param SplitItemEntity[]|array|string $splits
     *
     * @return $this
     */
    public function setSplits($splits)
    {
        if (!$splits) {
            return $this;
        }

        if (is_string($splits)) {
            $splits = json_decode($splits);
        }

        if (!is_array($splits)) {
            return $this;
        }

        $this->splits = [];

        foreach ($splits as $split) {
            $this->splits[] = new SplitItemEntity($split);
        }

        return $this;
    }

    /**
     * Sets shipping address
     *
     * @param CustomerAddressV3Entity|string $shippingAddress
     *
     * @return $this
     */
    public function setShippingAddress($shippingAddress)
    {
        if (!$shippingAddress) {
            return $this;
        }

        if (is_string($shippingAddress)) {
            $shippingAddress = json_decode($shippingAddress);
        }

        if (!is_array($shippingAddress) && !is_object($shippingAddress)) {
            return $this;
        }

        $this->shippingAddress = new CustomerAddressV3Entity($shippingAddress);

        return $this;
    }

    /**
     * Sets billing address
     *
     * @param CustomerAddressV3Entity|string $billingAddress
     *
     * @return $this
     */
    public function setBillingAddress($billingAddress)
    {
        if (!$billingAddress) {
            return $this;
        }

        if (is_string($billingAddress)) {
            $billingAddress = json_decode($billingAddress);
        }

        if (!is_array($billingAddress) && !is_object($billingAddress)) {
            return $this;
        }

        $this->billingAddress = new CustomerAddressV3Entity($billingAddress);

        return $this;
    }

    /**
     * Sets Attributes
     *
     * @param AttributesEntity|array $attributes
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        if (!$attributes) {
            return $this;
        }

        if (is_string($attributes)) {
            $attributes = json_decode($attributes);
        }

        if (!is_array($attributes) && !is_object($attributes)) {
            return $this;
        }

        $this->attributes = new AttributesEntity($attributes);

        return $this;
    }

    /**
     * Sets Urls
     *
     * @param UrlsEntity|array $urls
     *
     * @return $this
     */
    public function setUrls($urls)
    {
        if (!$urls) {
            return $this;
        }

        if (is_string($urls)) {
            $urls = json_decode($urls);
        }

        if (!is_array($urls) && !is_object($urls)) {
            return $this;
        }

        $this->urls = new UrlsEntity($urls);

        return $this;
    }

    /**
     * Sets Verify
     *
     * @param VerifyEntity|array $verify
     *
     * @return $this
     */
    public function setVerify($verify)
    {
        if (!$verify) {
            return $verify;
        }

        if (is_string($verify)) {
            $verify = json_decode($verify);
        }

        if (!is_array($verify) && !is_object($verify)) {
            return $this;
        }

        $this->verify = new VerifyEntity($verify);

        return $this;
    }

    /**
     * Sets Seller
     *
     * @param SellerEntity|array $seller
     *
     * @return $this
     */
    public function setSeller($seller)
    {
        if (!$seller) {
            return $seller;
        }

        if (is_string($seller)) {
            $seller = json_decode($seller);
        }

        if (!is_array($seller) && !is_object($seller)) {
            return $this;
        }

        $this->seller = new SellerEntity($seller);

        return $this;
    }

    /**
     * Sets Options
     *
     * @param OptionsEntity|array $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        if (!$options) {
            return $options;
        }

        if (is_string($options)) {
            $options = json_decode($options);
        }

        if (!is_array($options) && !is_object($options)) {
            return $this;
        }

        $this->options = new OptionsEntity($options);

        return $this;
    }

    /**
     * Sets payment data
     *
     * @param PaymentDataEntity|array|string $paymentData
     *
     * @return $this
     */
    public function setPaymentData($paymentData)
    {
        if (!$paymentData) {
            return $this;
        }

        if ($paymentData instanceof PaymentDataEntity) {
            $this->paymentData = $paymentData;

            return $this;
        }

        if (is_string($paymentData)) {
            $paymentData = json_decode($paymentData, true);
        }

        if (!is_array($paymentData)) {
            return $this;
        }

        $this->paymentData = new PaymentDataEntity($paymentData);

        return $this;
    }

    /**
     * Sets Channel
     *
     * @param ChannelEntity|string $channel
     *
     * @return $this
     */
    public function setChannel($channel)
    {
        if (!$channel) {
            return $this;
        }

        if (is_string($channel)) {
            $channel = json_decode($channel);
        }

        if (!is_array($channel) && !is_object($channel)) {
            return $this;
        }

        $this->channel = new ChannelEntity($channel);

        return $this;
    }

    /**
     * Define specific expire time for a payment, default has no expiration.
     *
     * @param string $expiresAt
     *
     * @return $this
     */
    public function setExpiresAt($expiresAt)
    {
        if ($expiresAt) {
            $this->expiresAt = date_create($expiresAt);
        }

        return $this;
    }
}
