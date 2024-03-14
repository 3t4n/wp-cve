<?php

/**
 * Plugin Name: Indian Rupee Symbol For Woocommerce
 * Plugin URI: https://aftabmuni.wordpress.com/
 * Description: This plugin is used to display new Indian currency rupee symbol for WooCommerce
 * Version: 1.0.0
 * Author: Aftab Muni
 * Author URI: https://aftabmuni.wordpress.com/
 * @package Indian Rupee Symbol For Woocommerce
 * @version 1.0.0
 */
// Terminate if accessing directlty
if (!defined('ABSPATH')) {
    exit;
}

global $woocommerce;
if (isset($woocommerce) || !function_exists('WC')) {

    // load latest fontawesome fonts to display symbol
    function amm_add_scripts() {
        wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    }

    add_action('wp_head', 'amm_add_scripts');

    // load font to admin panel as well
    function amm_add_admin_scripts() {
        wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    }

    add_action('admin_enqueue_scripts', 'amm_add_admin_scripts');

    /**
     * @param $currencies
     * @return mixed
     */
    function amm_add_INR_currency($currencies) {
        $currencies['INR'] = '<i class="fa fa-rupee"></i> ';
        return $currencies;
    }

    add_filter('woocommerce_currency_symbols', 'amm_add_INR_currency');
}

