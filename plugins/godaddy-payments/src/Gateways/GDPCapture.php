<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Gateways;

use GoDaddy\WooCommerce\Poynt\Helpers\WCHelper;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * Godaddy payments capture handler.
 *
 * @since 1.3.0
 */
class GDPCapture extends Framework\Payment_Gateway\Handlers\Capture
{
    /**
     * Captures an order on status change to a "paid" status.
     * Overrides the parent method to remove the gateway_id check from WC_Order.
     *
     * @internal
     *
     * @since 1.3.0
     *
     * @param int $order_id order ID
     * @param string $old_status status being changed
     * @param string $new_status new order status
     */
    public function maybe_capture_paid_order($order_id, $old_status, $new_status)
    {
        $paid_statuses = (array) wc_get_is_paid_statuses();

        // bail if changing to a non-paid status or from a paid status
        if (! in_array($new_status, $paid_statuses, true)) {
            return;
        }

        $order = wc_get_order($order_id);

        if (! $order) {
            return;
        }

        if (! WCHelper::orderHasPoyntProvider($order)) {
            return;
        }

        $this->maybe_perform_capture($order);
    }
}
