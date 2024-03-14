<?php
/*
 * Elementor Education Addon Banner Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_banner'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Banner extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_banner';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Banner', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-slider-full-screen';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Banner widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_banner',
			[
				'label' => __( 'Banner Item', 'education-addon' ),
			]
		);
		$this->add_control(
			'banner_style',
			[
				'label' => esc_html__( 'Banner Style', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'education-addon' ),
					'two' => esc_html__( 'Style Two', 'education-addon' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your style.', 'education-addon' ),
			]
		);
		$this->add_responsive_control(
			'banner_size',
			[
				'label' => esc_html__( 'Banner Size', 'education-addon' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 300,
						'max' => 1000,
						'step' => 10,
					],
					'vh' => [
						'min' => 30,
						'max' => 100,
						'step' => 1,
					]
				],				
				'default' => [
					'unit' => 'px',
					'size' => 800,
				],
				'size_units' => [ 'px', 'vh' ],
				'selectors' => [
					'{{WRAPPER}} .naedu-banner.swiper-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'title_highlight',
			[
				'label' => esc_html__( 'Title Highlight', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '#1 World', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'description' => esc_html__( 'Works for Style Two.', 'education-addon' ),
			]
		);
		$repeater->add_control(
			'highlight_bg',
			[
				'label' => esc_html__( 'Highlight Background', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your background image.', 'education-addon'),
				'selectors' => [
					'{{WRAPPER}} .banner-style-two .caption-title span:before' => 'background-image: url({{url}});',
				],
				'description' => esc_html__( 'Works for Style Two.', 'education-addon' ),
			]
		);
		$repeater->add_control(
			'banner_title',
			[
				'label' => esc_html__( 'Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '#1 World Class Education for Anyone, Anywhere.', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'title_link',
			[
				'label' => esc_html__( 'Title Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
				'separator' => 'before',
			]
		);		
		$repeater->add_control(
			'banner_image',
			[
				'label' => esc_html__( 'Content Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Works for Style One.', 'education-addon' ),
			]
		);
		$repeater->add_control(
			'bg_image',
			[
				'label' => esc_html__( 'Background Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'This field works for Style Two.', 'education-addon'),
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'btn_link',
			[
				'label' => esc_html__( 'Button Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'btn_two_text',
			[
				'label' => esc_html__( 'Button Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
				'description' => esc_html__( 'Works for Style Two.', 'education-addon' ),
			]
		);
		$repeater->add_control(
			'btn_two_link',
			[
				'label' => esc_html__( 'Button Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
				'separator' => 'before',
				'description' => esc_html__( 'Works for Style Two.', 'education-addon' ),
			]
		);
		$this->add_control(
			'banner_groups',
			[
				'label' => esc_html__( 'Banner Items', 'education-addon' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ banner_title }}}',
			]
		);
		$this->end_controls_section();// end: Section

		// Slider Animation
		$this->start_controls_section(
			'section_animation',
			[
				'label' => __( 'Slider Animation', 'education-addon' ),
			]
		);
		$this->add_control(
			'title_animation',
			[
				'label' => esc_html__( 'Title Entrance Animation', 'education-addon' ),
				'type' => Controls_Manager::ANIMATION,
			]
		);
		$this->add_control(
			'button_animation',
			[
				'label' => esc_html__( 'Button Entrance Animation', 'education-addon' ),
				'type' => Controls_Manager::ANIMATION,
			]
		);
		$this->add_control(
			'image_animation',
			[
				'label' => esc_html__( 'Image Entrance Animation', 'education-addon' ),
				'type' => Controls_Manager::ANIMATION,
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
			$this->add_control(
				'carousel_loop',
				[
					'label' => esc_html__( 'Disable Loop?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Continuously moving carousel, if enabled.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_dots',
				[
					'label' => esc_html__( 'Dots', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want Carousel Dots, enable it.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_nav',
				[
					'label' => esc_html__( 'Navigation', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want Carousel Navigation, enable it.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_autoplay',
				[
					'label' => esc_html__( 'Autoplay', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want to start Carousel automatically, enable it.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_autoplay_timeout',
				[
					'label' => __( 'Auto Play Timeout', 'education-addon' ),
					'type' => Controls_Manager::NUMBER,
					'condition' => [
						'carousel_autoplay' => 'true',
					],
				]
			);
			$this->add_control(
				'clickable_pagi',
				[
					'label' => esc_html__( 'Pagination Dots Clickable?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want pagination dots clickable, enable it.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_speed',
				[
					'label' => __( 'Auto Play Speed', 'education-addon' ),
					'type' => Controls_Manager::NUMBER,
				]
			);
			$this->add_control(
				'carousel_effect',
				[
					'label' => __( 'Slider Effect', 'education-addon' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'fade' => esc_html__( 'Fade', 'education-addon' ),
						'slide' => esc_html__( 'Slide', 'education-addon' ),
						'cube' => esc_html__( 'Cube', 'education-addon' ),
						'flip' => esc_html__( 'Flip', 'education-addon' ),
					],
					'default' => 'fade',
					'description' => esc_html__( 'Select your slider navigation style.', 'education-addon' ),
				]
			);
			$this->add_control(
				'carousel_mousedrag',
				[
					'label' => esc_html__( 'Disable Mouse Drag?', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want to disable Mouse Drag, check it.', 'education-addon' ),
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
						'{{WRAPPER}} .naedu-banner .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-banner, {{WRAPPER}} .banner-style-two .swiper-slide:before' => 'background-color: {{VALUE}};',
					],
				]
			);			
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-banner',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-banner',
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
				'image_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-banner .banner-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);				
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'image_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-banner .banner-image',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'image_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-banner .banner-image',
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
						'{{WRAPPER}} .banner-caption .caption-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'banner_title_typography',
					'selector' => '{{WRAPPER}} .banner-caption .caption-title',
				]
			);
			$this->start_controls_tabs( 'ttl_style' );
				$this->start_controls_tab(
					'ttl_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'ttl_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption .caption-title, {{WRAPPER}} .banner-caption .caption-title a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'ttl_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'ttl_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption .caption-title a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Button 1
			$this->start_controls_section(
				'section_btn_style',
				[
					'label' => esc_html__( 'Button 1', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'btn_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-btn-one' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'btn_padding',
				[
					'label' => __( 'Button Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-btn-one' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'btn_typography',
					'selector' => '{{WRAPPER}} .naedu-btn-one',
				]
			);
			$this->start_controls_tabs( 'btn_style' );
				$this->start_controls_tab(
					'btn_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'btn_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn-one' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn-one' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn-one',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn-one',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'btn_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'btn_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn-one:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn-one:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn-one:hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_hover_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn-one:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Button 2
			$this->start_controls_section(
				'section_btn_two_style',
				[
					'label' => esc_html__( 'Button 2', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'banner_style' => 'two',
					],
				]
			);
			$this->add_control(
				'btn_two_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-btn-two' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'btn_two_padding',
				[
					'label' => __( 'Button Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-btn-two' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'btn_two_typography',
					'selector' => '{{WRAPPER}} .naedu-btn-two',
				]
			);
			$this->start_controls_tabs( 'btn_two_style' );
				$this->start_controls_tab(
					'btn_two_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'btn_two_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn-two' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_two_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn-two' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_two_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn-two',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_two_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn-two',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'btn_two_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'btn_two_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn-two:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_two_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn-two:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_two_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn-two:hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_two_hover_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn-two:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
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
	 * Render Banner widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Banner query
		$settings = $this->get_settings_for_display();
		$banner_style = !empty( $settings['banner_style'] ) ? $settings['banner_style'] : '';
		$banner_groups = !empty( $settings['banner_groups'] ) ? $settings['banner_groups'] : '';
		$title_animation = !empty( $settings['title_animation'] ) ? $settings['title_animation'] : '';
		$button_animation = !empty( $settings['button_animation'] ) ? $settings['button_animation'] : '';
		$image_animation = !empty( $settings['image_animation'] ) ? $settings['image_animation'] : '';

		if ($banner_style === 'two') {
			$style_cls = ' banner-style-two';
		} else {
			$style_cls = '';
		}

		// Carousel Options
			$swipeSliders_groups = !empty( $settings['swipeSliders_groups'] ) ? $settings['swipeSliders_groups'] : [];
			$carousel_autoplay_timeout = !empty( $settings['carousel_autoplay_timeout'] ) ? $settings['carousel_autoplay_timeout'] : '';
			$carousel_speed = !empty( $settings['carousel_speed'] ) ? $settings['carousel_speed'] : '';
			$carousel_effect = !empty( $settings['carousel_effect'] ) ? $settings['carousel_effect'] : '';

			$carousel_loop  = ( isset( $settings['carousel_loop'] ) && ( 'true' == $settings['carousel_loop'] ) ) ? $settings['carousel_loop'] : 'false';
			$carousel_dots  = ( isset( $settings['carousel_dots'] ) && ( 'true' == $settings['carousel_dots'] ) ) ? true : false;
			$carousel_nav  = ( isset( $settings['carousel_nav'] ) && ( 'true' == $settings['carousel_nav'] ) ) ? true : false;
			$carousel_autoplay  = ( isset( $settings['carousel_autoplay'] ) && ( 'true' == $settings['carousel_autoplay'] ) ) ? true : false;
			$clickable_pagi = ( isset( $settings['clickable_pagi'] ) && ( 'true' == $settings['clickable_pagi'] ) ) ? true : false;

			$carousel_mousedrag  = ( isset( $settings['carousel_mousedrag'] ) && ( 'true' == $settings['carousel_mousedrag'] ) ) ? $settings['carousel_mousedrag'] : 'false';

		// Carousel Data's
			$carousel_loop = $carousel_loop !== 'true' ? ' data-loop="true"' : ' data-loop="false"';
			$carousel_autoplay_timeout = $carousel_autoplay_timeout ? ' data-swiper-autoplay='. $carousel_autoplay_timeout .'' : ' data-swiper-autoplay=5000';
			$carousel_speed = $carousel_speed ? ' data-speed="'. $carousel_speed .'"' : ' data-speed="1000"';
			$carousel_autoplay = $carousel_autoplay ? ' data-autoplay="true"' : ' data-autoplay="false"';
			$clickable_pagi = $clickable_pagi ? 'data-clickpage="true"' : '';
			$carousel_effect = $carousel_effect ? ' data-effect="'.$carousel_effect.'"' : ' data-effect="fade"';
			$carousel_mousedrag = $carousel_mousedrag !== 'true' ? ' data-mousedrag="true"' : ' data-mousedrag="false"';

		// Animation
			$title_animation = $title_animation ? $title_animation : 'fadeInDown';
			$button_animation = $button_animation ? $button_animation : 'fadeInDown';
			$image_animation = $image_animation ? $image_animation : 'fadeInDown';

		$output = '<div class="swiper-container  naedu-banner'.$style_cls.' swiper-slides swiper-keyboard" '.$carousel_loop . $carousel_autoplay . $carousel_effect . $carousel_speed . $clickable_pagi . $carousel_mousedrag.' data-swiper="container"><div class="swiper-wrapper">';
		if ( !empty( $banner_groups ) && is_array( $banner_groups ) ) {
			// Group Param Output
			foreach ( $banner_groups as $each_banner ) {
				$title_highlight = !empty( $each_banner['title_highlight'] ) ? $each_banner['title_highlight'] : '';
				$banner_title = !empty( $each_banner['banner_title'] ) ? $each_banner['banner_title'] : '';
				$title_link = !empty( $each_banner['title_link']['url'] ) ? $each_banner['title_link']['url'] : '';
				$title_link_external = !empty( $each_banner['title_link']['is_external'] ) ? 'target="_blank"' : '';
				$title_link_nofollow = !empty( $each_banner['title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$title_link_attr = !empty( $title_link ) ?  $title_link_external.' '.$title_link_nofollow : '';
				$banner_content = !empty( $each_banner['banner_content'] ) ? $each_banner['banner_content'] : '';
				$banner_image = !empty( $each_banner['banner_image']['id'] ) ? $each_banner['banner_image']['id'] : '';
				$bg_image = !empty( $each_banner['bg_image']['id'] ) ? $each_banner['bg_image']['id'] : '';

				$btn_text = !empty( $each_banner['btn_text'] ) ? $each_banner['btn_text'] : '';
				$btn_link = !empty( $each_banner['btn_link']['url'] ) ? esc_url($each_banner['btn_link']['url']) : '';
				$btn_link_external = !empty( $btn_link['is_external'] ) ? 'target="_blank"' : '';
				$btn_link_nofollow = !empty( $btn_link['nofollow'] ) ? 'rel="nofollow"' : '';
				$btn_link_attr = !empty( $btn_link['url'] ) ?  $btn_link_external.' '.$btn_link_nofollow : '';

				$btn_two_text = !empty( $each_banner['btn_two_text'] ) ? $each_banner['btn_two_text'] : '';
				$btn_two_link = !empty( $each_banner['btn_two_link']['url'] ) ? esc_url($each_banner['btn_two_link']['url']) : '';
				$btn_two_link_external = !empty( $btn_two_link['is_external'] ) ? 'target="_blank"' : '';
				$btn_two_link_nofollow = !empty( $btn_two_link['nofollow'] ) ? 'rel="nofollow"' : '';
				$btn_two_link_attr = !empty( $btn_two_link['url'] ) ?  $btn_two_link_external.' '.$btn_two_link_nofollow : '';

				$image_url = wp_get_attachment_url( $banner_image );
				$image = $image_url ? '<div class="banner-image animated" data-animation="'.esc_attr($image_animation).'"><img src="'.esc_url($image_url).'" alt="Title"></div>' : '';

				$bg_image_url = wp_get_attachment_url( $bg_image );
				$bg_img = $bg_image_url ? ' style="background-image: url('.esc_url($bg_image_url).');"' : '';

				$title_highlight = $title_highlight ? '<span>'.$title_highlight.'</span> ' : '';
				$title_link = $title_link ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'. $title_highlight . esc_html($banner_title) .'</a>' : $title_highlight . esc_html($banner_title);
				$title = $banner_title ? '<h1 class="caption-title animated" data-animation="'.esc_attr($title_animation).'">'.$title_link.'</h1>' : '';
				$button = $btn_link ? '<a href="'.esc_url($btn_link).'" class="naedu-btn naedu-btn-one naedu-btn-dark naedu-btn-lg animated" data-animation="'.esc_attr($button_animation).'" '.$btn_link_attr.'>'.esc_html($btn_text).'</a>' : '';
				$button_one = $btn_link ? '<a href="'.esc_url($btn_link).'" class="naedu-btn naedu-btn-one naedu-btn-lg animated" data-animation="'.esc_attr($button_animation).'" '.$btn_link_attr.'>'.esc_html($btn_text).'</a>' : '';
				$button_two = $btn_two_link ? '<a href="'.esc_url($btn_two_link).'" class="naedu-btn naedu-btn-two naedu-btn-light naedu-btn-lg animated" data-animation="'.esc_attr($button_animation).'" '.$btn_two_link_attr.'>'.esc_html($btn_two_text).'</a>' : '';

		    $output .= '<div class="swiper-slide"'.$bg_img.'>';
				if ($banner_style === 'two') {
					$output .= '<div class="nich-container">
									      <div class="banner-caption">
									        '.$title.$button_one.$button_two.'
									      </div>
									    </div>';
			  } else {
			  	$output .= '<div class="nich-container-fluid">
									      <div class="nich-row">
									        <div class="nich-col-xl-7 nich-my-auto">
									          <div class="banner-caption">
									          	'.$title.$button.'
									          </div>
									        </div>
									        <div class="nich-col-xl-5 nich-mt-auto">'.$image.'</div>
									      </div>
									    </div>';
			  }
		    $output .= '</div>';
			}
		}
	  $output .= '</div>';
	  if ($carousel_nav){ $output .= '<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>'; } 
    if ($carousel_dots) { $output .= '<div class="swiper-pagination"></div>'; }
	  $output .= '</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Banner() );

} // enable & disable
