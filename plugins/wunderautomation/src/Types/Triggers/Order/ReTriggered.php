<?php

namespace WunderAuto\Types\Triggers\Order;

use WunderAuto\Types\Triggers\BaseReTrigger;

/**
 * Class ReTriggered
 */
class ReTriggered extends BaseReTrigger
{
    /**
     * @var array<int, int>
     */
    private $triggeredPosts;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->addProvidedObject(
            'order',
            'order',
            __('The order object', 'wunderauto'),
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

        $this->triggeredPosts = [];
    }

    /**
     * @param \WP_Post $post
     *
     * @return array<string, object>|false
     */
    public function getObjects($post)
    {
        if (in_array($post->ID, $this->triggeredPosts)) {
            return false;
        }

        $order = wc_get_order($post->ID);
        if ($order instanceof \WC_Order) {
            $user = $order->get_user();
            if ($user === false) {
                $user = wa_empty_wp_user();
            }
            return $this->getResolverObjects(['order' => $order, 'user' => $user]);
        }

        return false;
    }
}
