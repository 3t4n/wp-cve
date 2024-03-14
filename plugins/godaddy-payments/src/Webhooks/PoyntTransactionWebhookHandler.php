<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Webhooks;

use Exception;
use GoDaddy\WooCommerce\Poynt\API\GatewayAPI;
use GoDaddy\WooCommerce\Poynt\API\Transactions\RefundResponse;
use GoDaddy\WooCommerce\Poynt\API\Transactions\TransactionResponse;
use GoDaddy\WooCommerce\Poynt\API\Transactions\VoidResponse;
use GoDaddy\WooCommerce\Poynt\Gateways\CreditCardGateway;
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\MoneyHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\PoyntHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\WCHelper;
use GoDaddy\WooCommerce\Poynt\Plugin;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;
use WC_Order;
use WC_Order_Item;
use WC_Order_Item_Fee;
use WC_Order_Item_Product;
use WC_Order_Item_Shipping;
use WC_Order_Refund;
use WP_Error;

/**
 * Poynt transaction webhook handler.
 *
 * @since 1.3.0
 */
class PoyntTransactionWebhookHandler extends PoyntWebhooksHandler implements ResourceWebhookHandlerContract
{
    /** @var GatewayAPI instance */
    private $api;

    /** @var TransactionResponse instance */
    protected $transaction;

    /** @var string transaction authorized webhook event type */
    const TRANSACTION_AUTHORIZED_EVENT_TYPE = 'TRANSACTION_AUTHORIZED';

    /** @var string transaction captured webhook event type */
    const TRANSACTION_CAPTURED_EVENT_TYPE = 'TRANSACTION_CAPTURED';

    /** @var string transaction refunded webhook event type */
    const TRANSACTION_REFUNDED_EVENT_TYPE = 'TRANSACTION_REFUNDED';

    /** @var string transaction voided webhook event type */
    const TRANSACTION_VOIDED_EVENT_TYPE = 'TRANSACTION_VOIDED';

    /** @var string transaction captured status */
    const TRANSACTION_CAPTURED_STATUS = 'CAPTURED';

    /** @var string transaction sale action */
    const TRANSACTION_SALE_ACTION = 'SALE';

    /** @var string transaction void status */
    const TRANSACTION_VOID_STATUS = 'VOIDED';

    /**
     * Handles the event payload.
     *
     * @since 1.3.0
     *
     * @param array<string, mixed> $payload payload data
     * @return void
     * @throws Exception
     */
    public function handlePayload($payload)
    {
        $eventType = ArrayHelper::get($payload, 'eventType');

        switch ($eventType) {
            case static::TRANSACTION_AUTHORIZED_EVENT_TYPE:
                $transaction = $this->getPaymentTransaction($payload);
                /* @see PoyntTransactionWebhookHandler::handleTransactionAuthorizedEvent() */
                $handlerMethod = 'handleTransactionAuthorizedEvent';
                break;
            case static::TRANSACTION_REFUNDED_EVENT_TYPE:
                $transaction = $this->getPaymentTransaction($payload);
                /* @see PoyntTransactionWebhookHandler::handleTransactionRefundedEvent() */
                $handlerMethod = 'handleTransactionRefundedEvent';
                break;
            case static::TRANSACTION_CAPTURED_EVENT_TYPE:
                $transaction = $this->getPaymentTransaction($payload);
                $handlerMethod = 'handleTransactionCapturedEvent';
                break;
            case static::TRANSACTION_VOIDED_EVENT_TYPE:
                $transaction = $this->getPaymentTransaction($payload, static::TRANSACTION_VOIDED_EVENT_TYPE);
                /* @see PoyntTransactionWebhookHandler::handleTransactionVoidedEvent() */
                $handlerMethod = 'handleTransactionVoidedEvent';
                break;
            default:
                return;
        }

        if (! $transaction instanceof TransactionResponse) {
            return;
        }

        $this->transaction = $transaction;
        $this->{$handlerMethod}();
    }

