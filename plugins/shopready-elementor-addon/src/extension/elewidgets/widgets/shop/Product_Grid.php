<?php

namespace Shop_Ready\extension\elewidgets\widgets\shop;

use Shop_Ready\system\base\Repository\Product_Modal;

class Product_Grid extends \Shop_Ready\extension\elewidgets\Widget_Base
{

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_product_grid_layout_tab',
            [
                'label' => esc_html__('Layout Options', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'wooready_products_grid_layout_select',
            [
                'label'   => esc_html__('Layout', 'shopready-elementor-addon'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => __('default', 'shopready-elementor-addon'),
                'options' => apply_filters('shop_ready_shop_product_grid', [

                    'default' => esc_html__('Default', 'shopready-elementor-addon'),
                    'pro_sy'  => esc_html__('Pro Style 1', 'shopready-elementor-addon'),
                    'pro_sy2' => esc_html__('Pro Style 2', 'shopready-elementor-addon'),
                    'pro_sy3' => esc_html__('Pro Style 3', 'shopready-elementor-addon'),
                    'pro_sy4' => esc_html__('Pro Style 4', 'shopready-elementor-addon'),


                ]),
            ]
        );

        $this->add_responsive_control(
            'wooready_products_grid_layout_column',
            [
                'label'   => esc_html__('Column', 'shopready-elementor-addon'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => __('4', 'shopready-elementor-addon'),
                'options' => [

                    '1' => __('1', 'shopready-elementor-addon'),
                    '2' => __('2', 'shopready-elementor-addon'),
                    '3' => __('3', 'shopready-elementor-addon'),
                    '4' => __('4', 'shopready-elementor-addon'),
                    '5' => __('5', 'shopready-elementor-addon'),
                    '6' => __('6', 'shopready-elementor-addon'),
                    '7' => __('7', 'shopready-elementor-addon'),
                    '8' => __('8', 'shopready-elementor-addon'),
                    '' => __('None', 'shopready-elementor-addon')

                ],

                'selectors' => [
                    '{{WRAPPER}} .woo-ready-product-grid-layout .display\:grid' => 'grid-template-columns:repeat({{VALUE}}, 1fr);',
                ],
            ]
        );



        $this->add_control(
            'wooready_products_grid_sale_text',
            [
                'label'       => __('Sale Text', 'shopready-elementor-addon'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __('Sale', 'shopready-elementor-addon'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'wooready_products_grid_popular_text',
            [
                'label'       => __('Popular Text', 'shopready-elementor-addon'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __('Popular', 'shopready-elementor-addon'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'wooready_products_grid_featured_text',
            [
                'label'       => __('Featured Text', 'shopready-elementor-addon'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __('Featured', 'shopready-elementor-addon'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'wooready_products_grid_addtocart_text',
            [
                'label'       => __('Cart Text', 'shopready-elementor-addon'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __('Add to cart', 'shopready-elementor-addon'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_product_grid_icon_tab',
            [
                'label' => esc_html__('Icon Options', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'add_to_cart_icon',
            [
                'label' => __('Cart Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-shopping-cart',
                    'library' => 'solid',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'quick_view_icon',
            [
                'label' => __('Quick View Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-eye',
                    'library' => 'solid',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'wishlist_icon',
            [
                'label' => __('Wishlist Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-heart-o',
                    'library' => 'solid',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'refresh_icon',
            [
                'label' => __('Refresh Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-refresh',
                    'library' => 'solid',
                ],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        do_action('shop_ready_section_product_minimum_general_tab', $this, $this->get_name());
        do_action('shop_ready_product_taxonomy_filter_tab', $this, $this->get_name());
        do_action('shop_ready_section_sort_tab', $this, $this->get_name());
        do_action('shop_ready_section_date_filter_tab', $this, $this->get_name());
        do_action('shop_ready_section_data_exclude_tab', $this, $this->get_name());

        // Product grid main layout

        // $this->box_css(
        //     [
        //         'title'        => esc_html__( 'Main Wrapper', 'shopready-elementor-addon' ),
        //         'slug'         => 'wooready_products_grid_main_wrapper',
        //         'element_name' => 's__wooready_products_grid_main_wrapper',
        //         'selector'     => '{{WRAPPER}} .woo-ready-product-grid-layout',
        //     ]
        // );


        // Product Box
        $this->box_css(
            [
                'title'        => esc_html__('Product Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_layout_wrapper',
                'element_name' => 's__wooready_products_grid_product_layout_wrapper',
                'selector'     => '{{WRAPPER}} .woo-ready-product-grid-layout .wooready_product_components .wooready_product_layout_wrapper',
                'disable_controls' => [
                    'position', 'size', 'alignment',
                ]
            ]
        );

        // All Product Image Wrapper

        $this->box_css(
            [
                'title'        => esc_html__('Image Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_image_box',
                'element_name' => 's__wooready_products_grid_product_image_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb img, {{WRAPPER}} .wooready_course_product_layout .wooready_course_thumb img',
                'disable_controls' => [
                    'position', 'size', 'alignment',
                ]
            ]
        );

        // All Product Image Size

        $this->element_size(
            [
                'title'        => esc_html__('Image Size', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_image_size',
                'element_name' => 's__wooready_products_grid_product_image_size',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb img, {{WRAPPER}} .wooready_course_product_layout .wooready_course_thumb img',
            ]
        );
 

        $this->text_minimum_css(
            [
                'title'          => esc_html__('Sale Discount', 'shopready-elementor-addon'),
                'slug'           => 'wooready_products_grid_product_sale_discount',
                'element_name'   => 's__wooready_products_grid_product_sale_discount',
                'selector'       => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb .wooready_sell_discount',
                'hover_selector' => false,
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1', 'style2']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'          => esc_html__('Popularity Box', 'shopready-elementor-addon'),
                'slug'           => 'wooready_products_grid_product_popularity_box',
                'element_name'   => 's__wooready_products_grid_product_popularity_box',
                'selector'       => '{{WRAPPER}} .wooready_course_product_layout .wooready_course_thumb span.wooready_course_category',
                'hover_selector' => '{{WRAPPER}} .wooready_course_product_layout .wooready_course_thumb .span.wooready_course_category:hover',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3', 'style4']
                ],
            ]
        );

        $this->position_css(
            [
                'title'          => esc_html__('Sale Discount Position', 'shopready-elementor-addon'),
                'slug'           => 'wooready_products_grid_product_sale_discount_position',
                'element_name'   => 's__wooready_products_grid_product_sale_discount_position',
                'selector'       => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb .wooready_sell_discount',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1', 'style2']
                ],
            ]
        );

        $this->position_css(
            [
                'title'          => esc_html__('Popularity Box Position', 'shopready-elementor-addon'),
                'slug'           => 'wooready_products_grid_product_popularity_box_position',
                'element_name'   => 's__wooready_products_grid_product_popularity_box_position',
                'selector'       => '{{WRAPPER}} .wooready_course_product_layout .wooready_course_thumb span.wooready_course_category',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3', 'style4']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Product Content Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_content_box',
                'element_name' => 's__wooready_products_grid_product_content_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1', 'style2']
                ],
                'disable_controls' => [
                    'position', 'size',
                ]
            ]
        );

        $this->element_before_psudocode(
            [
                'title'        => esc_html__('Product Content Before', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_content_before',
                'element_name' => 's__wooready_products_grid_product_content_before',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box::before',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1', 'style2']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Course Content Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_course_content_box',
                'element_name' => 's__wooready_products_grid_product_course_content_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3', 'style4']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Product Category Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_category_box',
                'element_name' => 's__wooready_products_grid_product_category_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_category',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style2']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Category Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_category_style',
                'element_name' => 's__wooready_products_grid_product_category_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_category a',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style2']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Title Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_title_box',
                'element_name' => 's__wooready_products_grid_product_title_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_title, {{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_course_title',
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Title Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_title_style',
                'element_name' => 's__wooready_products_grid_product_title_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_title .title a, {{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_title .title a',
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Product Review Wrapper', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_review_price_box',
                'element_name' => 's__wooready_products_grid_product_review_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_review',

            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Product Price Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_price_box',
                'element_name' => 's__wooready_products_grid_product_price_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_review .wooready_price_box',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1', 'style2']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Course Review & Price Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_course_review_price_box',
                'element_name' => 's__wooready_products_grid_product_review_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_price_item',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3', 'style4']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Course Price Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_course_price_box',
                'element_name' => 's__wooready_products_grid_product_course_price_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_price_item .wooready_course_price',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3', 'style4']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Price', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_normal_price_default_style1_style',
                'element_name' => 's__wooready_products_grid_product_normal_price_default_style1_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_default .wooready_price_box .wooready_price_normal .amount, {{WRAPPER}} .woo-ready-product-grid-layout-style1 .wooready_price_box .wooready_price_normal .amount',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Normal Price', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_normal_price_style2',
                'element_name' => 's__wooready_products_grid_product_normal_price_style2',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_two .wooready_price_box > .amount, {{WRAPPER}} .wooready_product_layout_two .wooready_price_box del .amount',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style2']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Discount Price', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_discount_price_style2',
                'element_name' => 's__wooready_products_grid_product_discount_price_style2',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_two .wooready_price_box ins .amount',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style2'],
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Normal Price', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_normal_price_style3_style4',
                'element_name' => 's__wooready_products_grid_product_normal_price_style3_style4',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_price_item .wooready_course_price .wooready_price_normal .woocommerce-Price-amount, {{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_price_item .wooready_course_variable_price',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3', 'style4'],
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Discount Price', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_discount_price_style3_style4',
                'element_name' => 's__wooready_products_grid_product_discount_price_style3_style4',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_price_item .wooready_course_price .wooreadu_discount_price .woocommerce-Price-amount',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3', 'style4']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Product Review Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_review_box',
                'element_name' => 's__wooready_products_grid_product_review_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_review .wooready_review_box',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1', 'style2']
                ],
            ]
        );

        $this->text_css(
            [
                'title'        => esc_html__('Review Item', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_review_item_default_one_two_style',
                'element_name' => 's__wooready_products_grid_product_review_item_default_one_two_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_review .wooready_review_box li i',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1', 'style2']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Course Review Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_review_box',
                'element_name' => 's__wooready_products_grid_course_review_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_price_item .wooready_course_review',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3', 'style4']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Review Item', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_review_item_style',
                'element_name' => 's__wooready_products_grid_product_review_item_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_review .wooready_review_box li, {{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_price_item .wooready_course_review ul li',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3', 'style4']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Rating Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_review_rating_style',
                'element_name' => 's__wooready_products_grid_product_review_rating_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_price_item .wooready_course_review span',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3', 'style4']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Cart Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_cart_box_default_style1',
                'element_name' => 's__wooready_products_grid_product_cart_box_default_style1',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_cart_box, {{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb .wooready_product_cart_box',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Cart Link Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_cart_link_box_default_style1',
                'element_name' => 's__wooready_products_grid_product_cart_link_box_default_style1',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_cart_box .wooready_product_cart, {{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb .wooready_product_cart_box .wooready_product_cart',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Cart Link Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_cart_link_style_default_style1',
                'element_name' => 's__wooready_products_grid_product_cart_link_style_default_style1',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_cart_box .wooready_product_cart a, {{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb .wooready_product_cart_box .wooready_product_cart a',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Cart Link Icon', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_cart_link_icon_style_default_style1',
                'element_name' => 's__wooready_products_grid_product_cart_link_icon_style_default_style1',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_cart_box .wooready_product_cart a i, {{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb .wooready_product_cart_box .wooready_product_cart a i',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Popup Link Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_cart_popup_box_default_style1',
                'element_name' => 's__wooready_products_grid_product_cart_popup_box_default_style1',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_cart_box .wooready_product_popup, {{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb .wooready_product_cart_box .wooready_product_popup',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Popup Link Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_cart_popup_link_style_default_style1',
                'element_name' => 's__wooready_products_grid_product_cart_popup_link_style__default_style1',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_cart_box .wooready_product_popup a i, {{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb .wooready_product_cart_box .wooready_product_popup a i',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['default', 'style1']
                ],
            ]
        );


        $this->box_css(
            [
                'title'        => esc_html__('Color box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_color_box',
                'element_name' => 's__wooready_products_grid_product_color_boxx',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_color',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style2']
                ],
            ]
        );


        $this->box_css(
            [
                'title'        => esc_html__('Cart Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_cart_box_style2',
                'element_name' => 's__wooready_products_grid_product_cart_box_style2',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_cart_box',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style2']
                ],
            ]
        );

        $this->text_css(
            [
                'title'        => esc_html__('Cart Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_cart_box_style_style2',
                'element_name' => 's__wooready_products_grid_product_cart_box_style_style2',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_cart_box .wooready_product_cart a',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style2']
                ],
            ]
        );

        $this->text_css(
            [
                'title'        => esc_html__('Cart Icon', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_cart_box_icon_style_style2',
                'element_name' => 's__wooready_products_grid_product_cart_box_icon_style_style2',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_content_box .wooready_product_cart_box .wooready_product_cart a i',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style2']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Popup Link Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_cart_popup_box_default_style2',
                'element_name' => 's__wooready_products_grid_product_cart_popup_box_default_style2',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb .wooready_product_cart_box .wooready_product_popup',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style2']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Popup Link Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_cart_popup_link_style_default_style2',
                'element_name' => 's__wooready_products_grid_product_cart_popup_link_style__default_style2',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_product_thumb .wooready_product_cart_box .wooready_product_popup a i',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style2']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Course Cart/Popup Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_cart_popup_box_style3',
                'element_name' => 's__wooready_products_grid_product_cart_popup_box_style3',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_thumb .wooready_product_cart_box',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Course Cart Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_cart_box_style3',
                'element_name' => 's__wooready_products_grid_product_cart_box_style3',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_thumb .wooready_product_cart_box .wooready_product_cart',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Course Cart Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_cart_box_style_style3',
                'element_name' => 's__wooready_products_grid_product_cart_box_style_style3',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_thumb .wooready_product_cart_box .wooready_product_cart a',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Course Cart Icon', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_cart_box_icon_style_style3',
                'element_name' => 's__wooready_products_grid_product_cart_box_icon_style_style3',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_thumb .wooready_product_cart_box .wooready_product_cart a i',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Course Popup Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_popup_box_style3',
                'element_name' => 's__wooready_products_grid_product_popup_box_style3',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_thumb .wooready_product_cart_box .wooready_product_popup',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Course Quickview Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_popup_box_quickview_style3',
                'element_name' => 's__wooready_products_grid_product_popup_box_quickview_style3',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_thumb .wooready_product_cart_box .wooready_product_popup .wready-product-quickview',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Course Wishlist Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_popup_box_wishlist_style3',
                'element_name' => 's__wooready_products_grid_product_popup_box_wishlist_style3',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_thumb .wooready_product_cart_box .wooready_product_popup .wready-product-wishlist',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Refresh Button Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_popup_box_refresh_style3',
                'element_name' => 's__wooready_products_grid_product_popup_box_refresh_style3',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_thumb .wooready_product_cart_box .wooready_product_popup .wready-product-refresh',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Course Sub Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_sub_box',
                'element_name' => 's__wooready_products_grid_course_sub_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_sub_item',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Course Sub Item', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_sub_item_box',
                'element_name' => 's__wooready_products_grid_course_sub_item_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_sub_item ul li',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Course Sub Item style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_sub_item_style',
                'element_name' => 's__wooready_products_grid_course_sub_item_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_content .wooready_course_sub_item ul li a',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style3']
                ],
            ]
        );


        // Layout Four Course Overly Styles

        $this->box_css(
            [
                'title'        => esc_html__('Course Overly Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_cart_overly_box',
                'element_name' => 's__wooready_products_grid_product_cart_overly_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Overly Title Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_overly_title_box_style4',
                'element_name' => 's__wooready_products_grid_course_overly_title_box_style4',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay .wooready_course_title',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );

        $this->text_css(
            [
                'title'        => esc_html__('Overly Title Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_overly_title_style_style4',
                'element_name' => 's__wooready_products_grid_course_overly_title_style_style4',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay .wooready_course_title .title a',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Overly Review Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_overly_review_box_style4',
                'element_name' => 's__wooready_products_grid_course_overly_review_box_style4',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay .wooready_course_review',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Overly Review Item', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_overly_review_item_style',
                'element_name' => 's__wooready_products_grid_product_overly_review_item_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay .wooready_course_review ul li',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Overly Rating Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_product_overly_review_rating_style',
                'element_name' => 's__wooready_products_grid_product_overly_review_rating_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay .wooready_course_review span',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Overly Cart Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_cart_box_style4',
                'element_name' => 's__wooready_products_grid_product_cart_box_style4',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay .wooready_course_cart',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Overly Cart Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_overly_cart_box_style4_style',
                'element_name' => 's__wooready_products_grid_course_overly_cart_box_style4_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay .wooready_course_cart a',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Overly Cart Icon Style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_cart_box_style4_icon_style',
                'element_name' => 's__wooready_products_grid_course_cart_box_style4_icon_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay .wooready_course_cart a i',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Overly Course Sub Box', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_overly_sub_box',
                'element_name' => 's__wooready_products_grid_course_overly_sub_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay .wooready_course_sub_item',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );

        $this->box_css(
            [
                'title'        => esc_html__('Overly Course Sub Item', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_overly_sub_item_box',
                'element_name' => 's__wooready_products_grid_course_overly_sub_item_box',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay .wooready_course_sub_item ul li',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title'        => esc_html__('Overly Course Sub Item style', 'shopready-elementor-addon'),
                'slug'         => 'wooready_products_grid_course_overly_sub_item_style',
                'element_name' => 's__wooready_products_grid_course_overly_sub_item_style',
                'selector'     => '{{WRAPPER}} .wooready_product_layout_wrapper .wooready_course_product_overlay .wooready_course_sub_item ul li a',
                'condition'       => [
                    'wooready_products_grid_layout_select' => ['style4']
                ],
            ]
        );
    }

    protected function html()
    {

        $settings      = $this->get_settings_for_display();

        $product_Modal = new Product_Modal($settings);
        $product_Modal->set_post_type();
        $products = $product_Modal->get_posts();

        $add_to_cart_icon = shop_ready_render_icons($settings['add_to_cart_icon'], 'wready-icons');
        $quick_view_icon  = shop_ready_render_icons($settings['quick_view_icon'], 'wready-icons');
        $wishlist_icon    = shop_ready_render_icons($settings['wishlist_icon'], 'wready-icons');
        $refresh_icon     = shop_ready_render_icons($settings['refresh_icon'], 'wready-icons');

        $sale_text       = $settings['wooready_products_grid_sale_text'] ? $settings['wooready_products_grid_sale_text'] : esc_html__('Sale', 'shopready-elementor-addon');
        $popular_text    = $settings['wooready_products_grid_popular_text'] ? $settings['wooready_products_grid_popular_text'] : esc_html__('Popular', 'shopready-elementor-addon');
        $featured_text   = $settings['wooready_products_grid_featured_text'] ? $settings['wooready_products_grid_featured_text'] : esc_html__('Featured', 'shopready-elementor-addon');
        $cart_text       = $settings['wooready_products_grid_addtocart_text'] ? $settings['wooready_products_grid_addtocart_text'] : esc_html__('Add to cart', 'shopready-elementor-addon');
        $post_title_crop = $settings['post_title_crop'] ? $settings['post_title_crop'] : 5;

        $data_settings = [
            'products'         => $products,
            'sale_text'        => $sale_text,
            'popular_text'     => $popular_text,
            'featured_text'    => $featured_text,
            'cart_text'        => $cart_text,
            'post_title_crop'  => $post_title_crop,
            'add_to_cart_icon' => $add_to_cart_icon,
            'quick_view_icon'  => $quick_view_icon,
            'wishlist_icon'    => $wishlist_icon,
            'refresh_icon'     => $refresh_icon,
            'image_size'       => $settings['product_image_size_size'],
        ];

        $this->add_render_attribute(
            'woo_ready_product_grid_wrapper_style',
            [
                'class' => ['woo-ready-product-grid-layout', 'woo-ready-product-grid-layout-' . $settings['wooready_products_grid_layout_select']],
            ]
        );

        echo wp_kses_post(sprintf("<div %s>", $this->get_render_attribute_string('woo_ready_product_grid_wrapper_style')));
        echo wp_kses_post(sprintf("<div class='display:grid grid-template-columns-%s'>", 4));

        if (file_exists(dirname(__FILE__) . '/template-parts/grid/' . $settings['wooready_products_grid_layout_select'] . '.php')) {

            shop_ready_widget_template_part(
                'shop/template-parts/grid/' . $settings['wooready_products_grid_layout_select'] . '.php',
                [
                    'settings' => $data_settings,
                ]
            );

        } else {

            shop_ready_widget_template_part(
                'shop/template-parts/grid/default.php',
                [
                    'settings' => $data_settings,
                ]
            );
            
        }

        wp_kses_post('</div></div>');
    }
}
