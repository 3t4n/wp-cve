<?php

namespace WunderAuto\Types\Triggers\Order;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Paid
 */
class Paid extends BaseTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Orders', 'wunderauto');
        $this->title       = __('Order paid', 'wunderauto');
        $this->description = __(
            'This trigger fires when an order is paid, meaning that the actual order status is changed from a non ' .
            'paid status (new, on-hold, pending etc.) to a paid status (processing or completed).',
            'wunderauto'
        );

        $this->addProvidedObject(
            'order',
            'order',
            __('The order that was set to paid', 'wunderauto'),
            true
        );
        $this->addProvidedObject(
            'user',
            'user',
            __(
                'The WordPress user that placed the order. Guest orders provides a valid but empty user object',
                'wunderauto'
            ),
            true
        );
    }

    /**
     * Register our hooks with WordPress
     *
     * @return void
     */
    public function registerHooks()
    {
        if (!$this->registered) {
            add_action('woocommerce_order_status_changed', [$this, 'orderStatusChanged'], 50, 3);
        }
        $this->registered = true;
    }

    /**
     * Event handler order_status_changed
     *
     * @param int    $orderId
     * @param string $oldStatus
     * @param string $newStatus
     *
     * @return void
     */
    public function orderStatusChanged($orderId, $oldStatus, $newStatus)
    {
        $paidStatuses = apply_filters('woocommerce_order_is_paid_statuses', ['processing', 'completed']);

        if (in_array($oldStatus, $paidStatuses)) {
            return;
        }

        if (!in_array($newStatus, $paidStatuses)) {
            return;
        }

        $order = wc_get_order($orderId);
        if ($order instanceof \WC_Order) {
            $user = $order->get_user();
            if ($user === false) {
                $user = wa_empty_wp_user();
            }
            $this->doTrigger(['order' => $order, 'user' => $user]);
        }
    }
}