    /**
     * Handles a transaction refunded event.
     *
     * @since 1.3.0
     *
     * @return void
     * @throws Exception
     */
    protected function handleTransactionRefundedEvent()
    {
        if (! $wcOrder = $this->findOrderByPoyntOrderId($this->transaction->getPoyntOrderId())) {
            return;
        }

        // remote refund has already been processed: skip
        if ($wcOrder->get_meta('_wc_poynt_credit_card_refund_remoteId')) {
            return;
        }

        $this->maybeFilterWooCommerceOrderPaymentMethod($wcOrder);

        $this->filterRefundResponse();

        try {
            $refund = $this->createRefund($this->getRefundArgs($wcOrder));
            $refund->update_meta_data('_wc_poynt_credit_card_refund_remoteId', $this->transaction->getTransactionId());
            $refund->save();

            $wcOrder->update_meta_data('_wc_poynt_credit_card_status_before_refund', $wcOrder->get_status());
            $wcOrder->update_meta_data('_wc_poynt_credit_card_refund_remoteId', $this->transaction->getTransactionId());
            $wcOrder->save();
        } catch (Exception $e) {
            poynt_for_woocommerce()->log($e->getMessage());
        }

        remove_filter('wc_poynt_refund_request_data', [$this, 'getRefundTransaction']);
    }

    /**
     * Maybe filters the WooCommerce order payment method.
     *
     * Tells WooCommerce that we want to process a refund or a void with the transaction provider gateway,
     * regardless of the actual gateway used (e.g. Bank Transfer, Cash on Delivery...).
     *
     * @see WC_Order::get_payment_method() corresponding filter hook
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @return void
     * @throws Exception
     */
    protected function maybeFilterWooCommerceOrderPaymentMethod(WC_Order $wcOrder)
    {
        if (! WCHelper::orderHasPoyntProvider($wcOrder)) {
            return;
        }

        add_filter('woocommerce_order_get_payment_method', function () {
            return Plugin::CREDIT_CARD_GATEWAY_ID;
        });
    }

    /**
     * Maybe filters the WooCommerce refund response using `wc_poynt_refund_request_data` hook.
     *
     * Provides transaction data to refund method before performing request if we already have the transaction.
     *
     * @see Gateway_API::refund() method
     *
     * @since 1.3.0
     *
     * @return void
     * @throws Exception
     */
    protected function filterRefundResponse()
    {
        add_filter('wc_poynt_refund_request_data', [$this, 'getRefundTransaction']);
    }

    /**
     * Gets the refund transaction.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @return RefundResponse
     * @throws Exception
     */
    public function getRefundTransaction() : RefundResponse
    {
        return new RefundResponse($this->transaction->to_string());
    }

    /**
     * Maybe filters the WooCommerce void response using `wc_poynt_void_request_data` hook.
     *
     * Provides transaction data to void method before performing request if we already have the transaction.
     *
     * @see Gateway_API::void() method
     *
     * @since 1.3.0
     *
     * @return void
     * @throws Exception
     */
    protected function filterVoidResponse()
    {
        add_filter('wc_poynt_void_request_data', [$this, 'getVoidTransaction']);
    }

    /**
     * Gets the refund transaction.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @return VoidResponse
     * @throws Exception
     */
    public function getVoidTransaction() : VoidResponse
    {
        return new VoidResponse($this->transaction->to_string());
    }

    /**
     * Prepares refund arguments given a refund or a void transaction.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @return array list of prepared arguments to generate a WooCommerce order refund
     * @throws Exception
     */
    protected function getRefundArgs(WC_Order $wcOrder) : array
    {
        // for voids, refund the full amount
        if (static::TRANSACTION_VOID_STATUS === $this->transaction->getStatus()) {
            $amount = $wcOrder->get_total();
        } else {
            $amount = MoneyHelper::convertCentsToDecimal($this->transaction->getTotalAmount());
        }

        $args = [
            'amount'          => $amount,
            'reason'          => $this->getRefundDescription($wcOrder),
            'order_id'        => $wcOrder->get_id(),
            'refund_payment'  => true,
            'restock_items'   => true,
            'skip_bopit_sync' => true,
        ];

        // for voids and full refunds we can tell WooCommerce to mark each line item as refunded
        if (
            static::TRANSACTION_VOID_STATUS === $this->transaction->getStatus()
            || MoneyHelper::convertDecimalToCents($wcOrder->get_total()) === $this->transaction->getTotalAmount()
        ) {
            $args['line_items'] = $this->parseLineItemsForRefund($wcOrder->get_items(['line_item', 'fee', 'shipping']));
        }

        return $args;
    }

