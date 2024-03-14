<?php
/*
 * Elementor Education Addon Testimonials Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_testimonials'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Testimonials extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_testimonials';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Testimonials', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-testimonial';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Testimonials widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_testimonials',
			[
				'label' => __( 'Testimonials Item', 'education-addon' ),
			]
		);
		$this->add_control(
			'testimonial_style',
			[
				'label' => esc_html__( 'Testimonials Style', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'education-addon' ),
					'two' => esc_html__( 'Style Two', 'education-addon' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your style.', 'education-addon' ),
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'icon_image',
			[
				'label' => esc_html__( 'Icon Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'education-addon'),
			]
		);
		$repeater->add_control(
			'testimonial_title',
			[
				'label' => esc_html__( 'Name', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '- Donald Logan', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'testimonial_title_link',
			[
				'label' => esc_html__( 'Name Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'testimonial_image',
			[
				'label' => esc_html__( 'Author Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'education-addon'),
			]
		);		
		$repeater->add_control(
			'testimonial_designation',
			[
				'label' => esc_html__( 'Designation Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Rockstore Inc -', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'testimonial_content',
			[
				'label' => esc_html__( 'Content', 'education-addon' ),
				'default' => esc_html__( '“ There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even  Ipsum, you need to be sure ”', 'education-addon' ),
				'placeholder' => esc_html__( 'Type your content here', 'education-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);
		$this->add_control(
			'testimonials_groups',
			[
				'label' => esc_html__( 'Testimonials Items', 'education-addon' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'testimonial_title' => esc_html__( 'Cathrine Wagner', 'education-addon' ),
						'testimonial_content' => esc_html__( '“ There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even  Ipsum, you need to be sure ”', 'education-addon' ),
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ testimonial_title }}}',
			]
		);
		$this->end_controls_section();// end: Section 

		// Carousel Options
			$this->start_controls_section(
				'section_carousel',
				[
					'label' => esc_html__( 'Carousel Options', 'education-addon' ),
				]
			);
			$this->add_responsive_control(
				'carousel_items',
				[
					'label' => esc_html__( 'How many items?', 'education-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 100,
					'step' => 1,
					'default' => 1,
					'description' => esc_html__( 'Enter the number of items to show.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_margin',
				[
					'label' => __( 'Space Between Items', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' =>30,
					],
					'label_block' => true,
				]
			);
			$this->add_control(
				'carousel_autoplay_timeout',
				[
					'label' => __( 'Auto Play Timeout', 'education-addon' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 5000,
				]
			);
			$this->add_control(
				'carousel_autoplay',
				[
					'label' => esc_html__( 'Need Autoplay?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want to start Carousel automatically, enable it.', 'education-addon' ),
					'default' => 'true',
				]
			);
			$this->add_control(
				'carousel_animatein',
				[
					'label' => esc_html__( 'Animate In', 'education-addon' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'false' => esc_html__( 'None', 'education-addon' ),
						'fadeIn' => esc_html__( 'fadeIn', 'education-addon' ),
						'fadeOut' => esc_html__( 'fadeOut', 'education-addon' ),
					],
					'default' => 'false',
					'description' => esc_html__( 'Select your style.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_animateout',
				[
					'label' => esc_html__( 'Animate Out', 'education-addon' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'false' => esc_html__( 'None', 'education-addon' ),
						'fadeIn' => esc_html__( 'fadeIn', 'education-addon' ),
						'fadeOut' => esc_html__( 'fadeOut', 'education-addon' ),
					],
					'default' => 'false',
					'description' => esc_html__( 'Select your style.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_loop',
				[
					'label' => esc_html__( 'Need Loop?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Continuously moving carousel, if enabled.', 'education-addon' ),
					'default' => 'true',
				]
			);
			$this->add_control(
				'carousel_dots',
				[
					'label' => esc_html__( 'Need Dots?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want Carousel Dots, enable it.', 'education-addon' ),
					'default' => 'true',
				]
			);
			$this->add_control(
				'carousel_nav',
				[
					'label' => esc_html__( 'Need Navigation?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want Carousel Navigation, enable it.', 'education-addon' ),
					'default' => 'true',
				]
			);			
			$this->add_control(
				'carousel_mousedrag',
				[
					'label' => esc_html__( 'Need Mouse Drag?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want to disable Mouse Drag, check it.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_autowidth',
				[
					'label' => esc_html__( 'Need Auto Width?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Adjust Auto Width automatically for each carousel items.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_autoheight',
				[
					'label' => esc_html__( 'Need Auto Height?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Adjust Auto Height automatically for each carousel items.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_center',
				[
					'label' => esc_html__( 'Need Center?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Center carousel items.', 'education-addon' ),
				]
			);
			$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .testimonial-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Section Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testimonial-info' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'testimonial_style' => 'one',
					],
				]
			);
			$this->add_control(
				'secn_two_bg_color',
				[
					'label' => esc_html__( 'Section Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testimonials-style-two' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .testimonial-users svg path' => 'fill: {{VALUE}};',
					],
					'condition' => [
						'testimonial_style' => 'two',
					],
				]
			);
			$this->add_control(
				'secn_item_bg_color',
				[
					'label' => esc_html__( 'Item Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testimonial-info' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .testimonial-info',
				]
			);
			$this->add_control(
				'secn_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .testimonial-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Image
			$this->start_controls_section(
				'section_image_style',
				[
					'label' => esc_html__( 'Image', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'image_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-item .testimonial-auther img, {{WRAPPER}} .owl-carousel .owl-item .testimonial-users img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'image_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-item .testimonial-auther img, {{WRAPPER}} .owl-carousel .owl-item .testimonial-users img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'image_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .owl-carousel .owl-item .testimonial-auther img, {{WRAPPER}} .owl-carousel .owl-item .testimonial-users img',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'image_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .owl-carousel .owl-item .testimonial-auther img, {{WRAPPER}} .owl-carousel .owl-item .testimonial-users img',
				]
			);
			$this->add_control(
				'image_width',
				[
					'label' => esc_html__( 'Image width', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-item .testimonial-auther img, {{WRAPPER}} .owl-carousel .owl-item .testimonial-users img' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Quote Icon
			$this->start_controls_section(
				'section_qicon_style',
				[
					'label' => esc_html__( 'Quote Icon', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'qicon_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .testimonial-info:before, {{WRAPPER}} .testimonial-info:after, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:before, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'qicon_typography',
					'selector' => '{{WRAPPER}} .testimonial-info:before, {{WRAPPER}} .testimonial-info:after, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:before, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:after',
				]
			);
			$this->add_control(
				'qicon_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testimonial-info:before, {{WRAPPER}} .testimonial-info:after, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:before, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:after' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'qicon_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testimonial-info:before, {{WRAPPER}} .testimonial-info:after, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:before, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'qicon_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .testimonial-info:before, {{WRAPPER}} .testimonial-info:after, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:before, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'qicon_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .testimonial-info:before, {{WRAPPER}} .testimonial-info:after, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:before, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:after',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'qicon_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .testimonial-info:before, {{WRAPPER}} .testimonial-info:after, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:before, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:after',
				]
			);
			$this->add_control(
				'qicon_width',
				[
					'label' => esc_html__( 'Width & Height', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .testimonial-info:before, {{WRAPPER}} .testimonial-info:after, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:before, {{WRAPPER}} .testimonials-style-two .testimonial-auther h3:after' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'title_padding',
				[
					'label' => __( 'Title Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .testimonial-auther h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'title_margin',
				[
					'label' => __( 'Title Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .testimonial-auther h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'sastestimonial_title_typography',
					'selector' => '{{WRAPPER}} .testimonial-auther h3',
				]
			);
			$this->start_controls_tabs( 'testimonials_title_style' );
				$this->start_controls_tab(
					'title_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .testimonial-auther h3, {{WRAPPER}} .testimonial-auther h3 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'title_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'title_hov_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .testimonial-auther h3 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Designation
			$this->start_controls_section(
				'section_subtitle_style',
				[
					'label' => esc_html__( 'Designation', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'subtitle_typography',
					'selector' => '{{WRAPPER}} .testimonial-auther span',
				]
			);
			$this->add_control(
				'subtitle_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testimonial-auther span' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Content', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .testimonial-info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'content_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .testimonial-info p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .testimonial-info p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testimonial-info p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Navigation
			$this->start_controls_section(
				'section_navigation_style',
				[
					'label' => esc_html__( 'Navigation', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'carousel_nav' => 'true',
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'arrow_size',
				[
					'label' => esc_html__( 'Size', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 42,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'nav_arrow_style' );
				$this->start_controls_tab(
					'nav_arrow_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'nav_arrow_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'nav_arrow_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'nav_arrow_hov_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_active_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab

			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Dots
			$this->start_controls_section(
				'section_dots_style',
				[
					'label' => esc_html__( 'Dots', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'carousel_dots' => 'true',
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'dots_size',
				[
					'label' => esc_html__( 'Size', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-dot' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->add_responsive_control(
				'dots_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-dot' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'dots_style' );
				$this->start_controls_tab(
					'dots_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'dots_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-dot' => 'background: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'dots_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-dot',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'dots_active',
					[
						'label' => esc_html__( 'Active', 'education-addon' ),
					]
				);
				$this->add_responsive_control(
					'active_dots_size',
					[
						'label' => esc_html__( 'Active Height', 'education-addon' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1000,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .owl-carousel button.owl-dot.active' => 'height: {{SIZE}}{{UNIT}}',
						],
					]
				);
				$this->add_control(
					'dots_active_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-dot.active' => 'background: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'dots_active_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-dot.active',
					]
				);
				$this->end_controls_tab();  // end:Active tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Testimonials widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Testimonials query
		$settings = $this->get_settings_for_display();
		$testimonial_style = !empty( $settings['testimonial_style'] ) ? $settings['testimonial_style'] : '';
		$testimonial_bg = !empty( $settings['testimonial_bg']['id'] ) ? $settings['testimonial_bg']['id'] : '';
		$testimonials_groups = !empty( $settings['testimonials_groups'] ) ? $settings['testimonials_groups'] : '';

		$bg_image_url = wp_get_attachment_url( $testimonial_bg );
		$bg_image = $bg_image_url ? '<div class="nich-row nich-no-gutters"><div class="nich-col-md-10 nich-col-xl-7"><img src="'.esc_url($bg_image_url).'" alt="Image"></div></div>' : '';

		// Carousel
			$carousel_items = !empty( $settings['carousel_items'] ) ? $settings['carousel_items'] : '';
			$carousel_items_tablet = !empty( $settings['carousel_items_tablet'] ) ? $settings['carousel_items_tablet'] : '';
			$carousel_items_mobile = !empty( $settings['carousel_items_mobile'] ) ? $settings['carousel_items_mobile'] : '';
			$carousel_margin = !empty( $settings['carousel_margin']['size'] ) ? $settings['carousel_margin']['size'] : '';
			$carousel_autoplay_timeout = !empty( $settings['carousel_autoplay_timeout'] ) ? $settings['carousel_autoplay_timeout'] : '';
			$carousel_autoplay  = ( isset( $settings['carousel_autoplay'] ) && ( 'true' == $settings['carousel_autoplay'] ) ) ? true : false;
			$carousel_animatein = !empty( $settings['carousel_animatein'] ) ? $settings['carousel_animatein'] : '';
			$carousel_animateout = !empty( $settings['carousel_animateout'] ) ? $settings['carousel_animateout'] : '';
			$carousel_loop  = ( isset( $settings['carousel_loop'] ) && ( 'true' == $settings['carousel_loop'] ) ) ? true : false;
			$carousel_dots  = ( isset( $settings['carousel_dots'] ) && ( 'true' == $settings['carousel_dots'] ) ) ? true : false;
			$carousel_nav  = ( isset( $settings['carousel_nav'] ) && ( 'true' == $settings['carousel_nav'] ) ) ? true : false;
			$carousel_mousedrag  = ( isset( $settings['carousel_mousedrag'] ) && ( 'true' == $settings['carousel_mousedrag'] ) ) ? $settings['carousel_mousedrag'] : 'false';
			$carousel_autowidth  = ( isset( $settings['carousel_autowidth'] ) && ( 'true' == $settings['carousel_autowidth'] ) ) ? true : false;
			$carousel_autoheight  = ( isset( $settings['carousel_autoheight'] ) && ( 'true' == $settings['carousel_autoheight'] ) ) ? true : false;
			$carousel_center  = ( isset( $settings['carousel_center'] ) && ( 'true' == $settings['carousel_center'] ) ) ? true : false;

		// Carousel Data's
			$carousel_items = $carousel_items ? ' data-items="'. $carousel_items .'"' : ' data-items="1"';
			$carousel_tablet = $carousel_items_tablet ? ' data-items-tablet="'. $carousel_items_tablet .'"' : ' data-items-tablet="1"';
			$carousel_mobile = $carousel_items_mobile ? ' data-items-mobile-landscape="'. $carousel_items_mobile .'"' : ' data-items-mobile-landscape="1"';
			$carousel_small_mobile = $carousel_items_mobile ? ' data-items-mobile-portrait="'. $carousel_items_mobile .'"' : ' data-items-mobile-portrait="1"';
			$carousel_margin = $carousel_margin ? ' data-margin="'. $carousel_margin .'"' : ' data-margin="0"';
			$carousel_autoplay_timeout = $carousel_autoplay_timeout ? ' data-autoplay-timeout="'. $carousel_autoplay_timeout .'"' : '';
			$carousel_autoplay = ('true' == $carousel_autoplay) ? ' data-autoplay="true"' : ' data-autoplay="false"';
			$carousel_animatein = $carousel_animatein ? ' data-animatein="'. $carousel_animatein .'"' : '';
			$carousel_animateout = $carousel_animateout ? ' data-animateout="'. $carousel_animateout .'"' : '';
			$carousel_loop = ('true' == $carousel_loop) ? ' data-loop="true"' : ' data-loop="false"';
			$carousel_dots = ('true' == $carousel_dots) ? ' data-dots="true"' : ' data-dots="false"';
			$carousel_nav = ('true' == $carousel_nav) ? ' data-nav="true"' : ' data-nav="false"';
			$carousel_mousedrag = ('true' == $carousel_mousedrag) ? ' data-mouse-drag="true"' : ' data-mouse-drag="false"';
			$carousel_autowidth = ('true' == $carousel_autowidth) ? ' data-auto-width="true"' : ' data-auto-width="false"';
			$carousel_autoheight = ('true' == $carousel_autoheight) ? ' data-auto-height="true"' : ' data-auto-height="false"';
			$carousel_center = ('true' == $carousel_center) ? ' data-center="true"' : ' data-center="false"';

		if ($testimonial_style === 'two') {
			$style_cls = ' testimonials-style-two';
			$contain_cls = 'nich-container-fluid';
		} else {
			$style_cls = '';
			$contain_cls = 'nich-container';
		}

		$output = '<div class="naedu-testimonials'.$style_cls.'"><div class="'.$contain_cls.'"><div class="owl-carousel" '. $carousel_items . $carousel_tablet . $carousel_mobile . $carousel_small_mobile . $carousel_margin . $carousel_autoplay_timeout . $carousel_loop . $carousel_dots . $carousel_nav . $carousel_autoplay . $carousel_mousedrag . $carousel_autowidth . $carousel_autoheight . $carousel_center .'>';
			if ( !empty( $testimonials_groups ) && is_array( $testimonials_groups ) ) {
				// Group Param Output
				foreach ( $testimonials_groups as $each_testimonial ) {
					$testimonial_image = !empty( $each_testimonial['testimonial_image']['id'] ) ? $each_testimonial['testimonial_image']['id'] : '';
					$icon_image = !empty( $each_testimonial['icon_image']['id'] ) ? $each_testimonial['icon_image']['id'] : '';
					$testimonial_title = !empty( $each_testimonial['testimonial_title'] ) ? $each_testimonial['testimonial_title'] : '';

					$testimonial_link = !empty( $each_testimonial['testimonial_title_link']['url'] ) ? esc_url($each_testimonial['testimonial_title_link']['url']) : '';
					$testimonial_link_external = !empty( $testimonial_link['is_external'] ) ? 'target="_blank"' : '';
					$testimonial_link_nofollow = !empty( $testimonial_link['nofollow'] ) ? 'rel="nofollow"' : '';
					$testimonial_link_attr = !empty( $testimonial_link['url'] ) ?  $testimonial_link_external.' '.$testimonial_link_nofollow : '';

					$testimonial_designation = !empty( $each_testimonial['testimonial_designation'] ) ? $each_testimonial['testimonial_designation'] : '';
					$testimonial_content = !empty( $each_testimonial['testimonial_content'] ) ? $each_testimonial['testimonial_content'] : '';

					$image_url = wp_get_attachment_url( $testimonial_image );
					$image = $image_url ? '<img src="'.esc_url($image_url).'" alt="'.esc_attr($testimonial_title).'">' : '';

					$icon_image_url = wp_get_attachment_url( $icon_image );
					$ico_image = $icon_image_url ? '<img src="'.esc_url($icon_image_url).'" alt="'.esc_attr($testimonial_title).'">' : '';

	 			  $title_link = !empty( $testimonial_link ) ? '<a href="'.esc_url($testimonial_link).'" '.$testimonial_link_attr.'>'.esc_html($testimonial_title).'</a>' : esc_html($testimonial_title);
					$designation = $testimonial_designation ? ', <span>'.esc_html($testimonial_designation).'</span>' : '';
					$title = $testimonial_title ? '<h3>'.$title_link.$designation.'</h3>' : '';
					$content = $testimonial_content ? '<p>'.esc_html($testimonial_content).'</p>' : '';

					if ($testimonial_style === 'two') {
						$output .= '<div class="item">
								          <div class="nich-row">
								            <div class="nich-col-lg-6 nich-my-auto">
								              <div class="testimonial-info">
								          			'.$ico_image.$content.'
								                <div class="testimonial-auther">
								            			'.$title.'
								                </div>
								              </div>  
								            </div>
								            <div class="nich-col-lg-6">
								              <div class="testimonial-users">
								            		'.$image.'
								                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								                   viewBox="0 0 202 650" style="enable-background:new 0 0 202 650;" xml:space="preserve">
								                  <path d="M0,0h201.97c0,0-248.97,305,0.03,650H0V0z"/>
								                </svg>
								              </div>
								            </div>
								          </div>
								        </div>';
					} else {
					  $output .= '<div class="item">
								          <div class="testimonial-info">
								          '.$content.'
								            <div class="testimonial-auther">
								            	'.$image.$title.'
								            </div>
								          </div>  
								        </div>';
					}
				}
			}
		$output .= '</div></div></div>'; 
		if ( Plugin::$instance->editor->is_edit_mode() ) : ?>
		<script type="text/javascript">
	    jQuery(document).ready(function($) {
				$('.owl-carousel').each( function() {
			    var $carousel = $(this);
			    var $items = ($carousel.data('items') !== undefined) ? $carousel.data('items') : 1;
			    var $items_tablet = ($carousel.data('items') !== undefined) ? $carousel.data('items-tablet') : 1;
			    var $items_mobile_landscape = ($carousel.data('items-mobile-landscape') !== undefined) ? $carousel.data('items-mobile-landscape') : 1;
			    var $items_mobile_portrait = ($carousel.data('items-mobile-portrait') !== undefined) ? $carousel.data('items-mobile-portrait') : 1;
			    var $stagep_tablet = ($carousel.data('stagep-tablet') !== undefined) ? $carousel.data('stagep-tablet') : 1;
			    var $stagep_desktop = ($carousel.data('stagep-desktop') !== undefined) ? $carousel.data('stagep-desktop') : 1;
			    $carousel.owlCarousel ({
			      loop : ($carousel.data('loop') !== undefined) ? $carousel.data('loop') : true,
			      items : $carousel.data('items'),
			      margin : ($carousel.data('margin') !== undefined) ? $carousel.data('margin') : 0,
			      stagePadding : ($carousel.data('stagepadding') !== undefined) ? $carousel.data('stagepadding') : 0,
			      dots : ($carousel.data('dots') !== undefined) ? $carousel.data('dots') : true,
			      nav : ($carousel.data('nav') !== undefined) ? $carousel.data('nav') : false,
			      navText : ["<div class='slider-no-current'><span class='current-no'></span><span class='total-no'></span></div><span class='current-monials'></span>", "<div class='slider-no-next'></div><span class='next-monials'></span>"],
			      autoplay : ($carousel.data('autoplay') !== undefined) ? $carousel.data('autoplay') : false,
			      autoplayTimeout : ($carousel.data('autoplay-timeout') !== undefined) ? $carousel.data('autoplay-timeout') : 5000,
			      animateIn : ($carousel.data('animatein') !== undefined) ? $carousel.data('animatein') : false,
			      animateOut : ($carousel.data('animateout') !== undefined) ? $carousel.data('animateout') : false,
			      mouseDrag : ($carousel.data('mouse-drag') !== undefined) ? $carousel.data('mouse-drag') : true,
			      autoWidth : ($carousel.data('auto-width') !== undefined) ? $carousel.data('auto-width') : false,
			      autoHeight : ($carousel.data('auto-height') !== undefined) ? $carousel.data('auto-height') : false,
			      center : ($carousel.data('center') !== undefined) ? $carousel.data('center') : false,
			      responsiveClass: true,
			      dotsEachNumber: true,
			      smartSpeed: 600,
			      autoplayHoverPause: true,
			      responsive : {
			        0 : {
			          items : $items_mobile_portrait,
			        },
			        480 : {
			          items : $items_mobile_landscape,
			        },
			        768 : {
			          items : $items_tablet,
			          stagePadding: $stagep_tablet,
			        },
			        1200 : {
			          items : $items,
			          stagePadding: $stagep_desktop,
			        }
			      }
			    });
			    var totLength = $('.owl-dot', $carousel).length;
			    $('.total-no', $carousel).html(totLength);
			    $('.current-no', $carousel).html(totLength);
			    $carousel.owlCarousel();
			    $('.current-no', $carousel).html(1);
			    $carousel.on('changed.owl.carousel', function(event) {
			      var total_items = event.page.count;
			      var currentNum = event.page.index + 1;
			      $('.total-no', $carousel ).html(total_items);
			      $('.current-no', $carousel).html(currentNum);
			    });
			  });
		  });
		</script>
		<?php endif;
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Testimonials() );

} // enable & disable
