<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Sync\Jobs;

use Exception;
use GoDaddy\WooCommerce\Poynt\API\GatewayAPI;
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\MoneyHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\PoyntHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\StringHelper;
use GoDaddy\WooCommerce\Poynt\Plugin;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;
use WC_Order;

/**
 * Handles push order transaction scheduled action.
 *
 * @since 1.3.0
 */
class PoyntTransactionSynchronizer
{
    /** @var string action push order transaction for scheduled action */
    const ACTION_PUSH_ORDER_TRANSACTION = 'wc_poynt_push_order_transaction';

    /** @var array payment methods to exclude transactions */
    const EXCLUDE_TRANSACTIONS_ON = [Plugin::CREDIT_CARD_GATEWAY_ID, Plugin::PAYINPERSON_GATEWAY_ID, 'cod'];

    /** @var string SALE transaction action */
    const SALE_ACTION = 'SALE';

    /** @var string AUTHORIZATION transaction action */
    const AUTHORIZATION_ACTION = 'AUTHORIZE';

    /** @var string CAPTURE transaction action */
    const CAPTURE_ACTION = 'CAPTURE';

    /**
     * Push order transaction scheduled constructor.
     *
     * @since 1.3.0
     */
    public function __construct()
    {
        $this->addHooks();
    }

    /**
     * Adds the hooks.
     *
     * @since 1.3.0
     *
     * @retur void
     */
    protected function addHooks()
    {
        add_action(self::ACTION_PUSH_ORDER_TRANSACTION, [$this, 'handlePushTransactionsJob']);
    }

    /**
     * Handles the job to push transactions to the Poynt API.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @param int $orderId
     * @return void
     * @throws Exception
     */
    public function handlePushTransactionsJob(int $orderId)
    {
        if (! $wcOrder = wc_get_order($orderId)) {
            return;
        }

        try {
            // the order was not sent to Poynt yet, reschedule the action to try again later
            if ($this->shouldRescheduleJob($wcOrder)) {
                as_schedule_single_action((new \DateTime('now'))->getTimestamp(), static::ACTION_PUSH_ORDER_TRANSACTION, [$wcOrder->get_id()]);

                return;
            }

            $this->maybePushTransactionToPoynt($wcOrder);
        } catch (Framework\SV_WC_API_Exception $e) {
            poynt_for_woocommerce()->log($e->getMessage());
        }
    }

    /**
     * Checks if the order was already sent to Poynt.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @return bool
     */
    protected function shouldRescheduleJob(WC_Order $wcOrder)
    {
        return empty($wcOrder->get_meta('_wc_poynt_order_remoteId'));
    }

    /**
     * Maybe Push transaction the Poynt API when payment is made via a 3rd party gateway.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @throws Framework\SV_WC_API_Exception|Exception
     * @return void
     */
    protected function maybePushTransactionToPoynt(WC_Order $wcOrder)
    {
        if (! $api = PoyntHelper::getGatewayAPI()) {
            return;
        }

        if (static::shouldPushSaleTransactionToPoynt($wcOrder)) {
            $this->pushPaymentTransactionToPoynt(static::SALE_ACTION, $api, $wcOrder);
        } elseif (static::shouldPushAuthorizationTransactionToPoynt($wcOrder)) {
            $this->pushPaymentTransactionToPoynt(static::AUTHORIZATION_ACTION, $api, $wcOrder);
        } elseif (static::shouldPushCaptureTransactionToPoynt($wcOrder)) {
            $this->pushCaptureTransactionToPoynt($api, $wcOrder);
        }
    }

    /**
     * Push payment transaction to the Poynt API when payment is made via a 3rd party gateway.
     *
     * @since 1.3.0
     *
     * @param string $action
     * @param GatewayAPI $api
     * @param WC_Order $wcOrder
     * @return void
     * @throws Exception
     */
    protected function pushPaymentTransactionToPoynt(string $action, $api, WC_Order $wcOrder)
    {
        $remoteTransactionId = StringHelper::generateUuid4();
        $requestBody = $this->buildTransactionRequestBody($action, $wcOrder);

        try {
            $response = $api->putTransactionRequest($remoteTransactionId, $requestBody);
            $wcOrder->update_meta_data('_wc_poynt_credit_card_trans_id', $response->getTransactionId());
            $wcOrder->save_meta_data();
        } catch (Framework\SV_WC_API_Exception $e) {
            throw new Exception("Could not send {$action} transaction to Poynt for order {$wcOrder->get_id()}: ({$e->getMessage()})");
        }
    }

    /**
     * Push capture transaction to the Poynt API when an order is captured via a 3rd party gateway.
     *
     * @since 1.3.0
     *
     * @param GatewayAPI $api
     * @param WC_Order $wcOrder
     * @return void
     * @throws Exception
     */
    protected function pushCaptureTransactionToPoynt($api, WC_Order $wcOrder)
    {
        $remoteTransactionId = StringHelper::generateUuid4();
        $requestBody = $this->buildTransactionRequestBody(static::CAPTURE_ACTION, $wcOrder, $remoteTransactionId);

        try {
            $response = $api->putTransactionRequest($remoteTransactionId, $requestBody);
            $wcOrder->update_meta_data('_wc_poynt_credit_card_capture_trans_id', $response->getTransactionId());
            $wcOrder->save_meta_data();
        } catch (Framework\SV_WC_API_Exception $e) {
            throw new Exception("Could not send CAPTURE transaction to Poynt for order {$wcOrder->get_id()}: ({$e->getMessage()})");
        }
    }

