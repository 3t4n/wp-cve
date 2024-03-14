<?php
if (!defined('ABSPATH')) {
    exit;
}
/************************** ***************** 
 * 
 * all Widgets Meta and category settings 
 * since 1.0
 * Registerd Widget Category for Widget Config
 *
 ***********************************************/
return [

    //  Extension Config
    'meta' => [
        'name' => esc_html__('Shop Ready Elementor Widgets', 'shopready-elementor-addon'),
        'description' => esc_html__('ELementor Widget Extension to use basic meta and category config', 'shopready-elementor-addon'),
        'author' => 'quomodosoft'
    ],

    'categories' => [

        'sready-gen' => [
            'name' => esc_html__('Shop Ready General', 'shopready-elementor-addon'),
            'icon' => 'eicon-basket-solid'
        ],

        'shopready-elementor-addon' => [
            'name' => esc_html__('Shop Ready', 'shopready-elementor-addon'),
            'icon' => 'eicon-basket-solid'
        ],

        'sready-product' => [
            'name' => esc_html__('Shop Ready Product', 'shopready-elementor-addon'),
            'icon' => 'eicon-product-pages'
        ],

        'sready-account' => [
            'name' => esc_html__('Shop Ready Account', 'shopready-elementor-addon'),
            'icon' => 'eicon-my-account'
        ],

        'sready-checkout' => [
            'name' => esc_html__('Shop Ready Checkout', 'shopready-elementor-addon'),
            'icon' => 'eicon-paypal-button'
        ],

        'sready-cart' => [
            'name' => esc_html__('Shop Ready Cart', 'shopready-elementor-addon'),
            'icon' => 'eicon-cart'
        ],

    ],


];