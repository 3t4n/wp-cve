<?php
namespace Enteraddons\Widgets\Vertical_Testimonial;

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
class Vertical_Testimonial extends Widget_Base {

	public function get_name() {
		return 'enteraddons-vertical-testimonial';
	}

	public function get_title() {
		return esc_html__( 'Vertical Testimonial', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-vertical-testimonial';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Testimonial content ------------------------------
        $this->start_controls_section(
            'enteraddons_vertical_testimonial_content',
            [
                'label' => esc_html__( 'Content', 'enteraddons' ),
            ]
        );
        $repeater->add_control(
            'show_review',
            [
                'label' => esc_html__( 'Show Review', 'enteraddons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $repeater->add_control(
            'ratings',
            [
                'label' => esc_html__( 'Ratings', 'enteraddons' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [ 'show_review' => 'yes' ],
                'label_block' => true,
                'default' => '4.5',
                'options' => [
                    '1'     => esc_html__( '1', 'enteraddons' ),
                    '1.5'   => esc_html__( '1.5', 'enteraddons' ),
                    '2'     => esc_html__( '2', 'enteraddons' ),
                    '2.5'   => esc_html__( '2.5', 'enteraddons' ),
                    '3'     => esc_html__( '3', 'enteraddons' ),
                    '3.5'   => esc_html__( '3.5', 'enteraddons' ),
                    '4'     => esc_html__( '4', 'enteraddons' ),
                    '4.5'   => esc_html__( '4.5', 'enteraddons' ),
                    '5'     => esc_html__( '5', 'enteraddons' )
                ],
            ]
        );
        $repeater->add_control(
            'client_name',
            [
                'label' => esc_html__( 'Name', 'enteraddons' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'John Doe', 'enteraddons' ),
                'placeholder' => esc_html__( 'Type your name here', 'enteraddons' ),
            ]
        );
        $repeater->add_control(
            'client_review',
            [
                'label' => esc_html__( 'Review', 'enteraddons' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'rows' => 10,
                'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'enteraddons' ),
                'placeholder' => esc_html__( 'Type your review here', 'enteraddons' ),
            ]
        );
        $repeater->add_control(
            'client_image',
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
        $this->add_control(
            'slider_list',
            [
                'label' => esc_html__( 'Slider List', 'enteraddons' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'ratings' => esc_html__( '4.5', 'enteraddons' ),
                        'client_name' => esc_html__( 'John Doe', 'enteraddons' ),
                        'client_review' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'enteraddons' ),
                        'client_image' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'ratings' => esc_html__( '4.5', 'enteraddons' ),
                        'client_name' => esc_html__( 'Jane Doe', 'enteraddons' ),
                        'client_review' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'enteraddons' ),
                        'client_image' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'ratings' => esc_html__( '4.5', 'enteraddons' ),
                        'client_name' => esc_html__( 'John Doe', 'enteraddons' ),
                        'client_review' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'enteraddons' ),
                        'client_image' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'ratings' => esc_html__( '4.5', 'enteraddons' ),
                        'client_name' => esc_html__( 'Jane Doe', 'enteraddons' ),
                        'client_review' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'enteraddons' ),
                        'client_image' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                    ]
                ],
                'title_field' => '{{{ client_name }}}',
            ]
        );

        $this->end_controls_section(); // End Testimonial content

        /**
         * Slider Control Settings Tab
         * ------------------------------ Testimonial Slider Control Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'slider_control_settings', 
            [
                'label' => esc_html__( 'Slider Control Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => esc_html__( 'Slides Item', 'enteraddons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->add_control(
            'auto_play',
            [
                'label' => esc_html__( 'Autoplay', 'enteraddons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'auto_play_speed',
            [
                'label' => esc_html__( 'Speed', 'enteraddons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 2000,
                'step' => 1,
                'default' => 500
            ]
        );
        $this->add_control(
            'clicked_slide',
            [
                'label' => esc_html__( 'Slide On Click', 'enteraddons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'mousewheel_control',
            [
                'label' => esc_html__( 'mouse Wheel Control', 'enteraddons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'loop',
            [
                'label' => esc_html__( 'Slide Loop', 'enteraddons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'centered_slides',
            [
                'label' => esc_html__( 'Center', 'enteraddons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'slide_nav',
            [
                'label' => esc_html__( 'Nav', 'enteraddons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );               
        $this->end_controls_section();
        /**
         * Slider Nav Control Settings Tab
         * ------------------------------ Slider Nav Control Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'slider_nav_control_settings', 
            [
                'label' => esc_html__( 'Slider Nav Icon', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [ 'slide_nav' => 'yes' ]
            ]
        );
        $this->add_control(
            'icon_up',
            [
                'label' => esc_html__( 'Up Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-up',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'icon_down',
            [
                'label' => esc_html__( 'Down Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-down',
                    'library' => 'solid',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Testimonial Slider Content area Settings ------------------------------
         *
         */

        // wrapper style
        $this->start_controls_section(
            'wrapper_style', [
                'label' => esc_html__( 'Wrapper', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'wrapper_width',
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
                    '{{WRAPPER}} .ea--feedback-slider-wrapper' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_height',
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
                    'size' => '800',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea--feedback-slider-wrapper' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea--feedback-slider-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea--feedback-slider-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea--feedback-slider-wrapper',
            ]
        );
        $this->add_responsive_control(
            'wrapper_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea--feedback-slider-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea--feedback-slider-wrapper',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea--feedback-slider-wrapper',
            ]
        );
        $this->end_controls_section();

        /**
         * Item Style
         * ------------------------------ Testimonial Slider Item Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'slider_item_style', [
                'label' => esc_html__( 'Slider Item Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'slider_item_margin',
            [
                'label' => esc_html__( 'Slider Item Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea--single-feedback-slider .single-feedback-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'slider_item_padding',
            [
                'label' => esc_html__( 'Slider Item Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea--single-feedback-slider .single-feedback-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tab_slider_item_wrapper' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_wrapper_normal',
            [
                'label' => esc_html__( 'General Item', 'enteraddons' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'slider_item_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea--single-feedback-slider .single-feedback-inner:after',
            ]
        );
        $this->add_responsive_control(
            'slider_item_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea--single-feedback-slider .single-feedback-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea--single-feedback-slider .single-feedback-inner:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'slider_item_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea--single-feedback-slider .single-feedback-inner',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'slider_item_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea--single-feedback-slider .single-feedback-inner:after',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'item_wrapper_active',
            [
                'label' => esc_html__( 'Active Item', 'enteraddons' ),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'active_slider_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea--single-feedback-slider.swiper-slide-active .single-feedback-inner',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'active_slider_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea--single-feedback-slider.swiper-slide-active .single-feedback-inner',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'active_slider_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea--single-feedback-slider.swiper-slide-active .single-feedback-inner:after',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        /****** name style **********/
        $this->start_controls_section(
            'client_name_style', [
                'label' => esc_html__( 'Client Name', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .customer-feedback .customer-name',
            ]
        );
        $this->add_control(
            'name_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .customer-feedback .customer-name' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'name_active_color',
            [
                'label' => esc_html__( 'Active Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide-active .customer-feedback .customer-name' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'name_align',
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
                    '{{WRAPPER}} .customer-feedback .customer-name' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'name_stroke',
                'selector' => '{{WRAPPER}} .customer-feedback .customer-name',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'name_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .customer-feedback .customer-name',
            ]
        );
        $this->add_responsive_control(
            'name_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .customer-feedback .customer-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'name_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .customer-feedback .customer-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();

        // review style
        $this->start_controls_section(
            'client_review_style', [
                'label' => esc_html__( 'Client Comment Text', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'review_typography',
                'selector' => '{{WRAPPER}} .customer-feedback p',
            ]
        );
        $this->add_control(
            'review_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .customer-feedback p' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'review_active_color',
            [
                'label' => esc_html__( 'Active Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide-active .customer-feedback p' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'review_align',
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
                    '{{WRAPPER}} .customer-feedback p' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'review_stroke',
                'selector' => '{{WRAPPER}} .customer-feedback p',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'review_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .customer-feedback p',
            ]
        );
        $this->add_responsive_control(
            'review_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .customer-feedback p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'review_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .customer-feedback p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();

        // ratings star style section
        $this->start_controls_section(
            'ratings_star_style',
            [
                'label' => esc_html__( 'Ratings Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
        $this->add_responsive_control(
            'ratings_star_font_size',
            [
                'label' => esc_html__( 'Font Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .feedback-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'ratings_star_icon_color',
            [
                'label' => esc_html__( 'Color', 'enteraddonst' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .feedback-rating i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'ratings_star_active_icon_color',
            [
                'label' => esc_html__( 'Active Color', 'enteraddonst' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide-active .feedback-rating i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ratings_star_border',
                'label' => esc_html__( 'Border', 'enteraddonst' ),
                'selector' => '{{WRAPPER}} .feedback-rating i',
            ]
        );
        $this->add_responsive_control(
            'ratings_star_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddonst' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px','%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .feedback-rating i' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ratings_star_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddonst' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .feedback-rating i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ratings_star_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddonst' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .feedback-rating i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // client image style section
        $this->start_controls_section(
            'client_image_style',
            [
                'label' => esc_html__( 'Image Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'image_size',
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
                    'size' => '80'
                ],
                'selectors' => [
                    '{{WRAPPER}} .customer-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .customer-image img',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .customer-image img',
            ]
        );
        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .customer-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .customer-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .customer-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_before_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        $this->add_control(
            'before_color',
            [
                'label' => esc_html__( 'Before Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .customer-image:before' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'before_border_radius',
            [
                'label' => esc_html__( 'After Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .customer-image:before' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_after_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
        $this->add_control(
            'after_color',
            [
                'label' => esc_html__( 'After Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .customer-image:after' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'after_border_radius',
            [
                'label' => esc_html__( 'After Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .customer-image:after' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // pagination style
        $this->start_controls_section(
            'pagination_style', [
                'label' => esc_html__( 'Nav Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nav_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea--feedback-slider-wrapper .ea--swiper-nav-button i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'nav_hover_color',
            [
                'label' => esc_html__( 'Icon Hover Color', 'enteraddons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea--feedback-slider-wrapper .ea--swiper-nav-button:hover i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea--feedback-slider-wrapper .ea--swiper-nav-button i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'navs_position_top',
            [
                'label' => esc_html__( 'Nav Position Top', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .ea--feedback-slider-wrapper .ea-swiper-pagi-nav' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'navs_position_right',
            [
                'label' => esc_html__( 'Nav Position Right', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea--feedback-slider-wrapper .ea-swiper-pagi-nav' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'space_between_icons',
            [
                'label' => esc_html__( 'Space Between Icons', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea--feedback-slider-wrapper .ea--swiper-button-next' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        

        $this->end_controls_section();

	}

	protected function render() {
        // get settings
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        // Testimonial template render
        $obj = new Vertical_Testimonial_Template();
        $obj::setDisplaySettings( $settings );
        $obj:: setDisplayID( $id );
        $obj->renderTemplate();
    }

    public function get_script_depends() {
        return [ 'enteraddons-main', 'swiper-slider' ];
    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style', 'swiper-slider', 'fontawesome' ];
    }


}
