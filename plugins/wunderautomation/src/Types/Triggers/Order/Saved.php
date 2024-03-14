<?php

namespace WunderAuto\Types\Triggers\Order;

use WP_Post;
use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Saved
 */
class Saved extends BaseTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->title       = __('Order saved', 'wunderauto');
        $this->group       = __('Orders', 'wunderauto');
        $this->description = __(
            'This trigger fires an order is saved (updated)',
            'wunderauto'
        );

        $this->addProvidedObject(
            'order',
            'order',
            __('The order that had was saved', 'wunderauto'),
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
            add_action('save_post', [$this, 'savePost'], 20, 2);
        }
        $this->registered = true;
    }

    /**
     * Handler for the save_post action
     *
     * @param int     $orderId
     * @param WP_Post $post
     *
     * @return void
     */
    public function savePost($orderId, $post)
    {
        if ($post->post_type == 'revision') {
            return;
        }

        if ($post->post_type != 'shop_order') {
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
