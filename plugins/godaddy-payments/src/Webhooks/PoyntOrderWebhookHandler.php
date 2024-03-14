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
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\CommonHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\PoyntHelper;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;
use WC_Order;

/**
 * Poynt order webhook handler.
 */
class PoyntOrderWebhookHandler extends PoyntWebhooksHandler implements ResourceWebhookHandlerContract
{
    /** @var string order completed webhook event type */
    const ORDER_COMPLETED_EVENT_TYPE = 'ORDER_COMPLETED';

    /** @var string order cancelled webhook event type */
    const ORDER_CANCELLED_EVENT_TYPE = 'ORDER_CANCELLED';

    /** @var string order updated webhook event type */
    const ORDER_UPDATED_EVENT_TYPE = 'ORDER_UPDATED';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handles the event payload.
     *
     * @param array $payload payload data
     *
     * @since 1.3.0
     * @throws Exception
     */
    public function handlePayload($payload)
    {
        $eventType = ArrayHelper::get($payload, 'eventType', '');
        $poyntOrderId = ArrayHelper::get($payload, 'resourceId');

        if (! $wcOrder = $this->findOrderByPoyntOrderId($poyntOrderId)) {
            return;
        }

        if (static::ORDER_COMPLETED_EVENT_TYPE === $eventType) {
            $this->handleOrderCompletedEvent($wcOrder);
        } elseif (static::ORDER_CANCELLED_EVENT_TYPE === $eventType) {
            $this->handleOrderCancelledEvent($wcOrder);
        } elseif (static::ORDER_UPDATED_EVENT_TYPE === $eventType) {
            $this->handleOrderUpdatedEvent($payload, $wcOrder);
        }
    }

    /**
     * Handles an order updated event.
     *
     * @since 1.3.0
     *
     * @param array $data
     * @param WC_Order $wcOrder
     */
    protected function handleOrderUpdatedEvent(array $data, WC_Order $wcOrder)
    {
        $this->maybeHandleOrderReadyForPickup($data, $wcOrder);
    }

    /**
     * May handle the event received when an order is marked as ready for pickup.
     *
     * @since 1.3.0
     *
     * @param array $data
     * @param WC_Order $wcOrder
     */
    protected function maybeHandleOrderReadyForPickup(array $data, WC_Order $wcOrder)
    {
        if ($wcOrder->get_meta('_poynt_order_status_ready_at')) {
            return;
        }

        if (! empty($orderData = $this->getRemoteOrderData($data)) && $this->isOrderReadyForPickup($orderData)) {
            $this->handleOrderReadyForPickup($orderData, $wcOrder);
        }
    }

    /**
     * Gets the remote order data from Poynt API.
     *
     * @since 1.3.0
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    protected function getRemoteOrderData(array $data) : array
    {
        $poyntOrderId = ArrayHelper::get($data, 'resourceId');

        if (! $api = PoyntHelper::getGatewayAPI()) {
            return [];
        }

        try {
            $response = $api->getPoyntOrder($poyntOrderId);

            return $response->getBody();
        } catch (Framework\SV_WC_API_Exception $e) {
            poynt_for_woocommerce()->log("Could not retrieve order# {$poyntOrderId} from poynt.{$e->getMessage()}");

            return [];
        }
    }

    /**
     * Checks if the order is ready for pickup.
     *
     * @since 1.3.0
     *
     * @param array $orderData from response body
     * @return bool
     */
    protected function isOrderReadyForPickup(array $orderData) : bool
    {
        if ('OPENED' != ArrayHelper::get($orderData, 'statuses.status')) {
            return false;
        }

        foreach (ArrayHelper::get($orderData, 'orderShipments', []) as $shipment) {
            if ('PICKUP' === ArrayHelper::get($shipment, 'deliveryMode')
                && 'AWAITING_PICKUP' === ArrayHelper::get($shipment, 'status')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handles communication when an order is marked as ready for pickup.
     *
     * @since 1.3.0
     *
     * @param array $orderData from response body
     * @param WC_Order $wcOrder
     * @throws Exception
     */
    protected function handleOrderReadyForPickup(array $orderData, WC_Order $wcOrder)
    {
        if ($wcOrder->get_meta('_poynt_order_status_ready_at')) {
            return;
        }

        $readyAtEvent = current(ArrayHelper::where(ArrayHelper::get($orderData, 'orderHistories', []), function ($value) {
            return 'AWAITING_PICKUP' === ArrayHelper::get($value, 'event');
        }));

        $readyAt = ArrayHelper::get($readyAtEvent, 'timestamp') ?: ArrayHelper::get($orderData, 'updatedAt');

        $wcOrder->add_meta_data('_poynt_order_status_ready_at', $readyAt);
        $wcOrder->save_meta_data();

        if ($timestamp = (int) strtotime($readyAt)) {
            $wcOrder->add_order_note(sprintf(
                /* translators: Placeholders: %1$s - date, %2$s - time */
                __('Order marked ready on terminal on %1$s at %2$s', 'godaddy-payments'),
                CommonHelper::getLocalizedDate(wc_date_format(), $timestamp),
                CommonHelper::getLocalizedDate(wc_time_format(), $timestamp)
            ));
        }

        WC()->mailer()->emails['ReadyForPickupEmail']->trigger($wcOrder->get_id(), $wcOrder);
    }

    /**
     * Handles an order completed event.
     *
     * @since 1.3.0
     * @param WC_Order $wcOrder
     */
    protected function handleOrderCompletedEvent(WC_Order $wcOrder)
    {
        if (! ArrayHelper::contains(['refunded', 'cancelled'], $wcOrder->get_status())) {
            $wcOrder->update_status('completed');
        }
    }

    /**
     * Handles an order cancelled event.
     *
     * @since 1.3.0
     * @param WC_Order $wcOrder
     */
    protected function handleOrderCancelledEvent(WC_Order $wcOrder)
    {
        if ('refunded' !== $wcOrder->get_status()) {
            $wcOrder->update_status('cancelled');
        }
    }
}
