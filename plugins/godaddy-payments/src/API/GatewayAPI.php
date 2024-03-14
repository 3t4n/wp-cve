<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API;

use GoDaddy\WooCommerce\Poynt\API;
use GoDaddy\WooCommerce\Poynt\API\Cards\TokenizeChargeRequest;
use GoDaddy\WooCommerce\Poynt\API\Cards\TokenizeChargeResponse;
use GoDaddy\WooCommerce\Poynt\API\Cards\TokenizeRequest;
use GoDaddy\WooCommerce\Poynt\API\Cards\TokenizeResponse;
use GoDaddy\WooCommerce\Poynt\API\Poynt\BusinessRequest;
use GoDaddy\WooCommerce\Poynt\API\Poynt\BusinessResponse;
use GoDaddy\WooCommerce\Poynt\API\Poynt\BusinessStoresRequest;
use GoDaddy\WooCommerce\Poynt\API\Poynt\BusinessStoresResponse;
use GoDaddy\WooCommerce\Poynt\API\Poynt\CancelOrderRequest;
use GoDaddy\WooCommerce\Poynt\API\Poynt\CancelOrderResponse;
use GoDaddy\WooCommerce\Poynt\API\Poynt\CompleteOrderRequest;
use GoDaddy\WooCommerce\Poynt\API\Poynt\CompleteOrderResponse;
use GoDaddy\WooCommerce\Poynt\API\Poynt\ForceCompleteOrderRequest;
use GoDaddy\WooCommerce\Poynt\API\Poynt\GetOrderRequest;
use GoDaddy\WooCommerce\Poynt\API\Poynt\GetOrderResponse;
use GoDaddy\WooCommerce\Poynt\API\Poynt\PushOrderRequest;
use GoDaddy\WooCommerce\Poynt\API\Poynt\PushOrderResponse;
use GoDaddy\WooCommerce\Poynt\API\Poynt\RegisterWebhooksRequest;
use GoDaddy\WooCommerce\Poynt\API\Poynt\RegisterWebhooksResponse;
use GoDaddy\WooCommerce\Poynt\API\Requests\AbstractBusinessRequest;
use GoDaddy\WooCommerce\Poynt\API\Requests\AbstractRequest;
use GoDaddy\WooCommerce\Poynt\API\Requests\GenerateTokenRequest;
use GoDaddy\WooCommerce\Poynt\API\Responses\AbstractResponse;
use GoDaddy\WooCommerce\Poynt\API\Responses\GenerateTokenResponse;
use GoDaddy\WooCommerce\Poynt\API\Transactions\CaptureRequest;
use GoDaddy\WooCommerce\Poynt\API\Transactions\CaptureResponse;
use GoDaddy\WooCommerce\Poynt\API\Transactions\RefundRequest;
use GoDaddy\WooCommerce\Poynt\API\Transactions\RefundResponse;
use GoDaddy\WooCommerce\Poynt\API\Transactions\TransactionRequest;
use GoDaddy\WooCommerce\Poynt\API\Transactions\TransactionResponse;
use GoDaddy\WooCommerce\Poynt\API\Transactions\VoidRequest;
use GoDaddy\WooCommerce\Poynt\API\Transactions\VoidResponse;
use GoDaddy\WooCommerce\Poynt\Helpers\StringHelper;
use GoDaddy\WooCommerce\Poynt\Plugin;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;
use WC_Order;

defined('ABSPATH') or exit;

/**
 * Gateway API handler.
 *
 * @since 1.0.0
 */
class GatewayAPI extends API implements Framework\SV_WC_Payment_Gateway_API
{
    /** @var string configured application ID */
    private $appId;

    /** @var string configured business ID */
    private $businessId;

    /** @var string configured private key */
    private $privateKey;

    /** @var WC_Order|null current order object, if any */
    private $order;

    /** @var int max retries allowed for generating token */
    const MAX_RETRIES_ALLOWED = 1;

    /** @var int number of attempts done for the token generation */
    protected $attempts = 0;

    /* @var string */
    const WEBHOOK_PATH = 'wc-api/poynt';

