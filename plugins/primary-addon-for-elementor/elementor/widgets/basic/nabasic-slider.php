<?php
/*
 * Elementor Primary Addon for Elementor Slider Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'pafe_bw_settings' )['napafe_slider'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Primary_Addon_Slider extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'prim_basic_slider';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Slider', 'primary-addon-for-elementor' );
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
		return ['prim-basic-category'];
	}

	/**
	 * Register Primary Addon for Elementor Slider widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_slider',
			[
				'label' => __( 'Slider Options', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'vertical_dots',
			[
				'label' => esc_html__( 'Vertical Dots?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'section_alignment',
			[
				'label' => esc_html__( 'Alignment', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
			]
		);
		$repeater->add_control(
			'slider_image',
			[
				'label' => esc_html__( 'Slider Background Image', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$repeater->add_control(
			'slider_sub_title',
			[
				'label' => esc_html__( 'Slider Sub Title', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Type slide sub title here', 'primary-addon-for-elementor' ),
			]
		);
		$repeater->add_control(
			'sub_title_seperator',
			[
				'label' => esc_html__( 'Sub Title Image', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$repeater->add_control(
			'slider_title',
			[
				'label' => esc_html__( 'Slider Title', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'placeholder' => esc_html__( 'Type slide title here', 'primary-addon-for-elementor' ),
			]
		);
		$repeater->add_control(
			'slider_content',
			[
				'label' => esc_html__( 'Slider Content', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'content_image',
			[
				'label' => esc_html__( 'Content Image', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$repeater->start_controls_tabs( 'button_optn' );
		$repeater->start_controls_tab(
			'button_one',
			[
				'label' => esc_html__( 'Button One', 'primary-addon-for-elementor' ),
			]
		);
		$repeater->add_control(
			'btn_type',
			[
				'label' => __( 'Button Two Type', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'button' => esc_html__( 'Button', 'primary-addon-for-elementor' ),
					'link' => esc_html__( 'Simple Link', 'primary-addon-for-elementor' ),
				],
				'default' => 'button',
			]
		);
		$repeater->add_control(
			'btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAPAE_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
			]
		);
		$repeater->add_control(
			'btn_txt',
			[
				'label' => esc_html__( 'Button One Text', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Type your button text here', 'primary-addon-for-elementor' ),
			]
		);
		$repeater->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Button One Link', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'primary-addon-for-elementor' ),
				'show_external' => true,
				'default' => [
					'url' => '',
				],
			]
		);
		$repeater->end_controls_tab();  // end:Button One tab
		$repeater->start_controls_tab(
			'button_two',
			[
				'label' => esc_html__( 'Button Two', 'primary-addon-for-elementor' ),
			]
		);
		$repeater->add_control(
			'btn_type_two',
			[
				'label' => __( 'Button Two Type', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'button' => esc_html__( 'Button', 'primary-addon-for-elementor' ),
					'link' => esc_html__( 'Simple Link', 'primary-addon-for-elementor' ),
				],
				'default' => 'button',
			]
		);
		$repeater->add_control(
			'btn_two_icon',
			[
				'label' => esc_html__( 'Button Icon', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAPAE_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
			]
		);
		$repeater->add_control(
			'btn_two_txt',
			[
				'label' => esc_html__( 'Button Two Text', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Type your button text here', 'primary-addon-for-elementor' ),
			]
		);
		$repeater->add_control(
			'button_two_link',
			[
				'label' => esc_html__( 'Button Two Link', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'primary-addon-for-elementor' ),
				'show_external' => true,
				'default' => [
					'url' => '',
				],
			]
		);
		$repeater->end_controls_tab();  // end:Button Two tab
		$repeater->end_controls_tabs();

		$repeater->add_control(
			'image_style',
			[
				'label' => __( 'Additional Content', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'primary-addon-for-elementor' ),
					'image' => esc_html__( 'Image', 'primary-addon-for-elementor' ),
					'content' => esc_html__( 'Content', 'primary-addon-for-elementor' ),
				],
				'default' => 'none',
			]
		);
		$repeater->add_control(
			'slider_content_image',
			[
				'label' => esc_html__( 'Slider Content Image', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'image_style' => 'image',
				],
			]
		);
		$repeater->add_control(
			'slider_form',
			[
				'label' => esc_html__( 'Slider Form', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( '[mc4wp_form id="40"]', 'primary-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'image_style' => 'content',
				],
			]
		);

		$this->add_control(
			'swipeSliders_groups',
			[
				'label' => esc_html__( 'Slider Items', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'slider_title' => esc_html__( 'Item #1', 'primary-addon-for-elementor' ),
					],

				],
				'fields' =>  $repeater->get_controls(),
				'title_field' => '{{{ slider_title }}}',
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_animation',
			[
				'label' => __( 'Slider Animation', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'sub_title_animation',
			[
				'label' => esc_html__( 'Title Entrance Animation', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::ANIMATION,
			]
		);
		$this->add_control(
			'title_entrance_animation',
			[
				'label' => esc_html__( 'Title Entrance Animation', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::ANIMATION,
			]
		);
		$this->add_control(
			'content_entrance_animation',
			[
				'label' => esc_html__( 'Content Entrance Animation', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::ANIMATION,
			]
		);
		$this->add_control(
			'button_entrance_animation',
			[
				'label' => esc_html__( 'Button Entrance Animation', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::ANIMATION,
			]
		);
		$this->add_control(
			'image_entrance_animation',
			[
				'label' => esc_html__( 'Image Entrance Animation', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::ANIMATION,
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_carousel',
			[
				'label' => esc_html__( 'Carousel Options', 'primary-addon-for-elementor' ),
			]
		);

		$this->add_control(
			'carousel_loop',
			[
				'label' => esc_html__( 'Disable Loop?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'description' => esc_html__( 'Continuously moving carousel, if enabled.', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'carousel_dots',
			[
				'label' => esc_html__( 'Dots', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want Carousel Dots, enable it.', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'carousel_nav',
			[
				'label' => esc_html__( 'Navigation', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want Carousel Navigation, enable it.', 'primary-addon-for-elementor' ),
			]
		);

		$this->add_control(
			'carousel_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want to start Carousel automatically, enable it.', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'carousel_autoplay_timeout',
			[
				'label' => __( 'Auto Play Timeout', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'condition' => [
					'carousel_autoplay' => 'true',
				],
			]
		);
		$this->add_control(
			'clickable_pagi',
			[
				'label' => esc_html__( 'Pagination Dots Clickable?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want pagination dots clickable, enable it.', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'carousel_speed',
			[
				'label' => __( 'Auto Play Speed', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
			]
		);
		$this->add_control(
			'carousel_effect',
			[
				'label' => __( 'Slider Effect', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fade' => esc_html__( 'Fade', 'primary-addon-for-elementor' ),
					'slide' => esc_html__( 'Slide', 'primary-addon-for-elementor' ),
					'cube' => esc_html__( 'Cube', 'primary-addon-for-elementor' ),
					'flip' => esc_html__( 'Flip', 'primary-addon-for-elementor' ),
				],
				'default' => 'fade',
				'description' => esc_html__( 'Select your slider navigation style.', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'carousel_mousedrag',
			[
				'label' => esc_html__( 'Disable Mouse Drag?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want to disable Mouse Drag, check it.', 'primary-addon-for-elementor' ),
			]
		);
		$this->end_controls_section();// end: Section

		// Slider
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Slider', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'slider_height',
				[
					'label' => esc_html__( 'Slider Height', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
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
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .napae-swiper-slide.swiper-container' => 'height:{{SIZE}}{{UNIT}};min-height:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'content_width',
				[
					'label' => esc_html__( 'Content Width', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
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
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption' => 'max-width:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_lay_color',
				[
					'label' => esc_html__( 'Overlay Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-overlay:before' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'slider_padding',
				[
					'label' => __( 'Content Outer Space', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'content_border_radius',
				[
					'label' => __( 'Border Radius', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Content Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .banner-caption' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .banner-caption',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .banner-caption',
				]
			);
			$this->end_controls_section();// end: Section

		// Navigation
			$this->start_controls_section(
				'section_navigation_style',
				[
					'label' => esc_html__( 'Navigation', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'carousel_nav' => 'true',
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'arrow_btn_size',
				[
					'label' => esc_html__( 'Size', 'primary-addon-for-elementor' ),
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
						'{{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-next, {{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'nav_arrow_style' );
				$this->start_controls_tab(
					'nav_arrow_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'nav_arrow_color',
					[
						'label' => esc_html__( 'Arrow Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-next:before, {{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-prev:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-next, {{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-prev' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-next, {{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-prev',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'nav_arrow_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'nav_arrow_hover_color',
					[
						'label' => esc_html__( 'Arrow Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-next:hover:before, {{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-prev:hover:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-next:hover, {{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_active_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-next:hover, {{WRAPPER}} .napae-swiper-slide.swiper-container .swiper-button-prev:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab

			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Dots
			$this->start_controls_section(
				'section_dots_style',
				[
					'label' => esc_html__( 'Dots', 'primary-addon-for-elementor' ),
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
					'label' => esc_html__( 'Size', 'primary-addon-for-elementor' ),
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
						'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->add_responsive_control(
				'dots_margin',
				[
					'label' => __( 'Margin', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .swiper-pagination-bullet' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'dots_style' );
				$this->start_controls_tab(
					'dots_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'dots_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'dots_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .swiper-pagination-bullet',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'dots_active',
					[
						'label' => esc_html__( 'Active', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'dots_active_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'dots_active_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active',
					]
				);
				$this->end_controls_tab();  // end:Active tab

			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .banner-caption h1',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .banner-caption h1' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'title_padding',
				[
					'label' => __( 'Title Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption h1.banner-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Sub Title
			$this->start_controls_section(
				'section_sub_title_style',
				[
					'label' => esc_html__( 'Sub Title', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'sub_title_typography',
					'selector' => '{{WRAPPER}} .banner-caption h3.banner-sub-title',
				]
			);
			$this->add_control(
				'sub_title_color',
				[
					'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .banner-caption h3.banner-sub-title' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'sub_title_padding',
				[
					'label' => __( 'Title Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption h3.banner-sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'sub_title_margin',
				[
					'label' => __( 'Title Margin', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption h3.banner-sub-title .sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'img_width',
				[
					'label' => esc_html__( 'Image Width', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 10,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .banner-sub-title .napae-image' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Content', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'slider_content_typography',
					'selector' => '{{WRAPPER}} .banner-caption p',
				]
			);
			$this->add_control(
				'slider_content_color',
				[
					'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .banner-caption p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'info_content_padding',
				[
					'label' => __( 'Content Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Content Image
			$this->start_controls_section(
				'section_content_img_style',
				[
					'label' => esc_html__( 'Content Image', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'info_content_img_padding',
				[
					'label' => __( 'Content Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption .content-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'info_content_img_margin',
				[
					'label' => __( 'Content Margin', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption .content-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'cnt_img_width',
				[
					'label' => esc_html__( 'Image Width', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 10,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption .content-image' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

	  // Button One Style
			$this->start_controls_section(
				'section_button_style',
				[
					'label' => esc_html__( 'Button One', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_typography',
					'selector' => '{{WRAPPER}} .banner-caption .btn-one.napae-btn',
				]
			);
			$this->add_responsive_control(
				'button_one_margin',
				[
					'label' => __( 'Margin', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .btn-one.napae-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'button_min_width',
				[
					'label' => esc_html__( 'Width', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 10,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption .btn-one.napae-btn' => 'min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'button_padding',
				[
					'label' => __( 'Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption .btn-one.napae-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'button_border_radius',
				[
					'label' => __( 'Border Radius', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption .btn-one.napae-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'button_style' );
				$this->start_controls_tab(
					'button_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'button_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption .btn-one.napae-btn' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption .btn-one.napae-btn' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .banner-caption .btn-one.napae-btn',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_shadow',
						'label' => esc_html__( 'Button Shadow', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .banner-caption .btn-one.napae-btn:after',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'button_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'button_hover_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption .btn-one.napae-btn:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption .btn-one.napae-btn:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_hover_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .banner-caption .btn-one.napae-btn:hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_hover_shadow',
						'label' => esc_html__( 'Button Shadow', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .banner-caption .btn-one.napae-btn:hover:after',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs

			$this->end_controls_section();// end: Section

		// Button Two
			$this->start_controls_section(
				'section_button_two_style',
				[
					'label' => esc_html__( 'Button Two', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_two_typography',
					'selector' => '{{WRAPPER}} .banner-caption .btn-two.napae-btn',
				]
			);
			$this->add_responsive_control(
				'button_two_margin',
				[
					'label' => __( 'Margin', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .btn-two.napae-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'button_two_min_width',
				[
					'label' => esc_html__( 'Width', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 10,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption .btn-two.napae-btn' => 'min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'button_two_padding',
				[
					'label' => __( 'Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption .btn-two.napae-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'button_two_border_radius',
				[
					'label' => __( 'Border Radius', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-caption .btn-two.napae-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'button_two_style' );
				$this->start_controls_tab(
					'button_two_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'button_two_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption .btn-two.napae-btn' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_two_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption .btn-two.napae-btn' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_two_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .banner-caption .btn-two.napae-btn',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btnt_shadow',
						'label' => esc_html__( 'Button Shadow', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .banner-caption .btn-two.napae-btn:after',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'button_two_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'button_two_hover_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption .btn-two.napae-btn:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_two_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption .btn-two.napae-btn:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_two_hover_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .banner-caption .btn-two.napae-btn:hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btnt_hover_shadow',
						'label' => esc_html__( 'Button Shadow', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .banner-caption .btn-two.napae-btn:hover:after',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Link
			$this->start_controls_section(
				'section_lnk_style',
				[
					'label' => esc_html__( 'Link', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'link_margin',
				[
					'label' => __( 'Margin', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
					'name' => 'lnk_typography',
					'selector' => '{{WRAPPER}} .napae-link',
				]
			);
			$this->start_controls_tabs( 'lnk_style' );
				$this->start_controls_tab(
					'lnk_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'lnk_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-link' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'lnk_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'lnk_hover_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-link:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'lnk_bg_hover_color',
					[
						'label' => esc_html__( 'Line Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-link span:after' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Form
			$this->start_controls_section(
				'section_form_style',
				[
					'label' => esc_html__( 'Form', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'form_bg',
				[
					'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .slider-form' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'form_padding',
				[
					'label' => __( 'Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .slider-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'form_border_radius',
				[
					'label' => __( 'Border Radius', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .slider-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'form_section_border',
					'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .slider-form',
				]
			);
			$this->add_responsive_control(
				'form_section_width',
				[
					'label' => esc_html__( 'Form Width', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1500,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .slider-form' => 'max-width:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'form_typography',
					'selector' => '{{WRAPPER}} .slider-form input[type="text"],
					{{WRAPPER}} .slider-form input[type="email"],
					{{WRAPPER}} .slider-form input[type="date"],
					{{WRAPPER}} .slider-form input[type="time"],
					{{WRAPPER}} .slider-form input[type="number"],
					{{WRAPPER}} .slider-form textarea,
					{{WRAPPER}} .slider-form select,
					{{WRAPPER}} .slider-form .form-control,
					{{WRAPPER}} .slider-form .nice-select',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'form_border',
					'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .slider-form input[type="text"],
					{{WRAPPER}} .slider-form input[type="email"],
					{{WRAPPER}} .slider-form input[type="date"],
					{{WRAPPER}} .slider-form input[type="time"],
					{{WRAPPER}} .slider-form input[type="number"],
					{{WRAPPER}} .slider-form textarea,
					{{WRAPPER}} .slider-form select,
					{{WRAPPER}} .slider-form .form-control,
					{{WRAPPER}} .slider-form .nice-select',
				]
			);
			$this->add_control(
				'placeholder_text_color',
				[
					'label' => __( 'Placeholder Text Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .slider-form input:not([type="submit"])::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .slider-form input:not([type="submit"])::-moz-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .slider-form input:not([type="submit"])::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .slider-form input:not([type="submit"])::-o-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .slider-form textarea::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .slider-form textarea::-moz-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .slider-form textarea::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .slider-form textarea::-o-placeholder' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->add_control(
				'text_color',
				[
					'label' => __( 'Text Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .slider-form input[type="text"],
						{{WRAPPER}} .slider-form input[type="email"],
						{{WRAPPER}} .slider-form input[type="date"],
						{{WRAPPER}} .slider-form input[type="time"],
						{{WRAPPER}} .slider-form input[type="number"],
						{{WRAPPER}} .slider-form textarea,
						{{WRAPPER}} .slider-form select,
						{{WRAPPER}} .slider-form .form-control,
						{{WRAPPER}} .slider-form .nice-select' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Submit Button
			$this->start_controls_section(
				'submit_button_style',
				[
					'label' => esc_html__( 'Form Submit Button', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'submit_button_typography',
					'selector' => '{{WRAPPER}} .slider-form input[type="submit"]',
				]
			);
			$this->add_responsive_control(
				'submit_btn_width',
				[
					'label' => esc_html__( 'Width', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
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
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .slider-form input[type="submit"]' => 'min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'submit_btn_margin',
				[
					'label' => __( 'Margin', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .slider-form input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'submit_button_border_radius',
				[
					'label' => __( 'Border Radius', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .slider-form input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'ssubmit_button_style' );
				$this->start_controls_tab(
					'submit_button_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'submit_button_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .slider-form input[type="submit"]' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'submit_button_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .slider-form input[type="submit"]' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'submit_button_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .slider-form input[type="submit"]',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'submit_button_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'submit_button_hover_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .slider-form input[type="submit"]:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'submit_button_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .slider-form input[type="submit"]:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'submit_button_hover_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .slider-form input[type="submit"]:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Slider widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$vertical_dots = !empty( $settings['vertical_dots'] ) ? $settings['vertical_dots'] : '';
		$carousel_effect = !empty( $settings['carousel_effect'] ) ? $settings['carousel_effect'] : '';

		// Carousel Options
		$swipeSliders_groups = !empty( $settings['swipeSliders_groups'] ) ? $settings['swipeSliders_groups'] : [];
		$carousel_autoplay_timeout = !empty( $settings['carousel_autoplay_timeout'] ) ? $settings['carousel_autoplay_timeout'] : '';
		$carousel_speed = !empty( $settings['carousel_speed'] ) ? $settings['carousel_speed'] : '';

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
		$carousel_effect = (isset($settings['carousel_effect'])) ? ' data-effect="'.$carousel_effect.'"' : '';
		$carousel_mousedrag = $carousel_mousedrag !== 'true' ? ' data-mousedrag="true"' : ' data-mousedrag="false"';

		$content_entrance_animation = !empty( $settings['content_entrance_animation'] ) ? $settings['content_entrance_animation'] : '';
		$sub_title_animation = !empty( $settings['sub_title_animation'] ) ? $settings['sub_title_animation'] : '';
		$title_entrance_animation = !empty( $settings['title_entrance_animation'] ) ? $settings['title_entrance_animation'] : '';
		$button_entrance_animation = !empty( $settings['button_entrance_animation'] ) ? $settings['button_entrance_animation'] : '';
		$image_entrance_animation = !empty( $settings['image_entrance_animation'] ) ? $settings['image_entrance_animation'] : '';

		// Animation
		$content_entrance_animation = $content_entrance_animation ? $content_entrance_animation : 'fadeInDown';
		$sub_title_animation = $sub_title_animation ? $sub_title_animation : 'fadeInDown';
		$title_entrance_animation = $title_entrance_animation ? $title_entrance_animation : 'fadeInDown';
		$button_entrance_animation = $button_entrance_animation ? $button_entrance_animation : 'fadeInDown';
		$image_entrance_animation = $image_entrance_animation ? $image_entrance_animation : 'fadeInDown';

		if ($vertical_dots) {
			$dot_class = ' vertical-dots';
		} else {
			$dot_class = '';
		}

		// Turn output buffer on
		ob_start();

		 ?>
<div class="swiper-container napae-swiper-slide swiper-slides swiper-keyboard<?php echo $dot_class; ?>" <?php echo $carousel_loop . $carousel_autoplay . $carousel_effect . $carousel_speed . $clickable_pagi . $carousel_mousedrag; ?> data-swiper="container">
  <div class="swiper-wrapper">

    <?php
			if ( is_array( $swipeSliders_groups ) && !empty( $swipeSliders_groups ) ){
				foreach ( $swipeSliders_groups as $each_item ) {

					$image_url = wp_get_attachment_url( $each_item['slider_image']['id'] );
					$section_alignment = !empty( $each_item['section_alignment'] ) ? $each_item['section_alignment'] : '';
					$slider_sub_title = !empty( $each_item['slider_sub_title'] ) ? $each_item['slider_sub_title'] : '';
					$sub_title_seperator = wp_get_attachment_url( $each_item['sub_title_seperator']['id'] );
					$slider_title = !empty( $each_item['slider_title'] ) ? $each_item['slider_title'] : '';
					$slider_content = !empty( $each_item['slider_content'] ) ? $each_item['slider_content'] : '';
					$content_image = wp_get_attachment_url( $each_item['content_image']['id'] );
					$image_style = !empty( $each_item['image_style'] ) ? $each_item['image_style'] : '';
					$slider_form = !empty( $each_item['slider_form'] ) ? $each_item['slider_form'] : '';
					$slider_content_image = !empty( $each_item['slider_content_image']['id'] ) ? $each_item['slider_content_image']['id'] : '';
					$content_image_url = wp_get_attachment_url( $slider_content_image );

					$btn_type = !empty( $each_item['btn_type'] ) ? $each_item['btn_type'] : '';
					$btn_icon = !empty( $each_item['btn_icon'] ) ? $each_item['btn_icon'] : '';
					$button_text = !empty( $each_item['btn_txt'] ) ? $each_item['btn_txt'] : '';
					$button_link = !empty( $each_item['button_link']['url'] ) ? $each_item['button_link']['url'] : '';
					$button_link_external = !empty( $each_item['button_link']['is_external'] ) ? 'target="_blank"' : '';
					$button_link_nofollow = !empty( $each_item['button_link']['nofollow'] ) ? 'rel="nofollow"' : '';
					$button_link_attr = !empty( $button_link ) ?  $button_link_external.' '.$button_link_nofollow : '';

					$btn_type_two = !empty( $each_item['btn_type_two'] ) ? $each_item['btn_type_two'] : '';
					$btn_two_icon = !empty( $each_item['btn_two_icon'] ) ? $each_item['btn_two_icon'] : '';
					$button_two_text = !empty( $each_item['btn_two_txt'] ) ? $each_item['btn_two_txt'] : '';
					$button_two_link = !empty( $each_item['button_two_link']['url'] ) ? $each_item['button_two_link']['url'] : '';
					$button_two_link_external = !empty( $each_item['button_two_link']['is_external'] ) ? 'target="_blank"' : '';
					$button_two_link_nofollow = !empty( $each_item['button_two_link']['nofollow'] ) ? 'rel="nofollow"' : '';
					$button_two_link_attr = !empty( $button_two_link ) ?  $button_two_link_external.' '.$button_two_link_nofollow : '';

					$title_seperator = $sub_title_seperator ? '<span class="napae-image one"><img src="'.esc_url($sub_title_seperator).'" alt="Seperator"></span>' : '';
					$title_seperator_two = $sub_title_seperator ? '<span class="napae-image"><img src="'.esc_url($sub_title_seperator).'" alt="Seperator"></span>' : '';
					$sub_title = $slider_sub_title ? ' <h3 class="banner-sub-title animated" data-animation="'.esc_attr($sub_title_animation).'">'.$title_seperator.'<span class="sub-title">'.$slider_sub_title.'</span>'.$title_seperator_two.'</h3>' : '';

					$slide_title = $slider_title ? ' <h1 class="banner-title animated" data-animation="'.esc_attr($title_entrance_animation).'">'.$slider_title.'</h1>' : '';
					$slide_content = $slider_content ? '<p class="animated" data-animation="'.esc_attr($content_entrance_animation).'">'.$slider_content.'</p>' : '';
					$content_image = $content_image ? '<div class="content-image animated" data-animation="'.esc_attr($image_entrance_animation).'"><img src="'.esc_url($content_image).'" alt="'.esc_attr($slider_title).'"></div>' : '';
					$icon = $btn_icon ? ' <i class="'.esc_attr($btn_icon).'" aria-hidden="true"></i>' : '';
					$icon_two = $btn_two_icon ? ' <i class="'.esc_attr($btn_two_icon).'" aria-hidden="true"></i>' : '';

					if($btn_type === 'link') {
						$btn_class = ' napae-link';
					} else {
						$btn_class = ' napae-btn';
					}

					if($btn_type_two === 'link') {
						$btn_two_class = ' napae-link';
					} else {
						$btn_two_class = ' napae-btn black-btn';
					}

					$button_one = $button_link ? '<a href="'.esc_url($button_link).'" '.$button_link_attr.' class="btn-one'.$btn_class.'"><span>'. esc_html($button_text) .'</span>'.$icon.'</a>' : '';
					$button_two = $button_two_link ? '<a href="'.esc_url($button_two_link).'" '.$button_two_link_attr.' class="btn-two'.$btn_two_class.'"><span>'. esc_html($button_two_text) .'</span>'.$icon_two.'</a>' : '';

					$button_actual = ($button_one || $button_two) ? '<div class="napae-btn-wrap animated" data-animation="'.esc_attr($button_entrance_animation).'">'.$button_one.$button_two.'</div>' : '';

					$image = $content_image_url ? '<div class="napae-image animated" data-animation="'.esc_attr($image_entrance_animation).'"><img src="'.esc_url($content_image_url).'" alt="Slider"></div>' : '';
					$slider_form = $slider_form ? '<div class="slider-form napae-form animated" data-animation="'.esc_attr($image_entrance_animation).'">'.do_shortcode( $slider_form ).'</div>' : '';

					if ($section_alignment === 'center') {
						$align_class = ' center-align';
					} elseif ($section_alignment === 'right') {
						$align_class = ' right-align';
					} else {
						$align_class = ' left-align';
					}

					if ($image_style === 'content') {
						$image_content = $slider_form;
					} else {
						$image_content = $image;
					}

					if ($image_style === 'content' || $image_style === 'image') {
						$col_class = 'nich-col-md-6';
					} else {
						$col_class = 'nich-col-md-12';
					}
					?>
					<div class="swiper-slide napae-banner napae-overlay" style="background-image: url(<?php echo esc_url($image_url); ?>);"<?php echo esc_attr( $carousel_autoplay_timeout ); ?>>
	          <div class="napae-table-wrap">
			        <div class="napae-align-wrap">
			          <div class="banner-container">
			          	<div class="nich-row nich-align-items-center">
			          		<div class="<?php echo esc_attr($col_class); ?>">
					            <div class="banner-caption<?php echo esc_attr($align_class); ?>">
					            	<?php echo $sub_title.$slide_title.$slide_content.$content_image.$button_actual; ?>
				            	</div>
			            	</div>
			            	<?php if ($image_style === 'content' || $image_style === 'image') { ?>
			          		<div class="nich-col-md-6">
					            <div class="banner-image"><?php echo $image_content; ?></div>
				            </div>
			            	<?php } ?>
			          	</div>
			          </div>
			        </div>
			      </div>
	        </div>
				<?php }
			} ?>
		</div>
		<?php if ($carousel_nav){ ?>
			<div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    <?php } if ($carousel_dots) { ?>
    <div class="swiper-pagination"></div>
    <?php } ?>
    </div>
		<?php
		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Primary_Addon_Slider() );

} // enable & disable
