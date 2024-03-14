<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Sync;

use Exception;
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\MoneyHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\PoyntHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\StringHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\WCHelper;
use GoDaddy\WooCommerce\Poynt\Plugin;
use GoDaddy\WooCommerce\Poynt\Sync\Jobs\PoyntTransactionSynchronizer;
use GoDaddy\WooCommerce\Poynt\Sync\Jobs\PushOrdersJob;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;
use WC_Order;

/**
 * Sync Poynt orders.
 *
 * Sync local orders to Poynt Terminal if a new order is received and the terminal is connected.
 *
 * @since 1.3.0
 */
class PoyntOrderSynchronizer
{
    /**
     * Sync Poynt orders constructor.
     *
     * @since 1.3.0
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->addHooks();
    }

    /**
     * Adds the hooks for checkout.
     *
     * @since 1.3.0
     *
     * @throws Exception
     */
    protected function addHooks()
    {
        add_action('woocommerce_thankyou', [$this, 'maybeSyncOrderAfterPayment']);
        add_action('woocommerce_new_order', [$this, 'maybeSyncCreatedOrder']);
        add_action('woocommerce_update_order', [$this, 'maybeSyncOrderTransaction']);

        add_action('woocommerce_order_status_completed', [$this, 'handleOrderStatusCompleted']);
        add_action('woocommerce_order_status_cancelled', [$this, 'handleOrderStatusCancelled']);
        // Note rather than the woocommerce_order_status_refunded action we're using
        // woocommerce_create_refund & woocommerce_refund_created which provides the refund details
        add_action('woocommerce_refund_created', [$this, 'handleRefundCreated'], 10, 2);
        add_action('woocommerce_create_refund', [$this, 'handleCreateRefund'], 10, 2);
    }

    /**
     * This handles the case of doing a "GoDaddy Payments - Pay in Person" refund/void
     * on an order that was placed online with GDP Pay in Person and paid on the terminal.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcRefundOrder
     * @param array $args arguments passed to wc_create_refund
     * @return void
     * @throws Exception
     */
    public function handleCreateRefund($wcRefundOrder, $args)
    {
        if (ArrayHelper::get($args, 'skip_bopit_sync')) {
            return;
        }

        if (! $wcOrder = wc_get_order($wcRefundOrder->get_parent_id())) {
            return;
        }

        if (! PoyntHelper::shouldPushOrderDetailsToPoynt($wcOrder)) {
            return;
        }

        if (! $wcOrder->get_transaction_id()) {
            return;
        }

        if (! WCHelper::orderHasPoyntProvider($wcOrder)) {
            return;
        }

        // tell WC that we want to process this refund with the poynt gateway
        add_filter('woocommerce_order_get_payment_method', function () {
            return Plugin::CREDIT_CARD_GATEWAY_ID;
        });
    }

    /**
     * Sync new order after the payment is processed.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @param $orderId
     * @throws Exception
     */
    public function maybeSyncOrderAfterPayment($orderId)
    {
        // here we retrieve the newly created order from the db so that we can have all id's available to us
        if (! $orderId || ! $order = wc_get_order($orderId)) {
            return;
        }

        $this->maybeSyncOrder($order);
    }

    /**
     * Maybe sync order transaction to Poynt by creating a scheduled action.
     *
     * @internal
     *
     * @param $orderId
     * @throws Exception
     */
    public function maybeSyncOrderTransaction($orderId)
    {
        // here we retrieve the newly created order from the db so that we can have all id's available to us
        if (! $orderId || ! $wcOrder = wc_get_order($orderId)) {
            return;
        }

        $transactionAction = '';
        if (PoyntTransactionSynchronizer::shouldPushSaleTransactionToPoynt($wcOrder)) {
            $transactionAction = PoyntTransactionSynchronizer::SALE_ACTION;
        } elseif (PoyntTransactionSynchronizer::shouldPushAuthorizationTransactionToPoynt($wcOrder)) {
            $transactionAction = PoyntTransactionSynchronizer::AUTHORIZATION_ACTION;
        } elseif (PoyntTransactionSynchronizer::shouldPushCaptureTransactionToPoynt($wcOrder)) {
            $transactionAction = PoyntTransactionSynchronizer::CAPTURE_ACTION;
        } else {
            return;
        }

        if (! as_next_scheduled_action(PoyntTransactionSynchronizer::ACTION_PUSH_ORDER_TRANSACTION, [$wcOrder->get_id(), $transactionAction])) {
            as_schedule_single_action((new \DateTime('now'))->getTimestamp(), PoyntTransactionSynchronizer::ACTION_PUSH_ORDER_TRANSACTION, [$wcOrder->get_id(), $transactionAction]);
        }
    }

    /**
     * Sync new order created.
     *
     * @internal
     *
     * @since 1.3.0
     *
     * @param $orderId
     * @throws Exception
     */
    public function maybeSyncCreatedOrder($orderId)
    {
        // only handle this hook if the order is created via admin panel
        if (! is_admin()) {
            return;
        }

        // here we retrieve the newly created order from the db so that we can have all id's available to us
        if (! $orderId || ! $order = wc_get_order($orderId)) {
            return;
        }

        $this->maybeSyncOrder($order);
    }

