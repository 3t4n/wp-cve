<?php

namespace WunderAuto\Types\Triggers\Order;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Failed
 */
class Failed extends BaseTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Orders', 'wunderauto');
        $this->title       = __('Order failed', 'wunderauto');
        $this->description = __(
            'This trigger fires when an order failed and the status is set to wc-failed.',
            'wunderauto'
        );

        $this->addProvidedObject(
            'order',
            'order',
            __('The order that failed', 'wunderauto'),
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
            add_action('woocommerce_order_status_failed', [$this, 'orderStatusFailed'], 10, 1);
        }
        $this->registered = true;
    }

    /**
     * Handle the woocommerce_order_status_processing action
     *
     * @param int $orderId
     *
     * @return void
     */
    public function orderStatusFailed($orderId)
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
