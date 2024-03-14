<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

class MEAFE_Product_Grid extends Widget_Base
{
    public function get_name()
    {
        return 'meafe-product-grid';
    }

    public function get_title()
    {
        return esc_html__('Product Grid', 'mega-elements-addons-for-elementor');
    }

    public function get_icon()
    {
        return 'meafe-product-grid';
    }

    public function get_categories()
    {
        return ['meafe-elements'];
    }

    public function get_style_depends()
    {
        return ['meafe-product-grid'];
    }

    public function get_script_depends()
    {
        return ['meafe-product-grid'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'meafe_PG_content_general_settings',
            array(
                'label' => __('General Settings', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'PG_layouts',
            [
                'label' => esc_html__('Select Layout', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'label_block' => false,
                'options' => [
                    '1' => esc_html__('Layout One', 'mega-elements-addons-for-elementor'),
                    '2' => esc_html__('Layout Two', 'mega-elements-addons-for-elementor'),
                    '3' => esc_html__('Layout Three', 'mega-elements-addons-for-elementor'),
                ],
            ]
        );

        $this->add_control(
            'PG_type',
            [
                'label' => esc_html__('Show products based on', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'latest',
                'label_block' => false,
                'options' => [
                    'latest' => esc_html__('Latest Products', 'mega-elements-addons-for-elementor'),
                    'popular' => esc_html__('Popular Products', 'mega-elements-addons-for-elementor'),
                    'sales' => esc_html__('Products on Sales', 'mega-elements-addons-for-elementor'),
                ],
            ]
        );

        $this->add_control(
            'PG_ed_title',
            [
                'label' => esc_html__('Show Title', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PG_ed_price',
            [
                'label' => esc_html__('Show Price', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PG_ed_cart',
            [
                'label' => esc_html__('Show Cart', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PG_ed_quick_view',
            [
                'label' => esc_html__('Show Quick View', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PG_ed_wishlist',
            [
                'label' => esc_html__('Show Wishlist', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PG_ed_badge',
            [
                'label' => esc_html__('Show badge', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'PG_number',
            [
                'label' => esc_html__('No. of Products', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'max' => '32',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PG_ed_product_ratings',
            [
                'label' => esc_html__('Show product ratings', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PG_ed_carousel',
            [
                'label' => esc_html__('Show Carousel', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PG_prev_icon',
            [
                'label' => __('Previous Icon', 'mega-elements-addons-for-elementor'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-chevron-left',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'PG_ed_carousel' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PG_next_icon',
            [
                'label' => __('Next Icon', 'mega-elements-addons-for-elementor'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'PG_ed_carousel' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'PG_carousel_dots',
            [
                'label' => esc_html__('Show Carousel Dots', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );


        $this->end_controls_section();

        /**
         *  Product Grid Tab Section General Style
         */
        $this->start_controls_section(
            'meafe_PG_general_style',
            [
                'label' => esc_html__('General Style', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'PG_padding',
            [
                'label' => __('Padding', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => ['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'PG_box_shadow',
                'selector' => '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner',
            ]
        );

        $this->add_responsive_control(
            'PG_border_radius',
            [
                'label' => __('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner, {{WRAPPER}} .meafe-product-grid-wrapper.layout-2 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-media img, .meafe-product-grid-wrapper.layout-2 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper, {{WRAPPER}} .meafe-product-grid-wrapper.layout-1 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-media img, .meafe-product-grid-wrapper.layout-1 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'PG_align',
            [
                'label' => __('Alignment', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'meafe-product-grid-content-align-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_PG_content_style',
            [
                'label' => __('Content', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'PG_content_padding',
            [
                'label' => esc_html__('Padding', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => ['top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'PG_content_bg_color',
            [
                'label' => __('Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'PG_title_color',
            [
                'label' => __('Title Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .meafe-entry-title .meafe-grid-post-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PG_title_typography',
                'label' => __('Title Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .meafe-entry-title',
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 20]],
                    'line_height' => ['default' => ['unit' => 'em', 'size' => 1]]
                ]
            ]
        );

        $this->add_control(
            'PG_cat_color',
            [
                'label' => __('Category Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#878787',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .category--wrapper' => 'color: {{VALUE}};',
                ],
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 14]],
                    'line_height' => ['default' => ['unit' => 'em', 'size' => 22.4]]
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PG_cat_typography',
                'label' => __('Category Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .category--wrapper',
            ]
        );

        $this->add_control(
            'PG_regular_price_color',
            [
                'label' => __('Regular Price Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#878787',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price del' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PG_regular_price_typo',
                'label' => __('Regular Price Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price del',
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 16]],
                    'line_height' => ['default' => ['unit' => 'px', 'size' => 28.8]],
                    'font_weight' => ['default' => 'normal']
                ]
            ]
        );

        $this->add_control(
            'PG_sale_price_color',
            [
                'label' => __('Sale Price Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#547c0e',
                'selectors' => [
                    '{{WRAPPER}}  .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price, {{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price ins' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PG_sale_price_typo',
                'label' => __('Sale Price Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '.meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price, {{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer .price ins',
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 20]],
                    'line_height' => ['default' => ['unit' => 'px', 'size' => 32]],
                    'font_weight' => ['default' => 700]
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_PG_icon_style',
            [
                'label' => __('Product Icons', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]

        );

        $this->add_responsive_control(
            'PG_icon_size',
            [
                'label' => esc_html__('Icon Size', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button::before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .quickview-icon .yith-wcqv-button::before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .yith-wcwl-add-to-wishlist .yith-wcwl-add-button .add_to_wishlist::before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs('PG_icon_colors');

        $this->start_controls_tab(
            'PG_icon_initial',
            [
                'label' => __('Initial', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PG_cart_icon_color_initial',
            [
                'label' => esc_html__('Cart Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button::before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PG_cart_icon_bg_color_initial',
            [
                'label' => esc_html__('Cart Icon Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .meafe-entry-wrapper .product-footer-wrapper .add-to-cart .button, {{WRAPPER}} .meafe-product-grid-wrapper:not(.layout-3) .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button' => 'background-color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PG_quickview_icon_color_initial',
            [
                'label' => esc_html__('Quick View Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .quickview-icon .yith-wcqv-button::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-product-grid-wrapper.layout-2 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .quickview-icon .view-text' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'PG_quickview_icon_bg_color_initial',
            [
                'label' => esc_html__('Quick View Icon Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper:not(layout-2) .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .quickview-icon .button, {{WRAPPER}} .meafe-product-grid-wrapper.layout-1 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .quickview-icon .button,{{WRAPPER}} .meafe-product-grid-wrapper.layout-2 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .quickview-icon' => 'background-color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PG_wishlist_icon_color_initial',
            [
                'label' => esc_html__('Wishlist Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .yith-wcwl-add-to-wishlist .yith-wcwl-add-button .add_to_wishlist::before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PG_wishlist_icon_bg_color_initial',
            [
                'label' => esc_html__('Wishlist Icon Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F84D4D',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .yith-wcwl-add-to-wishlist .yith-wcwl-add-button .add_to_wishlist' => 'background-color: {{VALUE}}; border-color:{{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PG_cart_icon_hover',
            [
                'label' => __('Hover', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PG_cart_icon_hover_color',
            [
                'label' => esc_html__('Cart Icon Hover Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button:hover .button::before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PG_cart_icon_hover_bg_color',
            [
                'label' => esc_html__('Cart Icon Hover Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper:not(.layout-3) .meafe-products .meafe-products-inner .add-to-cart .button:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PG_quickview_icon_hover_color',
            [
                'label' => esc_html__('Quick View Icon Hover Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .quickview-icon:hover .yith-wcqv-button::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-product-grid-wrapper.layout-2 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .quickview-icon:hover .view-text' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'PG_quickview_icon_hover_bg_color',
            [
                'label' => esc_html__('Quick View Icon Hover Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper:not(layout-2) .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .quickview-icon .button:hover,{{WRAPPER}} .meafe-product-grid-wrapper.layout-2 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .quickview-icon:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PG_wishlist_icon_hover_color',
            [
                'label' => esc_html__('Wishlist Icon Hover Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .yith-wcwl-add-to-wishlist .yith-wcwl-add-button .add_to_wishlist:hover::before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PG_wishlist_icon_hover_bg_color',
            [
                'label' => esc_html__('Wishlist Icon Hover Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f84d4d',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .yith-wcwl-add-to-wishlist .yith-wcwl-add-button .add_to_wishlist:hover' => 'background-color: {{VALUE}};  border-color:{{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_PG_button_style',
            [
                'label' => __('Cart Button', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'PG_layouts' => '3'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'PG_cart_typography',
                'label' => __('Typography', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe-product-grid-wrapper.layout-3 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button',
            ]
        );

        $this->start_controls_tabs('PG_button_colors');

        $this->start_controls_tab(
            'PG_cart_button_color_initial',
            [
                'label' => __('Initial', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PG_cart_btn_color_initial',
            [
                'label' => esc_html__('Button Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper.layout-3 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PG_btn_bg_color_initial',
            [
                'label' => esc_html__('Button Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper.layout-3 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PG_button_color_hover',
            [
                'label' => __('Hover', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PG_btn_color_hover',
            [
                'label' => esc_html__('Button Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper.layout-3 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'PG_btn_bg_color_hover',
            [
                'label' => esc_html__('Button Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper.layout-3 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'PG_button_border_normal',
                'selector' => '{{WRAPPER}} .meafe-product-grid-wrapper.layout-3 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button',
            ]
        );

        $this->add_control(
            'PG_button_border_radius_normal',
            [
                'label' => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper.layout-3 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button' => 'border-radius: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'PG_button_box_shadow_normal',
                'label' => esc_html__('Button Shadow', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe-product-grid-wrapper.layout-3 .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafe-products-inner .add-to-cart .button',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_PG_carousel_style',
            [
                'label' => __('Carousel', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('PG_carousel_color');

        $this->start_controls_tab(
            'PG_carousel_color_initial',
            [
                'label' => __('Initial', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PG_carousel_icon_color_initial',
            [
                'label' => __('Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-products-wrapper .meafa-navigation-wrap .nav i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'PG_carousel_bg_color_initial',
            [
                'label' => __('Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafa-navigation-wrap .nav' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'PG_carousel_dots_color_initial',
            [
                'label' => __('Carousel Dots Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4e7302',
                'selectors' => [
                    '{{WRAPPER}} .meafe-products-wrapper .meafa-swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'PG_carousel_color_hover',
            [
                'label' => __('Hover', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'PG_carousel_icon_hover_color',
            [
                'label' => __('Icon Hover Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meafe-products-wrapper .meafa-navigation-wrap .nav:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'PG_carousel_bg_hover_color',
            [
                'label' => __('Background Hover Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4e7302',
                'selectors' => [
                    '{{WRAPPER}} .meafe-product-grid-wrapper .meafe-product-innerwrapper .meafe-products-wrapper .meafe-products .meafa-navigation-wrap .nav:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'PG_carousel_dots_hover_color',
            [
                'label' => __('Carousel Dots Active Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4e7302',
                'selectors' => [
                    '{{WRAPPER}} .meafe-products-wrapper .meafa-swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
    }

    public function get_query_args($settings = [])
    {
        $settings = wp_parse_args($settings, [
            'post_type' => 'product',
            'posts_ids' => [],
            'orderby' => 'date',
            'order' => 'desc',
            'posts_per_page' => 4,
            'offset' => 0,
            'post__not_in' => [],
        ]);

        $args = [
            'post_type' => 'product',
            'orderby' => 'date',
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish',
            'meta_key' => '_wc_average_rating',
            'posts_per_page' => $settings['PG_number'],
        ];

        if ($settings['PG_type'] == 'sales') {
            $args['meta_query'] = WC()->query->get_meta_query();
            $args['post__in'] = wc_get_product_ids_on_sale();
        } elseif ($settings['PG_type'] == 'popular') {
            $args['meta_key'] = 'total_sales';
            $args['order_by'] = 'meta_value_num';
        };

        $args['tax_query'] = [];


        if (!empty($args['tax_query'])) {
            $args['tax_query']['relation'] = 'AND';
        }

        return $args;
    }

    public function get_nav_details(){
        $settings   = $this->get_settings_for_display();
        $ed_carousel = $settings['PG_ed_carousel'];
        $nav_prev   = $settings['PG_prev_icon'];
        $nav_next   = $settings['PG_next_icon'];

        if( $ed_carousel === 'yes' ) {
            $return_all = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_alls = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_all_start = [ '', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
            $return_all_end = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '' ];
            
            if( $nav_prev['library'] != 'svg' && $nav_next['library'] != 'svg' ) {
                return ( [ '<i class="' . esc_attr($nav_prev['value']) . '" aria-hidden="true"></i>', '<i class="' . esc_attr($nav_next['value']) . '" aria-hidden="true"></i>' ] );                    
            }
            
            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] == 'svg' ){
                return ( [ '<img src="' . esc_url($nav_prev['value']['url']) . '">', '<img src="' . esc_url($nav_next['value']['url']) . '">' ] );
            }
            
            if ( $nav_prev['library'] == '' && $nav_next['library'] == 'svg' ){
                array_pop($return_all_start);
                array_push($return_all_start, esc_url($nav_next['value']['url']));
                return ( [ '', '<img src="' . $return_all_start[1] . '">' ] );
                // return return_all_start;
            }

            if ( $nav_prev['library'] != 'svg' && $nav_next['library'] == 'svg' ){
                array_pop($return_all);
                array_push($return_all, '<img src="' . esc_url($nav_next['value']['url']) . '">');
                return $return_all;
            }
            
            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] == '' ){
                array_reverse($return_all_end);
                array_pop($return_all_end);
                array_push($return_all_end, esc_url($nav_prev['value']['url']));
                array_reverse($return_all_end);
                return ( [ '<img src="' . $return_all_end[0] . '">', '' ] );
            }

            if ( $nav_prev['library'] == 'svg' && $nav_next['library'] != 'svg' ){
                array_reverse($return_alls);
                array_pop($return_alls);
                array_push($return_alls, '<img src="' . esc_url($nav_prev['value']['url']) . '">');
                array_reverse($return_alls);
                return $return_alls;
            }   
        }
        
        return ( [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ] );

    }
    
    public static function render_product_grid_template($args, $settings, $nav_icons)
    {

        $cat_query = new \WP_Query($args);
        $class_cart = '';
        
        if (($settings['PG_layouts'] == 1 && ($settings['PG_ed_cart'] || $settings['PG_ed_quick_view'] || $settings['PG_ed_wishlist'])) || ($settings['PG_layouts'] == 3 && $settings['PG_ed_cart'])) {
            $class_cart = 'cart_exist ';
        }
        if($settings['PG_ed_carousel'] == 'yes'){
            $class_cart .= ' swiper-enabled';
        }

        $swiper_class = ($settings['PG_ed_carousel'] == 'yes') ? ' swiper-slide' : '';

        ob_start();

        if ( $cat_query->have_posts() && meafe_is_woocommerce_activated() ) { ?>
            <div class="meafe-products-wrapper">
                <div class="meafe-products <?php echo esc_attr($class_cart); ?>">
                    <?php if($settings['PG_ed_carousel'] === 'yes') echo '<div class="swiper-container"><div class="swiper-wrapper">';
                        while ($cat_query->have_posts()) {
                            $cat_query->the_post();
                            echo '<div class="meafe-products-inner' . esc_attr($swiper_class) .'">';
                            if (has_post_thumbnail()) {
                                echo '<figure class="meafe-entry-media image-wrapper">';
                                if ($settings['PG_layouts'] == 2 && (is_yith_whislist_activated() && $settings['PG_ed_wishlist'])) {
                                    echo '<span class ="wishlist-icon">';
                                    echo do_shortcode('[yith_wcwl_add_to_wishlist]');
                                    echo '</span>';
                                }
                                if ($settings['PG_layouts'] != 1 && (is_yith_quickview_activated() && $settings['PG_ed_quick_view'])) {
                                    echo '<span class="quickview-icon">';
                                    echo do_shortcode('[yith_quick_view]');
                                    if( $settings['PG_layouts'] == 2 ) echo '<span class="view-text">' . esc_html__( 'View', 'mega-elements-addons-for-elementor' ) . '</span>';
                                    echo '</span>';
                                }
                                if ($settings['PG_ed_badge']) woocommerce_show_product_sale_flash();
                                echo '<a class="meafe-grid-post-link" href="' . esc_url(get_the_permalink()) . '" title="' . esc_html(get_the_title()) . '">
                                        <img src="' . esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), 'meafe-product-one')) . '" alt="' . esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) . '">
                                    </a>';
                                echo '</figure>';
                            }
                            echo '<div class="meafe-entry-wrapper category--main">';
                            if ('product' === get_post_type() && $settings['PG_layouts'] != 3) {
                                $categories_list = get_the_terms(get_the_ID(), 'product_cat');
                                if ($categories_list) {
                                    foreach ($categories_list as $product_cat) {
                                        echo '<span class="category--wrapper" itemprop="about">' . esc_html($product_cat->name) . '</span>';
                                    }
                                }
                            }
                            if ($settings['PG_ed_product_ratings'] && $settings['PG_layouts'] == 3) {
                                global $product;
                                echo '<div class="product-rating">';
                                echo wc_get_rating_html($product->get_average_rating());
                                echo '</div>';
                            }

                            if ($settings['PG_ed_title']) {
                                echo '<h3 class="meafe-entry-title"><a class="meafe-grid-post-link" href="' . esc_url(get_the_permalink()) . '" title="' . esc_html(get_the_title()) . '">' . esc_html(get_the_title()) . '</a></h3>';
                            }
                            $stock = get_post_meta(get_the_ID(), '_stock_status', true);
                            if ($settings['PG_ed_price'] || $stock == 'outofstock') {
                                echo '<div class="product-footer-wrapper"><div class="product-footer">';
                                if ($settings['PG_ed_price']) woocommerce_template_single_price(); //price
                                if ($stock == 'outofstock') {
                                    echo '<span class="outofstock">' . esc_html__('Sold Out', 'mega-elements-addons-for-elementor') . '</span>';
                                }
                                echo '</div>';
                                if ($settings['PG_ed_cart'] && $settings['PG_layouts'] == 2) {
                                    echo '<span class="add-to-cart">';
                                    woocommerce_template_loop_add_to_cart();
                                    echo '</span>';
                                }
                                if ($settings['PG_layouts'] == 3 && (is_yith_whislist_activated() && $settings['PG_ed_wishlist'])) {
                                    echo '<span class ="wishlist-icon">';
                                    echo do_shortcode('[yith_wcwl_add_to_wishlist]');
                                    echo '</span>';
                                }
                                echo '</div>';
                            }
                            if (($settings['PG_layouts'] == 1) && ($settings['PG_ed_cart'] || (is_yith_quickview_activated() && $settings['PG_ed_quick_view']) || (is_yith_whislist_activated() && $settings['PG_ed_wishlist']))) {
                                echo '<div class="product-meta">';
                                if ($settings['PG_ed_cart'] && $settings['PG_layouts'] == 1) {
                                    echo '<span class="add-to-cart">';
                                    woocommerce_template_loop_add_to_cart();
                                    echo '</span>';
                                }

                                if ($settings['PG_layouts'] == 1 && (is_yith_quickview_activated() && $settings['PG_ed_quick_view'])) {
                                    echo '<span class="quickview-icon">';
                                    echo do_shortcode('[yith_quick_view]');
                                    echo '</span>';
                                }
                                if ($settings['PG_layouts'] == 1 && (is_yith_whislist_activated() && $settings['PG_ed_wishlist'])) {
                                    echo '<span class ="wishlist-icon">';
                                    echo do_shortcode('[yith_wcwl_add_to_wishlist]');
                                    echo '</span>';
                                }
                                echo '</div>';
                            }
                            if ($settings['PG_ed_cart'] && $settings['PG_layouts'] == 3) {
                                echo '<span class="add-to-cart">';
                                woocommerce_template_loop_add_to_cart();
                                echo '</span>';
                            }
                            echo '</div>';
                            echo '</div>';
                        }
                    if($settings['PG_ed_carousel'] === 'yes') echo '</div></div>';
                    
                    if($settings['PG_ed_carousel'] === 'yes' && $settings['PG_carousel_dots'] == 'yes') { ?>
                        <!-- If we need pagination -->
                        <?php if( ($settings['PG_number'] > 3 && wp_is_mobile()) || ($settings['PG_number'] > 4 && !wp_is_mobile())) { ?>
                            <div class="prod-grid meafa-swiper-pagination"></div>
                        <?php }
                    } 
                    if($settings['PG_ed_carousel'] === 'yes') { ?>
                        <!-- If we need navigation buttons -->
                        <?php if( ($settings['PG_number'] > 3 && wp_is_mobile()) || ($settings['PG_number'] > 4 && !wp_is_mobile())) { ?>
                            <div class="meafa-navigation-wrap">
                                <div class="prod-grid meafa-navigation-prev nav">
                                    <?php echo $nav_icons[0]; ?>
                                </div>
                                <div class="prod-grid meafa-navigation-next nav">
                                    <?php echo $nav_icons[1]; ?>
                                </div>
                            </div>
                        <?php } 
                    } ?>
                </div> 
            </div>
        <?php } else {
            echo '<p class="no-posts-found">' . esc_html__('No Products found!', 'mega-elements-addons-for-elementor') . '</p>';
        }

        wp_reset_postdata();

        return ob_get_clean();
    }

    protected function render() {
        $settings    = $this->get_settings();
        $args        = $this->get_query_args($settings);
        $nav_icons   = $this->get_nav_details();
        $settings_arry = [
            'PG_ed_title'           => $settings['PG_ed_title'],
            'PG_layouts'            => $settings['PG_layouts'],
            'PG_ed_badge'           => $settings['PG_ed_badge'],
            'PG_ed_cart'            => $settings['PG_ed_cart'],
            'PG_ed_quick_view'      => $settings['PG_ed_quick_view'],
            'PG_ed_wishlist'        => $settings['PG_ed_wishlist'],
            'PG_ed_product_ratings' => $settings['PG_ed_product_ratings'],
            'PG_ed_price'           => $settings['PG_ed_price'],
            'PG_type'               => $settings['PG_type'],
            'PG_ed_carousel'        => $settings['PG_ed_carousel'],
            'PG_align'              => $settings['PG_align'],
            'PG_carousel_dots'      => $settings['PG_carousel_dots'],
            'PG_ed_carousel'        => $settings['PG_ed_carousel'],
            'PG_number'             => $settings['PG_number']
        ];

        $this->add_render_attribute(
            'product_wrapper',
            [
                'id' => 'meafe-post-grid-' . esc_attr($this->get_id()),
                'class' => [
                    'meafe-product-grid-wrapper layout-' . esc_attr($settings['PG_layouts']),
                ],
                'data-post-no' => esc_attr($settings['PG_number'])
            ]
        );

        if ($settings['PG_number'] <= 2) {
            $this->add_render_attribute('product_wrapper', 'class', 'wrapper-alignment-center');
        }

        echo '<div ' . $this->get_render_attribute_string('product_wrapper') . '>
            <div class="meafe-product-innerwrapper">
                ' . self::render_product_grid_template($args, $settings_arry, $nav_icons) . '
            </div>
        </div>';
    }
}