    /**
     * Builds the request body based on the order data.
     *
     * @since 1.3.0
     *
     * @param string $action
     * @param WC_Order $wcOrder WC order to build the transaction request body for
     * @param string|null $transactionId transaction ID generated for this new transaction
     * @return array the transaction request body
     * @throws Exception
     */
    protected function buildTransactionRequestBody(string $action, WC_Order $wcOrder, string $transactionId = null) : array
    {
        $body = [
            'action'  => $action,
            'amounts' => [
                'currency'          => $wcOrder->get_currency(),
                'transactionAmount' => MoneyHelper::convertDecimalToCents($wcOrder->get_total()),
                'orderAmount'       => MoneyHelper::convertDecimalToCents($wcOrder->get_total()),
            ],
            'fundingSource' => [
                'type'         => 'CUSTOM_FUNDING_SOURCE',
                'entryDetails' => [
                    'customerPresenceStatus' => 'ECOMMERCE',
                    'entryMode'              => 'KEYED',
                ],
                'customFundingSource' => [
                    'type'      => 'OTHER',
                    'provider'  => $wcOrder->get_payment_method(),
                    'accountId' => 'none',
                    'processor' => 'co.poynt.services',
                ],
            ],
            'processorResponse' => [
                'status'        => 'Successful',
                'statusCode'    => 1,
                'transactionId' => static::CAPTURE_ACTION !== $action ? $wcOrder->get_transaction_id() : $transactionId,
            ],
            'references' => [
                [
                    'type' => 'POYNT_ORDER',
                    'id'   => $wcOrder->get_meta('_wc_poynt_order_remoteId', true),
                ],
            ],
            'context' => [
                'storeId' => get_option('wc_poynt_storeId', ''),
            ],
        ];

        switch ($action) {
            case static::AUTHORIZATION_ACTION:
                {
                    $body['processorResponse']['providerVerification'] = [
                        'signature' => 'none',
                    ];
                    $body['notes'] = sprintf(__('Paid in WooCommerce checkout by "%s"', 'godaddy-payments'), $wcOrder->get_payment_method_title());
                    break;
                }
            case static::SALE_ACTION:
                {
                    $body['notes'] = sprintf(__('Paid in WooCommerce checkout by "%s"', 'godaddy-payments'), $wcOrder->get_payment_method_title());
                    break;
                }
            case static::CAPTURE_ACTION:
                {
                    $body['parentId'] = $wcOrder->get_meta('_wc_poynt_credit_card_trans_id');
                }
        }

        return $body;
    }

    /**
     * Determines if the SALE transaction should be synchronized to the Poynt API using a scheduled action.
     *
     * Used to display orders paid with 3rd party gateways as Paid on the Smart Terminal.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @throws Exception
     * @return bool
     */
    public static function shouldPushSaleTransactionToPoynt(WC_Order $wcOrder) : bool
    {
        if (! PoyntHelper::shouldPushOrderDetailsToPoynt($wcOrder)) {
            return false;
        }

        // order was not paid
        if (empty($wcOrder->get_transaction_id()) || empty($wcOrder->get_date_paid())) {
            return false;
        }

        // payment transaction was already submitted to Poynt
        if (! empty($wcOrder->get_meta('_wc_poynt_credit_card_trans_id'))) {
            return false;
        }

        // skip orders paid with a GD gateway or cash
        return ! ArrayHelper::contains(PoyntTransactionSynchronizer::EXCLUDE_TRANSACTIONS_ON, $wcOrder->get_payment_method());
    }

    /**
     * Determines if the AUTHORIZE transaction should be synchronized to the Poynt API using a scheduled action.
     *
     * Used to display orders paid with 3rd party gateways as Paid on the Smart Terminal.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @throws Exception
     * @return bool
     */
    public static function shouldPushAuthorizationTransactionToPoynt(WC_Order $wcOrder) : bool
    {
        if (! PoyntHelper::shouldPushOrderDetailsToPoynt($wcOrder)) {
            return false;
        }

        // order was not authorized
        if (empty($wcOrder->get_transaction_id())) {
            return false;
        }

        // order was already paid (should push a SALE transaction instead)
        if (! empty($wcOrder->get_date_paid())) {
            return false;
        }

        // payment transaction was already submitted to Poynt
        if (! empty($wcOrder->get_meta('_wc_poynt_credit_card_trans_id'))) {
            return false;
        }

        // skip orders paid with a GD gateway or cash
        return ! ArrayHelper::contains(PoyntTransactionSynchronizer::EXCLUDE_TRANSACTIONS_ON, $wcOrder->get_payment_method());
    }

    /**
     * Determines if a CAPTURE transaction should be sent to the Poynt API.
     *
     * Used to display orders captured with 3rd party gateways as Paid on the Smart Terminal.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @throws Exception
     * @return bool
     */
    public static function shouldPushCaptureTransactionToPoynt(WC_Order $wcOrder) : bool
    {
        if (! PoyntHelper::shouldPushOrderDetailsToPoynt($wcOrder)) {
            return false;
        }

        // order was not paid
        if (empty($wcOrder->get_transaction_id()) || empty($wcOrder->get_date_paid())) {
            return false;
        }

        // capture transaction was already submitted to Poynt
        if (! empty($wcOrder->get_meta('_wc_poynt_credit_card_capture_trans_id'))) {
            return false;
        }

        // skip orders paid with a GD gateway or cash
        return ! ArrayHelper::contains(PoyntTransactionSynchronizer::EXCLUDE_TRANSACTIONS_ON, $wcOrder->get_payment_method());
    }
}
