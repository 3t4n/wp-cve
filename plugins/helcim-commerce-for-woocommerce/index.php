<?php
/*
Plugin Name: Helcim Commerce for WooCommerce
Plugin URI: https://www.helcim.com/
Description: Helcim Commerce for WooCommerce
Version: 4.0.3
Author: Helcim Inc.
Author URI: https://www.helcim.com/
*/

add_action('plugins_loaded', 'woocommerce_helcim_init', 0);

function woocommerce_helcim_init() {
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    require_once plugin_dir_path(__FILE__) . 'WCHelcimGateway.php';

    function add_helcim_commerce($methods)
    {
        $methods[] = 'WCHelcimGateway';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_helcim_commerce');
}
