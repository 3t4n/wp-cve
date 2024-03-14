<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Pages;

use WC_Order;

/**
 * View order page.
 *
 * @since 1.3.0
 */
class ViewOrderPage
{
    /**
     * ViewOrderPage constructor.
     *
     * @since 1.3.0
     */
    public function __construct()
    {
        $this->registerHooks();
    }

    /**
     * Register actions and filters hooks.
     *
     * @since 1.3.0
     *
     * @return void
     */
    protected function registerHooks()
    {
        add_action('woocommerce_order_details_before_order_table_items', [$this, 'maybeAddReadyForPickup']);
    }

    /**
     * Maybe add some text informing the user that their order is ready for pickup.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @param WC_Order $order
     * @return void
     */
    public function maybeAddReadyForPickup(WC_Order $order)
    {
        // don't show if order was not marked as ready for pickup
        if (empty($order->get_meta('_poynt_order_status_ready_at'))) {
            return;
        }

        // don't show if order is not in a status where it makes sense
        if (! in_array($order->get_status(), ['pending', 'processing', 'on-hold'])) {
            return;
        } ?><p><?php echo esc_html__('Order is ready for pickup.', 'godaddy-payments'); ?></p><?php
    }
}
