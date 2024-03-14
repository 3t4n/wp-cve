<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  Payments
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments;

use Payever\Sdk\Core\Authorization\OauthToken;
use Payever\Sdk\Core\CommonApiClient;
use Payever\Sdk\Core\Http\RequestBuilder;
use Payever\Sdk\Payments\Base\PaymentsApiClientInterface;
use Payever\Sdk\Payments\Http\RequestEntity\AuthorizePaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\CancelPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\ClaimPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\InvoicePaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\CreatePaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\CreatePaymentV2Request;
use Payever\Sdk\Payments\Http\RequestEntity\CreatePaymentV3Request;
use Payever\Sdk\Payments\Http\RequestEntity\ListPaymentsRequest;
use Payever\Sdk\Payments\Http\RequestEntity\RefundPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\ShippingGoodsPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\RefundItemsPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\CancelItemsPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\SubmitPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\SubmitPaymentRequestV3;
use Payever\Sdk\Payments\Http\RequestEntity\CompanySearchRequest;
use Payever\Sdk\Payments\Http\ResponseEntity\AuthorizePaymentResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\CancelPaymentResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\ClaimPaymentResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\InvoicePaymentResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\CollectPaymentsResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\CreatePaymentResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\GetTransactionResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\LatePaymentsResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\ListPaymentOptionsResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\ListPaymentOptionsWithVariantsResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\ListPaymentsResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\RefundPaymentResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\RemindPaymentResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\RetrieveApiCallResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\RetrievePaymentResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\SubmitPaymentResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\ShippingGoodsPaymentResponse;
use Payever\Sdk\Payments\Http\ResponseEntity\CompanySearchResponse;

