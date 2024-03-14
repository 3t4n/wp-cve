<?php
namespace Enteraddons\Widgets\Photo_Hanger;

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
 * Enteraddons elementor Animated Photo Frame widget.
 *
 * @since 1.0
 */
class Photo_Hanger extends Widget_Base {

	public function get_name() {
		return 'enteraddons-photo-hanger';
	}

	public function get_title() {
		return esc_html__( 'Photo Hanger', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-photo-hanger';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {
        
        // ----------------------------------------  Photo Frame content ------------------------------
        $this->start_controls_section(
            'enteraddons_photo_frame_settings',
            [
                'label' => esc_html__( 'Photo Frame Content', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Title', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'image',
            [
                'label' => esc_html__( 'Choose Image', 'enteraddons' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section(); 

        // ----------------------------------------  Photo Frame content ------------------------------
        $this->start_controls_section(
            'wrapper_style',
            [
                'label' => esc_html__( 'Wrapper Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wrapper_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => Controls_Manager::CHOOSE,
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
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame-wrapper' => 'justify-content: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_wrap_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_wrap_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'item_wrap_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-photo-frame-wrapper',
            ]
        );
        $this->add_responsive_control(
            'item_wrap_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_wrap_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-photo-frame-wrapper',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_wrap_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-photo-frame-wrapper',
            ]
        );
        $this->end_controls_section();
        //
        $this->start_controls_section(
            'animated_photo_frame_style',
            [
                'label' => esc_html__( 'Photo Frame Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'pause_on_hover',
            [
                'label' => esc_html__( 'Animation Play State', 'enteraddons' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Select', 'enteraddons' ),
                    'paused'  => esc_html__( 'Paused', 'enteraddons' ),
                    'running' => esc_html__( 'Running', 'enteraddons' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:hover' => 'animation-play-state: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'image_cursor',
            [
                'label' => esc_html__( 'Image Hover Cursor', 'enteraddons' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => [
                    '' => esc_html__( 'Select', 'enteraddons' ),
                    'pointer'  => esc_html__( 'Pointer', 'enteraddons' ),
                    'not-allowed' => esc_html__( 'Not-Allowed', 'enteraddons' ),
                    'grab' => esc_html__( 'Grab', 'enteraddons' ),
                    'none' => esc_html__( 'None', 'enteraddons' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:hover' => 'cursor: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'image_rotate',
            [
                'label' => esc_html__( 'Image Rotate', 'enteraddons' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => [
                    '' => esc_html__( 'Select', 'enteraddons' ),
                    'ea-photo-frame'  => esc_html__( 'Rotate One', 'enteraddons' ),
                    'ea-photo-frame2'  => esc_html__( 'Rotate Two', 'enteraddons' ),
                    'ea-photo-frame3'  => esc_html__( 'Rotate Three', 'enteraddons' ),
                    'ea-photo-frame4'  => esc_html__( 'Rotate Four', 'enteraddons' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame' => 'animation: {{VALUE}} ease-in-out 0.5s infinite alternate',
                ],
            ]
        );
        $this->add_control(
            'rotate_speed',
            [
                'label' => esc_html__( 'Rotate Speed', 'enteraddons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 3,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame' => 'animation-duration: {{VALUE}}s;',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_width',
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
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
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
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-photo-frame img',
            ]
        );
        $this->add_responsive_control(
            'image_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        
        $this->start_controls_section(
            'photo_frame_title_style',
            [
                'label' => esc_html__( 'Title Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} span',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => Controls_Manager::CHOOSE,
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
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame span' => 'text-align: {{VALUE}}!important',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} span',
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'hanger_photo_style',
            [
                'label' => esc_html__( 'Hanger Pin Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'hanger_pin_tabs' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'hanger_pin_settings',
            [
                'label' => esc_html__( 'Settings', 'enteraddons' ),
            ]
        );
        $this->add_responsive_control(
            'hanger_pin_width',
            [
                'label' => esc_html__( 'Icon Container Width', 'enteraddons' ),
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hanger_pin_height',
            [
                'label' => esc_html__( 'Icon Container Height', 'enteraddons' ),
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hanger_pin_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hanger_pin_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'hanger_pin_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-photo-frame:before',
            ]
        );
        $this->add_responsive_control(
            'hanger_pin_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hanger_pin_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-photo-frame:before',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hanger_pin_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-photo-frame:before',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For position
        $this->start_controls_tab(
            'hanger_pin_position',
            [
                'label' => esc_html__( 'Position', 'enteraddons' ),
            ]
        );
        $this->add_responsive_control(
            'hanger_position_top',
            [
                'label' => esc_html__( 'Top', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:before' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hanger_position_left',
            [
                'label' => esc_html__( 'Left', 'enteraddons' ),
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
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:before' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

        $this->start_controls_section(
            'hanger_hook_style',
            [
                'label' => esc_html__( 'Hanger Hook Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'hook_position_top',
            [
                'label' => esc_html__( 'Top', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:after' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hook_position_left',
            [
                'label' => esc_html__( 'Left', 'enteraddons' ),
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
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:after' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hook_rotate',
            [
                'label' => esc_html__( 'Rotate (deg)', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 35,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:after' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_responsive_control(
            'hook_width',
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
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hook_height',
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
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'hook_border_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-frame:after' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_section();
	}

	protected function render() {

        $settings = $this->get_settings_for_display();

        // Photo Frame template render
        $obj = new \Enteraddons\Widgets\Photo_Hanger\Photo_Hanger_Templates();
        //
        $obj::setDisplaySettings( $settings );
        //
        $obj::renderTemplate();
    }

    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }
    
}