    /**
     * Converts WooCommerce order item objects for refund handling.
     *
     * Formats the items as used by {@see wc_create_refund()}.
     * @see RefundsRepository::create()
     *
     * @since 1.3.0
     *
     * @param WC_Order_Item[]|WC_Order_Item_Product[]|WC_Order_Item_Fee[]|WC_Order_Item_Shipping[] $lineItems
     * @return array
     */
    protected function parseLineItemsForRefund(array $lineItems) : array
    {
        $result = [];

        foreach ($lineItems as $id => $item) {
            if (! $item instanceof WC_Order_Item) {
                continue;
            }

            $result[$id] = [
                'qty'          => $item->get_type() === 'line_item' ? $item->get_quantity() : 0,
                'refund_total' => $item->get_total(),
                'refund_tax'   => ArrayHelper::get($item->get_taxes(), 'total'),
            ];
        }

        return $result;
    }

    /**
     * Gets the refund description to be appended to an order item.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @return string
     */
    protected function getRefundDescription(WC_Order $wcOrder) : string
    {
        if (
            static::TRANSACTION_VOID_STATUS === $this->transaction->getStatus()
            || MoneyHelper::convertDecimalToCents($wcOrder->get_total()) === $this->transaction->getTotalAmount()
        ) {
            return __('From GoDaddy Payments Smart Terminal. Order fully refunded.', 'godaddy-payments');
        }

        return __('From GoDaddy Payments Smart Terminal. Order partially refunded.', 'godaddy-payments');
    }

    /**
     * Handles a transaction void event.
     *
     * @throws Exception
     */
    protected function handleTransactionVoidedEvent()
    {
        if (! $wcOrder = $this->findOrderByPoyntOrderId($this->transaction->getPoyntOrderId())) {
            return;
        }

        switch ($this->transaction->getParentType()) {
            case 'capture':
                $this->handleCaptureVoided($wcOrder);
                break;
            case 'sale':
                $this->handleSaleVoided($wcOrder);
                break;
            case 'payment':
                $this->handlePaymentVoided($wcOrder);
                break;
            case 'refund':
                $this->handleRefundVoided($wcOrder);
                break;
        }
    }

    /**
     * Voids a capture for a given order.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @return void
     */
    protected function handleCaptureVoided(WC_Order $wcOrder)
    {
        // don't replay capture void if already voided
        if (! WCHelper::hasCapturedOrder($wcOrder)) {
            return;
        }

        $wcOrder->update_meta_data('_wc_poynt_credit_card_charge_captured', 'no');
        $wcOrder->set_status('on-hold');
        $wcOrder->save();
    }

    /**
     * Handles a void transaction to void a SALE transaction for a given WooCommerce order.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @return void
     * @throws Exception
     */
    protected function handleSaleVoided(WC_Order $wcOrder)
    {
        // bail if remote void has already been processed
        if ($wcOrder->get_meta('_wc_poynt_credit_card_void_remoteId')) {
            return;
        }

        $wcOrder->update_meta_data('_wc_poynt_credit_card_void_remoteId', $this->transaction->getTransactionId());
        $wcOrder->save();

        $orderNote = sprintf(__('GoDaddy Payments sale transaction (ID %s) reversed by void transaction (ID %s) via Smart Terminal.', 'godaddy-payments'),
            $this->transaction->getParentId(),
            $this->transaction->getTransactionId());

        $wcOrder->update_status('refunded', $orderNote);
    }

    /**
     * Handles a void transaction to void a payment for a given WooCommerce order.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @return void
     * @throws Exception
     */
    protected function handlePaymentVoided(WC_Order $wcOrder)
    {
        // bail if remote void has already been processed
        if ($wcOrder->get_meta('_wc_poynt_credit_card_void_remoteId')) {
            return;
        }

        $this->voidOrder($wcOrder);
    }

