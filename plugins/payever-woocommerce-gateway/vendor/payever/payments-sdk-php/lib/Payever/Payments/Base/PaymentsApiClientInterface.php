<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  Base
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Base;

use Payever\Sdk\Core\Base\CommonApiClientInterface;
use Payever\Sdk\Core\Base\ResponseInterface;
use Payever\Sdk\Payments\Http\RequestEntity\AuthorizePaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\CompanySearchRequest;
use Payever\Sdk\Payments\Http\RequestEntity\ClaimPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\CreatePaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\CreatePaymentV2Request;
use Payever\Sdk\Payments\Http\RequestEntity\CreatePaymentV3Request;
use Payever\Sdk\Payments\Http\RequestEntity\SubmitPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\SubmitPaymentRequestV3;
use Payever\Sdk\Payments\Http\RequestEntity\ListPaymentsRequest;
use Payever\Sdk\Payments\Http\RequestEntity\ShippingGoodsPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\PaymentItemEntity;

/**
 * Interface represents Payever Payments API Connector
 */
interface PaymentsApiClientInterface extends CommonApiClientInterface
{
    /**
     * Sends a request to create payment
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/create-payment/create-payments Documentation
     *
     * @param CreatePaymentRequest $createPaymentRequest
     *
     * @return ResponseInterface
     */
    public function createPaymentRequest(CreatePaymentRequest $createPaymentRequest);

    /**
     * Sends a request to create payment for v2 version of api
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/create-payment/create-payments Documentation
     *
     * @param CreatePaymentV2Request $createPaymentRequest
     *
     * @return ResponseInterface
     */
    public function createPaymentV2Request(CreatePaymentV2Request $createPaymentRequest);

    /**
     * Sends a request to create payment for v2 version of api
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/create-payment/create-payments Documentation
     *
     * @param CreatePaymentV3Request $createPaymentRequest
     *
     * @return ResponseInterface
     */
    public function createPaymentV3Request(CreatePaymentV3Request $createPaymentRequest);

    /**
     * Sends a request to submit payment
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/create-payment/submit-payments Documentation
     *
     * @param SubmitPaymentRequest $createPaymentRequest
     *
     * @return ResponseInterface
     */
    public function submitPaymentRequest(SubmitPaymentRequest $createPaymentRequest);

    /**
     * Sends a request to submit payment
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/create-payment/submit-payments Documentation
     *
     * @param SubmitPaymentRequestV3 $createPaymentRequest
     *
     * @return ResponseInterface
     */
    public function submitPaymentRequestV3(SubmitPaymentRequestV3 $submitPaymentRequest);

    /**
     * Search company
     *
     * @param CompanySearchRequest $companySearchRequest
     *
     * @return ResponseInterface
     */
    public function searchCompany(CompanySearchRequest $companySearchRequest);

    /**
     * Requests payment details
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/view-payments/retrieve-payment Documentation
     *
     * @param string $paymentId Payment ID
     *
     * @return ResponseInterface
     */
    public function retrievePaymentRequest($paymentId);

    /**
     * Requests payments details
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/view-payments/list-payments Documentation
     *
     * @param ListPaymentsRequest $listPaymentsRequest
     *
     * @return ResponseInterface
     */
    public function listPaymentsRequest(ListPaymentsRequest $listPaymentsRequest);

    /**
     * Sends a request to refund payment
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/order-management/refund Documentation
     *
     * @param string $paymentId Payment ID
     * @param float $amount Specify the refund amount. If no amount is set, the whole amount will be refunded.
     * @param string $uniqueIdentifier Action Identifier
     *
     * @return ResponseInterface
     */
    public function refundPaymentRequest($paymentId, $amount, $uniqueIdentifier = null);

    /**
     * Sends a request to refund payment
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/order-management/refund Documentation
     *
     * @param string $paymentId Payment ID
     * @param PaymentItemEntity[] $items Specify the refund items.
     * @param null|float $deliveryFee Shipping total.
     * @param string $uniqueIdentifier Action Identifier
     *
     * @return ResponseInterface
     */
    public function refundItemsPaymentRequest($paymentId, $items, $deliveryFee = null, $uniqueIdentifier = null);

