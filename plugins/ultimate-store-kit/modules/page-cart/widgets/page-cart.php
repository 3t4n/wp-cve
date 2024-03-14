<?php

namespace UltimateStoreKit\Modules\PageCart\Widgets;

use UltimateStoreKit\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Page_Cart extends Module_Base {

    public function get_name() {
        return 'usk-page-cart';
    }

    public function get_title() {
        return BDTUSK . esc_html__('Cart Page', 'ultimate-store-kit');
    }

    public function get_icon() {
        return 'usk-widget-icon usk-icon-page-cart usk-new';
    }

    public function get_categories() {
        return ['ultimate-store-kit'];
    }

    public function get_style_depends() {
        if ($this->usk_is_edit_mode()) {
            return ['usk-all-styles'];
        } else {
            return ['usk-page-cart'];
        }
    }
    public function get_keywords() {
        return ['post', 'cart'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_cart_layout',
            [
                'label' => __('Cart Layout', 'ultimate-store-kit-pro'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label'   => __('Show Image', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'   => __('Show Title', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label'   => __('Show Price', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_quantity',
            [
                'label'   => __('Show Quantity', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_subtotal',
            [
                'label'   => __('Show Subtotal', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'cart_heading_style_section',
            [
                'label' => __('Cart Heading', 'ultimate-store-kit-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'cart_heading_align',
            [
                'label'     => __('Alignment', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __('Left', 'ultimate-store-kit-pro'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'ultimate-store-kit-pro'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'ultimate-store-kit-pro'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                // 'desktop_default' => 'left',
                // 'tablet_default' => 'left',
                // 'mobile_default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce thead th' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cart_heading_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce thead th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'cart_heading_background',
                'label'     => esc_html__('Background', 'ultimate-store-kit'),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .usk-page-cart .woocommerce-cart-form .shop_table th',
                'exclude'   => [
                    'image'
                ]
            ]
        );
        //border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'cart_heading_border',
                'label'     => esc_html__('Border', 'ultimate-store-kit'),
                'selector'  => '{{WRAPPER}} .usk-page-cart .woocommerce-cart-form .shop_table th',
            ]
        );
        //padding
        $this->add_responsive_control(
            'cart_heading_padding',
            [
                'label'      => esc_html__('Padding', 'ultimate-store-kit'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce-cart-form .shop_table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cart_heading_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .woocommerce thead th',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'product_item_style_section',
            [
                'label' => __('Cart Item', 'ultimate-store-kit-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'product_title_heading',
            [
                'label'   => esc_html__('Product Title', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::HEADING,
                'condition' => [
                    'show_title' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'product_title_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce tbody .usk-product-title a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'product_title_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .woocommerce tbody .usk-product-title a',
                'condition' => [
                    'show_title' => 'yes',
                ]
            ]
        );

        // price
        $this->add_control(
            'product_price_heading',
            [
                'label'   => esc_html__('Product Price', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::HEADING,
                'condition' => [
                    'show_price' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'product_price_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce tbody .usk-product-price' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_price' => 'yes',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'product_price_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .woocommerce tbody .usk-product-price',
                'condition' => [
                    'show_price' => 'yes',
                ]
            ]
        );

        // quantity
        $this->add_control(
            'product_quantity_heading',
            [
                'label'   => esc_html__('Product Quantity', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::HEADING,
                'condition' => [
                    'show_quantity' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'product_quantity_width',
            [
                'label'     => __('Width', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .usk-product-quantity .input-text.qty'  => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_quantity' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'product_quantity_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .usk-product-quantity .input-text.qty' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_quantity' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'product_quantity_align',
            [
                'label'     => __('Alignment', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __('Left', 'ultimate-store-kit-pro'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'ultimate-store-kit-pro'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'ultimate-store-kit-pro'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .usk-product-quantity .input-text.qty' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'show_quantity' => 'yes',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'product_quantity_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .usk-product-quantity .input-text.qty',
                'condition' => [
                    'show_quantity' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'product_quantity_padding',
            [
                'label'      => __('Padding', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .usk-product-quantity .input-text.qty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_quantity' => 'yes',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'product_quantity_border',
                'selector'    => '{{WRAPPER}} .usk-page-cart .usk-product-quantity .input-text.qty',
                'condition' => [
                    'show_quantity' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'product_quantity_border_radius',
            [
                'label'      => __('Border Radius', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .usk-product-quantity .input-text.qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
                'condition' => [
                    'show_quantity' => 'yes',
                ]
            ]
        );

        // sub total
        $this->add_control(
            'product_subtotal_heading',
            [
                'label'   => esc_html__('Product Sub Total', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::HEADING,
                'condition' => [
                    'show_subtotal' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'product_subtotal_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce tbody .usk-product-subtotal' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_subtotal' => 'yes',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'product_subtotal_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .woocommerce tbody .usk-product-subtotal',
                'condition' => [
                    'show_subtotal' => 'yes',
                ]
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'product_coupon_style_section',
            [
                'label' => __('Coupon Style', 'ultimate-store-kit-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        //label color
        $this->add_control(
            'coupon_label_heading',
            [
                'label'   => esc_html__('Coupon Label', 'ultimate-store-kit-pro') . BDTUSK_NC,
                'type'    => Controls_Manager::HEADING,
            ]
        );

        //color 
        $this->add_control(
            'coupon_label_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .coupon label' => 'color: {{VALUE}};',
                ],
            ]
        );

        //typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'coupon_label_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .coupon label',
            ]
        );

        // coupon field
        $this->add_control(
            'coupon_field_heading',
            [
                'label'   => esc_html__('Coupon Field', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'coupon_field_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .coupon #coupon_code::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'coupon_field_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .coupon #coupon_code',
            ]
        );

        $this->add_responsive_control(
            'coupon_field_padding',
            [
                'label'      => __('Padding', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .coupon #coupon_code' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'coupon_field_border',
                'selector'    => '{{WRAPPER}} .usk-page-cart .coupon #coupon_code',
            ]
        );

        $this->add_responsive_control(
            'coupon_field_border_radius',
            [
                'label'      => __('Border Radius', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .coupon #coupon_code' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );


        // coupon button
        $this->add_control(
            'coupon_button_heading',
            [
                'label'   => esc_html__('Coupon Button', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'coupon_button_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .coupon .button',
            ]
        );

        $this->add_responsive_control(
            'coupon_button_padding',
            [
                'label'      => __('Padding', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .coupon .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'coupon_button_border',
                'selector'    => '{{WRAPPER}} .usk-page-cart .coupon .button',
            ]
        );

        $this->add_responsive_control(
            'coupon_button_border_radius',
            [
                'label'      => __('Border Radius', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .coupon .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs('coupon_button_tabs');

        $this->start_controls_tab(
            'coupon_button_normal',
            [
                'label' => __('Normal', 'ultimate-store-kit-pro'),
            ]
        );

        $this->add_control(
            'coupon_button_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .coupon .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'coupon_button_bg',
                'selector'  => '{{WRAPPER}} .usk-page-cart .coupon .button'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'coupon_button_hover',
            [
                'label' => __('Hover', 'ultimate-store-kit-pro'),
            ]
        );

        $this->add_control(
            'coupon_button_color_hover',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .coupon .button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'coupon_button_bg_hover',
                'selector'  => '{{WRAPPER}} .usk-page-cart .coupon .button:hover'
            ]
        );

        $this->add_control(
            'coupon_button_border_color_hover',
            [
                'label'     => __('Border Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .coupon .button:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'coupon_button_border_border!' => '',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();


        $this->start_controls_section(
            'update_cart_style_section',
            [
                'label' => __('Cart Update Button', 'ultimate-store-kit-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'update_cart_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .actions>.button',
            ]
        );

        $this->add_responsive_control(
            'update_cart_padding',
            [
                'label'      => __('Padding', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .actions>.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'update_cart_border',
                'selector'    => '{{WRAPPER}} .usk-page-cart .actions>.button',
            ]
        );

        $this->add_responsive_control(
            'update_cart_border_radius',
            [
                'label'      => __('Border Radius', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .actions>.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs('update_cart_tabs');

        $this->start_controls_tab(
            'update_cart_normal',
            [
                'label' => __('Normal', 'ultimate-store-kit-pro'),
            ]
        );

        $this->add_control(
            'update_cart_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .actions>.button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'update_cart_bg',
                'selector'  => '{{WRAPPER}} .usk-page-cart .actions>.button'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'update_cart_hover',
            [
                'label' => __('Hover', 'ultimate-store-kit-pro'),
            ]
        );

        $this->add_control(
            'update_cart_color_hover',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .actions>.button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'update_cart_bg_hover',
                'selector'  => '{{WRAPPER}} .usk-page-cart .actions>.button:hover'
            ]
        );

        $this->add_control(
            'update_cart_border_color_hover',
            [
                'label'     => __('Border Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .actions>.button:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'update_cart_border_border!' => '',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();



        $this->start_controls_section(
            'cart_collaterals_style_section',
            [
                'label' => __('Cart Collaterals', 'ultimate-store-kit-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'cart_total_heading',
            [
                'label'   => esc_html__('Heading', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'cart_total_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .cart-collaterals .cart_totals h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cart_total_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .cart-collaterals .cart_totals h2',
            ]
        );

        $this->add_control(
            'cart_total_bottom_spacing',
            [
                'label'      => esc_html__('Bottom Spacing', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', ''],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .cart-collaterals .cart_totals h2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'cart_sub_total_heading',
            [
                'label'   => esc_html__('Sub Total', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cart_sub_total_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .cart-collaterals .cart-subtotal th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cart_sub_total_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .cart-collaterals .cart-subtotal th',
            ]
        );

        $this->add_control(
            'cart_sub_total_amount_heading',
            [
                'label'   => esc_html__('Sub Total Amount', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cart_sub_total_amount_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .cart-collaterals .cart-subtotal .woocommerce-Price-amount.amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cart_sub_total_amount_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .cart-collaterals .cart-subtotal .woocommerce-Price-amount.amount',
            ]
        );

        $this->add_control(
            'cart_final_total_heading',
            [
                'label'   => esc_html__('Total', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cart_final_total_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .cart-collaterals .order-total th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cart_final_total_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .cart-collaterals .order-total th',
            ]
        );

        $this->add_control(
            'cart_final_total_amount_heading',
            [
                'label'   => esc_html__('Total Amount', 'ultimate-store-kit-pro'),
                'type'    => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cart_final_total_amount_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .cart-collaterals .order-total .woocommerce-Price-amount.amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cart_final_total_amount_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .cart-collaterals .order-total .woocommerce-Price-amount.amount',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'checkout_button_style_section',
            [
                'label' => __('Checkout Button', 'ultimate-store-kit-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'checkout_button_typo',
                'selector' => '{{WRAPPER}} .usk-page-cart .woocommerce .wc-proceed-to-checkout a',
            ]
        );

        $this->add_responsive_control(
            'checkout_button_padding',
            [
                'label'      => __('Padding', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce .wc-proceed-to-checkout a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'checkout_button_margin',
            [
                'label'      => __('Margin', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce .wc-proceed-to-checkout a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'checkout_button_border',
                'selector'    => '{{WRAPPER}} .usk-page-cart .woocommerce .wc-proceed-to-checkout a',
            ]
        );

        $this->add_responsive_control(
            'checkout_button_border_radius',
            [
                'label'      => __('Border Radius', 'ultimate-store-kit-pro'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce .wc-proceed-to-checkout a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );


        $this->start_controls_tabs('checkout_button_tabs');

        $this->start_controls_tab(
            'checkout_button_normal',
            [
                'label' => __('Normal', 'ultimate-store-kit-pro'),
            ]
        );

        $this->add_control(
            'checkout_button_color',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce .wc-proceed-to-checkout a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'checkout_button_bg',
                'selector'  => '{{WRAPPER}} .usk-page-cart .woocommerce .wc-proceed-to-checkout a'
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'checkout_button_hover',
            [
                'label' => __('Hover', 'ultimate-store-kit-pro'),
            ]
        );

        $this->add_control(
            'checkout_button_color_hover',
            [
                'label'     => __('Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce .wc-proceed-to-checkout a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'checkout_button_bg_hover',
                'selector'  => '{{WRAPPER}} .usk-page-cart .woocommerce .wc-proceed-to-checkout a:hover'
            ]
        );

        $this->add_control(
            'checkout_button_border_color_hover',
            [
                'label'     => __('Border Color', 'ultimate-store-kit-pro'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .usk-page-cart .woocommerce .wc-proceed-to-checkout a:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'checkout_button_border_border!' => '',
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }


    protected function render() {
        $settings = $this->get_settings_for_display();
        $cart_url = wc_get_cart_url();

        //echo do_shortcode('[woocommerce_cart]');
        global $woocommerce;
        $items = $woocommerce->cart->get_cart();

?>

        <div class="usk-page-cart">
            <div class="woocommerce">
                <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
                        <thead>
                            <tr>
                                <?php if ($settings['show_image'] == 'yes') : ?>
                                    <th><?php esc_html_e('Close', 'ultimate-store-kit-pro'); ?></th>
                                <?php endif; ?>

                                <?php if ($settings['show_image'] == 'yes') : ?>
                                    <th><?php esc_html_e('Image', 'ultimate-store-kit-pro'); ?></th>
                                <?php endif; ?>

                                <?php if ($settings['show_title'] == 'yes') : ?>
                                    <th><?php esc_html_e('Product Title', 'ultimate-store-kit-pro'); ?></th>
                                <?php endif; ?>

                                <?php if ($settings['show_price'] == 'yes') : ?>
                                    <th><?php esc_html_e('Price', 'ultimate-store-kit-pro'); ?></th>
                                <?php endif; ?>

                                <?php if ($settings['show_quantity'] == 'yes') : ?>
                                    <th><?php esc_html_e('Quantity', 'ultimate-store-kit-pro'); ?></th>
                                <?php endif; ?>

                                <?php if ($settings['show_subtotal'] == 'yes') : ?>
                                    <th><?php esc_html_e('Subtotal', 'ultimate-store-kit-pro'); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $product_names = array();
                            foreach ($items as $item => $values) {

                                $_product =  wc_get_product($values['data']->get_id());
                                // $_product2   = apply_filters( 'woocommerce_cart_item_product', $values['data'], $values, $item );
                                // Retrieve WC_Product object from the product-id:
                                $_woo_product = wc_get_product($values['product_id']);

                                // Get SKU from the WC_Product object:
                                $product_names['sku'] = $_woo_product->get_sku();
                                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($values) : '', $values, $item);
                            ?>

                                <tr>
                                    <td class="usk-product-remove" data-title="<?php esc_html_e('Remove', 'ultimate-store-kit-pro'); ?>">
                                        <a href="<?php echo esc_url(wc_get_cart_remove_url($item)); ?>" class="remove" aria-label="Remove this item" data-product_id="<?php echo $values['product_id']; ?>" data-product_sku="<?php echo $product_names['sku']; ?>">
                                            ×
                                        </a>
                                    </td>
                                    <?php if ($settings['show_image'] == 'yes') : ?>
                                        <td class="usk-product-image" data-title="<?php esc_html_e('Image', 'ultimate-store-kit-pro'); ?>">
                                            <?php
                                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $values, $item);

                                            if (!$product_permalink) {
                                                echo $thumbnail; // PHPCS: XSS ok.
                                            } else {
                                                printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                            }
                                            ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ($settings['show_title'] == 'yes') : ?>
                                        <td class="usk-product-title" data-title="<?php esc_html_e('Product Title', 'ultimate-store-kit-pro'); ?>">
                                            <?php
                                            // echo $_product->get_title();
                                            if (!$product_permalink) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $values, $item) . '&nbsp;');
                                            } else {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $values, $item));
                                            }

                                            do_action('woocommerce_after_cart_item_name', $values, $item);

                                            // Meta data.
                                            echo wc_get_formatted_cart_item_data($values); // PHPCS: XSS ok.

                                            // Backorder notification.
                                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($values['quantity'])) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $_product));
                                            }
                                            ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ($settings['show_price'] == 'yes') : ?>
                                        <td class="usk-product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
                                            <?php
                                            echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $values, $item); // PHPCS: XSS ok.
                                            ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ($settings['show_quantity'] == 'yes') : ?>
                                        <td class="usk-product-quantity" data-title="<?php esc_html_e('Quantity', 'ultimate-store-kit-pro'); ?>">
                                            <?php

                                            if ($_product->is_sold_individually()) {
                                                $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $item);
                                            } else {
                                                $product_quantity = woocommerce_quantity_input(
                                                    array(
                                                        'input_name'   => "cart[{$item}][qty]",
                                                        'input_value'  => $values['quantity'],
                                                        'max_value'    => $_product->get_max_purchase_quantity(),
                                                        'min_value'    => '0',
                                                        'product_name' => $_product->get_name(),
                                                    ),
                                                    $_product,
                                                    false
                                                );
                                            }

                                            echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $item, $values); // PHPCS: XSS ok.

                                            ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ($settings['show_subtotal'] == 'yes') : ?>
                                        <td class="usk-product-subtotal" data-title="<?php esc_html_e('Subtotal', 'ultimate-store-kit-pro'); ?>">
                                            <?php
                                            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $values['quantity']), $values, $item); // PHPCS: XSS ok.
                                            ?>
                                        </td>
                                    <?php endif; ?>

                                </tr>

                            <?php
                            }

                            ?>
                            <tr>
                                <td colspan="6" class="actions">
                                    <?php if (wc_coupons_enabled()) { ?>
                                        <div class="coupon">
                                            <label for="coupon_code"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label>
                                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" />
                                            <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_attr_e('Apply coupon', 'woocommerce'); ?></button>
                                            <?php do_action('woocommerce_cart_coupon'); ?>
                                        </div>
                                    <?php } ?>

                                    <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?>

                                    </button>

                                    <?php do_action('woocommerce_cart_actions'); ?>

                                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </form>

                <div class="cart-collaterals">
                    <?php
                    /**
                     * Cart collaterals hook.
                     *
                     * @hooked woocommerce_cross_sell_display
                     * @hooked woocommerce_cart_totals - 10
                     */
                    do_action('woocommerce_cart_collaterals');
                    ?>
                </div>

            </div>
        </div>

<?php }
}