    /**
     * Voids a WooCommerce order.
     *
     * This method handles the business creating a "refund" record and firing events as if the void was performed in WooCommerce.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @throws Exception
     */
    protected function voidOrder(WC_Order $wcOrder)
    {
        $this->maybeFilterWooCommerceOrderPaymentMethod($wcOrder);
        $this->filterVoidResponse();

        try {
            $refund = $this->createRefund($this->getRefundArgs($wcOrder));
            $refund->update_meta_data('_wc_poynt_credit_card_void_remoteId', $this->transaction->getTransactionId());
            $refund->save();
        } catch (Exception $e) {
            poynt_for_woocommerce()->log($e->getMessage());
        }
        remove_filter('wc_poynt_void_request_data', [$this, 'getVoidTransaction']);
    }

    /**
     * Voids (deletes) a refund for a given void refund transaction.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @return void
     * @throws Exception
     */
    protected function handleRefundVoided(WC_Order $wcOrder)
    {
        if (! $refund = $this->findOrderByPoyntId($this->transaction->getParentId(), '_wc_poynt_credit_card_refund_remoteId', 'shop_order_refund')) {
            return;
        }

        if (! $order = $this->findOrderByPoyntId($this->transaction->getParentId(), '_wc_poynt_credit_card_refund_remoteId')) {
            return;
        }

        $refund->delete();

        $order->update_meta_data('_wc_poynt_credit_card_refund_void_remoteId', $this->transaction->getTransactionId());
        $order->save();

        if ($previousStatus = $wcOrder->get_meta('_wc_poynt_credit_card_status_before_refund')) {
            $orderNote = sprintf(__('GoDaddy Payments refund transaction (ID %s) reversed by void transaction (ID %s) via Smart Terminal.', 'godaddy-payments'),
                $this->transaction->getParentId(),
                $this->transaction->getTransactionId());

            $wcOrder->update_status($previousStatus, $orderNote);
        }
    }

    /**
     * Creates a WooCommerce refund.
     *
     * @since 1.3.0
     *
     * @param array $args
     * @return WC_Order_Refund
     * @throws Exception
     */
    protected function createRefund(array $args = []) : WC_Order_Refund
    {
        /* translators: Placeholder: %s - error message */
        $errorMessage = __('Could not create refund: %s', 'godaddy-payments');

        $refund = wc_create_refund($args);

        if ($refund instanceof WP_Error) {
            throw new Exception(sprintf($errorMessage, $refund->get_error_message()));
        }

        return $refund;
    }

    /**
     * Handles a transaction authorization event.
     *
     * @since 1.3.0
     *
     * @return void
     * @throws Exception
     */
    protected function handleTransactionAuthorizedEvent()
    {
        if (! $poyntOrderId = $this->transaction->getPoyntOrderId()) {
            return;
        }

        if (! $wcOrder = $this->findOrderByPoyntOrderId($poyntOrderId)) {
            return;
        }

        // bail out if the transaction is already processed
        if ($wcOrder->get_meta('_wc_poynt_credit_card_trans_id')) {
            return;
        }

        $this->addOrderItemFees($wcOrder);

        if ('on-hold' === $wcOrder->get_status()) {
            $wcOrder->update_status('processing');
        }

        $wcOrder->set_transaction_id($this->transaction->getTransactionId());
        $wcOrder->update_meta_data('_wc_poynt_credit_card_trans_id', $this->transaction->getTransactionId());
        $wcOrder->update_meta_data('_wc_poynt_credit_card_trans_date', current_time('mysql'));
        $wcOrder->update_meta_data('_wc_poynt_provider_name', 'poynt');

        if ('CREDIT_DEBIT' === $this->transaction->getFundingSourceType()) {
            $paymentDetails = $this->transaction->getPaymentDetails();

            foreach ($paymentDetails as $optionKey => $optionValue) {
                $wcOrder->update_meta_data('_wc_poynt_credit_card_'.$optionKey, $optionValue);
            }
        }

        $wcOrder->add_order_note(CreditCardGateway::generateTransactionOrderNote($wcOrder));
        $wcOrder->save();
    }