    /** @var array */
    const WEBHOOK_TOPICS = [
        'ORDER_CANCELLED',
        'ORDER_COMPLETED',
        'ORDER_UPDATED',
        'TRANSACTION_AUTHORIZED',
        'TRANSACTION_CAPTURED',
        'TRANSACTION_REFUNDED',
        'TRANSACTION_UPDATED',
        'TRANSACTION_VOIDED',
    ];

    /**
     * Gateway API constructor.
     *
     * @since 1.0.0
     *
     * @param string $appId the configured application ID
     * @param string $businessId the configured business ID
     * @param string $privateKey the configured private key
     * @param string $environment the configured environment (e.g. production or staging)
     */
    public function __construct(string $appId, string $businessId, string $privateKey, string $environment)
    {
        $this->appId = $appId;
        $this->businessId = $businessId;
        $this->privateKey = $privateKey;

        parent::__construct($environment);
    }

    /**
     * Performs a credit card authorization for the given order.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order the order object
     * @return TokenizeChargeResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function credit_card_authorization(WC_Order $order) : TokenizeChargeResponse
    {
        $this->order = $order;

        $request = new TokenizeChargeRequest($this->getBusinessId());

        $request->setAuthorizeData($order);

        $this->set_response_handler(TokenizeChargeResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Performs a request to /businesses/{businessId}/ to get the business data.
     *
     * @since 1.3.1
     *
     * @return BusinessResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function getBusinessDetails() : BusinessResponse
    {
        $request = new BusinessRequest($this->getBusinessId());
        $this->set_response_handler(BusinessResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Performs a request to /businesses/{businessId}/ to get the business data.
     *
     * @since 1.3.1
     *
     * @param $remoteTransactionId
     * @param $requestBody
     *
     * @return TransactionResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function putTransactionRequest($remoteTransactionId, $requestBody) : TransactionResponse
    {
        $request = new TransactionRequest($this->getBusinessId(), $remoteTransactionId, 'PUT');
        $request->setRequestData($requestBody);

        $this->set_response_handler(TransactionResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Performs a request to /businesses/{businessId}/stores to get business stores data.
     *
     * @since 1.3.1
     *
     * @return BusinessStoresResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function getBusinessStores() : BusinessStoresResponse
    {
        $request = new BusinessStoresRequest($this->getBusinessId());
        $this->set_response_handler(BusinessStoresResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Performs a request to /businesses/{businessId}/transactions/{transactionId} to get the transaction data by transactionId.
     *
     * @since 1.3.0
     *
     * @param string $transactionId
     *
     * @return TransactionResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function getTransaction(string $transactionId) : TransactionResponse
    {
        $request = new TransactionRequest($this->getBusinessId(), $transactionId);
        $this->set_response_handler(TransactionResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Performs a POST request to /businesses/{businessId}/orders to create a new order.
     *
     * @since 1.3.0
     *
     * @param array<string, mixed> $body request body
     * @return PushOrderResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function pushNewOrder(array $body) : PushOrderResponse
    {
        $request = new PushOrderRequest($this->getBusinessId(), $body);
        $this->set_response_handler(PushOrderResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Performs a credit card charge for the given order.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order the order object
     * @return TokenizeChargeResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function credit_card_charge(WC_Order $order) : TokenizeChargeResponse
    {
        $this->order = $order;

        $request = new TokenizeChargeRequest($this->getBusinessId());

        $request->setSaleData($order);

        $this->set_response_handler(TokenizeChargeResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Performs a credit card capture for the given order.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order the order object
     * @return CaptureResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function credit_card_capture(WC_Order $order) : CaptureResponse
    {
        $this->order = $order;

        $request = new CaptureRequest($this->getBusinessId(), $this->order->capture->trans_id);

        $request->setCaptureData($order);

        $this->set_response_handler(CaptureResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Gets the app ID associated with this API instance.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getAppId() : string
    {
        return $this->appId;
    }

    /**
     * Gets the business ID associated with this API instance.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getBusinessId() : string
    {
        return $this->businessId;
    }

    /**
     * Gets the private key associated with this API instance.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getPrivateKey() : string
    {
        return $this->privateKey;
    }

    /**
     * Gets the order in context.
     *
     * @since 1.0.0
     *
     * @return WC_Order|null
     */
    public function get_order()
    {
        return $this->order;
    }

