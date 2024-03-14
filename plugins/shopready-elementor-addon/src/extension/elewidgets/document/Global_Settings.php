<?php

namespace Shop_Ready\extension\elewidgets\document;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;
use Elementor\Core\Base\Document;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/* 
 * Site Global Settings
 * @since 1.0 
 */

class Global_Settings extends Tab_Base
{

    public function get_id()
    {
        return 'woo-ready-basic';
    }

    public function get_title()
    {
        return esc_html__('ShopReady', 'shopready-elementor-addon');
    }

    public function get_group()
    {
        return 'settings';
    }

    public function get_icon()
    {
        return 'eicon-button';
    }

    public function get_help_url()
    {
        return 'quomodosoft.com';
    }

    protected function register_tab_controls()
    {

        $this->preloader();
        do_action('woo_ready_header_footer', $this, $this->get_id());

        $this->General_cart_widget();
        do_action('shop_ready_cart_gl_settings', $this, $this->get_id());
        $this->Currency_Swicher();
        $this->grid_layout();

        $this->login_register();
        $this->payment();

        $this->checkout();
        $this->order_review();
        $this->checkout_address();

        do_action('shop_ready_sale_notifications', $this, $this->get_id());


    }
    public function preloader()
    {

        $this->start_controls_section(
            'shop_ready_preloaders_settings',
            [
                'label' => esc_html__('Preloader', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );

        $this->add_control(
            'shop_ready_preloader_active',
            [
                'label' => esc_html__('Active?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'shop_ready_preloader_bg_color',
            [
                'label' => esc_html__('Background', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::COLOR,
            ]
        );

        $this->end_controls_section();
    }
    public function Currency_Swicher()
    {

        $this->start_controls_section(
            'woo_ready_currency_swicher_settings',
            [

                'label' => apply_filters('shop_ready_product_gl_label', esc_html__('Currency Swicher Pro', 'shopready-elementor-addon')),
                'tab' => $this->get_id(),
            ]
        );

        $this->add_control(
            'woo_ready_disable_currency_in_checkout',
            [
                'label' => esc_html__('Disable in Checkout?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'woo_ready_disable_currency_in_cart',
            [
                'label' => esc_html__('Disable in Cart?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'woo_ready_select_currencies',
            [
                'label' => __('Select Currencies', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'options' => woo_ready_get_product_currency_options(),
                'default' => ['USD', 'EUR', 'SGD'],
            ]
        );

        $this->end_controls_section();
    }

    public function General_cart_widget()
    {


        $this->start_controls_section(
            'woo_ready_general_settings',
            [

                'label' => apply_filters('shop_ready_product_gl_label', esc_html__('Cart Count Widget Pro', 'shopready-elementor-addon')),
                'tab' => $this->get_id(),
            ]
        );

        $this->add_control(
            'woo_ready_widget_cart_count_icon',
            [
                'label' => __('Add Cart Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-shopping-cart',
                    'library' => 'solid',
                ],

            ]
        );

        $this->add_control(
            'woo_ready_widget_cart_number_before_text',
            [
                'label' => esc_html__('Cart Count before text?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'woo_ready_widget_cart_label',
            [
                'label' => esc_html__('Cart Label', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Cart'
            ]
        );

        $this->add_control(
            'woo_ready_widget_cart_count_singular',
            [
                'label' => esc_html__('Count Singular', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'item'
            ]
        );

        $this->add_control(
            'woo_ready_widget_cart_count_plural',
            [
                'label' => esc_html__('Count Plural', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'items'
            ]
        );



        $this->end_controls_section();
        $this->start_controls_section(
            'shop_ready_sidebar_mini_cart_layouts',
            [
                'label' => esc_html__('Mini Cart', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );

        do_action('shop_ready_mini_cart_layout', $this);

        $this->add_control(
            'woo_ready_mini_cart_title_limit_plural',
            [
                'label' => esc_html__('Title limit', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '3'
            ]
        );

        do_action('shop_ready_mini_cart_end', $this);
        $this->end_controls_section();
    }

    public function grid_layout()
    {

        $is_pro = get_option('ShopReady_lic_Key') || get_option('QSBundle_lic_Key');
        $this->start_controls_section(
            'woo_ready_products_archive_grid_layouts',
            [
                'label' => esc_html__('Shop Archive', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );

        $this->add_control(
            'wooready_products_archive_shop_grid_style',
            [
                'label' => esc_html__('Product Grid Style', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => $is_pro ? 'eforest' : 'wc',
                'options' => apply_filters('shop_ready_products_archive_grid_style', [

                    'classic' => esc_html__('Classic', 'shopready-elementor-addon'),
                    'wc' => esc_html__('WC', 'shopready-elementor-addon'),
                    'side_flip_center' => esc_html__('Side Flip Center', 'shopready-elementor-addon'),
                    'side_flip_center_two' => esc_html__('Side Flip Center 2 Pro', 'shopready-elementor-addon'),
                    'side_flip' => esc_html__('Side Flip Pro', 'shopready-elementor-addon'),
                    'side_flip_left' => esc_html__('Side Flip Left Pro', 'shopready-elementor-addon'),
                    'eforest' => esc_html__('Eforest Pro', 'shopready-elementor-addon'),

                ]),
            ]
        );

        $query['autofocus[panel]'] = 'shopready-elementor-addon';
        $panel_link = add_query_arg($query, admin_url('customize.php'));
        $this->add_control(
            'woo_ready_csutomizer_usage_direction_notice',
            [
                'label' => esc_html__('Important Note', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(__('<a target="_blank" href="%s">Use grid</a> Column from customizer -> WooCommerce -> Categol Settings', 'shopready-elementor-addon'), esc_url($panel_link)),
                'content_classes' => 'woo-ready-shop-page-notice',
            ]
        );

        $this->add_control(
            'woo_ready_product_grid_stock_seperator',
            [
                'label' => esc_html__('Stock Seperator', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '/'
            ]
        );

        $this->add_control(
            'shop_ready_product_grid_loadmore',
            [
                'label' => esc_html__('LoadMore', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'loadmore'
            ]
        );

        do_action('shop_ready_pro_global_archive_settings', $this);

        $this->start_controls_tabs(
            'shop__ready_pro_global_settings_yuiot_shop_setytingsss_tabs'
        );

        $this->start_controls_tab(
            'shop_ready_pro_global_settings_normal_style_flex_align_tab',
            [
                'label' => __('Alignment', 'shopready-elementor-addon'),
            ]
        );

        $this->add_responsive_control(
            'wooready_products_archive_shop_grid_ui_alignment_element',
            [
                'label' => esc_html__('Flex Alignment', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'flex-start' => esc_html__('Left', 'shopready-elementor-addon'),
                    'flex-end' => esc_html__('Right', 'shopready-elementor-addon'),
                    'center' => esc_html__('Center', 'shopready-elementor-addon'),
                    'space-around' => esc_html__('Space Around', 'shopready-elementor-addon'),
                    'space-between' => esc_html__('Space Between', 'shopready-elementor-addon'),
                    'space-evenly' => esc_html__('Space Evenly', 'shopready-elementor-addon'),
                    '' => esc_html__('inherit', 'shopready-elementor-addon'),
                ],

                'selectors' => [
                    'body .wooready_product_content_box' => 'justify-content: {{VALUE}};',
                    'body .wooready_price_box' => 'justify-content: {{VALUE}};',
                    'body .wooready_product_color' => 'justify-content: {{VALUE}};',
                    'body .sr-grid-review-wrapper' => 'justify-content: {{VALUE}};',
                    'body .wready-product-loop-price-wrapper.sr-grid-price' => 'justify-content: {{VALUE}};',
                    'body .wooready-slider-product-layout .product-details .sr-review-rating' => 'justify-content: {{VALUE}};',
                ],
            ]

        );

        $this->add_responsive_control(
            'wooready_products_archive_shop_grid_ui_alignment_item',
            [
                'label' => esc_html__('Align Item', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'flex-start' => esc_html__('Left', 'shopready-elementor-addon'),
                    'flex-end' => esc_html__('Right', 'shopready-elementor-addon'),
                    'center' => esc_html__('Center', 'shopready-elementor-addon'),
                    'space-around' => esc_html__('Space Around', 'shopready-elementor-addon'),
                    'space-between' => esc_html__('Space Between', 'shopready-elementor-addon'),
                    'space-evenly' => esc_html__('Space Evenly', 'shopready-elementor-addon'),
                    '' => esc_html__('inherit', 'shopready-elementor-addon'),
                ],

                'selectors' => [
                    'body .wooready_product_content_box' => 'align-items: {{VALUE}};',
                    'body .wooready_price_box' => 'align-items: {{VALUE}};',
                    'body .wooready_product_color' => 'jalign-items: {{VALUE}};',
                    'body .wooready-slider-product-layout .product-details .sr-review-rating' => 'align-items: {{VALUE}};',
                ],
            ]

        );

        $this->add_responsive_control(
            'wooready_products_archive_shop_grid_ui_alignment_text_element',
            [
                'label' => esc_html__('Text Alignment', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [

                    'left' => esc_html__('Left', 'shopready-elementor-addon'),
                    'right' => esc_html__('Right', 'shopready-elementor-addon'),
                    'center' => esc_html__('Center', 'shopready-elementor-addon'),
                    'inherit' => esc_html__('Inherit', 'shopready-elementor-addon'),

                ],

                'selectors' => [
                    'body .wooready_product_content_box' => 'text-align: {{VALUE}};',
                    'body .wooready_price_box' => 'align-items: {{VALUE}};',
                    'body .classic-sr-customize .woo-ready-single-product' => 'text-align: {{VALUE}};',
                    'body .shop-ready-auto-product-load' => 'text-align: {{VALUE}};',
                    'body .wooready-slider-product-layout .product-meta' => 'text-align: {{VALUE}};',
                ],
            ]

        );

        $this->end_controls_tab();
        // grid order
        $this->start_controls_tab(
            'shop_ready_pro_global_settings_normal_sgrid_order_tab',
            [
                'label' => __('Order', 'shopready-elementor-addon'),
            ]
        );

        $this->add_responsive_control(
            'wooready_products_archive_shop_grid_order_title_element',
            [
                'label' => esc_html__('Title Order', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,

                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -30,
                        'max' => 100,
                        'step' => 1,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} .wooready_title' => 'order: {{SIZE}}',

                ],
                'condition' => [

                    'wooready_products_archive_shop_grid_style' => ['side_flip_center_two', 'side_flip', 'side_flip_left', 'side_flip_center']
                ]
            ]
        );

        $this->add_responsive_control(
            'wooready_products_archive_shop_grid_order_priceelement',
            [
                'label' => esc_html__('Price Order', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,

                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -30,
                        'max' => 100,
                        'step' => 1,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} .wooready_price_box' => 'order: {{SIZE}}',

                ],
                'condition' => [

                    'wooready_products_archive_shop_grid_style' => ['side_flip_center_two', 'side_flip', 'side_flip_left', 'side_flip_center']
                ]
            ]
        );

        $this->add_responsive_control(
            'wooready_products_archive_shop_grid_order_review_element',
            [
                'label' => esc_html__('Review Order', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,

                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -30,
                        'max' => 100,
                        'step' => 1,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} .wooready_review' => 'order: {{SIZE}}',

                ],
                'condition' => [

                    'wooready_products_archive_shop_grid_style' => ['side_flip_center_two', 'side_flip', 'side_flip_left', 'side_flip_center']
                ]
            ]
        );

        $this->add_responsive_control(
            'wooready_products_archive_shop_grid_order_color_element',
            [
                'label' => esc_html__('Color Order', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,

                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -30,
                        'max' => 100,
                        'step' => 1,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} .wooready_product_color' => 'order: {{SIZE}}',

                ],
                'condition' => [

                    'wooready_products_archive_shop_grid_style' => ['side_flip_center_two', 'side_flip', 'side_flip_left', 'side_flip_center']
                ]
            ]
        );

        $this->add_responsive_control(
            'wooready_products_archive_shop_grid_order_range_element',
            [
                'label' => esc_html__('Sold Ranger Order', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,

                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -30,
                        'max' => 100,
                        'step' => 1,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} .wooready_product_sold_range' => 'order: {{SIZE}}',

                ],
                'condition' => [

                    'wooready_products_archive_shop_grid_style' => ['side_flip_center_two', 'side_flip', 'side_flip_left', 'side_flip_center']
                ]
            ]
        );


        $this->add_responsive_control(
            'wooready_products_archive_shop_grid_order_image_element',
            [
                'label' => esc_html__('Image Order', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,

                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -30,
                        'max' => 100,
                        'step' => 1,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} .wooready_product_thumb ' => 'order: {{SIZE}}',

                ],
                'condition' => [

                    'wooready_products_archive_shop_grid_style' => ['side_flip_center_two', 'side_flip', 'side_flip_left', 'side_flip_center']
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'shop_ready_pro_global_settings_normal_cart_tab',
            [
                'label' => __('Cart', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'wooready_products_archive_shop_grid_cart_icon_enable',
            [
                'label' => esc_html__('Cart Add Icon?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'wooready_products_archive_shop_grid_cart_icon',
            [
                'label' => __('Add Cart Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-shopping-cart',
                    'library' => 'solid',
                ],
                'condition' => [
                    'wooready_products_archive_shop_grid_cart_icon_enable' => [
                        'yes',
                    ]

                ]
            ]
        );

        $this->add_control(
            'wooready_products_archive_shop_grid_cart_text',
            [
                'label' => esc_html__('Cart Text', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add To Cart', 'shopready-elementor-addon'),
                'default' => esc_html__('Add To Cart', 'shopready-elementor-addon'),
                'wooready_products_archive_shop_grid_style' => ['wready_style', 'side_flip', 'side_flip_left', 'side_flip_center'],
                'label_block' => true
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    public function checkout_address()
    {

        $this->start_controls_section(
            'woo_ready_wc_b_module',
            [

                'label' => apply_filters('shop_ready_product_gl_label', esc_html__('Checkout Address Pro', 'shopready-elementor-addon')),
                'tab' => $this->get_id(),
            ]
        );

        $this->add_control(
            'wr_checkout_address_modify',
            [
                'label' => esc_html__('Address modify?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'list_field',
            [
                'label' => esc_html__('Billing Fields', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => shop_ready_get_default_billing_address()
            ]
        );

        $repeater->add_control(
            'list_disable',
            [
                'label' => esc_html__('Disable?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'list_field!' => ''
                ],
                'description' => esc_html__('It Will remove field from billing address', 'shopready-elementor-addon'),
            ]
        );

        $repeater->add_control(
            'list_label_change',
            [
                'label' => esc_html__('Label Change?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'list_disable' => ''
                ]
            ]
        );

        $repeater->add_control(
            'list_title',
            [
                'label' => esc_html__('Label', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Field Label', 'shopready-elementor-addon'),
                'condition' => [
                    'list_label_change' => ['yes'],
                    'list_disable' => ''
                ],
                'label_block' => true
            ]
        );

        $repeater->add_control(
            'list_required',
            [
                'label' => esc_html__('Required?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'list_disable' => ''
                ]
            ]
        );


        $repeater->add_control(
            'list_priority',
            [
                'label' => esc_html__('Priority', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'placeholder' => esc_html__('Field Priority', 'shopready-elementor-addon'),
                'default' => 1,
                'label_block' => true,
                'condition' => [
                    'list_disable' => ''
                ]
            ]
        );

        $repeater->add_control(
            'list_col_wdith',
            [
                'label' => esc_html__('Wide', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'label_block' => true,
                'options' => [
                    'flex-basis:col-12' => esc_html__('Full', 'shopready-elementor-addon'),
                    'flex-basis:col-6' => esc_html__('Two Column', 'shopready-elementor-addon'),
                    'flex-basis:col-4' => esc_html__('Three Column', 'shopready-elementor-addon'),
                    'flex-basis:col-3' => esc_html__('Four Column', 'shopready-elementor-addon'),
                    'flex-basis:col-2' => esc_html__('Six Column', 'shopready-elementor-addon'),
                    'flex-basis:col-10' => esc_html__('Ten Column', 'shopready-elementor-addon'),
                    'flex-basis:col-8' => esc_html__('Eight Column', 'shopready-elementor-addon'),
                ],
                'condition' => [
                    'list_disable' => ''
                ]
            ]
        );



        $this->add_control(
            'wr_checkout_billing_address_list',
            [
                'label' => esc_html__('Billing Address Fields', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [

                ],
                'title_field' => '{{{ list_title  }}} {{ list_disable == "yes"? " is Disable":"" }}',
                'condition' => [
                    'wr_checkout_address_modify' => ['yes']
                ]
            ]
        );

        // Shipping Address

        $ship_repeater = new \Elementor\Repeater();

        $ship_repeater->add_control(
            'list_field',
            [
                'label' => esc_html__('Fields', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => shop_ready_get_default_shipping_address()
            ]
        );

        $ship_repeater->add_control(
            'list_disable',
            [
                'label' => esc_html__('Disable?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'list_field!' => ''
                ],
                'description' => esc_html__('It Will remove field from billing address', 'shopready-elementor-addon'),
            ]
        );

        $ship_repeater->add_control(
            'list_label_change',
            [
                'label' => esc_html__('Label Change?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'list_disable' => ''
                ]
            ]
        );



        $ship_repeater->add_control(
            'list_title',
            [
                'label' => esc_html__('Label', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Field Label', 'shopready-elementor-addon'),

            ]
        );

        $ship_repeater->add_control(
            'list_required',
            [
                'label' => esc_html__('Required?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'list_disable' => ''
                ]
            ]
        );


        $ship_repeater->add_control(
            'list_priority',
            [
                'label' => esc_html__('Priority', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Field Priority', 'shopready-elementor-addon'),
                'default' => 1,
                'condition' => [
                    'list_disable' => ''
                ]
            ]
        );

        $ship_repeater->add_control(
            'list_col_wdith',
            [
                'label' => esc_html__('Wide', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'flex-basis:col-12' => esc_html__('Full', 'shopready-elementor-addon'),
                    'flex-basis:col-6' => esc_html__('Two Column', 'shopready-elementor-addon'),
                    'flex-basis:col-4' => esc_html__('Three Column', 'shopready-elementor-addon'),
                    'flex-basis:col-3' => esc_html__('Four Column', 'shopready-elementor-addon'),
                    'flex-basis:col-2' => esc_html__('Six Column', 'shopready-elementor-addon'),
                    'flex-basis:col-10' => esc_html__('Ten Column', 'shopready-elementor-addon'),
                    'flex-basis:col-8' => esc_html__('Eight Column', 'shopready-elementor-addon'),
                ],
                'condition' => [
                    'list_disable' => ''
                ]
            ]
        );

        $this->add_control(
            'disable_shipping_address',
            [
                'label' => esc_html__('Disable Shipping Address?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',

            ]
        );

        $this->add_control(
            'wr_checkout_shipping_address_list',
            [
                'label' => esc_html__('Shipping Address Fields', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $ship_repeater->get_controls(),
                'default' => [

                ],
                'title_field' => '{{{ list_title }}}',
                'condition' => [
                    'disable_shipping_address' => ''
                ]
            ]
        );


        $this->end_controls_section();
    }

    public function order_review()
    {


        $this->start_controls_section(
            'woo_ready_wc_order_review_',
            [
                'label' => esc_html__('Thank You', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_order_details',
            [
                'label' => esc_html__('Order Details?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_billing_address',
            [
                'label' => esc_html__('Billing Address?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->start_controls_tabs(
            'woo_ready_thankyou_billing_style_tabs'

        );

        $this->start_controls_tab(
            'woo_ready_tnkstyle_billing_tab',
            [
                'label' => __('Addrress', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_billing_heading',
            [
                'label' => esc_html__('Billing Title?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'woo_ready_thankyou_order_details_billing_heading',
            [
                'label' => __('Title', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Billing Address', 'shopready-elementor-addon'),
                'placeholder' => __('Billing Address', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_thankyou_billing_heading' => ['yes'],


                ]
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_shipping_heading',
            [
                'label' => esc_html__('Shipping Title?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'woo_ready_thankyou_order_details_shipping_heading',
            [
                'label' => __('Title', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Shipping Address', 'shopready-elementor-addon'),
                'placeholder' => __('Shipping Address', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_thankyou_shipping_heading' => ['yes'],


                ]
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'woo_ready_tnkstyle_order_details_tab',
            [
                'label' => __('Order', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_order_details_heading',
            [
                'label' => esc_html__('Heading Show?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'woo_ready_thankyou_order_details_heading',
            [
                'label' => __('Heading Content', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Order details', 'shopready-elementor-addon'),
                'placeholder' => __('Order Details', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_thankyou_order_details_heading' => ['yes']
                ]
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_order_download',
            [
                'label' => esc_html__('Download Show?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_order_download_title',
            [
                'label' => esc_html__('Download Title?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'woo_ready_enable_thankyou_order_download' => ['yes']
                ]
            ]
        );

        $this->add_control(
            'woo_ready_thankyou_order_details_download_heading',
            [
                'label' => __('Download Title', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Download', 'shopready-elementor-addon'),
                'placeholder' => __('Downlaods', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_thankyou_order_download_title' => ['yes'],
                    'woo_ready_enable_thankyou_order_download' => ['yes']

                ]
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_order_again_button',
            [
                'label' => esc_html__('Order Again?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'woo_ready_tnkstyle_review_tab',
            [
                'label' => __('Thank You', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_msg',
            [
                'label' => esc_html__('Message?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );



        $this->add_control(
            'woo_ready_enable_thankyou_payment_method',
            [
                'label' => esc_html__('Payment Method?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_email',
            [
                'label' => esc_html__('Email?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_date',
            [
                'label' => esc_html__('Date?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_order_number',
            [
                'label' => esc_html__('Order Number?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_order_total',
            [
                'label' => esc_html__('Order Total?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'woo_ready_thank_you_msg',
            [
                'label' => __('Message Content', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Thank you. Your order has been received.', 'shopready-elementor-addon'),
                'placeholder' => __('Thank you. Your order has been received.', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_thankyou_msg' => ['yes']
                ]
            ]
        );

        $this->add_control(
            'woo_ready_thank_you_order_number',
            [
                'label' => __('Order Number', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Order Number', 'shopready-elementor-addon'),
                'placeholder' => __('Order Number', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_thank_you_order_date',
            [
                'label' => __('Order Date', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Date', 'shopready-elementor-addon'),
                'placeholder' => __('Date', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_thank_you_order_email',
            [
                'label' => __('Order Email', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Email', 'shopready-elementor-addon'),
                'placeholder' => __('Email', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_thank_you_order_total',
            [
                'label' => __('Order Total', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Total', 'shopready-elementor-addon'),
                'placeholder' => __('Total', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_thank_you_order_payment_method',
            [
                'label' => __('Payment method', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Payment method', 'shopready-elementor-addon'),
                'placeholder' => __('Payment method', 'shopready-elementor-addon'),
            ]
        );


        $this->end_controls_tab();
        $this->start_controls_tab(
            'woo_ready_tnkstyle_order_fail',
            [
                'label' => __('Fail', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'woo_ready_thank_you_order_fail_msg',
            [
                'label' => __('Content', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'shopready-elementor-addon'),
                'placeholder' => __('Please attempt your purchase again.', 'shopready-elementor-addon'),

            ]
        );

        $this->add_control(
            'woo_ready_thank_you_order_pay_text',
            [
                'label' => __('Pay', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Pay', 'shopready-elementor-addon'),
                'placeholder' => __('Pay Button', 'shopready-elementor-addon'),

            ]
        );

        $this->add_control(
            'woo_ready_enable_thankyou_fail_myaccount',
            [
                'label' => esc_html__('My Account Redirect?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'woo_ready_thankyou_fail_redirect_url',
            [
                'label' => __('My account Custom Link', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::URL,
                'label_block' => true,
                'placeholder' => __('https://your-link.com', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_thankyou_fail_myaccount' => ['yes']
                ]
            ]
        );


        $this->add_control(
            'woo_ready_thank_you_order_fail_myaccount_text',
            [
                'label' => __('My Account', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('My Account', 'shopready-elementor-addon'),
                'placeholder' => __('My Account', 'shopready-elementor-addon'),
                'condition' => [
                    'woo_ready_enable_thankyou_fail_myaccount' => ['yes']
                ]

            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }


    public function checkout()
    {

        $this->start_controls_section(
            'woo_ready_wc_checkout_gen',
            [

                'label' => apply_filters('shop_ready_product_gl_label', esc_html__('Checkout General Pro', 'shopready-elementor-addon')),
                'tab' => $this->get_id(),
            ]
        );



        $this->add_control(
            'wr_checkout_terms',
            [
                'label' => esc_html__('Terms?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        do_action('shop_ready_checkout_pro_feature_option', $this, 'gen');


        $this->end_controls_section();

    }

    public function payment()
    {

        $this->start_controls_section(
            'woo_ready_wc_payment_module',
            [

                'label' => apply_filters('shop_ready_product_gl_label', esc_html__('Payment Pro', 'shopready-elementor-addon')),
                'tab' => $this->get_id(),
            ]
        );

        $this->add_control(
            'wr_disable_payment_gateway',
            [
                'label' => esc_html__('Disable Payment?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'wr_order_button_text',
            [
                'label' => esc_html__('Order Button Text', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Place Order', 'shopready-elementor-addon'),
                'placeholder' => esc_html__('Place Order', 'shopready-elementor-addon'),
                'label_block' => true
            ]
        );

        $this->add_control(
            'wr_checkout_order_btn_sep_row',
            [
                'label' => esc_html__('Button New Line?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    public function login_register()
    {

        $this->start_controls_section(
            'woo_ready_account_login',
            [
                'label' => esc_html__('Login Register', 'shopready-elementor-addon'),
                'tab' => $this->get_id(),
            ]
        );

        $this->add_control(
            'wr_login_redirect_enable',
            [
                'label' => esc_html__('Login Redirect?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'wr_login_redirect',
            [
                'label' => esc_html__('Login Redirect', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'shopready-elementor-addon'),
                'condition' => [
                    'wr_login_redirect_enable' => ['yes']
                ]

            ]
        );

        $this->end_controls_section();
    }



    /**
     * Should check for the current action to avoid infinite loop
     * when updating options like: "wr_login_redirect" and "wr_login_redirect_enable".
     */
    public function on_save($data)
    {

        if (
            !isset($data['settings'])
        ) {
            return;
        }

        $grid_style = 'wooready_products_archive_shop_grid_style';

        if (isset($data['settings'][$grid_style])) {

            update_option($grid_style, $data['settings'][$grid_style]);
        }

        if (isset($data['settings']['shop_ready_pro_cart_page_layout'])) {

            update_option('shop_ready_pro_cart_page_layout', $data['settings']['shop_ready_pro_cart_page_layout']);
        }

    }

    public function get_additional_tab_content()
    {

        // use this for notice 
        // as a helper link
        // docs 
        return sprintf(
            '
				<div class="woo-ready-account-module elementor-nerd-box">
                <a class="elementor-button elementor-button-success elementor-nerd-box-link" target="_blank" href="#"> Account Module </a>
				</div>
				'

        );
    }

    /**
     * Checkout Address Fields
     * @since 1.0
     * @param string type ex: billing | shipping
     * @param string item col ex: label | required | priority | autocomplete | class as array
     * @defs woocommerce
     * @return array
     */
    function shop_ready_get_wc_checkout_address_fields($type = 'billing', $col = 'label')
    {

        $fields_with_label = [];

        try {

            $checkout = WC()->checkout;
            if (isset($checkout->checkout_fields)) {

                if (isset($checkout->checkout_fields[$type]) && is_array($checkout->checkout_fields[$type])) {
                    foreach ($checkout->checkout_fields[$type] as $key => $item) {
                        $fields_with_label[$key] = $item[$col];
                    }

                    return $fields_with_label;
                }

            }

        } catch (\Exception $e) {
            wc_add_notice(esc_html__('Checkout not Init', 'shopready-elementor-addon'));
        }

        return $fields_with_label;
    }
}