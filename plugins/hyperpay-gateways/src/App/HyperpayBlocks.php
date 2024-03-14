<?php

namespace Hyperpay\Gateways\App;


trait HyperpayBlocks
{
    public $isExpress = false;
    public $isDynamicCheck = false;
    public $supports = ["products"];
    public $action_button = '';



    public function canMakePayment()
    {
        return  wp_send_json(["canMakePayment" => true]);
    }

    /**
     * Initializes the payment method type.
     */

    public function initialize()
    {
    }

    public function extraScriptData()
    {
        return [];
    }

    /**
     * Check if the store uses blocks on the cart or checkout page.
     *
     * @return boolean
     */
    public static function has_checkout_block()
    {
        $checkout_id   = wc_get_page_id('checkout');
        $has_classic_checkout = $checkout_id && has_block('woocommerce/classic-shortcode', $checkout_id);

        return !$has_classic_checkout;
    }
}
