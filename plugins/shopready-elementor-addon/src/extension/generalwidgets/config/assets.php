<?php
if (!defined('ABSPATH')) {
    exit;
}
return [

    // Elementor
    // handle name will be converted to - hyphen 
    'css' => [

        [
            'handle_name' => 'woo-ready-extra-widgets-base',
            'src' => SHOP_READY_URL . 'src/extension/generalwidgets/assets/css/core.css',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/assets/css/core.css',
            'minimize' => false,
            'public' => true,
            'media' => 'all',
            'deps' => [

            ]
        ],

        [
            'handle_name' => 'animatedheadline',
            'src' => SHOP_READY_URL . 'src/extension/generalwidgets/assets/css/animatedheadline.css',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/assets/css/animatedheadline.css',
            'minimize' => false,
            'public' => true,
            'media' => 'all',
            'deps' => [

            ]
        ],

        [
            'handle_name' => 'roadmap',
            'src' => SHOP_READY_URL . 'src/extension/generalwidgets/assets/css/roadmap.min.css',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/assets/css/roadmap.min.css',
            'minimize' => false,
            'public' => true,
            'media' => 'all',
            'deps' => [

            ]
        ],

        [
            'handle_name' => 'woo-ready-m-menu',
            'src' => SHOP_READY_URL . 'src/extension/generalwidgets/assets/css/menu.css',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/assets/css/menu.css',
            'minimize' => false,
            'public' => true,
            'media' => 'all',
            'deps' => [

            ]
        ]

    ],

    'js' => [

        [
            'handle_name' => 'woo-ready-extra-widgets',
            'src' => SHOP_READY_URL . 'src/extension/generalwidgets/assets/js/pro.js',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/assets/js/pro.js',
            'minimize' => false,
            'public' => true,
            // will load in_admin panel
            'in_footer' => false,
            'media' => 'all',
            'deps' => [
                'jquery'
            ]
        ],

        [
            'handle_name' => 'shop-ready-admin-menu',
            'src' => SHOP_READY_URL . 'src/extension/generalwidgets/assets/js/nav-admin.js',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/assets/js/nav-admin.js',
            'minimize' => false,
            'public' => false,
            // will load in_admin panel
            'in_footer' => true,
            'media' => 'all',
            'deps' => [
                'jquery'
            ]
        ],

        [
            'handle_name' => 'ajaxchimp',
            'src' => SHOP_READY_URL . 'src/extension/generalwidgets/assets/js/ajaxchimp.js',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/assets/js/ajaxchimp.js',
            'minimize' => false,
            'public' => true,
            // will load in_admin panel
            'in_footer' => false,
            'media' => 'all',
            'deps' => [
                'jquery'
            ]
        ],

        [
            'handle_name' => 'roadmap',
            'src' => SHOP_READY_URL . 'src/extension/generalwidgets/assets/js/roadmap.min.js',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/assets/js/roadmap.min.js',
            'minimize' => false,
            'public' => true,
            // will load in_admin panel
            'in_footer' => false,
            'media' => 'all',
            'deps' => [
                'jquery'
            ]
        ],

        [
            'handle_name' => 'animatedheadline',
            'src' => SHOP_READY_URL . 'src/extension/generalwidgets/assets/js/jquery.animatedheadline.min.js',
            'file' => SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/assets/js/jquery.animatedheadline.min.js',
            'minimize' => false,
            'public' => true,
            // will load in_admin panel
            'in_footer' => true,
            'media' => 'all',
            'deps' => [
                'jquery'
            ]
        ],


    ],

];