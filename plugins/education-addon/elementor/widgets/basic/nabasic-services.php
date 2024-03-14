<?php
/*
 * Elementor Education Addon Step Flow Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_services'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Services extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_services';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Services', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-plus-square-o';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Profile widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_profile',
			[
				'label' => __( 'Services', 'education-addon' ),
			]
		);
		$this->add_control(
			'services_section_title',
			[
				'label' => esc_html__( 'Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Our Services', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'education-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'service_image',
			[
				'label' => esc_html__( 'Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'education-addon'),
			]
		);
		$repeater->add_control(
			'service_title',
			[
				'label' => esc_html__( 'Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Service #ADD', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'service_content',
			[
				'label' => esc_html__( 'Content', 'education-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'There are many variations of passages of Lorem Ipsum available.', 'education-addon' ),
				'placeholder' => esc_html__( 'Type text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'service_link',
			[
				'label' => esc_html__( 'Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'services',
			[
				'label' => esc_html__( 'Services', 'education-addon' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ service_title }}}',
				'prevent_empty' => false,
				'separator' => 'before',
				'default' => [
					[
						'service_title' => __( 'Service #1', 'education-addon' ),
						'service_content' => __( 'There are many variations of passages of Lorem Ipsum available.', 'education-addon' ),
						'service_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'service_link' => [
							'url' => '',
						],
					],
					[
						'service_title' => __( 'Service #2', 'education-addon' ),
						'service_content' => __( 'There are many variations of passages of Lorem Ipsum available.', 'education-addon' ),
						'service_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'service_link' => [
							'url' => '',
						],
					],
					[
						'service_title' => __( 'Service #3', 'education-addon' ),
						'service_content' => __( 'There are many variations of passages of Lorem Ipsum available.', 'education-addon' ),
						'service_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'service_link' => [
							'url' => '',
						],
					],
					[
						'service_title' => __( 'Service #4', 'education-addon' ),
						'service_content' => __( 'There are many variations of passages of Lorem Ipsum available.', 'education-addon' ),
						'service_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'service_link' => [
							'url' => '',
						],
					],
					[
						'service_title' => __( 'Service #5', 'education-addon' ),
						'service_content' => __( 'There are many variations of passages of Lorem Ipsum available.', 'education-addon' ),
						'service_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'service_link' => [
							'url' => '',
						],
					],
				],
			]
		);
		$this->end_controls_section();// end: Section

		/**
		 * Carousel
		 */
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
				'default' => 2,
				'description' => esc_html__( 'Enter the number of items to show.', 'education-addon' ),
			]
		);
		$this->add_control(
			'carousel_margin',
			[
				'label' => esc_html__( 'Space Between Items', 'education-addon' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'carousel_autoplay_timeout',
			[
				'label' => esc_html__( 'Auto Play Timeout', 'education-addon' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
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
			'carousel_animate_out',
			[
				'label' => esc_html__( 'Animate Out', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'CSS3 animation out.', 'education-addon' ),
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
		$this->add_control(
			'carousel_autowidth',
			[
				'label' => esc_html__( 'Auto Width', 'education-addon' ),
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
				'label' => esc_html__( 'Auto Height', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'Adjust Auto Height automatically for each carousel items.', 'education-addon' ),
			]
		);
		$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Section Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'title_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-service-title h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .naedu-service-title h2',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-service-title h2, {{WRAPPER}} .naedu-service-title h2 a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Section Content', 'education-addon' ),
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
						'{{WRAPPER}} .naedu-service-desc p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .naedu-service-desc p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-service-desc p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Service Grid
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Service', 'education-addon' ),
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
					'{{WRAPPER}} .naedu-service' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'section_margin',
			[
				'label' => __( 'Margin', 'education-addon' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naedu-service' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'section_bdr_rad',
			[
				'label' => __( 'Border Radius', 'education-addon' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .naedu-service' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'secn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'education-addon' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .naedu-service' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'secn_border',
				'label' => esc_html__( 'Border', 'education-addon' ),
				'selector' => '{{WRAPPER}} .naedu-service',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'secn_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'education-addon' ),
				'selector' => '{{WRAPPER}} .naedu-service',
			]
		);
		$this->end_controls_section();// end: Section

		// Icon
			$this->start_controls_section(
				'section_sicon_style',
				[
					'label' => esc_html__( 'Carousel Image', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'sicon_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-service-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'sicon_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-service-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'sicon_size',
				[
					'label' => esc_html__( 'Image Height', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-service-img' => 'height:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section
		
		// Title
			$this->start_controls_section(
				'section_siconc_title_style',
				[
					'label' => esc_html__( 'Carousel Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'siconct_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-service h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'siconct_typography',
					'selector' => '{{WRAPPER}} .naedu-service h3',
				]
			);
			$this->add_control(
				'siconct_title_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-service h3, {{WRAPPER}} .naedu-service h3 a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_siconct_content_style',
				[
					'label' => esc_html__( 'Carousel Content', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'siconct_content_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-service p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'siconct_content_typography',
					'selector' => '{{WRAPPER}} .naedu-service p',
				]
			);
			$this->add_control(
				'siconct_content_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-service p' => 'color: {{VALUE}};',
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
				'nav_arrow_section_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-service-carousel-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-prev:after, {{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-next:after' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-prev, {{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-next' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-prev, {{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-next',
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
							'{{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-prev:hover:after, {{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-next:hover:after' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-prev:hover, {{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-next:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_active_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-prev:hover, {{WRAPPER}} .naedu-service-carousel-nav .owl-nav button.owl-next:hover',
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
						'carousel_dots' => array('true'),
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
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-dot' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->add_responsive_control(
				'dots_margin',
				[
					'label' => esc_html__( 'Margin', 'education-addon' ),
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
	 * Render Profile widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings 	= $this->get_settings_for_display();
		$title 		= !empty( $settings['services_section_title'] ) ? $settings['services_section_title'] : '';
		$content 	= !empty( $settings['content'] ) ? $settings['content'] : '';
		$services 	= !empty( $settings['services'] ) ? $settings['services'] : '';

		// Carousel Data
		$carousel_items = !empty( $settings['carousel_items'] ) ? $settings['carousel_items'] : '';
		$carousel_items_tablet = !empty( $settings['carousel_items_tablet'] ) ? $settings['carousel_items_tablet'] : '';
		$carousel_items_mobile = !empty( $settings['carousel_items_mobile'] ) ? $settings['carousel_items_mobile'] : '';
		$carousel_margin = !empty( $settings['carousel_margin']['size'] ) ? $settings['carousel_margin']['size'] : '';
		$carousel_autoplay_timeout = !empty( $settings['carousel_autoplay_timeout'] ) ? $settings['carousel_autoplay_timeout'] : '';
		$carousel_loop  = ( isset( $settings['carousel_loop'] ) && ( 'true' == $settings['carousel_loop'] ) ) ? $settings['carousel_loop'] : 'false';
		$carousel_dots  = ( isset( $settings['carousel_dots'] ) && ( 'true' == $settings['carousel_dots'] ) ) ? true : false;
		$carousel_nav  = ( isset( $settings['carousel_nav'] ) && ( 'true' == $settings['carousel_nav'] ) ) ? true : false;
		$carousel_autoplay  = ( isset( $settings['carousel_autoplay'] ) && ( 'true' == $settings['carousel_autoplay'] ) ) ? true : false;
		$carousel_animate_out  = ( isset( $settings['carousel_animate_out'] ) && ( 'true' == $settings['carousel_animate_out'] ) ) ? true : false;
		$carousel_mousedrag  = ( isset( $settings['carousel_mousedrag'] ) && ( 'true' == $settings['carousel_mousedrag'] ) ) ? $settings['carousel_mousedrag'] : 'false';
		$carousel_autowidth  = ( isset( $settings['carousel_autowidth'] ) && ( 'true' == $settings['carousel_autowidth'] ) ) ? true : false;
		$carousel_autoheight  = ( isset( $settings['carousel_autoheight'] ) && ( 'true' == $settings['carousel_autoheight'] ) ) ? true : false;

		// Carousel Data's
		$carousel_loop = $carousel_loop !== 'true' ? ' data-loop="true"' : ' data-loop="false"';
		$carousel_items = $carousel_items ? ' data-items="'. $carousel_items .'"' : ' data-items="2"';
		$carousel_margin = $carousel_margin ? ' data-margin="'. $carousel_margin .'"' : ' data-margin="20"';
		$carousel_dots = $carousel_dots ? ' data-dots="true"' : ' data-dots="false"';
		$carousel_nav = $carousel_nav ? ' data-nav="true"' : ' data-nav="false"';
		$carousel_autoplay_timeout = $carousel_autoplay_timeout ? ' data-autoplay-timeout="'. $carousel_autoplay_timeout .'"' : '';
		$carousel_autoplay = $carousel_autoplay ? ' data-autoplay="true"' : '';
		$carousel_animate_out = $carousel_animate_out ? ' data-animateout="true"' : '';
		$carousel_mousedrag = $carousel_mousedrag !== 'true' ? ' data-mouse-drag="true"' : ' data-mouse-drag="false"';
		$carousel_autowidth = $carousel_autowidth ? ' data-auto-width="true"' : '';
		$carousel_autoheight = $carousel_autoheight ? ' data-auto-height="true"' : '';
		$carousel_tablet = $carousel_items_tablet ? ' data-items-tablet="'. $carousel_items_tablet .'"' : ' data-items-tablet="2"';
		$carousel_mobile = $carousel_items_mobile ? ' data-items-mobile-landscape="'. $carousel_items_mobile .'"' : ' data-items-mobile-landscape="1"';
		$carousel_small_mobile = $carousel_items_mobile ? ' data-items-mobile-portrait="'. $carousel_items_mobile .'"' : ' data-items-mobile-portrait="1"';		
		
		$title 		= $title ? '<div class="naedu-service-title"><h2>'.$title.'</h2></div>' : '';
		$content 	= $content ? '<div class="naedu-service-desc"><p>'.$content.'</p></div>' : '';
		?>
		<div class="naedu-service-wrapper">
			<div class="nich-row nich-align-items-center">
				<div class="nich-col-lg-4">
					<div class="naedu-service-section-info">
						<?php echo $title . $content; ?>
						<?php if($settings['carousel_nav']) { ?>
						<div class="naedu-service-carousel-nav">
							<div class="owl-nav">
								<button type="button" role="presentation" class="owl-prev"></button>
								<button type="button" role="presentation" class="owl-next"></button>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="nich-col-lg-8">
					<div class="owl-carousel" <?php echo $carousel_loop . $carousel_items . $carousel_margin . $carousel_dots . $carousel_nav . $carousel_autoplay_timeout . $carousel_autoplay . $carousel_animate_out . $carousel_mousedrag . $carousel_autowidth . $carousel_autoheight  . $carousel_tablet . $carousel_mobile . $carousel_small_mobile; ?>>
						<?php 
							if ( is_array( $services ) && !empty( $services ) ){ 
							foreach ( $services as $service ) {
								$image_id 	= !empty( $service['service_image']['id'] ) ? $service['service_image']['id'] : '';
								$image_url 	= wp_get_attachment_url( $image_id );
								$image_url 	= $image_url ? $image_url : Utils::get_placeholder_image_src();

								$service_link = !empty( $service['service_link']['url'] ) ? esc_url($service['service_link']['url']) : '';
								$service_link_external = !empty( $service_link['is_external'] ) ? 'target="_blank"' : '';
								$service_link_nofollow = !empty( $service_link['nofollow'] ) ? 'rel="nofollow"' : '';
								$service_link_attr = !empty( $service_link['url'] ) ?  $service_link_external.' '.$service_link_nofollow : '';

								$service_image_link = $service_link ? '<a href="'.esc_url($service_link).'" '.$service_link_attr.'><div class="naedu-service-img-wrapper"><div class="naedu-service-img" style="background-image: url('.esc_url($image_url).');"></div></div></a>' : '<div class="naedu-service-img-wrapper"><div class="naedu-service-img" style="background-image: url('.esc_url($image_url).');"></div></div>';
								$image 		= $image_url ? $service_image_link : '';

								$c_title_link = $service_link ? '<a href="'.esc_url($service_link).'" '.$service_link_attr.'>'.$service['service_title'] .'</a>' : $service['service_title'];
								$c_title 	= $service['service_title'] ? '<h3>'.$c_title_link.'</h3>' : '';
								$c_content 	= $service['service_content'] ? '<p>'.$service['service_content'].'</p>' : '';
						?>
						<div class="naedu-service">
							<?php echo $image . $c_title . $c_content; ?>
						</div>
						<?php } } ?>
					</div>					
				</div>
			</div>
		</div>

		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Services() );

} // enable & disable
