<?php
if (!defined('ABSPATH')) {
    exit;
}
return [

    // Elementor
    // handle name will be converted to - hyphen 
    'css' => [

        [
            'handle_name' => 'shop-ready-astra',
            'src' => SHOP_READY_URL . 'src/extension/elewidgets/assets/css/astra.css',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/elewidgets/assets/css/astra.css',
            'minimize' => false,
            'public' => true,
            'media' => 'all',
            'deps' => [

            ]
        ],

        [
            'handle_name' => 'shop_ready_elementor_base',
            'src' => SHOP_READY_URL . 'src/extension/elewidgets/assets/css/core.css',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/elewidgets/assets/css/core.css',
            'minimize' => false,
            'public' => true,
            'media' => 'all',
            'deps' => [

            ]
        ],


        [
            'handle_name' => 'shop_ready_elementor_grid',
            'src' => SHOP_READY_URL . 'src/extension/elewidgets/assets/css/grid.css',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/elewidgets/assets/css/grid.css',
            'minimize' => false,
            'public' => true,
            'media' => 'all',
            'deps' => [

            ]
        ],

        [
            'handle_name' => 'woo_ready_position',
            'src' => SHOP_READY_URL . 'src/extension/elewidgets/assets/css/position-elements.css',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/elewidgets/assets/css/position-elements.css',
            'minimize' => false,
            'public' => true,
            'media' => 'all',
            'deps' => [

            ]
        ],

        [
            'handle_name' => 'shop-ready-vertical-menu',
            'src' => SHOP_READY_URL . 'src/extension/elewidgets/assets/css/vertical-menu.css',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/elewidgets/assets/css/vertical-menu.css',
            'minimize' => false,
            'public' => true,
            'media' => 'all',
            'deps' => [

            ]
        ]

    ],

    'js' => [

        [
            'handle_name' => 'shop-ready-elementor-base',
            'src' => SHOP_READY_URL . 'src/extension/elewidgets/assets/js/core.js',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/elewidgets/assets/js/core.js',
            'minimize' => false,
            'public' => true,
            // will load in_admin panel
            'in_footer' => true,
            'media' => 'all',
            'deps' => [
                'jquery',
                'nifty',
                'wp-util',
                'elementor-frontend'
            ]
        ],

        [
            'handle_name' => 'shop-ready-checkout',
            'src' => SHOP_READY_URL . 'src/extension/elewidgets/assets/js/checkout.js',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/elewidgets/assets/js/checkout.js',
            'minimize' => false,
            'public' => true,
            // will load in_admin panel
            'in_footer' => false,
            'media' => 'all',
            'deps' => [

            ]
        ],

        [
            'handle_name' => 'shop-ready-shop-cart',
            'src' => SHOP_READY_URL . 'src/extension/elewidgets/assets/js/cart.js',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/elewidgets/assets/js/cart.js',
            'minimize' => false,
            'public' => true,
            // will load in_admin panel
            'in_footer' => false,
            'media' => 'all',
            'deps' => [

            ]
        ],

        [
            'handle_name' => 'shop-ready-single-product',
            'src' => SHOP_READY_URL . 'src/extension/elewidgets/assets/js/single-product.js',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/elewidgets/assets/js/single-product.js',
            'minimize' => false,
            'public' => true,
            // will load in_admin panel
            'in_footer' => false,
            'media' => 'all',
            'deps' => [
                'wc-single-product'
            ]
        ],


    ],

];