/**
 * Class represents Payever Payments API Connector
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class PaymentsApiClient extends CommonApiClient implements PaymentsApiClientInterface
{
    const SUB_URL_CREATE_PAYMENT = 'api/payment';
    const SUB_URL_CREATE_PAYMENT_V2 = 'api/v2/payment';
    const SUB_URL_CREATE_PAYMENT_V3 = 'api/v3/payment';
    const SUB_URL_CREATE_PAYMENT_SUBMIT = 'api/payment/submit';
    const SUB_URL_CREATE_PAYMENT_SUBMIT_V3 = 'api/v3/payment/submit';
    const SUB_URL_RETRIEVE_PAYMENT = 'api/payment/%s';
    const SUB_URL_LIST_PAYMENTS = 'api/payment';
    const SUB_URL_REFUND_PAYMENT = 'api/payment/refund/%s';
    const SUB_URL_AUTHORIZE_PAYMENT = 'api/payment/authorize/%s';
    const SUB_URL_REMIND_PAYMENT = 'api/payment/remind/%s';
    const SUB_URL_COLLECT_PAYMENTS = 'api/payment/collect/%s';
    const SUB_URL_LATE_PAYMENTS = 'api/payment/late-payment/%s';
    const SUB_URL_SHIPPING_GOODS_PAYMENT = 'api/payment/shipping-goods/%s';
    const SUB_URL_CANCEL_PAYMENT = 'api/payment/cancel/%s';
    const SUB_URL_CLAIM_PAYMENT = 'api/payment/claim/%s';
    const SUB_URL_INVOICE_PAYMENT = 'api/payment/invoice/%s';
    const SUB_URL_RETRIEVE_API_CALL = 'api/%s';
    const SUB_URL_LIST_PAYMENT_OPTIONS = 'api/shop/oauth/%s/payment-options/%s';
    const SUB_URL_LIST_PAYMENT_OPTIONS_VARIANTS = 'api/shop/oauth/%s/payment-options/variants/%s';
    const SUB_URL_TRANSACTION = 'api/rest/v1/transactions/%s';

    const SUB_URL_COMPANY_SEARCH = 'api/b2b/search';

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function createPaymentRequest(CreatePaymentRequest $createPaymentRequest)
    {
        $this->configuration->assertLoaded();

        if (!$createPaymentRequest->getChannel()) {
            $createPaymentRequest->setChannel(
                $this->configuration->getChannelSet()
            );
        }

        $request = RequestBuilder::post($this->getCreatePaymentURL())
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_CREATE_PAYMENT)->getAuthorizationString()
            )
            ->setRequestEntity($createPaymentRequest)
            ->setResponseEntity(new CreatePaymentResponse())
            ->build();

        return $this->executeRequest($request, OauthToken::SCOPE_CREATE_PAYMENT);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function createPaymentV2Request(CreatePaymentV2Request $createPaymentRequest)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::post($this->getCreatePaymentV2URL())
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_CREATE_PAYMENT)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($createPaymentRequest)
            ->setResponseEntity(new CreatePaymentResponse())
            ->build();

        return $this->executeRequest($request, OauthToken::SCOPE_CREATE_PAYMENT);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function createPaymentV3Request(CreatePaymentV3Request $createPaymentRequest)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::post($this->getCreatePaymentV3URL())
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_CREATE_PAYMENT)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($createPaymentRequest)
            ->setResponseEntity(new CreatePaymentResponse())
            ->build();

        return $this->executeRequest($request, OauthToken::SCOPE_CREATE_PAYMENT);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function submitPaymentRequest(SubmitPaymentRequest $submitPaymentRequest)
    {
        $this->configuration->assertLoaded();

        if (!$submitPaymentRequest->getChannel()) {
            $submitPaymentRequest->setChannel(
                $this->configuration->getChannelSet()
            );
        }

        $request = RequestBuilder::post($this->getSubmitPaymentURL())
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_CREATE_PAYMENT)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($submitPaymentRequest)
            ->setResponseEntity(new RetrievePaymentResponse())
            ->build();

        return $this->executeRequest($request, OauthToken::SCOPE_CREATE_PAYMENT);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function submitPaymentRequestV3(SubmitPaymentRequestV3 $submitPaymentRequest)
    {
        $this->configuration->assertLoaded();

        if (!$submitPaymentRequest->getChannel()) {
            $submitPaymentRequest->setChannel(
                $this->configuration->getChannelSet()
            );
        }

        $request = RequestBuilder::post($this->getSubmitPaymentV3URL())
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_CREATE_PAYMENT)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($submitPaymentRequest)
            ->setResponseEntity(new SubmitPaymentResponse())
            ->build();

        return $this->executeRequest($request, OauthToken::SCOPE_CREATE_PAYMENT);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function searchCompany(CompanySearchRequest $companySearchRequest)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::post($this->getCompanySearchURL())
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_CREATE_PAYMENT)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($companySearchRequest)
            ->setResponseEntity(new CompanySearchResponse())
            ->build();

        return $this->executeRequest($request, OauthToken::SCOPE_CREATE_PAYMENT);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function retrievePaymentRequest($paymentId)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::get($this->getRetrievePaymentURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_INFO)->getAuthorizationString()
            )
            ->setResponseEntity(new RetrievePaymentResponse())
            ->build();

        return $this->executeRequest($request, OauthToken::SCOPE_PAYMENT_INFO);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function listPaymentsRequest(ListPaymentsRequest $listPaymentsRequest)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::get($this->getListPaymentsURL())
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->setRequestEntity($listPaymentsRequest)
            ->setResponseEntity(new ListPaymentsResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function refundPaymentRequest($paymentId, $amount, $uniqueIdentifier = null)
    {
        $this->configuration->assertLoaded();
        $refundPaymentRequest = new RefundPaymentRequest(['amount' => $amount]);

        $request = RequestBuilder::post($this->getRefundPaymentURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($refundPaymentRequest)
            ->setResponseEntity(new RefundPaymentResponse())
            ->addIdempotencyHeader($uniqueIdentifier)
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function refundItemsPaymentRequest($paymentId, $items, $deliveryFee = null, $uniqueIdentifier = null)
    {
        $this->configuration->assertLoaded();

        $refundPaymentRequest = new RefundItemsPaymentRequest();
        $refundPaymentRequest->setPaymentItems($items);

        if ($deliveryFee) {
            $refundPaymentRequest->setDeliveryFee($deliveryFee);
        }
        $request = RequestBuilder::post($this->getRefundPaymentURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($refundPaymentRequest)
            ->setResponseEntity(new RefundPaymentResponse())
            ->addIdempotencyHeader($uniqueIdentifier)
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function authorizePaymentRequest($paymentId, AuthorizePaymentRequest $paymentRequest = null)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::post($this->getAuthorizePaymentURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->setRequestEntity($paymentRequest ?: new AuthorizePaymentRequest())
            ->setResponseEntity(new AuthorizePaymentResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     *
     * @deprecated This request is only available for Santander DE Invoice and not used anywhere
     */
    public function remindPaymentRequest($paymentId)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::post($this->getRemindPaymentURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->setResponseEntity(new RemindPaymentResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     *
     * @deprecated This request is only available for Santander DE Invoice and not used anywhere
     */
    public function collectPaymentsRequest($paymentId)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::post($this->getCollectPaymentsURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->setResponseEntity(new CollectPaymentsResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     *
     * @deprecated This request is only available for Santander DE Invoice and not used anywhere
     */
    public function latePaymentsRequest($paymentId)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::post($this->getLatePaymentsURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->setResponseEntity(new LatePaymentsResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function shippingGoodsPaymentRequest(
        $paymentId,
        ShippingGoodsPaymentRequest $paymentRequest = null,
        $uniqueIdentifier = null
    ) {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::post($this->getShippingGoodsPaymentURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($paymentRequest ?: new ShippingGoodsPaymentRequest())
            ->setResponseEntity(new ShippingGoodsPaymentResponse())
            ->addIdempotencyHeader($uniqueIdentifier)
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function cancelPaymentRequest($paymentId, $amount = null, $uniqueIdentifier = null)
    {
        $this->configuration->assertLoaded();
        $cancelRequest = new CancelPaymentRequest(['amount' => $amount]);

        $request = RequestBuilder::post($this->getCancelPaymentURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($cancelRequest)
            ->setResponseEntity(new CancelPaymentResponse())
            ->addIdempotencyHeader($uniqueIdentifier)
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function cancelItemsPaymentRequest($paymentId, $items, $deliveryFee = null, $uniqueIdentifier = null)
    {
        $this->configuration->assertLoaded();

        $cancelPaymentRequest = new CancelItemsPaymentRequest();
        $cancelPaymentRequest->setPaymentItems($items);

        if ($deliveryFee) {
            $cancelPaymentRequest->setDeliveryFee($deliveryFee);
        }

        $request = RequestBuilder::post($this->getCancelPaymentURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($cancelPaymentRequest)
            ->setResponseEntity(new CancelPaymentResponse())
            ->addIdempotencyHeader($uniqueIdentifier)
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function invoicePaymentRequest($paymentId, $amount = null, $uniqueIdentifier = null)
    {
        $this->configuration->assertLoaded();

        $params = ['amount' => $amount];
        $request = RequestBuilder::post($this->getInvoicePaymentURL($paymentId))
            ->setParams($params)
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity(new InvoicePaymentRequest())
            ->setResponseEntity(new InvoicePaymentResponse())
            ->addIdempotencyHeader($uniqueIdentifier)
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function claimPaymentRequest($paymentId, ClaimPaymentRequest $paymentRequest = null)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::post($this->getClaimPaymentURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->contentTypeIsJson()
            ->setRequestEntity($paymentRequest ?: new ClaimPaymentRequest())
            ->setResponseEntity(new ClaimPaymentResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function retrieveApiCallRequest($callId)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::get($this->getRetrieveApiCallURL($callId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->setResponseEntity(new RetrieveApiCallResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function listPaymentOptionsRequest($params = [], $businessUuid = '', $channel = '')
    {
        $businessUuid = $businessUuid ?: $this->getConfiguration()->getBusinessUuid();
        $channel = $channel ?: $this->getConfiguration()->getChannelSet();

        $request = RequestBuilder::get($this->getListPaymentOptionsURL($businessUuid, $channel, $params))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_INFO)->getAuthorizationString()
            )
            ->setResponseEntity(new ListPaymentOptionsResponse())
            ->build();

        return $this->executeRequest($request, OauthToken::SCOPE_PAYMENT_INFO);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function listPaymentOptionsWithVariantsRequest($params = [], $businessUuid = '', $channel = '')
    {
        $businessUuid = $businessUuid ?: $this->getConfiguration()->getBusinessUuid();
        $channel = $channel ?: $this->getConfiguration()->getChannelSet();

        $request = RequestBuilder::get($this->getListPaymentOptionsVariantsURL($businessUuid, $channel, $params))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_INFO)->getAuthorizationString()
            )
            ->setResponseEntity(new ListPaymentOptionsWithVariantsResponse())
            ->build();

        return $this->executeRequest($request, OauthToken::SCOPE_PAYMENT_INFO);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function getTransactionRequest($paymentId)
    {
        $this->configuration->assertLoaded();

        $request = RequestBuilder::get($this->getTransactionURL($paymentId))
            ->addRawHeader(
                $this->getToken(OauthToken::SCOPE_PAYMENT_ACTIONS)->getAuthorizationString()
            )
            ->setResponseEntity(new GetTransactionResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * Returns URL for Create Payment path
     *
     * @return string
     */
    protected function getCreatePaymentURL()
    {
        return $this->getBaseUrl() . self::SUB_URL_CREATE_PAYMENT;
    }

    /**
     * Returns URL for Create Payment path
     *
     * @return string
     */
    protected function getCreatePaymentV2URL()
    {
        return $this->getBaseUrl() . self::SUB_URL_CREATE_PAYMENT_V2;
    }

    /**
     * Returns URL for Create Payment path
     *
     * @return string
     */
    protected function getCreatePaymentV3URL()
    {
        return $this->getBaseUrl() . self::SUB_URL_CREATE_PAYMENT_V3;
    }

    /**
     * Returns URL for Submit Payment path
     *
     * @return string
     */
    protected function getSubmitPaymentURL()
    {
        return $this->getBaseUrl() . self::SUB_URL_CREATE_PAYMENT_SUBMIT;
    }

    /**
     * Returns URL for Submit Payment path V3
     *
     * @return string
     */
    protected function getSubmitPaymentV3URL()
    {
        return $this->getBaseUrl() . self::SUB_URL_CREATE_PAYMENT_SUBMIT_V3;
    }

    /**
     * Returns URL for B2B Company Search
     *
     * @return string
     */
    protected function getCompanySearchURL()
    {
        return $this->getBaseUrl() . self::SUB_URL_COMPANY_SEARCH;
    }

    /**
     * Returns URL for Retrieve Payment path
     *
     * @param string $paymentId
     *
     * @return string
     */
    protected function getRetrievePaymentURL($paymentId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_RETRIEVE_PAYMENT, $paymentId);
    }

    /**
     * Returns URL for List Payments path
     *
     * @return string
     */
    protected function getListPaymentsURL()
    {
        return $this->getBaseUrl() . self::SUB_URL_LIST_PAYMENTS;
    }

    /**
     * Returns URL for Refund Payment path
     *
     * @param string $paymentId
     *
     * @return string
     */
    protected function getRefundPaymentURL($paymentId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_REFUND_PAYMENT, $paymentId);
    }

    /**
     * Returns URL for Authorize Payment path
     *
     * @param string $paymentId
     *
     * @return string
     */
    protected function getAuthorizePaymentURL($paymentId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_AUTHORIZE_PAYMENT, $paymentId);
    }

    /**
     * Returns URL for Remind Payment path
     *
     * @param string $paymentId
     *
     * @return string
     */
    protected function getRemindPaymentURL($paymentId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_REMIND_PAYMENT, $paymentId);
    }

    /**
     * Returns URL for Collect Payment path
     *
     * @param string $paymentId
     *
     * @return string
     */
    protected function getCollectPaymentsURL($paymentId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_COLLECT_PAYMENTS, $paymentId);
    }

    /**
     * Returns URL for Late Payments path
     *
     * @param string $paymentId
     *
     * @return string
     */
    protected function getLatePaymentsURL($paymentId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_LATE_PAYMENTS, $paymentId);
    }

    /**
     * Returns URL for Shipping Goods Payment path
     *
     * @param string $paymentId
     *
     * @return string
     */
    protected function getShippingGoodsPaymentURL($paymentId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_SHIPPING_GOODS_PAYMENT, $paymentId);
    }

    /**
     * Returns URL for Cancel Payment path
     *
     * @param string $paymentId
     *
     * @return string
     */
    protected function getCancelPaymentURL($paymentId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_CANCEL_PAYMENT, $paymentId);
    }

    /**
     * Returns URL for Claim Payment path
     *
     * @param string $paymentId
     *
     * @return string
     */
    protected function getClaimPaymentURL($paymentId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_CLAIM_PAYMENT, $paymentId);
    }

    /**
     * Returns URL for Invoice Payment path
     *
     * @param string $paymentId
     *
     * @return string
     */
    protected function getInvoicePaymentURL($paymentId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_INVOICE_PAYMENT, $paymentId);
    }

    /**
     * Returns URL for Retrieve API Call path
     *
     * @param string $callId
     *
     * @return string
     */
    protected function getRetrieveApiCallURL($callId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_RETRIEVE_API_CALL, $callId);
    }

    /**
     * Returns URL for Available Payment Options path
     *
     * @param string $businessUuid
     * @param string $channel
     * @param array $params
     *
     * @return string
     */
    protected function getListPaymentOptionsURL($businessUuid, $channel, $params = [])
    {
        return $this->getBaseUrl()
            . sprintf(self::SUB_URL_LIST_PAYMENT_OPTIONS, $businessUuid, $channel)
            . (empty($params) ? '' : '?' . http_build_query($params));
    }

    /**
     * Returns URL for Available Payment Options request
     *
     * @param string $businessUuid
     * @param string $channel
     * @param array $params
     *
     * @return string
     */
    protected function getListPaymentOptionsVariantsURL($businessUuid, $channel, $params = [])
    {
        return $this->getBaseUrl()
            . sprintf(self::SUB_URL_LIST_PAYMENT_OPTIONS_VARIANTS, $businessUuid, $channel)
            . (empty($params) ? '' : '?' . http_build_query($params));
    }

    /**
     * Returns URL to Transaction path
     *
     * @param int $paymentId
     *
     * @return string
     */
    protected function getTransactionURL($paymentId)
    {
        return $this->getBaseUrl() . sprintf(self::SUB_URL_TRANSACTION, $paymentId);
    }

    /**
     * Returns Base URL to payever Payments API
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->getBaseEntrypoint();
    }
}
