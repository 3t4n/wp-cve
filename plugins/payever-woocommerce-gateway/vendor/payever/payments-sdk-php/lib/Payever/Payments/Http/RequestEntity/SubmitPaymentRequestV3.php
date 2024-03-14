<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  RequestEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\RequestEntity;

use Payever\Sdk\Payments\Http\MessageEntity\CartItemEntity;
use Payever\Sdk\Payments\Http\MessageEntity\ChannelEntity;
use Payever\Sdk\Payments\Http\MessageEntity\CompanyEntity;
use Payever\Sdk\Payments\Http\MessageEntity\CustomerAddressV3Entity;
use Payever\Sdk\Payments\Http\MessageEntity\CustomerEntity;
use Payever\Sdk\Payments\Http\MessageEntity\OptionsEntity;
use Payever\Sdk\Payments\Http\MessageEntity\PaymentDataEntity;
use Payever\Sdk\Payments\Http\MessageEntity\PurchaseEntity;
use Payever\Sdk\Payments\Http\MessageEntity\SellerEntity;
use Payever\Sdk\Payments\Http\MessageEntity\ShippingOptionEntity;
use Payever\Sdk\Payments\Http\MessageEntity\SplitItemEntity;
use Payever\Sdk\Payments\Http\MessageEntity\UrlsEntity;
use Payever\Sdk\Payments\Http\MessageEntity\VerifyEntity;

/**
 * This class represents Create Payment RequestInterface Entity
 *
 * @method ChannelEntity          getChannel()
 * @method PurchaseEntity         getPurchase()
 * @method CustomerEntity         getCustomer()
 * @method CompanyEntity          getCompany()
 * @method CustomerAddressV3Entity   getBillingAddress()
 * @method CustomerAddressV3Entity   getShippingAddress()
 * @method ShippingOptionEntity   getShippingOption()
 * @method CartItemEntity[]       getCart()
 * @method SplitItemEntity[]      getSplits()
 * @method UrlsEntity             getUrls()
 * @method OptionsEntity          getOptions()
 * @method VerifyEntity           getVerify()
 * @method SellerEntity           getSeller()
 * @method string                 getReference()
 * @method string                 getReferenceExtra()
 * @method string|null            getPaymentVariantId()
 * @method string|null            getPaymentMethod()
 * @method array                  getPaymentMethods()
 * @method string                 getLocale()
 * @method string                 getXFrameHost()
 * @method string                 getPluginVersion()
 * @method string                 getClientIp()
 * @method \DateTime|null         getExpiresAt()
 * @method PaymentDataEntity|null getPaymentData()
 * @method self                   setReference(string $id)
 * @method self                   setReferenceExtra(string $id)
 * @method self                   setPaymentVariantId(string|null $variantId)
 * @method self                   setPaymentMethod(string $paymentMethod)
 * @method self                   setPaymentMethods(array $paymentMethods)
 * @method self                   setLocale(string $locale)
 * @method self                   setXFrameHost(string $host)
 * @method self                   setPluginVersion(string $version)
 * @method self                   setClientIp(string $ip)
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class SubmitPaymentRequestV3 extends CreatePaymentV3Request
{
    /** @var PaymentDataEntity $paymentData */
    protected $paymentData;

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        if (!$this->paymentData instanceof PaymentDataEntity) {
            return false;
        }

        return parent::isValid();
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
}
