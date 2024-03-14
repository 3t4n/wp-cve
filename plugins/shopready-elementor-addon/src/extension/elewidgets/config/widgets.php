<?php
if (!defined('ABSPATH')) {
    exit;
}
/**************************************** 
 * all Widgets settings 
 * Widget will read this array
 * since 1.0
   

/****************************************
 * widgets/components Config
 * slug = directory_name+filename
 * key should be lower_case
 **************** *************/

$return_component = [

    'shop_product_grid' => [
        'title' => esc_html__('Creative Product Grid', 'shopready-elementor-addon'),
        'icon' => 'eicon-products',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['shop', 'grid', 'product grid', 'creative', 'product'],

    ],

    'shop_sr_deals_products_counter' => [
        'title' => esc_html__('Deal Product Counter', 'shopready-elementor-addon'),
        'icon' => 'eicon-products',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['counter', 'slider', 'product deals', 'deals', 'product counter'],
        'js' => [
            'owl-carousel',
            'woo-ready-extra-widgets'
        ],
        'css' => ['shop-ready-pro-common-base', 'owl-carousel']
    ],

    'shop_product_list' => [
        'title' => esc_html__('Product List', 'shopready-elementor-addon'),
        'icon' => 'eicon-products',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['shop', 'list', 'product list', 'product'],
    ],

    'shop_sidebar_product_attribute_filter' => [
        'title' => esc_html__('Product Attribute Filter', 'shopready-elementor-addon'),
        'icon' => 'eicon-filter',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['shop', 'product filter', 'attribute', 'filter'],
        'js' => [
            'shop-ready-elementor-base'
        ],
        'css' => []
    ],

    'shop_sidebar_sr_category_filter' => [
        'title' => esc_html__('Shop Category Filter', 'shopready-elementor-addon'),
        'icon' => 'eicon-filter',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['shopready-elementor-addon', 'category', 'shop sidebar', 'widget'],
        'dashboard' => 'yes',
        'keywords' => ['shop', 'product filter', 'attribute', 'filter'],
        'js' => [
            'shop-ready-elementor-base'
        ],
        'css' => []
    ],

    'shop_sidebar_cart' => [
        'title' => esc_html__('Shop Cart Content', 'shopready-elementor-addon'),
        'icon' => 'eicon-cart',
        'show_in_panel' => false,
        'is_pro' => false,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['Shop ready', 'shop', 'sidebar', 'cart content'],
        'js' => [],
        'css' => []
    ],

    'shop_sidebar_nav_filter' => [
        'title' => esc_html__('Shop Nav Filter', 'shopready-elementor-addon'),
        'icon' => 'eicon-filter',
        'show_in_panel' => false,
        'is_pro' => false,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['shop ready', 'shop', 'layer nav', 'Nav Filter'],
        'js' => [],
        'css' => []
    ],

    'shop_sidebar_price_filter' => [
        'title' => esc_html__('Shop Price Filter', 'shopready-elementor-addon'),
        'icon' => 'eicon-product-price',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['shop ready', 'shop', 'Filter', 'price Filter'],
        'js' => [
            'wc-price-slider'
        ],
        'css' => ['woocommerce-general']
    ],

    'shop_sidebar_rating_filter' => [
        'title' => esc_html__('Shop Rating Filter', 'shopready-elementor-addon'),
        'icon' => 'eicon-star-o',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['Shop ready', 'shop sideabr', 'Filter', 'Rating Filter'],
        'js' => [],
        'css' => []
    ],

    'shop_sidebar_search_form' => [
        'title' => esc_html__('Shop Search', 'shopready-elementor-addon'),
        'icon' => 'eicon-site-search',
        'show_in_panel' => false,
        'is_pro' => false,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['Shop ready', 'shop sideabr', 'Search', ' Search Form'],
        'js' => [
            'nice-select',
            'shop-ready-elementor-base'
        ],
        'css' => ['nice-select', 'shop-ready-elementor-base', 'woo-ready-extra-widgets-base']
    ],

    'shop_shop_ready_sidebar_cart' => [
        'title' => esc_html__('Drawer Cart', 'shopready-elementor-addon'),
        'icon' => 'eicon-site-search',
        'show_in_panel' => false,
        'is_pro' => false,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['cart', 'shop sidebar', 'floating', 'shop cart'],
        'js' => [
            'woo-ready-extra-widgets',
            'wp-util'
        ],
        'css' => ['woo-ready-extra-widgets-base']
    ],

    'general_popup' => [
        'title' => esc_html__('Mini PopUp', 'shopready-elementor-addon'),
        'icon' => 'eicon-frame-minimize',
        'show_in_panel' => false,
        'is_pro' => false,
        'category' => ['sready-gen'],
        'dashboard' => 'yes',
        'keywords' => ['Shop ready', 'login', 'account', 'signin', 'popup']
    ],

    'general_floating_cart' => [
        'title' => esc_html__('Floating Cart', 'shopready-elementor-addon'),
        'icon' => 'eicon-woo-cart',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-gen'],
        'dashboard' => 'yes',
        'keywords' => ['shop ready', 'floating cart', 'floating', 'cart', 'popup']
    ],

    'general_category_slider' => [
        'title' => esc_html__('Category Slider', 'shopready-elementor-addon'),
        'icon' => 'eicon-slider-album',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['Shop Ready', 'Category Slider', 'Category', 'Slider'],
        'js' => [
            'jquery',
            'slick'
        ],
        'css' => [
            'slick'
        ]
    ],

    'general_product_slider' => [
        'title' => esc_html__('Product Slider', 'shopready-elementor-addon'),
        'icon' => 'eicon-slider-album',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['shopready-elementor-addon'],
        'dashboard' => 'yes',
        'keywords' => ['shop ready', 'product slider', 'category', 'slider'],
        'js' => [
            'jquery',
            'owl-carousel'
        ],
        'css' => [
            'owl-carousel'
        ]
    ],


    'general_woo_ready_social_share' => [
        'title' => esc_html__('Social Share', 'shopready-elementor-addon'),
        'icon' => 'eicon-share',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-product'],
        'dashboard' => 'yes',
        'keywords' => ['Social', 'Product Share', 'Social Button'],
        'js' => [
            'goodshare'
        ],
        'css' => [
            'qsocial-share'
        ]
    ],

    'general_promotional_banner' => [
        'title' => esc_html__('Promotional Banner', 'shopready-elementor-addon'),
        'icon' => 'eicon-banner',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-gen'],
        'dashboard' => 'yes',
        'keywords' => ['Shop Ready', 'promotional banner', 'promotional', 'banner'],
    ],

    'login_register_login' => [
        'title' => esc_html__('Account Login', 'shopready-elementor-addon'),
        'icon' => 'eicon-sign-out',
        'show_in_panel' => false,
        'is_pro' => false,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['shop ready', 'login', 'account', 'signin', 'form']
    ],

    'login_register_lost_pass' => [
        'title' => esc_html__('Lost Password', 'shopready-elementor-addon'),
        'icon' => 'eicon-sign-out',
        'show_in_panel' => false,
        'is_pro' => false,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Shop Ready', 'login', 'lost password', 'form']
    ],

    'login_register_lost_pass_msg' => [
        'title' => esc_html__('Lost Password Msg', 'shopready-elementor-addon'),
        'icon' => 'eicon-header',
        'show_in_panel' => false,
        'is_pro' => false,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['message', 'password', 'lost password message', 'lost']
    ],

    'login_register_lost_pass_error' => [
        'title' => esc_html__('Lost Password Error Msg', 'shopready-elementor-addon'),
        'icon' => 'eicon-header',
        'show_in_panel' => false,
        'is_pro' => false,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['lost', 'shop ready', 'lost password alert', 'error']
    ],

    'login_register_user_generate' => [
        'title' => esc_html__('User Register', 'shopready-elementor-addon'),
        'icon' => 'eicon-lock-user',
        'show_in_panel' => false,
        'is_pro' => false,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Login Register', 'shop ready', 'User genrate', 'email send']
    ],

    'account_navigation' => [
        'title' => esc_html__('Account Nav', 'shopready-elementor-addon'),
        'icon' => 'eicon-navigation-horizontal',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Account', 'Navigation', 'tab', 'shop ready']
    ],

    'account_dashboard' => [
        'title' => esc_html__('Account Dashboard', 'shopready-elementor-addon'),
        'icon' => 'eicon-dashboard',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Account', 'dashboard', 'user details', 'customer logout']
    ],

    'account_orders' => [
        'title' => esc_html__('Account Orders', 'shopready-elementor-addon'),
        'icon' => 'eicon-my-account',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Account', 'dashboard', 'orders', 'order list']
    ],
    'account_order_view' => [
        'title' => esc_html__('Orders View', 'shopready-elementor-addon'),
        'icon' => 'eicon-preview-medium',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Account', 'dashboard', 'order details', 'order view']
    ],

    'account_order_view_notes' => [
        'title' => esc_html__('Orders View Notes', 'shopready-elementor-addon'),
        'icon' => 'eicon-post-info',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Account', 'dashboard', 'order notes', 'order view']
    ],

    'account_order_address' => [
        'title' => esc_html__('Order view Address', 'shopready-elementor-addon'),
        'icon' => 'eicon-google-maps',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Account', 'address', 'order view address', 'billing address', 'shipping']
    ],

    'account_order_downloads' => [
        'title' => esc_html__('Order view Downloads', 'shopready-elementor-addon'),
        'icon' => 'eicon-file-download',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Account', 'order downlaods', 'order view download', 'downlaod']
    ],

    'account_order_again' => [
        'title' => esc_html__('Order Again button', 'shopready-elementor-addon'),
        'icon' => 'eicon-button',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Account', 'order again', 'order view button', 'button']
    ],


    'account_downloads' => [
        'title' => esc_html__('SR Account Downloads', 'shopready-elementor-addon'),
        'icon' => 'eicon-file-download',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Account', 'downlaods', 'Download Template']
    ],

    'account_edit_address' => [
        'title' => esc_html__('SR Account Address', 'shopready-elementor-addon'),
        'icon' => 'eicon-google-maps',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Account', 'address', 'edit address', 'billing form', 'shipping form']
    ],


    'account_edit_account' => [
        'title' => esc_html__('SR Edit Account', 'shopready-elementor-addon'),
        'icon' => 'eicon-edit',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-account'],
        'dashboard' => 'yes',
        'keywords' => ['Account', 'edit account', 'account details', 'account form']
    ],



    'checkout_mini_cart' => [
        'title' => esc_html__('Mini Cart', 'shopready-elementor-addon'),
        'icon' => 'eicon-woo-cart',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['Mini Cart', 'checkout mini cart'],
        'js' => [
            'jquery',
            'shop-ready-checkout'
        ]
    ],

    'checkout_cart' => [
        'title' => esc_html__('Cart', 'shopready-elementor-addon'),
        'icon' => 'eicon-cart',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['Cart', 'checkout cart'],
        'js' => [
            'jquery',
            'shop-ready-checkout'
        ]
    ],

    'checkout_cart_sub_total' => [
        'title' => esc_html__('Cart Sub Total', 'shopready-elementor-addon'),
        'icon' => 'eicon-sort-amount-desc',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['subtotal', 'checkout cart sub Total', 'cart total'],
        'js' => [
            'jquery',
            'shop-ready-checkout'
        ]
    ],


    'checkout_cart_total' => [
        'title' => esc_html__('Cart Total', 'shopready-elementor-addon'),
        'icon' => 'eicon-sort-amount-desc',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['checkout cart Total', 'cart total'],
        'js' => [
            'jquery',
            'shop-ready-checkout'
        ]
    ],

    'checkout_cart_shipping' => [
        'title' => esc_html__('Cart Shipping', 'shopready-elementor-addon'),
        'icon' => 'eicon-banner',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['checkout cart shipping', 'cart shipping'],
        'js' => [
            'jquery',
            'shop-ready-checkout'
        ]
    ],

    'checkout_cart_tax' => [
        'title' => esc_html__('Cart Tax', 'shopready-elementor-addon'),
        'icon' => ' eicon-gallery-group',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['Tax', 'cart tax'],
        'js' => [
            'jquery',
            'shop-ready-checkout'
        ]
    ],

    'checkout_cart_fee' => [
        'title' => esc_html__('Cart Custom Fee', 'shopready-elementor-addon'),
        'icon' => ' eicon-gallery-group',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['Fees', 'Custom fee'],
        'js' => [
            'jquery',
            'shop-ready-checkout'
        ]
    ],

    'checkout_coupon' => [
        'title' => esc_html__('Cart Coupon', 'shopready-elementor-addon'),
        'icon' => ' eicon-gallery-group',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['Coupon', 'Cart Coupon'],
        'js' => [
            'jquery',
            'shop-ready-checkout'
        ]
    ],


    'checkout_login_form' => [
        'title' => esc_html__('Login Form', 'shopready-elementor-addon'),
        'icon' => 'eicon-sign-out',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['Login', 'login form'],
        'js' => [
            'jquery'
        ]
    ],


    'checkout_checkout' => [
        'title' => esc_html__('Checkout', 'shopready-elementor-addon'),
        'icon' => 'eicon-checkout',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['Payment', 'Checkout Payment', 'order review'],
        'js' => [
            'jquery',
            'shop-ready-checkout'
        ],
        'css' => [
            'shop-ready-elementor-base'
        ]
    ],

    'checkout_custom_checkout' => [
        'title' => esc_html__('Custom Checkout', 'shopready-elementor-addon'),
        'icon' => 'eicon-checkout',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['Payment', 'Checkout Payment', 'order review'],

    ],


    'checkout_empty_cart' => [
        'title' => esc_html__('Empty Cart', 'shopready-elementor-addon'),
        'icon' => 'eicon-cart-solid',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['checkout Empty cart'],

    ],



    'cart_shortcode' => [
        'title' => esc_html__('Cart', 'shopready-elementor-addon'),
        'icon' => 'eicon-product-add-to-cart',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-cart'],
        'keywords' => ['shop ready', 'cart', 'Shortcode'],
        'js' => [
            //'jquery','shop-ready-shop-cart'
            'jquery'
        ],

    ],

    // 'cart_custom_cart' => [
    //     'title'         => esc_html__('Custom Cart', 'shopready-elementor-addon'),
    //     'icon'          => 'eicon-product-add-to-cart',
    //     'show_in_panel' => false,
    //     'is_pro' => true,
    //     'dashboard'     => 'yes',
    //     'category'      => ['sready-cart'],
    //     'keywords'      => ['shopready', 'cart', 'custom cart'],

    // ],

    'cart_product_table' => [
        'title' => esc_html__('Cart Product Table', 'shopready-elementor-addon'),
        'icon' => 'eicon-table',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-cart'],
        'keywords' => ['Shop Ready', 'cart', 'cart table'],
        'js' => [
            'jquery',
            'shop-ready-shop-cart'
        ]
    ],

    // 'cart_collaterals' => [
    //     'title'         => esc_html__('Cart Collaterals', 'shopready-elementor-addon'),
    //     'icon'          => 'eicon-table',
    //     'show_in_panel' => false,
    //     'is_pro' => true,
    //     'dashboard'     => 'yes',
    //     'category'      => ['sready-cart'],
    //     'keywords'      => ['Shop Ready', 'cart', 'cart total'],
    //     'js'            => [
    //         'jquery', 'shop-ready-shop-cart'
    //     ]
    // ],

    'cart_crosssell' => [
        'title' => esc_html__('Cart Cross Sell', 'shopready-elementor-addon'),
        'icon' => 'eicon-gallery-grid',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-cart'],
        'keywords' => ['Shop Ready', 'cart cross Sell', 'Cross Sell'],
        'js' => []
    ],


    'product_title' => [
        'title' => esc_html__('Product title', 'shopready-elementor-addon'),
        'icon' => 'eicon-editor-h1',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product title', 'single', 'heading'],
        'js' => [
            'jquery',
            'shop-ready-single-product'
        ]
    ],

    'product_compare_popUp_button' => [
        'title' => esc_html__('Compare Button', 'shopready-elementor-addon'),
        'icon' => 'eicon-preview-thin',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product compare', 'compare', 'button'],
        'js' => [
            'jquery',
            'shop-ready-single-product'
        ]
    ],

    'product_wishlist_popUp_button' => [
        'title' => esc_html__('WishList Button', 'shopready-elementor-addon'),
        'icon' => 'eicon-bag-solid',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product wishlist', 'wishlist', 'button'],
        'js' => [
            'jquery',
            'shop-ready-single-product'
        ]
    ],

    'product_price' => [
        'title' => esc_html__('Price', 'shopready-elementor-addon'),
        'icon' => 'eicon-price-list',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product price', 'price'],
        'js' => [
            'jquery',
            'shop-ready-single-product'
        ]
    ],

    'product_comming_soon' => [
        'title' => esc_html__('Comming Soon', 'shopready-elementor-addon'),
        'icon' => 'eicon-clone',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['Comming Soon', 'Schedule'],
        'js' => []
    ],

    'product_grid' => [
        'title' => esc_html__('Product Grid', 'shopready-elementor-addon'),
        'icon' => 'eicon-gallery-grid',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['product grid', 'grid', 'product'],

    ],

    'shop_result_count' => [
        'title' => esc_html__('Grid Result Count', 'shopready-elementor-addon'),
        'icon' => 'eicon-gallery-grid',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['shop', 'shopready', 'result count'],

    ],

    'shop_catelog_ordering' => [
        'title' => esc_html__('Grid Catelog Ordering', 'shopready-elementor-addon'),
        'icon' => 'eicon-gallery-grid',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['shop', 'shopready', 'Catelog Order', 'dropdown'],

    ],


    'product_thumbnail_zoom' => [
        'title' => esc_html__('Thumbnail With Zoom', 'shopready-elementor-addon'),
        'icon' => 'eicon-featured-image',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product image', 'image'],
        'js' => [
            'jquery',
            'shop-ready-single-product',
            'slick',
            'zoom',
            'flexslider'
        ],
        'css' => [
            'slick'
        ]

    ],

    'product_stock' => [
        'title' => esc_html__('Stock', 'shopready-elementor-addon'),
        'icon' => 'eicon-product-stock',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product stock', 'stock'],
        'js' => [
            'jquery',
            'shop-ready-single-product'
        ]
    ],

    'product_upsell' => [
        'title' => esc_html__('Upsell', 'shopready-elementor-addon'),
        'icon' => 'eicon-product-related',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product upsell', 'upsell'],
        'css' => [
            'woocommerce-general',
            'shop-ready-single-product'

        ]
    ],

    'product_meta' => [
        'title' => esc_html__('Meta', 'shopready-elementor-addon'),
        'icon' => 'eicon-meta-data',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product meta', 'meta'],
        'js' => [
            'jquery',
            'shop-ready-single-product'
        ]
    ],

    'product_quick_checkout_button' => [
        'title' => esc_html__('Quick Checkout Button', 'shopready-elementor-addon'),
        'icon' => 'eicon-button',
        'show_in_panel' => false,
        'is_pro' => true,
        'category' => ['sready-product'],
        'dashboard' => 'yes',
        'keywords' => ['quick checkout', 'button']
    ],

    'product_rating' => [
        'title' => esc_html__('Product Ratings', 'shopready-elementor-addon'),
        'icon' => 'eicon-rating',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product ratings', 'ratings', 'review'],
        'js' => [
            'jquery',
            'shop-ready-single-product'
        ]
    ],

    'product_stock_progressbar' => [
        'title' => esc_html__('Product Stock Progress', 'shopready-elementor-addon'),
        'icon' => 'eicon-product-stock',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product Stock Progress', 'progressbar', 'stock'],
        'js' => [
            'jquery',
            'shop-ready-single-product'
        ]
    ],

    'product_qrcode' => [
        'title' => esc_html__('Product QRCode', 'shopready-elementor-addon'),
        'icon' => 'eicon-barcode',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product qrcode', 'qrcode'],
        'js' => [
            'shop-ready-single-product'
        ]
    ],

    'product_description' => [
        'title' => esc_html__('Product Description', 'shopready-elementor-addon'),
        'icon' => 'eicon-product-description',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product description', 'description', 'product details', 'description'],
        'js' => [
            'jquery',
            'shop-ready-single-product'
        ]
    ],

    'product_add_to_cart' => [
        'title' => esc_html__('Add To cart', 'shopready-elementor-addon'),
        'icon' => ' eicon-product-add-to-cart',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['add to cart', 'buy'],
        'js' => [
            'jquery',
            'shop-ready-single-product',
            'nice-select'
        ],
        'css' => [
            'nice-select'
        ]
    ],

    'product_data_tabs' => [
        'title' => esc_html__('Product Data Tabs', 'shopready-elementor-addon'),
        'icon' => 'eicon-product-tabs',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['product data tabs', 'product tabs', 'data tabs'],
        'js' => [
            'jquery',
            'shop-ready-single-product'
        ]
    ],

    'product_related' => [
        'title' => esc_html__('Related Products', 'shopready-elementor-addon'),
        'icon' => 'eicon-product-related',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-product'],
        'keywords' => ['related products', 'same products'],
        'js' => [
            'jquery',
            'shop-ready-single-product'
        ]
    ],

    'product_vertical_menu' => [
        'title' => esc_html__('Vertical Menu', 'shopready-elementor-addon'),
        'icon' => ' eicon-form-vertical',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-gen'],
        'keywords' => ['products category', 'products', 'category', 'vertical', 'menu', 'vertical menu'],
        'js' => [
            'jquery'
        ],
        'css' => [
            'shop-ready-vertical-menu'
        ]
    ],

    'general_breadcrumb' => [
        'title' => esc_html__('Breadcrumb', 'shopready-elementor-addon'),
        'icon' => 'eicon-product-breadcrumbs',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-gen'],
        'keywords' => ['Shop Ready Breadcrumb', 'breadcrumb'],
        'js' => []
    ],

    'general_currency_swatcher' => [
        'title' => esc_html__('Currency Switcher', 'shopready-elementor-addon'),
        'icon' => 'eicon-divider',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['Currency Swatcher', 'Currency', 'Swatcher'],
        'js' => [
            'nice-select'
        ],
        'css' => ['nice-select']
    ],

    'general_wishlist' => [
        'title' => esc_html__('Wishlist', 'shopready-elementor-addon'),
        'icon' => 'eicon-plus',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['Shop Ready Wishlist', 'Wishlist', 'Grid', 'table'],
        'js' => []
    ],

    'general_wishlist_interface' => [
        'title' => esc_html__('Wishlist Interface', 'shopready-elementor-addon'),
        'icon' => 'eicon-plus',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['Shop Ready Wishlist', 'Wishlist'],
        'js' => []
    ],

    'general_product_compare' => [
        'title' => esc_html__('Product Compare', 'shopready-elementor-addon'),
        'icon' => 'eicon-woo-settings',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['Shop Ready Product Compare', 'Product Compare', 'Compare', 'table'],
        'js' => []
    ],

    'general_user_notice' => [
        'title' => esc_html__('User Notice', 'shopready-elementor-addon'),
        'icon' => ' eicon-h-align-center',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-gen'],
        'keywords' => ['Notice', 'user notice'],
        'js' => [
            'jquery'
        ]
    ],

    'general_classic_banner' => [
        'title' => esc_html__('Classic Banner', 'shopready-elementor-addon'),
        'icon' => 'eicon-banner',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['Shop Ready Banner', 'banner'],
        'js' => []
    ],

    'general_countdown_banner' => [

        'title' => esc_html__('Countdown Product Banner', 'shopready-elementor-addon'),
        'icon' => 'eicon-countdown',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['ShopReady Banner', 'banner', 'product countdown'],
        'js' => [],
        'css' => [
            'shop-ready-elementor-base'
        ]
    ],

    'general_countdown' => [

        'title' => esc_html__('Countdown', 'shopready-elementor-addon'),
        'icon' => 'eicon-countdown',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['ShopReady', 'countdown'],
        'js' => [],
        'css' => [
            'shop-ready-elementor-base'
        ]
    ],

    'general_slider_banner' => [
        'title' => esc_html__('Slider Banner', 'shopready-elementor-addon'),
        'icon' => 'eicon-slider-album',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['Shop Ready Banner', 'banner', 'slider'],
        'js' => [
            'jquery',
            'slick'
        ],
        'css' => [
            'slick'
        ]
    ],

    'general_tabs' => [
        'title' => esc_html__('ShopReady Tabs', 'shopready-elementor-addon'),
        'icon' => 'eicon-tabs',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['Shop Ready Tab', 'Tab'],
        'js' => [
            'jquery'
        ],
        'css' => [
            'shop-ready-elementor-base'
        ]
    ],

    // 'general_visited_product' => [
    //     'title'         => esc_html__('Visited Products', 'shopready-elementor-addon'),
    //     'icon'          => 'eicon-product-categories',
    //     'show_in_panel' => false,
    //     'is_pro'        => true,
    //     'dashboard'     => 'yes',
    //     'category'      => ['shopready-elementor-addon'],
    //     'keywords'      => ['Shop Ready visited product', 'breadcrumb'],
    //     'js'            => [
    //         'jquery', 'shop-ready-single-product'
    //     ]
    // ],

    'general_cart_count' => [
        'title' => esc_html__('Header Cart Count', 'shopready-elementor-addon'),
        'icon' => 'eicon-counter-circle',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['shopready-elementor-addon'],
        'keywords' => ['Cart Count', 'cart popup'],
        'js' => [
            'jquery',
            'shop-ready-elementor-base'
        ]
    ],

    'general_positions_element' => [
        'title' => esc_html__('Position Element', 'shopready-elementor-addon'),
        'icon' => 'eicon-drag-n-drop',
        'show_in_panel' => false,
        'is_pro' => true,
        'dashboard' => 'yes',
        'category' => ['sready-gen'],
        'keywords' => ['position element', 'position'],
        'css' => [
            'woo-ready-position'
        ]
    ],

    'thankyou_quick_review' => [
        'title' => esc_html__('Thankyou Order', 'shopready-elementor-addon'),
        'icon' => 'eicon-woocommerce',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['Order details', 'quick review'],
        'js' => []
    ],

    'thankyou_order_details' => [
        'title' => esc_html__('Thankyou Order Details', 'shopready-elementor-addon'),
        'icon' => 'eicon-woocommerce',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['Order details', 'Thankyou details'],
        'js' => []
    ],

    'thankyou_order_downloads' => [
        'title' => esc_html__('Thankyou Order Downloads', 'shopready-elementor-addon'),
        'icon' => 'eicon-download-bold',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['Order Downlaods', 'Downloads'],
        'js' => []
    ],

    'thankyou_customer_address' => [
        'title' => esc_html__('Thankyou Order Address', 'shopready-elementor-addon'),
        'icon' => 'eicon-map-pin',
        'show_in_panel' => false,
        'is_pro' => false,
        'dashboard' => 'yes',
        'category' => ['sready-checkout'],
        'keywords' => ['customer address', 'thankyou'],
        'js' => []
    ],

];

// Call from anywhere
return apply_filters('shop_ready_sr_elewidgets_config', $return_component);