    /**
     * Handles a transaction captured event.
     *
     * @since 1.3.0
     *
     * @return void
     * @throws Exception
     */
    protected function handleTransactionCapturedEvent()
    {
        $poyntOrderId = $this->transaction->getPoyntOrderId();
        $transactionId = $this->transaction->getTransactionId();

        // handle SALE and CAPTURED transactions.
        if ($poyntOrderId) {
            $wcOrder = $this->findOrderByPoyntOrderId($poyntOrderId);
        } else {
            $wcOrder = $this->findOrderByPoyntTransactionId($transactionId);
        }

        if (! $wcOrder) {
            return;
        }

        $this->addOrderItemFees($wcOrder);

        // bail out if the capture event is already processed
        if (WCHelper::hasCapturedOrder($wcOrder)) {
            return;
        }
        $isSale = $this->isSaleTransaction();

        $wcOrder->set_transaction_id($this->transaction->getTransactionId());
        // Note: using the current time as the transaction time is not very accurate. It would be better to retrieve the remote order and use the actual transaction time
        $wcOrder->set_date_paid(time());
        $wcOrder->update_meta_data('_wc_poynt_credit_card_trans_id', $this->transaction->getTransactionId());
        $wcOrder->update_meta_data('_wc_poynt_credit_card_trans_date', current_time('mysql'));
        $wcOrder->update_meta_data('_wc_poynt_provider_name', 'poynt');

        if ($isSale) {
            $wcOrder->payment_complete($this->transaction->getTransactionId());
        }

        if ('CREDIT_DEBIT' === $this->transaction->getFundingSourceType()) {
            $paymentDetails = $this->transaction->getPaymentDetails();

            foreach ($paymentDetails as $optionKey => $optionValue) {
                $wcOrder->update_meta_data('_wc_poynt_credit_card_'.$optionKey, $optionValue);
            }
        } else {
            $wcOrder->update_meta_data('_wc_poynt_credit_card_charge_captured', PoyntTransactionWebhookHandler::TRANSACTION_CAPTURED_STATUS === $this->transaction->__get('status') ? 'yes' : 'no');
            $wcOrder->update_meta_data('_wc_poynt_credit_card_authorization_amount', Framework\SV_WC_Helper::number_format(MoneyHelper::convertCentsToDecimal($this->transaction->__get('amounts')->transactionAmount ?? 0)));
        }

        $formattedAmount = wc_price($paymentDetails['authorization_amount'] ?? 0, ['currency' => $wcOrder->get_currency()]);
        if ($isSale) {
            $orderNote = CreditCardGateway::generateTransactionOrderNote($wcOrder);
        } else {
            $orderNote = __(sprintf('A payment of %s was successfully captured via GoDaddy Smart Terminal (Transaction ID %s).', $formattedAmount, $this->transaction->getTransactionId()));
        }
        $wcOrder->add_order_note($orderNote);

        if (! $isSale && 'on-hold' === $wcOrder->get_status()) {
            $wcOrder->update_status('processing');
        }

        $wcOrder->save();
    }

    /**
     * Determines if transaction is a SALE transaction.
     *
     * @since 1.3.0
     *
     * @return bool
     * @throws Exception
     */
    protected function isSaleTransaction() : bool
    {
        return static::TRANSACTION_SALE_ACTION === $this->transaction->__get('action') && static::TRANSACTION_CAPTURED_STATUS === $this->transaction->__get('status');
    }

    /**
     * Gets a payment transaction adapted from the given webhook payload.
     *
     * @since 1.3.0
     *
     * @param array $payload
     * @param string $transactionType
     * @return TransactionResponse|null
     * @throws Exception
     */
    protected function getPaymentTransaction(array $payload, string $transactionType = '')
    {
        if (! $api = $this->getAPIInstance()) {
            return null;
        }

        // Handle void transactions by using the transactionId from properties.childTxnId
        $transactionIdKey = static::TRANSACTION_VOIDED_EVENT_TYPE === $transactionType ? 'properties.childTxnId' : 'resourceId';

        if (! $transactionId = ArrayHelper::get($payload, $transactionIdKey, '')) {
            return null;
        }

        try {
            return $api->getTransaction($transactionId);
        } catch (Framework\SV_WC_API_Exception $e) {
            return null;
        }
    }