    /**
     * Sends a request to authorize previously made payment
     *
     * @link https://getpayever.com/developer/api-documentation/#authorize-payment Documentation
     *
     * @param string $paymentId Payment ID
     * @param AuthorizePaymentRequest $paymentRequest
     *
     * @return ResponseInterface
     */
    public function authorizePaymentRequest($paymentId, AuthorizePaymentRequest $paymentRequest);

    /**
     * Requests to remind customer to pay the bill
     *
     * @link https://getpayever.com/developer/api-documentation/#remind-payment Documentation
     *
     * @param string $paymentId Payment ID
     *
     * @return ResponseInterface
     */
    public function remindPaymentRequest($paymentId);

    /**
     * Requests to collect payment
     *
     * https://getpayever.com/developer/api-documentation/#collect-payments Documentation
     *
     * @param string $paymentId Payment ID
     *
     * @return ResponseInterface
     */
    public function collectPaymentsRequest($paymentId);

    /**
     * Requests to notify late payment
     *
     * @link https://getpayever.com/developer/api-documentation/#late-payments Documentation
     *
     * @param string $paymentId Payment ID
     *
     * @return ResponseInterface
     */
    public function latePaymentsRequest($paymentId);

    /**
     * Sends a request about completing shipping
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/capture-payments/shipping-goods Documentation
     *
     * @param string $paymentId Payment ID
     * @param ShippingGoodsPaymentRequest $paymentRequest
     *
     * @return ResponseInterface
     */
    public function shippingGoodsPaymentRequest($paymentId, ShippingGoodsPaymentRequest $paymentRequest);

    /**
     * Sends a request to cancel non-completed payment
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/order-management/cancel Documentation
     *
     * @param string $paymentId Payment ID
     * @param float $amount Specify the partial cancel amount. If no amount is set, the whole amount will be cancelled.
     * @param string $uniqueIdentifier Action Identifier
     *
     * @return ResponseInterface
     */
    public function cancelPaymentRequest($paymentId, $amount, $uniqueIdentifier = null);

    /**
     * Sends a request to refund payment
     *
     * @link https://docs.payever.org/api/payments/order-management/cancel Documentation
     *
     * @param string $paymentId Payment ID
     * @param PaymentItemEntity[] $items Specify the refund items.
     * @param null|float $deliveryFee Shipping total.
     * @param string $uniqueIdentifier Action Identifier
     *
     * @return ResponseInterface
     */
    public function cancelItemsPaymentRequest($paymentId, $items, $deliveryFee = null, $uniqueIdentifier = null);

    /**
     * Sends a request to claim payment
     *
     * @link https://docs.payever.org/api/payments/order-management/claim Documentation
     *
     * @param string $paymentId Payment ID
     * @param ClaimPaymentRequest $paymentRequest Specify the claim payment request.
     *
     * @return ResponseInterface
     */
    public function claimPaymentRequest($paymentId, ClaimPaymentRequest $paymentRequest);

    /**
     * Requests serialized API Call record
     *
     * @link https://getpayever.com/developer/api-documentation/#retrieve-api-call Documentation
     *
     * @param string $callId API Call ID
     *
     * @return ResponseInterface
     */
    public function retrieveApiCallRequest($callId);

    /**
     * Returns payment options
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/display-list&or&options/list-payment-options
     *
     * @param array $params Query part of , available params: _locale, _currency
     * @param string $businessUuid Business UUID
     * @param string $channel Shopsystem channel
     *
     * @return ResponseInterface
     */
    public function listPaymentOptionsRequest($params = [], $businessUuid = '', $channel = '');

    /**
     * Same as listPaymentOptionsRequest, additionally contains list of payment option variants
     *
     * @link https://docs.payever.org/shopsystems/api/getting-started/api/display-list&or&options/list-variant-options
     *
     * @param array $params Query part of , available params: _locale, _currency
     * @param string $businessUuid Business UUID
     * @param string $channel Shopsystem channel
     *
     * @return ResponseInterface
     */
    public function listPaymentOptionsWithVariantsRequest($params = [], $businessUuid = '', $channel = '');

    /**
     * Returns transaction
     *
     * @param string $paymentId Payment ID
     *
     * @return ResponseInterface
     */
    public function getTransactionRequest($paymentId);
}
