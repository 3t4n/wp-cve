<?php
namespace Enteraddons\Widgets\Product_Carousel;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *
 * Enteraddons elementor Logo Carousel widget.
 *
 * @since 1.0
 */

class Product_Carousel extends Widget_Base {

	public function get_name() {
		return 'enteraddons-product-carousel';
	}

	public function get_title() {
		return esc_html__( 'Product Carousel', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-product-carousel';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Product Carousel content ------------------------------
        $this->start_controls_section(
            'enteraddons_product_carousel_content',
            [
                'label' => esc_html__( 'Product Carousel Content', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'UPCOMING COLLECTIONS'
            ]
        );
        $this->add_control(
            'product_limit',
            [
                'label' => esc_html__( 'Product Limit', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 10
            ]
        );
        $this->add_control(
            'product_type',
            [
                'label' => esc_html__( 'Product Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'recent_product',
                'options' => [
                    'recent_product' => esc_html__( 'Recent Product', 'enteraddons' ),
                    'featured_product' => esc_html__( 'Featured', 'enteraddons' ),
                    'on_sale' => esc_html__( 'On Sale', 'enteraddons' )
                ]
            ]
        );

        $this->end_controls_section(); // End content

        // ----------------------------------------  Product carousel content ------------------------------
        $this->start_controls_section(
            'enteraddons_product_carousel_slider_settings',
            [
                'label' => esc_html__( 'Slider Settings', 'enteraddons' ),
            ]
        );

        // Slider Settings
        $this->add_responsive_control(
            'slider_items',
            [
                'label' => esc_html__( 'Items', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 4
            ]
        );
        $this->add_control(
            'slider_autoplay',
            [
                'label'     => esc_html__( 'Autoplay', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'NO', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
       
        $this->add_control(
            'slider_mouseDrag',
            [
                'label'     => esc_html__( 'Mouse Drag', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'NO', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'slider_loop',
            [
                'label'     => esc_html__( 'Loop', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'NO', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'slider_center',
            [
                'label'     => esc_html__( 'Center', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'NO', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'slider_animateIn',
            [
                'label'     => esc_html__( 'Animate In', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'NO', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'slider_animateOut',
            [
                'label'     => esc_html__( 'Animate Out', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'NO', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'slider_nav',
            [
                'label'     => esc_html__( 'Nav', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'NO', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'slider_dots',
            [
                'label'     => esc_html__( 'Dots', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'NO', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'slider_autoWidth',
            [
                'label'     => esc_html__( 'Auto Width', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'NO', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        $this->add_control(
            'slider_autoplayTimeout',
            [
                'label' => esc_html__( 'Autoplay Timeout', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 8000
            ]
        );
        $this->add_control(
            'slider_smartSpeed',
            [
                'label' => esc_html__( 'Smart Speed', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 450
            ]
        );
        $this->add_control(
            'slider_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 30
            ]
        );

        $this->end_controls_section(); // End product Carousel content

        /**
         * Style Tab
         * ------------------------------ Product Carousel Slider Content area Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_product_carousel_content_wrapper_settings', [
                'label' => esc_html__( 'Content Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .entera-product-carousel-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .entera-product-carousel-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .entera-product-carousel-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .entera-product-carousel-wrap',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .entera-product-carousel-wrap',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Product Carousel Title Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_product_carousel_section_title_wrapper_settings', [
                'label' => esc_html__( 'Section Title Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'section_title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-carousel-before-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'section_title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .product-carousel-before-title',
            ]
        );
        $this->add_responsive_control(
            'section_title_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .product-carousel-before-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'section_title_border',
                'label'     => esc_html__( 'Title Wrapper Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .product-carousel-before-section-title-wrap',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'section_title_bg_color',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .product-carousel-before-title',
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Item Wrapper Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_product_carousel_item_wrapper_settings', [
                'label' => esc_html__( 'Item Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            // start controls tabs

            $this->start_controls_tabs( 'item_wrap_tabs' );

            //  Controls tab For Normal
            $this->start_controls_tab(
                'item_wrap_normal',
                [
                    'label' => esc_html__( 'Normal', 'enteraddons' ),
                ]
            );

            $this->add_responsive_control(
                'item_wrapper_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-carousel-single-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'item_wrapper_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-carousel-single-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'item_wrapper_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-carousel-single-item',
                ]
            );
            $this->add_responsive_control(
                'item_wrapper_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-carousel-single-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_wrapper_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-carousel-single-item',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'item_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-product-carousel-single-item',
                ]
            );

            $this->end_controls_tab(); // End Controls tab

            //  Controls tab For Hover
            $this->start_controls_tab(
                'item_wrap_hover',
                [
                    'label' => esc_html__( 'Hover', 'enteraddons' ),
                ]
            );
            $this->add_responsive_control(
                'item_wrapper_hover_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-carousel-single-item:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'item_wrapper_hover_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-carousel-single-item:hover',
                ]
            );
            $this->add_responsive_control(
                'item_wrapper_hover_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-carousel-single-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_wrapper_hover__shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-carousel-single-item:hover',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'item_hover_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-product-carousel-single-item:hover',
                ]
            );
            $this->end_controls_tab(); // End Controls tab

            $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------  Item Image Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_product_carousel_item_image_settings', [
                'label' => esc_html__( 'Item Image Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'item_image_width',
                [
                    'label' => esc_html__( 'Width', 'enteraddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => '100',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-shop-img img' => 'width: {{SIZE}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_responsive_control(
                'item_image_max_width',
                [
                    'label' => esc_html__( 'Max Width', 'enteraddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => '100',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-shop-img img' => 'max-width: {{SIZE}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_responsive_control(
                'item_img_alignment',
                [
                    'label' => esc_html__( 'Content Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'enteraddons' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'enteraddons' ),
                            'icon' => 'eicon-h-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'enteraddons' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-shop-img' => 'display: flex;justify-content: {{VALUE}} !important',
                    ],
                ]
            );

            // start controls tabs
            $this->start_controls_tabs( 'item_img_tabs' );
            //  Controls tab For Normal
            $this->start_controls_tab(
                'item_img_normal',
                [
                    'label' => esc_html__( 'Normal', 'enteraddons' ),
                ]
            );

            $this->add_responsive_control(
                'item_img_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-carousel-single-item img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'item_img_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-carousel-single-item img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'item_img_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-carousel-single-item img',
                ]
            );
            $this->add_responsive_control(
                'item_img_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-carousel-single-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_img_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-carousel-single-item img',
                ]
            );
            $this->end_controls_tab(); // End Controls tab

            //  Controls tab For Hover
            $this->start_controls_tab(
                'item_img_hover',
                [
                    'label' => esc_html__( 'Hover', 'enteraddons' ),
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'item_img_hover_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-carousel-single-item:hover img',
                ]
            );
            $this->add_responsive_control(
                'item_img_hover_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-carousel-single-item:hover img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_img_hover_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-carousel-single-item:hover img',
                ]
            );
            $this->end_controls_tab(); // End Controls tab

            $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------  Item Content Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_product_carousel_item_content_settings', [
                'label' => esc_html__( 'Item Content Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'item_content_alignment',
            [
                'label' => esc_html__( 'Content Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-shop-content' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_content_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-shop-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_content_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-shop-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_content_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-shop-content',
            ]
        );
        $this->add_responsive_control(
            'item_content_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-shop-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_content_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-shop-content',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_content_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-shop-content',
            ]
        );
        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------  Item Title and Category Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_product_carousel_item_title_cat_settings', [
                'label' => esc_html__( 'Item Title And Category Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            // start controls tabs
            $this->start_controls_tabs( 'item_title_cat_tabs' );
            //  Controls tab For Normal
            $this->start_controls_tab(
                'item_title_cat_normal',
                [
                    'label' => esc_html__( 'Normal', 'enteraddons' ),
                ]
            );
            $this->add_control(
            'title_settings',
            [
                'label' => esc_html__( 'Title Settings', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
            );
            $this->add_control(
            'title_color',
                [
                    'label' => esc_html__( 'Title Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-title a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => esc_html__( 'Typography', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-title a',
                ]
            );
            $this->add_responsive_control(
                'item_title_margin',
                [
                    'label' => esc_html__( 'Title Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'item_title_padding',
                [
                    'label' => esc_html__( 'Title Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'item_title_border',
                    'label' => esc_html__( 'Title Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-title',
                ]
            );
            // Category Settings
            $this->add_control(
            'cat_settings',
            [
                'label' => esc_html__( 'Category Settings', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
            );

            $this->add_control(
            'cat_color',
                [
                    'label' => esc_html__( 'Category Text Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-tag a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'cat_typography',
                    'label' => esc_html__( 'Typography', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-tag a',
                ]
            );
            $this->add_responsive_control(
                'cat_margin',
                [
                    'label' => esc_html__( 'Category Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-tag' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->end_controls_tab(); // End Controls tab

            //  Controls tab For Hover
            $this->start_controls_tab(
                'item_title_cat_hover',
                [
                    'label' => esc_html__( 'Hover', 'enteraddons' ),
                ]
            );
            
            $this->add_control(
            'title_hover_settings',
            [
                'label' => esc_html__( 'Title Hover Settings', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
            );
            $this->add_control(
            'title_hover_color',
                [
                    'label' => esc_html__( 'Title Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-title a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );
            // Category Settings
            $this->add_control(
            'cat_hover_settings',
            [
                'label' => esc_html__( 'Category Hover Settings', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
            );

            $this->add_control(
            'cat_hover_color',
                [
                    'label' => esc_html__( 'Category Hover Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-tag a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->end_controls_tab(); // End Controls tab

            $this->end_controls_tabs(); //  end controls tabs section
            
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------  Item Price and Rating Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_product_carousel_item_price_rating_settings', [
                'label' => esc_html__( 'Item Price and Rating Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'item_price_rating_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .product-carousel-item-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'item_price_rating_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .sproduct-carousel-item-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'item_price_rating_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .product-carousel-item-meta',
                ]
            );

            // start controls tabs
            $this->start_controls_tabs( 'item_price_rating_tabs' );
            //  Controls tab For Price
            $this->start_controls_tab(
                'item_price_normal',
                [
                    'label' => esc_html__( 'Price', 'enteraddons' ),
                ]
            );
            $this->add_control(
            'price_color',
                [
                    'label' => esc_html__( 'Price Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .price' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'price_typography',
                    'label' => esc_html__( 'Typography', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .price',
                ]
            );
            $this->add_control(
            'price_del_color',
                [
                    'label' => esc_html__( 'Del Price Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-product-carousel-single-item .price del .amount' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'price_del_typography',
                    'label' => esc_html__( 'Typography', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-product-carousel-single-item .price del .amount',
                ]
            );
            
            $this->end_controls_tab(); // End Controls tab

            //  Controls tab For Hover
            $this->start_controls_tab(
                'item_ratings',
                [
                    'label' => esc_html__( 'Rating', 'enteraddons' ),
                ]
            );
            
            $this->add_control(
            'rating_color',
                [
                    'label' => esc_html__( 'Rating Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .star-rating i' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Size', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .star-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
            );
            $this->add_responsive_control(
                'icon_rating_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .star-rating i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->end_controls_tab(); // End Controls tab

            $this->end_controls_tabs(); //  end controls tabs section
            
        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------  Item Content Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_product_carousel_item_cart_button_settings', [
                'label' => esc_html__( 'Item Cart Button Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // start controls tabs
        $this->start_controls_tabs( 'item_cart_btn_tabs' );
        //  Controls tab For
        $this->start_controls_tab(
            'item_cart_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );

        $this->add_responsive_control(
            'item_cart_btn_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-product-action a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_cart_btn_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-product-action a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_cart_btn_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-product-action a',
            ]
        );
        $this->add_responsive_control(
            'item_cart_btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-product-action a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_control(
            'item_cart_btn_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-product-action a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_cart_btn_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-product-action a',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'item_cart_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'item_cart_btn_hover_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-product-action a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_cart_btn_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-product-action a:hover',
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Carousel Nav Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_carousel_nav_settings', [
                'label' => esc_html__( 'Carousel Nav Setings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'slider_nav' => 'yes' ]
            ]
        );
        $this->add_responsive_control(
            'nav_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-nav button' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-nav button' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-nav button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-nav button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'nav_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button',
            ]
        );
        $this->add_responsive_control(
            'nav_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        // start controls tabs

        $this->start_controls_tabs( 'nav_normal_style' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'nav_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'nav_color',
            [
                'label' => esc_html__( 'Nav Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-nav button' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'nav_bg_color',
                'label' => esc_html__( 'Nav Background', 'enteraddons' ),
                'show_label' => false,
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'nav_hover_normal',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'nav_hover_color',
            [
                'label' => esc_html__( 'Nav Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-nav button:hover' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'nav_hover_bg_color',
                'label' => esc_html__( 'Nav Hover Background', 'enteraddons' ),
                'show_label' => true,
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button:hover',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section


        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ carousel dot Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_carousel_dot_settings', [
                'label' => esc_html__( 'Carousel Dot Setings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'slider_dots' => 'yes' ]
            ]
        );

        $this->add_responsive_control(
            'dot_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-dots button.owl-dot' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-dots button.owl-dot' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-dots button.owl-dot' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-dots button.owl-dot' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-dots button.owl-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        // start controls tabs

        $this->start_controls_tabs( 'logo_carousel_dot_style' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'dot_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'dot_point_color',
            [
                'label' => esc_html__( 'Dot Point Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-dots button.owl-dot span' => 'background-color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dot_normal_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .owl-carousel .owl-dots button.owl-dot',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'dot_bg_color',
                'label' => esc_html__( 'Dot Background', 'enteraddons' ),
                'show_label' => false,
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .owl-carousel .owl-dots button.owl-dot',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'dot_active',
            [
                'label' => esc_html__( 'Active', 'enteraddons' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dot_ative_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .owl-carousel .owl-dots button.owl-dot.active',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'dot_active_bg_color',
                'label' => esc_html__( 'Dot Active Background', 'enteraddons' ),
                'show_label' => true,
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .owl-carousel .owl-dots button.owl-dot.active',
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        
        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

	}

	protected function render() {
        // get settings
        $settings = $this->get_settings_for_display();
        // WooCommerce Product template render
        if( \Enteraddons\Classes\Helper::is_woo_activated() ) {
            $obj = new Product_Carousel_Template();
            $obj::setDisplaySettings( $settings );
            $obj->renderTemplate();
        } else {
            esc_html_e( 'Please install and activate the WooCommerce plugin.', 'enteraddons' );
        }
    }
	
    public function get_script_depends() {
        return [ 'enteraddons-main', 'owl-carousel' ];
    }
    public function get_style_depends() {
        return [ 'enteraddons-global-style', 'owl-carousel' ];
    }

}