    /**
     * Gets the gateway API instance.
     *
     * @since 1.0.0
     *
     * @return GatewayAPI|bool gateway API instance or false if the gateway is not configured
     */
    public function getAPIInstance() : GatewayAPI
    {
        $businessId = PoyntHelper::getBusinessId();
        $appId = PoyntHelper::getAppId();
        $privateKey = PoyntHelper::getPrivateKey();
        $environment = PoyntHelper::getEnvironment();

        // ensure we have the minimum requirements to be connected to the API
        if (! $businessId || ! $appId || ! $privateKey) {
            return false;
        }

        if (! $this->api instanceof GatewayAPI) {
            $this->api = new GatewayAPI($appId, $businessId, $privateKey, $environment);
        }

        return $this->api;
    }

    /**
     * May add order item fees.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @return void
     */
    protected function addOrderItemFees(WC_Order $wcOrder)
    {
        $shouldCalculateTotals = false;

        // may add a tip amount, if any
        if ($tipAmount = $this->transaction->getTipAmount()) {
            $tipAmount = MoneyHelper::convertCentsToDecimal($tipAmount);
            $shouldCalculateTotals = $this->addOrderItemFee(__('Tip', 'godaddy-payments'), $tipAmount, $wcOrder);
        }

        // may add a cashback amount, if any
        if ($cashbackAmount = $this->transaction->getCashbackAmount()) {
            $cashbackAmount = MoneyHelper::convertCentsToDecimal($cashbackAmount);
            $shouldCalculateTotals = $this->addOrderItemFee(__('Cashback', 'godaddy-payments'), $cashbackAmount, $wcOrder) || $shouldCalculateTotals;
        }

        if ($shouldCalculateTotals) {
            $wcOrder->calculate_totals();
        }
    }

    /**
     * Adds an order item fee to an order.
     *
     * This method can be used to add items from a transaction like a tip or a cashback.
     *
     * @since 1.3.0
     *
     * @param string $itemFeeName
     * @param int|float $amount
     * @param WC_Order $order
     * @return bool
     */
    protected function addOrderItemFee(string $itemFeeName, $amount, WC_Order $order) : bool
    {
        if (0 === $amount || $this->orderHasItemFee($order, $itemFeeName)) {
            return false;
        }

        $item = $this->createOrderItemFee($amount, $itemFeeName);

        $order->add_item($item);
        $order->add_order_note(sprintf(
            /* translators: Placeholders: %1$s - item fee name, %2$s - item fee amount */
            __('%1$s amount of %2$s added to order by GoDaddy Payments Smart Terminal', 'godaddy-payments'),
            $itemFeeName,
            wc_price($amount, get_woocommerce_currency_symbol())
        ));

        return true;
    }

    /**
     * Creates a new WC_Order_Item_Fee with the provided amount.
     *
     * All fees created here are non-taxable and must be used to add order items like tip, cashback, etc.
     *
     * @since 1.3.0
     *
     * @param float $amount
     * @param string $feeName
     * @return WC_Order_Item_Fee
     */
    protected function createOrderItemFee(float $amount, string $feeName) : WC_Order_Item_Fee
    {
        $item = new WC_Order_Item_Fee();
        $item->set_name($feeName);
        $item->set_amount($amount);
        $item->set_total($amount);
        $item->set_tax_status('none');

        return $item;
    }

    /**
     * Determines whether an order has a item fee with a specific name.
     *
     * @since 1.3.0
     *
     * @param WC_Order $order
     * @param string $itemFeeName
     * @return bool
     */
    protected function orderHasItemFee(WC_Order $order, string $itemFeeName) : bool
    {
        foreach ($order->get_fees() as $item) {
            if ($item instanceof WC_Order_Item_Fee && $itemFeeName === $item->get_name()) {
                return true;
            }
        }

        return false;
    }
}
