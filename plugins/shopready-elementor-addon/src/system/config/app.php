<?php
if (!defined('ABSPATH')) {
    exit;
}
return [
    'app' => [
        'product_name' => esc_html__('ShopReady', 'shopready-elementor-addon'),
        'author' => 'QuomodoSoft'
    ],

    'views' => [

        'templating' => SHOP_READY_DIR_PATH . 'src/extension/templates/views',
        'single_scene' => SHOP_READY_DIR_PATH . 'src/extension/templates/views/product/scene',
        'shop_scene' => SHOP_READY_DIR_PATH . 'src/extension/templates/views/shop/scene',
        'shop_archive_scene' => SHOP_READY_DIR_PATH . 'src/extension/templates/views/shop_archive/scene',
        'cart_scene' => SHOP_READY_DIR_PATH . 'src/extension/templates/views/cart/scene',
        'checkout_scene' => SHOP_READY_DIR_PATH . 'src/extension/templates/views/checkout/scene',
        'order_scene' => SHOP_READY_DIR_PATH . 'src/extension/templates/views/order/scene',
        'non_woo_single' => SHOP_READY_DIR_PATH . 'src/extension/templates/views/product',
        'single_editor' => SHOP_READY_DIR_PATH . 'src/extension/templates/views/product',
        'myaccount_scene' => SHOP_READY_DIR_PATH . 'src/extension/templates/views/account/scene',

    ],

    'templates' => [

        'single' => [
            'title' => esc_html__('Product', 'shopready-elementor-addon'),
            'active' => 1,
            'demo' => '#',
            'path' => 'product/single.php',
            'id' => '',
            'type' => 'select2'

        ],

        'shop' => [
            'title' => esc_html__('Shop', 'shopready-elementor-addon'),
            'active' => 1,
            'demo' => '#',
            'path' => 'shop/shop.php',
            'id' => '',
            'type' => 'select2'
        ],

        'shop_archive' => [
            'title' => esc_html__('Shop Archive', 'shopready-elementor-addon'),
            'active' => 0,
            'demo' => '#',
            'path' => 'shop/shop.php',
            'id' => '',
            'type' => 'select2'
        ],

        'cart' => [
            'title' => esc_html__('Cart', 'shopready-elementor-addon'),
            'active' => 1,
            'demo' => '#',
            'path' => 'cart/cart.php',
            'id' => '',
            'type' => 'select2'
        ],

        'empty_cart' => [
            'title' => esc_html__('Empty Cart', 'shopready-elementor-addon'),
            'active' => 1,
            'template_temp_id' => 65,
            'demo' => '#',
            'path' => 'cart/scene/empty-cart.php',
            'id' => '',
            'type' => 'select2'
        ],

        'checkout' => [
            'title' => esc_html__('CheckOut', 'shopready-elementor-addon'),
            'active' => 1,
            'demo' => '#',
            'path' => 'checkout/checkout.php',
            'id' => '',
            'type' => 'select2'
        ],

        // unused
        'order' => [
            'title' => esc_html__('Order', 'shopready-elementor-addon'),
            'active' => 1,
            'demo' => '#',
            'path' => 'order/order.php',
            'id' => '',
            'type' => 'select2'
        ],

        'order_received' => [
            'title' => esc_html__('Order Received', 'shopready-elementor-addon'),
            'active' => 1,
            'demo' => '#',
            'path' => 'order/order-details.php',
            'id' => '',
            'type' => 'select2'
        ],

        // unused  
        'quick_view' => [
            'title' => esc_html__('Product Quick View', 'shopready-elementor-addon'),
            'active' => 1,
            'demo' => '#',
            'id' => '',
            'type' => 'select2'
        ],

        'my_account' => [
            'title' => esc_html__('My Account', 'shopready-elementor-addon'),
            'active' => 1,
            'path' => 'account/account.php',
            'demo' => '#',
            'id' => '',
            'type' => 'select2'
        ],
        'account_orders' => [
            'title' => esc_html__('Account Orders', 'shopready-elementor-addon'),
            'active' => 1,
            'path' => 'account/order.php',
            'demo' => '#',
            'id' => '',
            'type' => 'select2'
        ],

        'account_orders_view' => [
            'title' => esc_html__('Account Orders View', 'shopready-elementor-addon'),
            'active' => 1,
            'path' => 'account/order-view.php',
            'demo' => '#',
            'id' => '',
            'type' => 'select2'
        ],

        'account_downloads' => [
            'title' => esc_html__('Account Download', 'shopready-elementor-addon'),
            'active' => 1,
            'path' => 'account/download.php',
            'demo' => '#',
            'id' => '',
            'type' => 'select2'
        ],

        'account_edit_address' => [
            'title' => esc_html__('Account Edit Address', 'shopready-elementor-addon'),
            'active' => 1,
            'path' => 'account/address.php',
            'demo' => '#',
            'id' => '',
            'type' => 'select2'
        ],

        'my_account_edit' => [
            'title' => esc_html__('Account Edit', 'shopready-elementor-addon'),
            'active' => 1,
            'path' => 'account/details.php',
            'demo' => '#',
            'id' => '',
            'type' => 'select2'
        ],

        'my_account_login_register' => [
            'title' => esc_html__('Login Register', 'shopready-elementor-addon'),
            'active' => 1,
            'path' => 'account/login.php',
            'demo' => '#',
            'id' => '',
            'type' => 'select2'
        ]

    ],

];