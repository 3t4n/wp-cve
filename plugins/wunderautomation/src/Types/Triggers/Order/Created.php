<?php

namespace WunderAuto\Types\Triggers\Order;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Created
 */
class Created extends BaseTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Orders', 'wunderauto');
        $this->title       = __('Order created', 'wunderauto');
        $this->description = __(
            'This trigger fires when an order is first created and is assigned an id. Note that at this stage ' .
            'the order is still editable regarding order items, payment method, payment status, fees etc. ' .
            'Setting a trigger at creation might be too early for many workflows.',
            'wunderauto'
        );

        $this->addProvidedObject(
            'order',
            'order',
            __('The order that was created', 'wunderauto'),
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
            add_action('woocommerce_new_order', [$this, 'orderCreate'], 99, 2);
        }
        $this->registered = true;
    }

    /**
     * Event handler for the hooked event
     *
     * @param int    $orderId
     * @param object $data
     *
     * @return void
     */
    public function orderCreate($orderId, $data)
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
