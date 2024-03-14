<?php

namespace WOOER;

if (!defined('ABSPATH')) {
    exit;
}

class Order_Manager {
    public static function init() {
        $self = new self();
        
        add_action('woocommerce_checkout_update_order_meta', array($self, 'checkout_update_order_meta'), 10, 2);
        //add_filter('woocommerce_get_order_currency', array($self, 'get_order_currency'), 9999, 2);
    }
    
    /**
     * Update customer checkout page
     * @param int $order_id
     * @param array $posted Array of posted form data
     */
    public function checkout_update_order_meta($order_id, $posted) {
        update_post_meta($order_id, '_order_currency', Currency_Manager::get_currency_code());
    }
    
    /**
     * WC HOOK
     * https://docs.woocommerce.com/wc-apidocs/source-class-WC_Abstract_Order.html
     * @param string $currency
     * @param WC_Abstract_Order $order
     * @return string
     */
    public function get_order_currency($currency, $order) {
        return Currency_Manager::get_currency_code();
    }
    
}