    /**
     * Sync order to Poynt.
     *
     * @internal
     *
     * @since 1.3.0
     *
     * @param WC_Order $order
     * @return false|void
     * @throws Exception
     */
    protected function maybeSyncOrder(WC_Order $order)
    {
        // bail if the order is already syncing
        if (! PoyntHelper::shouldPushOrderDetailsToPoynt($order)
            || (bool) $order->get_meta('_wc_poynt_order_syncing')
        ) {
            return false;
        }

        // mark an order in syncing state to avoid multiple requests
        $order->update_meta_data('_wc_poynt_order_syncing', true);
        $order->save_meta_data();

        new PushOrdersJob($order);
    }

    /**
     * Handler to complete order status.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @param int $orderId
     * @return void
     * @throws Exception
     */
    public function handleOrderStatusCompleted($orderId)
    {
        $this->handleOrderStatusChange('Completed', (int) $orderId);
    }

    /**
     * Handler to cancel order status.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @param int $orderId
     * @return void
     * @throws Exception
     */
    public function handleOrderStatusCancelled($orderId)
    {
        $this->handleOrderStatusChange('Cancelled', (int) $orderId);
    }

    /**
     * Handler to refund created.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @param int $refundId
     * @param array $args arguments passed to wc_create_refund
     * @return void
     * @throws Exception
     */
    public function handleRefundCreated($refundId, $args)
    {
        if (ArrayHelper::get($args, 'skip_bopit_sync')) {
            return;
        }

        if (! ($wcRefundOrder = wc_get_order($refundId))) {
            return;
        }

        $this->handleOrderStatusChange('Refunded', (int) $wcRefundOrder->get_parent_id(), $args);
    }

    /**
     * Handler to change order status.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @param string $status
     * @param int $orderId
     * @param array $additionalArgs additional arguments to pass along
     * @return void
     * @throws Exception
     */
    public function handleOrderStatusChange(string $status, int $orderId, array $additionalArgs = [])
    {
        if (! ($wcOrder = wc_get_order($orderId))) {
            return;
        }

        if (
            ! PoyntHelper::shouldPushOrderDetailsToPoynt($wcOrder)
            || ! ($poyntOrderId = $wcOrder->get_meta('_wc_poynt_order_remoteId'))
        ) {
            return;
        }

        try {
            $methodName = 'doHandleOrderStatus'.$status;
            $this->{$methodName}($wcOrder, $additionalArgs);
        } catch (Exception $e) {
            poynt_for_woocommerce()->log($e->getMessage());
        }
    }

    /**
     * Complete order status on poynt.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @param array $notUsed
     * @throws Exception
     */
    protected function doHandleOrderStatusCompleted(WC_Order $wcOrder, array $notUsed = [])
    {
        if (! $api = PoyntHelper::getGatewayAPI()) {
            return;
        }
        $remoteOrderId = $wcOrder->get_meta('_wc_poynt_order_remoteId');
        //NOTE: if response httpStatus code is not 200. Then API throws an error there is no way to check response code is ITEM_NOT_FULFILLED_OR_RETURNED.
        try {
            $api->completePoyntOrder($remoteOrderId);
        } catch (Framework\SV_WC_API_Exception $e) {
            poynt_for_woocommerce()->log("Could not complete order Remote Id# {$remoteOrderId}. Trying to complete order forcefully".$e->getMessage());

            try {
                $api->forceCompletePoyntOrder($remoteOrderId);
            } catch (Framework\SV_WC_API_Exception $e) {
                poynt_for_woocommerce()->log("Could not complete order ForceFully Remote Id# {$remoteOrderId}.{$e->getMessage()}");
            }
        }
    }

    /**
     * Cancel order status on poynt.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @param array $notUsed
     * @throws Exception
     */
    protected function doHandleOrderStatusCancelled(WC_Order $wcOrder, array $notUsed = [])
    {
        if (! $api = PoyntHelper::getGatewayAPI()) {
            return;
        }
        $remoteOrderId = $wcOrder->get_meta('_wc_poynt_order_remoteId');
        try {
            $api->cancelPoyntOrder($remoteOrderId);
        } catch (Framework\SV_WC_API_Exception $e) {
            poynt_for_woocommerce()->log("Could not cancel Poynt order {$remoteOrderId}: {$e->getMessage()}");
        }
    }

    /**
     * Refunds order on poynt.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @param array $args the arguments passed to wc_create_refund
     * @return void
     * @throws Exception
     */
    protected function doHandleOrderStatusRefunded(WC_Order $wcOrder, array $args = [])
    {
        if ($wcOrder->get_meta('_wc_poynt_credit_card_refund_remoteId')) {
            // this refund has already been pushed to Poynt
            return;
        }

        if (WCHelper::orderHasPoyntProvider($wcOrder) && ! WCHelper::hasCapturedOrder($wcOrder) && $wcOrder->get_transaction_id()) {
            // pass off authorization voids to the Poynt payment gateway for
            // actual processing since the Poynt API does not allow for a
            // "manual" void transaction to be created
            return poynt_for_woocommerce()->get_gateway(Plugin::CREDIT_CARD_GATEWAY_ID)->process_refund(
                $wcOrder->get_id(),
                ArrayHelper::get($args, 'amount'),
                ArrayHelper::get($args, 'reason')
            );
        }

        $this->performManualRefund($wcOrder, $args);
    }

