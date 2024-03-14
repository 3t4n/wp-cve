<?php
defined('ABSPATH') || exit;
/* all shortcode basic settings 
 * array widgets key is shortcode unique identifier
 */
return [

    //  Extension Config
    'meta' => [
        'name' => esc_html__('Wp Shortcode', 'shopready-elementor-addon'),
        'description' => esc_html__('Wp Shortcode use for base widget setting that can run any editor', 'shopready-elementor-addon'),
        'author' => 'quomodosoft'
    ],

    'widgets' => [

        'shop_ready_countdown' => [
            'name' => esc_html__('Shop Ready Countdown', 'shopready-elementor-addon'),
            'category' => ['shop', 'product'],
            // configure defaults settings otherwise will not works
            'defaults' => [
                'expire_date' => null,
                'expire_time' => null,
                'style' => 'default'
            ],
        ],

        'shop_ready_notice' => [
            'name' => esc_html__('WooCommerce Notice', 'shopready-elementor-addon'),
            'category' => ['Account', 'Checkout'],
            // configure defaults settings otherwise will not works
            'defaults' => [
                'type' => 'default',
            ],
        ],

    ],


];