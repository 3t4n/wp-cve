<?php
namespace Enteraddons\Widgets\Logo_Carousel;

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

class Logo_Carousel extends Widget_Base {

	public function get_name() {
		return 'enteraddons-logo-carousel';
	}

	public function get_title() {
		return esc_html__( 'Logo Carousel', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-logo-carousel';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Logo Carousel content ------------------------------
        $this->start_controls_section(
            'enteraddons_logo_carousel_content',
            [
                'label' => esc_html__( 'Logo Carousel Content', 'enteraddons' ),
            ]
        );

        $repeater->add_control(
            'name', [
                'label' => esc_html__( 'Name', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'MARY P. JOHNSON' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );
        $repeater->add_control(
            'img', [
                'label' => esc_html__( 'Logo', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'logo_carousel',
            [
                'label' => esc_html__( 'Add Logo Carousel', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default' => [
                    [
                        'name'   => esc_html__( 'MARY P. JOHNSON', 'enteraddons' ),
                        'link'   => '#',
                        'img'    => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    [
                        'name'      => esc_html__( 'Diane M. Tackett', 'enteraddons' ),
                        'link'   => "#",
                        'img'    => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    [
                        'name'   => esc_html__( 'MARY P. JOHNSON', 'enteraddons' ),
                        'link'   => '#',
                        'img'    => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    [
                        'name'      => esc_html__( 'Diane M. Tackett', 'enteraddons' ),
                        'link'   => "#",
                        'img'    => \Elementor\Utils::get_placeholder_image_src(),
                    ]
                    
                ]
            ]
        );

        $this->end_controls_section(); // End logo carousel content

        // ----------------------------------------  logo carousel content ------------------------------
        $this->start_controls_section(
            'enteraddons_logo_carousel_slider_settings',
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
                'default' => 'no',
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
                'default' => 'no',
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
                'default' => 'no',
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
                'default' => 'no',
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
                'default' => '',
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
                'default' => 'no',
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
        $this->add_responsive_control(
            'slider_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 10
            ]
        );

        $this->end_controls_section(); // End Logo Carousel content

        /**
         * Style Tab
         * ------------------------------ Logo Carousel Slider Content area Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_logo_carousel_content_wrapper_settings', [
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
                    '{{WRAPPER}} .enteraddons-client-slider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .enteraddons-client-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .enteraddons-client-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-client-slider',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-client-slider',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Logo Carousel Slider layout Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_logo_carousel_wrapper_settings', [
                'label' => esc_html__( 'Logo Item Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'logo_img_wrapper_width',
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
                    
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-client' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'logo_img_wrapper_height',
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
                    
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-client' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'img_horizontal_alignment',
                [
                    'label' => esc_html__( 'Horizontal Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__( 'Left', 'enteraddons' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'enteraddons' ),
                            'icon' => 'eicon-h-align-center',
                        ],
                        'flex-end' => [
                            'title' => esc_html__( 'Right', 'enteraddons' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-client-slider .enteraddons-single-client' => 'justify-content: {{VALUE}} !important',
                    ],
                ]
            );
            $this->add_responsive_control(
                'img_vertical_alignment',
                [
                    'label' => esc_html__( 'Vertical Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__( 'Top', 'enteraddons' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'enteraddons' ),
                            'icon' => 'eicon-v-align-middle',
                        ],
                        'flex-end' => [
                            'title' => esc_html__( 'Bottom', 'enteraddons' ),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-client-slider .enteraddons-single-client' => 'align-items: {{VALUE}} !important',
                    ],
                ]
            );
            $this->add_responsive_control(
                'logo_wrapper_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-client' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'logo_wrapper_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-client' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'logo_wrapper_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-client',
                ]
            );
            $this->add_responsive_control(
                'logo_wrapper_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-client' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'logo_wrapper_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-client',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'layout_area_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-single-client',
                ]
            );

        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Logo Carousel Slider layout Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_logo_carousel_logo_settings', [
                'label' => esc_html__( 'Logo Item Image Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'logo_hover_style',
                [
                    'label' => esc_html__( 'Hover Style', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '0' => esc_html__(  'Hover Style None', 'enteraddons' ),
                        '1' => esc_html__(  'Hover Style 1', 'enteraddons' ),
                        '2'  => esc_html__( 'Hover Style 2', 'enteraddons' ),
                        '3'  => esc_html__( 'Hover Style 3', 'enteraddons' )
                    ],
                ]
            );
            $this->add_control(
                'logo_image_width_type',
                [
                    'label' => esc_html__( 'Width Type', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'max-width',
                    'options' => [
                        'full-width' => esc_html__(  'Width 100%', 'enteraddons' ),
                        'max-width'  => esc_html__( 'Max Width 100%', 'enteraddons' ),
                        'custom-width'  => esc_html__( 'Custom', 'enteraddons' )
                    ],
                ]
            );
            $this->add_responsive_control(
                'logo_img_width',
                [
                    'label' => esc_html__( 'Image Width', 'enteraddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => ['logo_image_width_type' => 'custom-width'],
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
                        '{{WRAPPER}} .enteraddons-single-client img' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'logo_img_max_width',
                [
                    'label' => esc_html__( 'Image Max Width', 'enteraddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => ['logo_image_width_type' => 'custom-width'],
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
                        '{{WRAPPER}} .enteraddons-single-client img' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'logo_img_height',
                [
                    'label' => esc_html__( 'Image Height', 'enteraddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => ['logo_image_width_type' => 'custom-width'],
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
                        '{{WRAPPER}} .enteraddons-single-client img' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'logo_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-client img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'logo_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-client img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'logo_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-client img',
                ]
            );
            $this->add_responsive_control(
                'logo_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-client img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'logo_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-client img',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'logo_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-single-client img',
                ]
            );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Logo Carousel Nav Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_logo_carousel_nav_settings', [
                'label' => esc_html__( 'Carousel Nav Setings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .enteraddons-client-slider .owl-nav button' => 'color: {{VALUE}} !important',
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
                'selector' => '{{WRAPPER}} .enteraddons-client-slider .owl-nav button',
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
                    '{{WRAPPER}} .enteraddons-client-slider .owl-nav button:hover' => 'color: {{VALUE}} !important',
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
                'selector' => '{{WRAPPER}} .enteraddons-client-slider .owl-nav button:hover',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section


        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ logo carousel dot Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_logo_carousel_dot_settings', [
                'label' => esc_html__( 'Carousel Dot Setings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
        // Testimonial template render
        $obj = new Logo_Carousel_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
        
    }
	
    public function get_script_depends() {
        return [ 'enteraddons-main', 'owl-carousel' ];
    }
    public function get_style_depends() {
        return [ 'enteraddons-global-style', 'owl-carousel' ];
    }

}
