<?php

namespace WunderAuto\Types\Triggers\Order;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Completed
 */
class Completed extends BaseTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Orders', 'wunderauto');
        $this->title       = __('Order completed', 'wunderauto');
        $this->description = __(
            'This trigger fies when an order marked as completed and status set to wc-completed.',
            'wunderauto'
        );

        $this->description = __(
            'This trigger fires when an order status is changed to completed. This normally happens after payment is ' .
            'secured and the store owner then manually sets the status from processing to completed.',
            'wunderauto'
        );

        $this->addProvidedObject(
            'order',
            'order',
            __('The order that was completed', 'wunderauto'),
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
            add_action('woocommerce_order_status_completed', [$this, 'orderStatusCompleted'], 10, 1);
        }
        $this->registered = true;
    }

    /**
     * Event handler for the hooked event
     *
     * @param int $orderId
     *
     * @return void
     */
    public function orderStatusCompleted($orderId)
    {
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