    /**
     * Gets the API ID.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_api_id()
    {
        return Plugin::CREDIT_CARD_GATEWAY_ID;
    }

    /**
     * Generates a new access token.
     *
     * @since 1.0.0
     *
     * @return GenerateTokenResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function generateAccessToken() : GenerateTokenResponse
    {
        $request = new GenerateTokenRequest();
        $requestURL = str_replace(parse_url($this->get_request_uri(), PHP_URL_PATH), '', $this->get_request_uri());

        $request->setTokenData(
            $this->getAppId(),
            $this->getPrivateKey(),
            $requestURL
        );

        $this->set_request_content_type_header('application/x-www-form-urlencoded');
        $this->set_response_handler(GenerateTokenResponse::class);

        return parent::perform_request($request);
    }

    /**
     * Performs a refund for the given order.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order the order object
     * @return RefundResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function refund(WC_Order $order) : RefundResponse
    {
        $this->order = $order;

        $request = new RefundRequest($this->getBusinessId());

        $request->setRefundData($order, $order->refund->trans_id);

        $this->set_response_handler(RefundResponse::class);

        $response = apply_filters('wc_poynt_refund_request_data', $request);

        return $response instanceof RefundResponse ? $response : $this->perform_request($request);
    }

    /**
     * Performs a void for the given order.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order the order object
     * @return VoidResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function void(WC_Order $order) : VoidResponse
    {
        $this->order = $order;

        $request = new VoidRequest($this->getBusinessId(), $this->order->refund->trans_id);

        $this->set_response_handler(VoidResponse::class);

        $response = apply_filters('wc_poynt_void_request_data', $request);

        return $response instanceof VoidResponse ? $response : $this->perform_request($request);
    }

    /**
     * Creates a payment token for the given order.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order the order object
     * @return TokenizeResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function tokenize_payment_method(WC_Order $order) : TokenizeResponse
    {
        $this->order = $order;

        $request = new TokenizeRequest($this->getBusinessId(), $order->payment->nonce);

        $this->set_response_handler(TokenizeResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Performs the given request.
     *
     * @since 1.0.0
     *
     * @param AbstractRequest $request
     * @return AbstractResponse
     * @throws Framework\SV_WC_API_Exception
     */
    protected function perform_request($request) : AbstractResponse
    {
        // generate a new access token before the request if none already exists
        if (! $this->getAccessToken()) {
            $responseHandler = $this->get_response_handler();

            $response = $this->generateAccessToken();

            $this->setAccessToken($response->getAccessToken());

            $this->set_request_content_type_header('application/json');

            $this->reset_response();

            $this->set_response_handler($responseHandler);
        }

        /**
         * Append compliance data for business risk review to transaction requests.
         *
         * @var AbstractBusinessRequest|TokenizeChargeRequest $request
         */
        if ($this->shouldAppendComplianceData($request) && ($order = $this->get_order())) {
            $request->setVerificationData($order);
            $request->setReceiptData($order);
            $request->setShippingAddress($order);
        }

        try {
            return parent::perform_request($request);
        } catch (Framework\SV_WC_API_Exception $e) {
            if (401 === $e->getCode() && $this->attempts <= self::MAX_RETRIES_ALLOWED) {
                // token is invalid, try sending request again after generating a new token
                $this->attempts++;
                $this->clearAccessToken();
                $this->reset_response();

                return $this->perform_request($request);
            }
            throw $e;
        }
    }

    /**
     * Determines whether a request should have compliance data appended to its body.
     *
     * @since 1.1.0
     *
     * @param AbstractRequest $request
     * @return bool
     */
    private function shouldAppendComplianceData($request) : bool
    {
        return $request instanceof AbstractBusinessRequest && ! $request instanceof TokenizeRequest;
    }

