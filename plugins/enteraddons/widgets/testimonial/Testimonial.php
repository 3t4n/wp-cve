<?php
namespace Enteraddons\Widgets\Testimonial;

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
 * Enteraddons elementor testimonial widget.
 *
 * @since 1.0
 */
class Testimonial extends Widget_Base {

	public function get_name() {
		return 'enteraddons-testimonial';
	}

	public function get_title() {
		return esc_html__( 'Testimonial Carousel', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-testimonial';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Testimonial content ------------------------------
        $this->start_controls_section(
            'enteraddons_testimonial_content',
            [
                'label' => esc_html__( 'Testimonial Content', 'enteraddons' ),
            ]
        );

        $repeater->add_control(
            'testimonial_name', [
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
            'testimonial_designation', [
                'label' => esc_html__( 'Designation', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'CEO, Octen, Melborne,' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'testimonial_location', [
                'label' => esc_html__( 'Location', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Auenteralia.' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'testimonial_rating', [
                'label' => esc_html__( 'Rating', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__( '1', 'enteraddons' ),
                    '2' => esc_html__( '2', 'enteraddons' ),
                    '3' => esc_html__( '3', 'enteraddons' ),
                    '4' => esc_html__( '4', 'enteraddons' ),
                    '5' => esc_html__( '5', 'enteraddons' ),
                    'none' => esc_html__( 'None', 'enteraddons' )
                ],
                'default' => 5
            ]
        );
        $repeater->add_control(
            'testimonial_desc', [
                'label' => esc_html__( 'Review Comment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'It is a long established fact that a reader will be dienteracted by the readable content of a page' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'testimonial_img', [
                'label' => esc_html__( 'Picture', 'enteraddons' ),
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
            'testimonial',
            [
                'label' => esc_html__( 'Testimonial', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ testimonial_name }}}',
                'default' => [
                    [
                        'testimonial_name'          => esc_html__( 'MARY P. JOHNSON', 'enteraddons' ),
                        'testimonial_designation'   => esc_html__( 'CEO, Octen, Melborne,', 'enteraddons' ),
                        'testimonial_location'      => esc_html__( 'Auenteralia.', 'enteraddons' ),
                        'testimonial_rating'        => esc_html__( '5', 'enteraddons' ),
                        'testimonial_desc'          => esc_html__( 'It is a long established fact that a reader will be dienteracted by the readable content of a page when looking at its layout.', 'enteraddons' ),
                        'testimonial_img'           => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    [
                        'testimonial_name'          => esc_html__( 'Diane M. Tackett', 'enteraddons' ),
                        'testimonial_designation'   => esc_html__( 'CEO, Octen, Melborne,', 'enteraddons' ),
                        'testimonial_location'      => esc_html__( 'Auenteralia.', 'enteraddons' ),
                        'testimonial_rating'        => esc_html__( '4', 'enteraddons' ),
                        'testimonial_desc'          => esc_html__( 'It is a long established fact that a reader will be dienteracted by the readable content of a page when looking at its layout.', 'enteraddons' ),
                        'testimonial_img'           => \Elementor\Utils::get_placeholder_image_src(),
                    ]
                    
                ]
            ]
        );

        $this->end_controls_section(); // End Testimonial content

        // ----------------------------------------  Testimonial content ------------------------------
        $this->start_controls_section(
            'enteraddons_testimonial_slider_settings',
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
                'default' => 1
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
                'default' => 15
            ]
        );

        $this->end_controls_section(); // End Testimonial content

        /**
         * Style Tab
         * ------------------------------ Testimonial Slider Content area Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_content_wrapper_settings', [
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
                    '{{WRAPPER}} .enteraddons-testimonial-slider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .enteraddons-testimonial-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .enteraddons-testimonial-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-testimonial-slider',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-testimonial-slider',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Slider layout Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_layout_settings', [
                'label' => esc_html__( 'Item Layout Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'testimonial_temp_layout', [
                    'label' => esc_html__( 'Select Layout', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '1' => esc_html__( 'Layout Style 1', 'enteraddons' ),
                        '2' => esc_html__( 'Layout Style 2', 'enteraddons' )
                    ],
                    'default' => 1
                ]
            );
            $this->add_control(
                'thumbnail_alignment',
                [
                    'label' => esc_html__( 'Thumbnail Position', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'top' => [
                            'title' => esc_html__( 'Top', 'enteraddons' ),
                            'icon' => 'fas fa-angle-up',
                        ],
                        'bottom' => [
                            'title' => esc_html__( 'Bottom', 'enteraddons' ),
                            'icon' => 'fas fa-angle-down',
                        ]
                    ],
                    'default' => 'top',
                    'toggle' => true
                ]
            );

            $this->add_control(
                'author_info_alignment',
                [
                    'label' => esc_html__( 'Author Info Position', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'top' => [
                            'title' => esc_html__( 'Top', 'enteraddons' ),
                            'icon' => 'fas fa-angle-up',
                        ],
                        'bottom' => [
                            'title' => esc_html__( 'Bottom', 'enteraddons' ),
                            'icon' => 'fas fa-angle-down',
                        ]
                    ],
                    'default' => 'top',
                    'toggle' => true
                ]
            );

            $this->add_control(
                'rating_alignment',
                [
                    'label' => esc_html__( 'Rating Position', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'top' => [
                            'title' => esc_html__( 'Top', 'enteraddons' ),
                            'icon' => 'fas fa-angle-up',
                        ],
                        'bottom' => [
                            'title' => esc_html__( 'Bottom', 'enteraddons' ),
                            'icon' => 'fas fa-angle-down',
                        ]
                    ],
                    'default' => 'top',
                    'toggle' => true
                ]
            );

            $this->add_responsive_control(
                'layout_content_text_alignment',
                [
                    'label' => esc_html__( 'Alignment', 'enteraddons' ),
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
                        '{{WRAPPER}} .enteraddons-single-testimonial' => 'text-align: {{VALUE}} !important',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'layout_area_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-testimonial' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'layout_area_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-testimonial' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'single_testimonial_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-testimonial',
                ]
            );
            $this->add_responsive_control(
                'layout_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-testimonial' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'layout_area_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-testimonial',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'layout_area_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-single-testimonial',
                ]
            );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Slider Name Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_name_settings', [
                'label' => esc_html__( 'Name', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
             
        $this->add_control(
            'name_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .author-name' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-testimonial .author-name',
            ]
        );
        $this->add_responsive_control(
            'name_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .author-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'name_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .author-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Designation Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_designation_settings', [
                'label' => esc_html__( 'Designation', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
           
        $this->add_control(
            'designation_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .author-designation' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'designation_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-testimonial .author-designation',
            ]
        );
        $this->add_responsive_control(
            'designation_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .author-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'designation_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .author-designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        ); 


        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Location Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_location_settings', [
                'label' => esc_html__( 'Location', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
             
        $this->add_control(
            'location_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .author-location' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'location_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-testimonial .author-location',
            ]
        );
        $this->add_responsive_control(
            'location_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .author-location' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'location_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .author-location' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Descriptions Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_descriptions_settings', [
                'label' => esc_html__( 'Descriptions', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'descriptions_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .enteraddons-testimonial-text p' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'descriptions_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-testimonial .enteraddons-testimonial-text p',
            ]
        );
        $this->add_responsive_control(
            'descriptions_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .enteraddons-testimonial-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'descriptions_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-testimonial .enteraddons-testimonial-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'descriptions_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-testimonial .enteraddons-testimonial-text',
                ]
            );
            $this->add_responsive_control(
                'descriptions_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-testimonial .enteraddons-testimonial-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'descriptions_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-testimonial .enteraddons-testimonial-text',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'descriptions_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-single-testimonial .enteraddons-testimonial-text',
                ]
            );

        $this->end_controls_section();


        /**
         * Style Tab
         * ------------------------------ Testimonial Rating Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_rating_settings', [
                'label' => esc_html__( 'Rating', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'rating_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-rating i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'rating_margin',
            [
                'label' => esc_html__( 'Icon Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-rating i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'rating_block_margin',
            [
                'label' => esc_html__( 'Icon Block Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'rating_block_padding',
            [
                'label' => esc_html__( 'Icon Block Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Quote Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_quote_settings', [
                'label' => esc_html__( 'Quote', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'left_quote_icon',
            [
                'label' => esc_html__( 'Left Quote Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-quote-left',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'right_quote_icon',
            [
                'label' => esc_html__( 'Right Quote Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-quote-right',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_icon_size',
            [
                'label' => esc_html__( 'Quote Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '22',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-text i' => 'font-size: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_control(
            'quote_color',
            [
                'label' => esc_html__( 'Quote Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-text i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'quote_bg_color',
            [
                'label' => esc_html__( 'Quote Background Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-text i' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'quote_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-testimonial-text i',
            ]
        );
        $this->add_responsive_control(
            'quote_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-text i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_left_space',
            [
                'label' => esc_html__( 'Quote Left Space', 'enteraddons' ),
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
                    'size' => '15',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-text .testimonial-left-quote' => 'margin-right: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_right_space',
            [
                'label' => esc_html__( 'Quote Right Space', 'enteraddons' ),
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
                    'size' => '15',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-text .testimonial-right-quote' => 'margin-left: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-text i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'quote_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-text i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Iamge Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_img_settings', [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'img_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-thumb img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_height',
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
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-thumb img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-thumb img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-thumb img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'img_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-testimonial-thumb img',
            ]
        );
        $this->add_responsive_control(
            'img_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'img_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-testimonial-thumb img',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Nav Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_nav_settings', [
                'label' => esc_html__( 'Nav Setings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'navs_alignment',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-testimonial-slider.slider-style--default .owl-nav' => 'text-align: {{VALUE}} !important',
                ],
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
            'nav_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
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
                    'size' => '15',
                ],
                'selectors' => [
                    '{{WRAPPER}} .owl-carousel .owl-nav button i' => 'font-size: {{SIZE}}{{UNIT}};',
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

        $this->start_controls_tabs( 'tab_link_style' );

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
         * ------------------------------ Testimonial dot Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_testimonial_dot_settings', [
                'label' => esc_html__( 'Dot Setings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'dot_alignment',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-testimonial-slider.slider-style--default .owl-dots' => 'justify-content: {{VALUE}} !important',
                ],
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
                    'size' => 12,
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
                    'size' => 12,
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

        $this->start_controls_tabs( 'tab_dot_style' );

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
        $obj = new Testimonial_Carousel_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
    }

    public function get_script_depends() {
        return [ 'enteraddons-main', 'owl-carousel'];
    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style', 'owl-carousel', 'fontawesome' ];
    }


}