    /**
     * Create a "manual" refund transaction in Poynt for the sole purpose of
     * marking the order as refunded; no actual funds will be refunded or
     * voided.
     *
     * A manual refund can be:
     *
     * - A WC "manual" refund of a GDP paid order (has $args['refund_total'] == false)
     * - A WC "manual" refund of a 3rd party paid order (has $args['refund_total'] == false)
     * - A WC 3rd party refund of a 3rd party paid order (has $args['refund_total'] == true)
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder the order to refund
     * @param array $args the arguments passed to wc_create_refund
     * @return void
     * @throws Exception
     */
    protected function performManualRefund(WC_Order $wcOrder, array $args = [])
    {
        if (! ($remoteOrderId = $wcOrder->get_meta('_wc_poynt_order_remoteId', true))) {
            return;
        }

        $remoteRefundTransactionId = StringHelper::generateUuid4();
        $fundingSourceProvider = ArrayHelper::get($args, 'refund_payment') ? $wcOrder->get_payment_method() : 'manual';

        if (! $api = PoyntHelper::getGatewayAPI()) {
            return;
        }

        $requestBody = $this->buildRefundTransactionRequestBody($wcOrder, $fundingSourceProvider, $remoteRefundTransactionId, $args);

        try {
            $response = $api->putTransactionRequest($remoteRefundTransactionId, $requestBody);
            $wcOrder->update_meta_data('_wc_poynt_credit_card_refund_remoteId', $response->getTransactionId());
            $wcOrder->update_meta_data('_wc_poynt_credit_card_refund_amount', ArrayHelper::get($args, 'amount'));
            $wcOrder->update_meta_data('_wc_poynt_credit_card_refund_fundingSource_provider', $fundingSourceProvider);
            $wcOrder->save();
        } catch (Framework\SV_WC_API_Exception $e) {
            throw new Exception("Could not refund Poynt order {$remoteOrderId}: ({$e->getMessage()})");
        }
    }

    /**
     * Builds request body for refund transaction.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @param string $fundingSourceProvider the refund transaction funding source provider name
     * @param string $remoteRefundTransactionId the remote refund transaction ID to set
     * @param array $args the arguments passed to wc_create_refund
     * @return array the refund transaction request body
     * @throws Exception
     */
    protected function buildRefundTransactionRequestBody(WC_Order $wcOrder, string $fundingSourceProvider, string $remoteRefundTransactionId, array $args)
    {
        $refundTotal = ArrayHelper::get($args, 'amount');

        if (! $refundTotal) {
            throw new Exception("Unable to get refund total to refund {$wcOrder->get_id()}");
        }

        if (ArrayHelper::get($args, 'refund_payment')) {
            /* translators: Placeholder: %1$s: payment gateway name */
            $refundNotes = sprintf(__('Transaction refunded by %1$s from WooCommerce.', 'godaddy-payments'), $wcOrder->get_payment_method_title());
        } else {
            $refundNotes = __('Transaction manually refunded from WooCommerce.', 'godaddy-payments');
        }

        if ($reason = ArrayHelper::get($args, 'reason')) {
            $refundNotes .= "\n\n{$reason}";
        }

        $body = [
            'action'  => 'REFUND',
            'amounts' => [
                'currency'          => $wcOrder->get_currency(),
                'orderAmount'       => MoneyHelper::convertDecimalToCents($wcOrder->get_total()),
                'transactionAmount' => MoneyHelper::convertDecimalToCents($refundTotal),
            ],
            'fundingSource' => [
                'type'                => 'CUSTOM_FUNDING_SOURCE',
                'customFundingSource' => [
                    'type'      => 'OTHER',
                    'provider'  => $fundingSourceProvider,
                    'accountId' => 'none',
                    'processor' => 'co.poynt.services',
                ],
            ],
            'processorResponse' => [
                'status'        => 'Successful',
                'statusCode'    => 1,
                'transactionId' => $remoteRefundTransactionId,
            ],
            'references' => [
                [
                    'type' => 'POYNT_ORDER',
                    'id'   => $wcOrder->get_meta('_wc_poynt_order_remoteId', true),
                ],
            ],
            'context' => [
                'sourceApp'  => 'WEB',
                'businessId' => PoyntHelper::getBusinessId(),
                'storeId'    => get_option('wc_poynt_storeId', ''),
            ],
            'notes' => $refundNotes,
        ];

        if ($parentTransactionId = $wcOrder->get_meta('_wc_poynt_credit_card_capture_trans_id')) {
            // read the poynt capture transaction (if any) from meta
            $body['parentId'] = $parentTransactionId;
        } elseif ($parentTransactionId = $wcOrder->get_meta('_wc_poynt_credit_card_trans_id')) {
            // read the poynt payment transaction (if any) from meta
            $body['parentId'] = $parentTransactionId;
        }

        return $body;
    }
}
