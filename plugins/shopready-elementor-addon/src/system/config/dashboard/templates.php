<?php

if (!defined('ABSPATH')) {
  exit;
}

return array(
  'templates' =>
  array(
    'single' =>
    array(
      'title' => esc_html__('Product', 'shopready-elementor-addon'),
      'is_pro' => false,
      'active' => 1,
      'demo' => '#',
      'path' => 'product/single.php',
      'id' => '',
      'type' => 'select2',
      'add_new' => true,
      'presets_active' => 0,
      'presets_path' => 'product',
      'presets_active_path' => '',
      'presets' => [

        'content-one' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'product/presets/content-one.php'
        ],
        'content-two' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'product/presets/content-two.php'
        ],
        'content-three' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'product/presets/content-three.php'
        ],

      ]
    ),
    'variable_single' =>
    array(
      'title' => esc_html__('Variable Product', 'shopready-elementor-addon'),
      'is_pro' => true,
      'active' => 1,
      'demo' => '#',
      'path' => 'product/single.php',
      'id' => '',
      'type' => 'select2',
      'add_new' => true,
      'presets_active' => false,
      'presets_path' => 'vproduct',
      'presets_active_path' => '',
    ),
    'grouped_single' =>
    array(
      'title' => esc_html__('Grouped Product', 'shopready-elementor-addon'),
      'is_pro' => true,
      'active' => 1,
      'demo' => '#',
      'path' => 'product/single.php',
      'id' => '',
      'type' => 'select2',
      'add_new' => true,
      'presets_active' => 0,
      'presets_path' => 'gproduct',
      'presets_active_path' => '',
    ),
    'shop' =>
    array(
      'title' => esc_html__('Shop', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => false,
      'demo' => '#',
      'path' => 'shop/shop.php',
      'id' => '',
      'type' => 'select2',
      'add_new' => true,
      'presets_active' => 0,
      'presets_path' => 'tpl',
      'presets_active_path' => '',
      'presets' => [

        'classic' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'grid/layout-classic.php',
          'pro' => false
        ],

        'side_flip_center' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'presets/layout-side_flip_center.php',
          'pro' => false
        ],

        'side_flip' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'grid/layout-side_flip.php',
          'pro' => true
        ],

        'side_flip_center_two' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'grid/layout-side_flip_center_two.php',
          'pro' => true
        ],

        'side_flip_left' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'grid/layout-side_flip_left.php',
          'pro' => true
        ],

        'eforest' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'grid/layout-eforest.php',
          'pro' => true
        ],

      ]
    ),
    'shop_archive' =>
    array(
      'title' => esc_html__('Shop Archive', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => true,
      'demo' => '#',
      'path' => 'shop/shop.php',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'shop_archive',
      'presets_active_path' => '',
    ),
    'cart' => array(

      'title' => esc_html__('Cart', 'shopready-elementor-addon'),
      'active' => 0,
      'is_pro' => true,
      'demo' => '#',
      'path' => 'cart/cart.php',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'cart',
      'presets_active_path' => '',
      'presets' => [

        'cart-style1' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'cart/layout-style1.php',
          'pro' => false
        ],

        'cart-style2' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'cart/layout-style2.php',
          'pro' => false
        ],

        'cart-style3' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'cart/layout-style3.php',
          'pro' => true
        ]

      ]

    ),

    'empty_cart' =>
    array(
      'title' => esc_html__('Empty Cart', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => true,
      'template_temp_id' => '',
      'demo' => '#',
      'path' => 'cart/scene/empty-cart.php',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'empty_cart',
      'presets_active_path' => '',
    ),

    'checkout' =>
    array(
      'title' => esc_html__('CheckOut', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => true,
      'demo' => '#',
      'path' => 'checkout/checkout.php',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'checkout',
      'presets_active_path' => '',
      'presets' => [

        'form-checkout-one' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'checkout/checkout-one.php',
          'pro' => false
        ],

        'form-checkout-two' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'checkout/form-checkout-two.php',
          'pro' => false
        ],

        'form-checkout-three' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'checkout/form-checkout-three.php',
          'pro' => false
        ],


      ]
    ),
    'order' =>
    array(
      'title' => esc_html__('Order', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => true,
      'demo' => '#',
      'path' => 'order/order.php',
      'id' => '',
      'type' => 'select2',
      'add_new' => true,
      'presets_active' => 0,
      'presets_path' => 'order',
      'presets_active_path' => '',
    ),

    'order_received' =>
    array(
      'title' => esc_html__('Thank You Order', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => false,
      'demo' => '#',
      'path' => 'order/order-details.php',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'thankyou',
      'presets_active_path' => 'presets',
      'presets' => [

        'layout-one' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'thankyou/layout-one.php',
          'pro' => false
        ],

        'layout-three' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'thankyou/layout-three.php',
          'pro' => false
        ],

        'layout-two' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'thankyou/layout-two.php',
          'pro' => false
        ]
      ]
    ),

    'my_account' =>
    array(
      'title' => esc_html__('My Account', 'shopready-elementor-addon'),
      'active' => 0,
      'is_pro' => false,
      'path' => 'account/account.php',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'myaccount',
      'presets_active_path' => '',
      'presets' => [

        'my-account-one' => [

          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'login/my-account-one.php',
          'pro' => false

        ],

        'my-account-two' => [

          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'login/my-account-two.php',
          'pro' => false

        ],

        'my-account-three' => [

          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'login/my-account-three.php',
          'pro' => false

        ]

      ]
    ),
    'account_orders' =>
    array(
      'title' => esc_html__('Account Orders', 'shopready-elementor-addon'),
      'active' => 0,
      'is_pro' => false,
      'path' => 'account/order.php',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'account_order',
      'presets_active_path' => '',
    ),
    'account_orders_view' =>
    array(
      'title' => esc_html__('Account Orders View', 'shopready-elementor-addon'),
      'active' => 0,
      'is_pro' => false,
      'path' => 'account/order-view.php',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'order_view',
      'presets_active_path' => '',
    ),
    'account_downloads' =>
    array(
      'title' => esc_html__('Account Download', 'shopready-elementor-addon'),
      'active' => 0,
      'is_pro' => false,
      'path' => 'account/download.php',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'account_download',
      'presets_active_path' => '',
    ),
    'account_edit_address' =>
    array(
      'title' => esc_html__('Account Edit Address', 'shopready-elementor-addon'),
      'active' => 0,
      'is_pro' => false,
      'path' => 'account/address.php',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'account_edit_address',
      'presets_active_path' => '',
    ),
    'my_account_edit' =>
    array(
      'title' => esc_html__('Account Edit', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => false,
      'path' => 'account/details.php',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'account_edit',
      'presets_active_path' => '',
    ),
    'my_account_login_register' =>
    array(
      'title' => esc_html__('Login Register', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => true,
      'path' => 'account/login.php',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'login',
      'presets_active_path' => '',
      'presets' => [

        'guest-account-one' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'login/layout-one.php',
          'pro' => false
        ],

        'guest-account-two' => [
          'img_path' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'demo_url' => 'https://dummyimage.com/100x100/464099/fcfcfc',
          'path' => 'login/layout-two.php',
          'pro' => false
        ]

      ]
    ),

    'woo_ready_enable_product_wishlist_template_id' =>
    array(
      'title' => esc_html__('WishList PopUp', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => true,
      'path' => '#',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',

      'presets_active' => 0,
      'presets_path' => 'whishlist',
      'presets_active_path' => '',
    ),
    'woo_ready_enable_product_compare_template_id' =>
    array(
      'title' => esc_html__('Product Compare PopUp', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => true,
      'path' => '#',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'compare',
      'presets_active_path' => '',
    ),
    'shop_ready_product_quickview_popup_ele_template_id' =>
    array(
      'title' => esc_html__('Product QuickView PopUp', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => true,
      'path' => '#',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'quickview',
      'presets_active_path' => '',
    ),

    'shop_ready_quick_checkout_template_id' =>
    array(
      'title' => esc_html__('Quick Checkout PopUp', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => true,
      'path' => '#',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'quick_checkout',
      'presets_active_path' => '',
    ),

    'shop_ready_newslatte_template_id' =>
    array(
      'title' => esc_html__('Newslatter PopUp', 'shopready-elementor-addon'),
      'active' => 1,
      'is_pro' => true,
      'path' => '#',
      'demo' => '#',
      'id' => '',
      'add_new' => true,
      'type' => 'select2',
      'presets_active' => 0,
      'presets_path' => 'newslatter',
      'presets_active_path' => '',
    ),
  ),
);