    /**
     * Updates a tokenized payment method.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order the order object
     * @return null
     */
    public function update_tokenized_payment_method(WC_Order $order)
    {
        // no op, implements interface method
        return null;
    }

    /**
     * Flags whether updating tokenized payment methods is supported.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function supports_update_tokenized_payment_method() : bool
    {
        return false;
    }

    /**
     * Removes a tokenized payment method.
     *
     * @since 1.0.0
     *
     * @param string $token
     * @param string $customerId
     * @return null
     */
    public function remove_tokenized_payment_method($token, $customerId)
    {
        // no op, implements interface method
        return null;
    }

    /**
     * Flags whether removing a tokenized payment method is supported.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function supports_remove_tokenized_payment_method() : bool
    {
        return false;
    }

    /**
     * Gets the tokenized payment methods.
     *
     * @since 1.0.0
     *
     * @param string $customerId
     * @return null
     */
    public function get_tokenized_payment_methods($customerId)
    {
        // no-op, implements interface method
        return null;
    }

    /**
     * Flags whether returning tokenized payment methods is supported.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function supports_get_tokenized_payment_methods() : bool
    {
        return false;
    }

    /**
     * Performs an eCheck debit (ACH transaction) for the given order.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order
     * @return null
     */
    public function check_debit(WC_Order $order)
    {
        // no-op, implements interface method
        return null;
    }

    /**
     * Register poynt webhooks.
     *
     * @since 1.3.0
     * @return RegisterWebhooksResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function registerWebhooks() : RegisterWebhooksResponse
    {
        $request = new RegisterWebhooksRequest();

        $request->setRequestData(
            [
                'businessId'    => $this->getBusinessId(),
                'applicationId' => $this->getAppId(),
                'eventTypes'    => self::WEBHOOK_TOPICS,
                'secret'        => self::getWebhookSecret(),
                'deliveryUrl'   => StringHelper::trailingSlash(site_url()).static::WEBHOOK_PATH,
            ]
        );

        $this->set_response_handler(RegisterWebhooksResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Gets the Poynt API webhook secret. This secret is passed during Webhook
     * registration calls, and is used by Poynt to sign outgoing webhooks, and
     * by us to verify them.
     *
     * @return string
     */
    public static function getWebhookSecret() : string
    {
        if (! $webhookSecret = get_option('wc_poynt_webhookSecret')) {
            $webhookSecret = StringHelper::generateUuid4();
            update_option('wc_poynt_webhookSecret', $webhookSecret);
        }

        return (string) $webhookSecret;
    }

    /**
     * Complete Order on poynt.
     *
     * @since 1.3.0
     * @param string $resourceId
     * @return CompleteOrderResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function completePoyntOrder($resourceId) : CompleteOrderResponse
    {
        $request = new CompleteOrderRequest($resourceId, $this->getBusinessId());
        $this->set_response_handler(CompleteOrderResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Force complete order on poynt.
     *
     * @since 1.3.0
     * @param string $resourceId
     * @return CompleteOrderResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function forceCompletePoyntOrder($resourceId) : CompleteOrderResponse
    {
        $request = new ForceCompleteOrderRequest($resourceId, $this->getBusinessId());
        $this->set_response_handler(CompleteOrderResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Cancel complete Order Status on poynt.
     *
     * @since 1.3.0
     * @param string $resourceId
     * @return CancelOrderResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function cancelPoyntOrder($resourceId) : CancelOrderResponse
    {
        $request = new CancelOrderRequest($resourceId, $this->getBusinessId());
        $this->set_response_handler(CancelOrderResponse::class);

        return $this->perform_request($request);
    }

    /**
     * Get poynt order.
     *
     * @since 1.3.0
     * @param string $resourceId
     * @return GetOrderResponse
     * @throws Framework\SV_WC_API_Exception
     */
    public function getPoyntOrder($resourceId) : GetOrderResponse
    {
        $request = new GetOrderRequest($resourceId, $this->getBusinessId());
        $this->set_response_handler(GetOrderResponse::class);

        return $this->perform_request($request);
    }
}
