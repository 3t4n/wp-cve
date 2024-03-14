<?php
/*
 * Elementor Charity Addon for Elementor Slider Widget
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Unique_Slider extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_unique_slider_cause';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Slider', 'charity-addon-for-elementor' );
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
			return ['nacharity-unique-category'];
		}

		/**
		 * Register Charity Addon for Elementor Slider widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$charity = get_posts( 'post_type="give_forms"&numberposts=-1' );
	    $CharityID = array();
	    if ( $charity ) {
	      foreach ( $charity as $form ) {
	        $CharityID[ $form->ID ] = $form->post_title;
	      }
	    } else {
	      $CharityID[ __( 'No ID\'s found', 'charity-addon-for-elementor' ) ] = 0;
	    }

			$this->start_controls_section(
				'section_slider',
				[
					'label' => __( 'Slider Options', 'charity-addon-for-elementor' ),
				]
			);

			$repeater = new Repeater();
			$repeater->add_control(
				'section_alignment',
				[
					'label' => esc_html__( 'Alignment', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'charity-addon-for-elementor' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'charity-addon-for-elementor' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'charity-addon-for-elementor' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'left',
				]
			);
			$repeater->add_control(
				'toggle_align',
				[
					'label' => esc_html__( 'Toggle Align', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$repeater->add_control(
				'hide_content',
				[
					'label' => esc_html__( 'Hide Content', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$repeater->add_control(
				'hide_form',
				[
					'label' => esc_html__( 'Hide Form', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$repeater->add_control(
				'slider_image',
				[
					'label' => esc_html__( 'Slider Background Image', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::MEDIA,
				]
			);
			$repeater->add_control(
				'slider_title',
				[
					'label' => esc_html__( 'Slider Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'placeholder' => esc_html__( 'Type slide title here', 'charity-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'slider_content',
				[
					'label' => esc_html__( 'Slider Content', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXTAREA,
					'label_block' => true,
				]
			);
			$repeater->start_controls_tabs( 'button_optn' );
			$repeater->start_controls_tab(
				'button_one',
				[
					'label' => esc_html__( 'Button One', 'charity-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'btn_txt',
				[
					'label' => esc_html__( 'Button One Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'placeholder' => esc_html__( 'Type your button text here', 'charity-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
			  'btn_icon',
			  [
			    'label' => esc_html__( 'Button Icon', 'charity-addon-for-elementor' ),
			    'type' => Controls_Manager::ICON,
			    'options' => NACEP_Controls_Helper_Output::get_include_icons(),
			    'frontend_available' => true,
			    'default' => 'fa fa-long-arrow-right',
			  ]
			);
			$repeater->add_control(
				'button_link',
				[
					'label' => esc_html__( 'Button One Link', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::URL,
					'placeholder' => __( 'https://your-link.com', 'charity-addon-for-elementor' ),
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
					'label' => esc_html__( 'Button Two', 'charity-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'btn_two_txt',
				[
					'label' => esc_html__( 'Button Two Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'placeholder' => esc_html__( 'Type your button text here', 'charity-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
			  'btn_two_icon',
			  [
			    'label' => esc_html__( 'Button Icon', 'charity-addon-for-elementor' ),
			    'type' => Controls_Manager::ICON,
			    'options' => NACEP_Controls_Helper_Output::get_include_icons(),
			    'frontend_available' => true,
			    'default' => 'fa fa-long-arrow-right',
			  ]
			);
			$repeater->add_control(
				'button_two_link',
				[
					'label' => esc_html__( 'Button Two Link', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::URL,
					'placeholder' => __( 'https://your-link.com', 'charity-addon-for-elementor' ),
					'show_external' => true,
					'default' => [
						'url' => '',
					],
				]
			);
			$repeater->end_controls_tab();  // end:Button Two tab
			$repeater->end_controls_tabs();
			$repeater->add_control(
				'cause_id',
				[
					'label' => __( 'Select a Donation', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => [],
					'options' => $CharityID,
					'description' => esc_html__( 'You must select a Form!', 'charity-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'goal_title',
				[
					'label' => esc_html__( 'Goal Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Goal', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'remaing_title',
				[
					'label' => esc_html__( 'Remaing Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Remaing', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'donor_title',
				[
					'label' => esc_html__( 'Donors Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Donors', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'swipeSliders_groups',
				[
					'label' => esc_html__( 'Slider Items', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::REPEATER,
					'default' => [
						[
							'slider_title' => esc_html__( 'Item #1', 'charity-addon-for-elementor' ),
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
					'label' => __( 'Slider Animation', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title_entrance_animation',
				[
					'label' => esc_html__( 'Title Entrance Animation', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::ANIMATION,
				]
			);
			$this->add_control(
				'content_entrance_animation',
				[
					'label' => esc_html__( 'Content Entrance Animation', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::ANIMATION,
				]
			);
			$this->add_control(
				'button_entrance_animation',
				[
					'label' => esc_html__( 'Button Entrance Animation', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::ANIMATION,
				]
			);
			$this->add_control(
				'box_entrance_animation',
				[
					'label' => esc_html__( 'Box Entrance Animation', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::ANIMATION,
				]
			);
			$this->end_controls_section();// end: Section

			// Circle Progress
				$this->start_controls_section(
					'section_bar',
					[
						'label' => esc_html__( 'Circle Progress Bar', 'charity-addon-for-elementor' ),
						'condition' => [
							'cause_style' => array('two'),
						],
					]
				);
				$this->add_control(
					'bar_color',
					[
						'label' => esc_html__( 'Progress Bar Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
					]
				);
				$this->add_control(
					'bar_fill_color',
					[
						'label' => esc_html__( 'Progress Bar Fill Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
					]
				);
				$this->add_control(
					'bar_bg_color',
					[
						'label' => esc_html__( 'Progress Bar Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .circle-progressbar canvas' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'reverse',
					[
						'label' => esc_html__( 'Reverse Animation?', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
						'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
						'return_value' => 'true',
					]
				);
				$this->add_responsive_control(
					'size',
					[
						'label' => esc_html__( 'Canvas Size', 'charity-elementor-addon' ),
						'type' => Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 1000,
						'step' => 1,
						'default' => 150,
					]
				);
				$this->add_responsive_control(
					'thickness',
					[
						'label' => esc_html__( 'Thickness', 'charity-elementor-addon' ),
						'type' => Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 100,
						'step' => 1,
						'default' => 10,
					]
				);
				$this->end_controls_section();// end: Section

			// Carousel
				$this->start_controls_section(
					'section_carousel',
					[
						'label' => esc_html__( 'Carousel Options', 'charity-addon-for-elementor' ),
					]
				);

				$this->add_control(
					'carousel_loop',
					[
						'label' => esc_html__( 'Disable Loop?', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
						'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
						'return_value' => 'true',
						'description' => esc_html__( 'Continuously moving carousel, if enabled.', 'charity-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'carousel_dots',
					[
						'label' => esc_html__( 'Dots', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
						'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
						'return_value' => 'true',
						'description' => esc_html__( 'If you want Carousel Dots, enable it.', 'charity-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'carousel_nav',
					[
						'label' => esc_html__( 'Navigation', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
						'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
						'return_value' => 'true',
						'description' => esc_html__( 'If you want Carousel Navigation, enable it.', 'charity-addon-for-elementor' ),
					]
				);

				$this->add_control(
					'carousel_autoplay',
					[
						'label' => esc_html__( 'Autoplay', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
						'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
						'return_value' => 'true',
						'description' => esc_html__( 'If you want to start Carousel automatically, enable it.', 'charity-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'carousel_autoplay_timeout',
					[
						'label' => __( 'Auto Play Timeout', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::NUMBER,
						'condition' => [
							'carousel_autoplay' => 'true',
						],
					]
				);
				$this->add_control(
					'clickable_pagi',
					[
						'label' => esc_html__( 'Pagination Dots Clickable?', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
						'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
						'return_value' => 'true',
						'description' => esc_html__( 'If you want pagination dots clickable, enable it.', 'charity-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'carousel_speed',
					[
						'label' => __( 'Auto Play Speed', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::NUMBER,
					]
				);
				$this->add_control(
					'carousel_effect',
					[
						'label' => __( 'Slider Effect', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'fade' => esc_html__( 'Fade', 'charity-addon-for-elementor' ),
							'slide' => esc_html__( 'Slide', 'charity-addon-for-elementor' ),
							'cube' => esc_html__( 'Cube', 'charity-addon-for-elementor' ),
							'coverflow' => esc_html__( 'Coverflow', 'charity-addon-for-elementor' ),
						],
						'default' => 'fade',
						'description' => esc_html__( 'Select your slider navigation style.', 'charity-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'carousel_mousedrag',
					[
						'label' => esc_html__( 'Disable Mouse Drag?', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
						'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
						'return_value' => 'true',
						'description' => esc_html__( 'If you want to disable Mouse Drag, check it.', 'charity-addon-for-elementor' ),
					]
				);
				$this->end_controls_section();// end: Section

			// Slider
				$this->start_controls_section(
					'sectn_style',
					[
						'label' => esc_html__( 'Slider', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_responsive_control(
					'slider_height',
					[
						'label' => esc_html__( 'Slider Height', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .nacep-swiper-slide.swiper-container' => 'height:{{SIZE}}{{UNIT}};min-height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'content_width',
					[
						'label' => esc_html__( 'Content Width', 'charity-addon-for-elementor' ),
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
						'label' => esc_html__( 'Overlay Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-overlay:before' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_responsive_control(
					'content_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
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
						'label' => __( 'Content Outer Space', 'charity-addon-for-elementor' ),
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
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
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
						'label' => esc_html__( 'Content Background Color', 'charity-addon-for-elementor' ),
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
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .banner-caption',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .banner-caption',
					]
				);
				$this->end_controls_section();// end: Section

			// Navigation
				$this->start_controls_section(
					'section_navigation_style',
					[
						'label' => esc_html__( 'Navigation', 'charity-addon-for-elementor' ),
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
						'label' => esc_html__( 'Size', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-next, {{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->start_controls_tabs( 'nav_arrow_style' );
					$this->start_controls_tab(
						'nav_arrow_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'nav_arrow_color',
						[
							'label' => esc_html__( 'Arrow Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-next:before, {{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-prev:before' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'nav_arrow_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-next, {{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-prev' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'nav_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-next, {{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-prev',
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'nav_arrow_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'nav_arrow_hover_color',
						[
							'label' => esc_html__( 'Arrow Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-next:hover:before, {{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-prev:hover:before' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'nav_arrow_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-next:hover, {{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'nav_active_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-next:hover, {{WRAPPER}} .nacep-swiper-slide.swiper-container .swiper-button-prev:hover',
						]
					);
					$this->end_controls_tab();  // end:Hover tab

				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Dots
				$this->start_controls_section(
					'section_dots_style',
					[
						'label' => esc_html__( 'Dots', 'charity-addon-for-elementor' ),
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
						'label' => esc_html__( 'Size', 'charity-addon-for-elementor' ),
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
						'label' => __( 'Margin', 'charity-addon-for-elementor' ),
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
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'dots_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
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
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .swiper-pagination-bullet',
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'dots_active',
						[
							'label' => esc_html__( 'Active', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'dots_active_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
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
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
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
						'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
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
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption h1' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Content
				$this->start_controls_section(
					'section_content_style',
					[
						'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
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
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .banner-caption p' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Button
				$this->start_controls_section(
					'section_btn_style',
					[
						'label' => esc_html__( 'Button One', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'btn_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .btn-one.nacep-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'btn_margin',
					[
						'label' => __( 'Margin', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .btn-one.nacep-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'btn_border_radius',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .btn-one.nacep-btn:before, {{WRAPPER}} .btn-one.nacep-btn:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'btn_width',
					[
						'label' => esc_html__( 'Button Width', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .btn-one.nacep-btn:before, {{WRAPPER}} .btn-one.nacep-btn:after' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'btn_line_height',
					[
						'label' => esc_html__( 'Button Line Height', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .btn-one.nacep-btn' => 'line-height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'btn_typography',
						'selector' => '{{WRAPPER}} .btn-one.nacep-btn',
					]
				);
				$this->add_responsive_control(
					'btn_icon_size',
					[
						'label' => esc_html__( 'Icon Size', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1500,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .btn-one.nacep-btn i' => 'font-size:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->start_controls_tabs( 'btn_style' );
					$this->start_controls_tab(
						'btn_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'btn_color',
						[
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-one.nacep-btn' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_icon_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-one.nacep-btn i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-one.nacep-btn:before' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .btn-one.nacep-btn:before',
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'btn_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'btn_hover_color',
						[
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-one.nacep-btn:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_icon_hover_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-one.nacep-btn:hover i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-one.nacep-btn:hover:before' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_hover_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .btn-one.nacep-btn:hover:before',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
					$this->start_controls_tab(
						'btn_active',
						[
							'label' => esc_html__( 'Active', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'btn_active_color',
						[
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-one.nacep-btn:active, {{WRAPPER}} .btn-one.nacep-btn:focus' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_icon_active_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-one.nacep-btn:active i, {{WRAPPER}} .btn-one.nacep-btn:focus i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_active_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-one.nacep-btn:active:after, {{WRAPPER}} .btn-one.nacep-btn:focus:after' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_active_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .btn-one.nacep-btn:active:after, {{WRAPPER}} .btn-one.nacep-btn:focus:after',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Button Two
				$this->start_controls_section(
					'section_btnt_style',
					[
						'label' => esc_html__( 'Button Two', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'btnt_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .btn-two.nacep-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'btnt_margin',
					[
						'label' => __( 'Margin', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .btn-two.nacep-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'btnt_border_radius',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .btn-two.nacep-btn:before, {{WRAPPER}} .btn-two.nacep-btn:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'btnt_width',
					[
						'label' => esc_html__( 'Button Width', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .btn-two.nacep-btn:before, {{WRAPPER}} .btn-two.nacep-btn:after' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'btnt_line_height',
					[
						'label' => esc_html__( 'Button Line Height', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .btn-two.nacep-btn' => 'line-height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'btnt_typography',
						'selector' => '{{WRAPPER}} .btn-two.nacep-btn',
					]
				);
				$this->add_responsive_control(
					'btnt_icon_size',
					[
						'label' => esc_html__( 'Icon Size', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1500,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .btn-two.nacep-btn i' => 'font-size:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->start_controls_tabs( 'btnt_style' );
					$this->start_controls_tab(
						'btnt_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'btnt_color',
						[
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-two.nacep-btn' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btnt_icon_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-two.nacep-btn i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btnt_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-two.nacep-btn:before' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btnt_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .btn-two.nacep-btn:before',
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'btnt_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'btnt_hover_color',
						[
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-two.nacep-btn:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btnt_icon_hover_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-two.nacep-btn:hover i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btnt_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-two.nacep-btn:hover:before' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btnt_hover_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .btn-two.nacep-btn:hover:before',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
					$this->start_controls_tab(
						'btnt_active',
						[
							'label' => esc_html__( 'Active', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'btnt_active_color',
						[
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-two.nacep-btn:active, {{WRAPPER}} .btn-two.nacep-btn:focus' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btnt_icon_active_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-two.nacep-btn:active i, {{WRAPPER}} .btn-two.nacep-btn:focus i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btnt_bg_active_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .btn-two.nacep-btn:active:after, {{WRAPPER}} .btn-two.nacep-btn:focus:after' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btnt_active_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .btn-two.nacep-btn:active:after, {{WRAPPER}} .btn-two.nacep-btn:focus:after',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Box
				$this->start_controls_section(
					'box_style',
					[
						'label' => esc_html__( 'Box', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'box_border_radius',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'box_section_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'box_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'box_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-cause-list-item',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'box_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-cause-list-item',
					]
				);
				$this->end_controls_section();// end: Section

			// Title
				$this->start_controls_section(
					'dona_title_style',
					[
						'label' => esc_html__( 'Box Title', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'dona_title_typography',
						'selector' => '{{WRAPPER}} .nacep-cause-list-item h3',
					]
				);
				$this->start_controls_tabs( 'dona_title_sle' );
					$this->start_controls_tab(
						'dona_title_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'dona_title_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-cause-list-item h3, {{WRAPPER}} .nacep-cause-list-item h3 a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'dona_title_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'dona_title_hover_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-cause-list-item h3 a:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Content
				$this->start_controls_section(
					'dona_content_style',
					[
						'label' => esc_html__( 'Box Content', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'dona_content_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'dona_content_typography',
						'selector' => '{{WRAPPER}} .nacep-cause-list-item p',
					]
				);
				$this->add_control(
					'dona_content_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-cause-list-item p' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Meta
				$this->start_controls_section(
					'section_meta_style',
					[
						'label' => esc_html__( 'Metas', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'meta_bg_color',
					[
						'label' => esc_html__( 'Meta Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .cause-donate-info' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_responsive_control(
					'meta_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .cause-donate-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Title Typography', 'charity-addon-for-elementor' ),
						'name' => 'meta_title_typography',
						'selector' => '{{WRAPPER}} .cause-donate-info h3',
					]
				);
				$this->add_control(
					'meta_color',
					[
						'label' => esc_html__( 'Title Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .cause-donate-info h3' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Content Typography', 'charity-addon-for-elementor' ),
						'name' => 'meta_cnt_typography',
						'selector' => '{{WRAPPER}} .cause-donate-info p',
					]
				);
				$this->add_control(
					'meta_cnt_color',
					[
						'label' => esc_html__( 'Content Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .cause-donate-info p' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Progress Bar
				$this->start_controls_section(
					'bar_style',
					[
						'label' => esc_html__( 'Progress Bar', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Tooltip Typography', 'charity-addon-for-elementor' ),
						'name' => 'bar_typography',
						'selector' => '{{WRAPPER}} .progress-counter',
					]
				);
				$this->add_control(
					'tip_color',
					[
						'label' => esc_html__( 'Tooltip Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .progress-counter' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'tip_bg_color',
					[
						'label' => esc_html__( 'Tooltip Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .progress-counter' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .progress-counter:after' => 'border-bottom-color: {{VALUE}};',
						],
					]
				);
				$this->start_controls_tabs( 'prog_style' );
					$this->start_controls_tab(
						'prog_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'prog_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-cause-bar' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'bar_shadow',
							'label' => esc_html__( 'Box Shadow', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-cause-bar',
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'prog_hover',
						[
							'label' => esc_html__( 'Active', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'bar_gradient_background',
							'label' => __( 'Background', 'events-addon-for-elementor' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .nacep-cause-bar .progress-bar',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Button
				$this->start_controls_section(
					'dona_btn_style',
					[
						'label' => esc_html__( 'Button', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'dona_btn_border_radius',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} a.nacep-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'dona_btn_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} a.nacep-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'dona_btn_width',
					[
						'label' => esc_html__( 'Button Width', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} a.nacep-btn' => 'min-width:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'dona_btn_typography',
						'selector' => '{{WRAPPER}} a.nacep-btn',
					]
				);
				$this->start_controls_tabs( 'dona_btn_sle' );
					$this->start_controls_tab(
						'dona_btn_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'dona_btn_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a.nacep-btn' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'dona_btn_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a.nacep-btn' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'dona_btn_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} a.nacep-btn',
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'dona_btn_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'dona_btn_hover_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a.nacep-btn:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'dona_btn_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} a.nacep-btn:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'dona_btn_hover_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} a.nacep-btn:hover',
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
			$title_entrance_animation = !empty( $settings['title_entrance_animation'] ) ? $settings['title_entrance_animation'] : '';
			$button_entrance_animation = !empty( $settings['button_entrance_animation'] ) ? $settings['button_entrance_animation'] : '';
			$box_entrance_animation = !empty( $settings['box_entrance_animation'] ) ? $settings['box_entrance_animation'] : '';

			// Animation
			$content_entrance_animation = $content_entrance_animation ? $content_entrance_animation : 'fadeInDown';
			$title_entrance_animation = $title_entrance_animation ? $title_entrance_animation : 'fadeInDown';
			$button_entrance_animation = $button_entrance_animation ? $button_entrance_animation : 'fadeInDown';
			$box_entrance_animation = $box_entrance_animation ? $box_entrance_animation : 'fadeInDown';

			$bar_color 				= !empty( $settings['bar_color'] ) ? $settings['bar_color'] : '';
			$bar_fill_color 	= !empty( $settings['bar_fill_color'] ) ? $settings['bar_fill_color'] : '';
			$reverse 				  = !empty( $settings['reverse'] ) ? $settings['reverse'] : '';
			$size 						= !empty( $settings['size'] ) ? $settings['size'] : '';
			$thickness 						= !empty( $settings['thickness'] ) ? $settings['thickness'] : '';

			$bar_color = $bar_color ? ' data-color="'.$bar_color.'"' : '';
			$bar_fill_color = $bar_fill_color ? ' data-fill="'.$bar_fill_color.'"' : '';
			$reverse = $reverse ? ' data-reverse="true"' : ' data-reverse="false"';
			$size = $size ? ' data-size="'.$size.'"' : '';
			$thickness = $thickness ? ' data-thickness="'.$thickness.'"' : '';

			// Turn output buffer on
			ob_start();
			?>
			<div class="swiper-container nacep-swiper-slide nacep-swiper-charity swiper-slides swiper-keyboard" <?php echo $carousel_loop . $carousel_autoplay . $carousel_effect . $carousel_speed . $clickable_pagi . $carousel_mousedrag; ?> data-swiper="container">
			  <div class="swiper-wrapper">

	    	<?php if ( is_array( $swipeSliders_groups ) && !empty( $swipeSliders_groups ) ){
					foreach ( $swipeSliders_groups as $each_item ) {

						$image_url = wp_get_attachment_url( $each_item['slider_image']['id'] );
						$toggle_align = !empty( $each_item['toggle_align'] ) ? $each_item['toggle_align'] : '';
						$hide_content = !empty( $each_item['hide_content'] ) ? $each_item['hide_content'] : '';
						$hide_form = !empty( $each_item['hide_form'] ) ? $each_item['hide_form'] : '';
						$section_alignment = !empty( $each_item['section_alignment'] ) ? $each_item['section_alignment'] : '';
						$slider_title = !empty( $each_item['slider_title'] ) ? $each_item['slider_title'] : '';
						$slider_content = !empty( $each_item['slider_content'] ) ? $each_item['slider_content'] : '';
						$cause_id = !empty( $each_item['cause_id'] ) ? $each_item['cause_id'] : '';
						$goal_title 				= !empty( $each_item['goal_title'] ) ? $each_item['goal_title'] : '';
						$remaing_title 				= !empty( $each_item['remaing_title'] ) ? $each_item['remaing_title'] : '';
						$donor_title 				= !empty( $each_item['donor_title'] ) ? $each_item['donor_title'] : '';

						$button_text = !empty( $each_item['btn_txt'] ) ? $each_item['btn_txt'] : '';
						$btn_icon         = !empty( $each_item['btn_icon'] ) ? $each_item['btn_icon'] : '';
						$icon = $btn_icon ? ' <i class="'.$btn_icon.'" aria-hidden="true"></i>' : '';
						$button_link = !empty( $each_item['button_link']['url'] ) ? $each_item['button_link']['url'] : '';
						$button_link_external = !empty( $each_item['button_link']['is_external'] ) ? 'target="_blank"' : '';
						$button_link_nofollow = !empty( $each_item['button_link']['nofollow'] ) ? 'rel="nofollow"' : '';
						$button_link_attr = !empty( $button_link ) ?  $button_link_external.' '.$button_link_nofollow : '';

						$button_two_text = !empty( $each_item['btn_two_txt'] ) ? $each_item['btn_two_txt'] : '';
						$btn_two_icon         = !empty( $each_item['btn_two_icon'] ) ? $each_item['btn_two_icon'] : '';
						$icon_two = $btn_two_icon ? ' <i class="'.$btn_two_icon.'" aria-hidden="true"></i>' : '';
						$button_two_link = !empty( $each_item['button_two_link']['url'] ) ? $each_item['button_two_link']['url'] : '';
						$button_two_link_external = !empty( $each_item['button_two_link']['is_external'] ) ? 'target="_blank"' : '';
						$button_two_link_nofollow = !empty( $each_item['button_two_link']['nofollow'] ) ? 'rel="nofollow"' : '';
						$button_two_link_attr = !empty( $button_two_link ) ?  $button_two_link_external.' '.$button_two_link_nofollow : '';

						$slide_title = $slider_title ? ' <h1 class="banner-title animated" data-animation="'.esc_attr($title_entrance_animation).'">'.esc_html($slider_title).'</h1>' : '';
						$slide_content = $slider_content ? '<p class="animated" data-animation="'.esc_attr($content_entrance_animation).'">'.esc_html($slider_content).'</p>' : '';

						$button_one = $button_link ? '<a href="'.esc_url($button_link).'" '.$button_link_attr.' class="btn-one nacep-btn">'. esc_html($button_text) . $icon .'</a>' : '';
						$button_two = $button_two_link ? '<a href="'.esc_url($button_two_link).'" '.$button_two_link_attr.' class="btn-two nacep-btn nacep-btn-orng">'. esc_html($button_two_text) . $icon_two .'</a>' : '';

						$button_actual = ($button_one || $button_two) ? '<div class="nacep-btn-wrap animated" data-animation="'.esc_attr($button_entrance_animation).'">'.$button_one.$button_two.'</div>' : '';

						if ($section_alignment === 'center') {
							$align_class = ' center-align';
						} elseif ($section_alignment === 'right') {
							$align_class = ' right-align';
						} else {
							$align_class = ' left-align';
						}

						if ($toggle_align) {
							$f_class = ' order-1';
							$s_class = ' order-2';
						} else {
							$f_class = '';
							$s_class = '';
						}
	    			?>
						<div class="swiper-slide nacep-banner nacep-overlay" style="background-image: url(<?php echo esc_url($image_url); ?>);"<?php echo esc_attr( $carousel_autoplay_timeout ); ?>>
		          <div class="nacep-table-wrap">
				        <div class="nacep-align-wrap">
				          <div class="banner-container">
				          	<div class="col-na-row align-items-center">
				          		<div class="col-na-7<?php echo esc_attr($s_class); ?>">
				          			<?php if ($hide_content) { } else { ?>
						            <div class="banner-caption<?php echo esc_attr($align_class); ?>">
						            	<?php echo $slide_title.$slide_content.$button_actual; ?>
					            	</div>
				          			<?php } ?>
				            	</div>
				          		<div class="col-na-5<?php echo esc_attr($f_class); ?>">
				          			<?php if ($hide_form) { } else {
				          			$cas_args = array(
										      'post_type' => 'give_forms',
										      'posts_per_page' => 1,
										      'orderby' => 'none',
										      'order' => 'ASC',
										      'post__in' => (array) $cause_id,
										    );

												$nacep_cas = new \WP_Query( $cas_args );
				          			if ($nacep_cas->have_posts()) : while ($nacep_cas->have_posts()) : $nacep_cas->the_post();
							    			$form        = new \Give_Donate_Form( get_the_ID() );
												$goal        = $form->goal;
												$income      = $form->get_earnings();

												$remaing = $goal - $income;
												if ($income && $goal) {
													$progress = round( ( $income / $goal ) * 100 );
												} else {
													$progress = '';
												}
												if ( $income >= $goal ) {
												  $progress = 100;
												  $remaing = 0;
												}

												$income = give_human_format_large_amount( give_format_amount( $income ) );
												$goal = give_human_format_large_amount( give_format_amount( $goal ) );
												$remaing = give_human_format_large_amount( give_format_amount( $remaing ) );
												if(function_exists('give_get_payments')) {
													$args = array(
														'give_forms' => array( get_the_ID() ),
													);
													$donations = give_get_payments( $args );
													$donor_count = count($donations);
												} else {
													$donor_count = 0;
												}
				          			?>
				          			<div class="nacep-give-cause-list style-two animated" data-animation="<?php echo esc_attr($box_entrance_animation); ?>">
					          			<div class="nacep-cause-list-item">
														<div class="cause-info">
													  	<h3 class="cause-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
													  	<?php nacharity_excerpt(); ?>
															<div class="nacep-cause-bar"><span class="progress-bar" role="progressbar" aria-valuenow="<?php echo esc_attr($progress); ?>" aria-valuemin="0" aria-valuemax="100"><span class="progress-counter"><?php echo esc_html($progress); ?>%</span></span></div>
													  </div>
										  			<div class="cause-donate-info">
											  			<div class="col-na-row">
											  				<div class="col-na-4">
											  					<h3 class="goal-title"><?php echo esc_html($goal_title); ?></h3>
											  					<p class="goal-symbol"><?php echo esc_html(give_currency_symbol().$goal); ?></p>
											  				</div>
											  				<div class="col-na-4">
											  					<h3 class="remaing-title"><?php echo esc_html($remaing_title); ?></h3>
											  					<p class="remaing-symbol"><?php echo esc_html(give_currency_symbol().$remaing); ?></p>
											  				</div>
											  				<div class="col-na-4">
											  					<h3 class="donor-title"><?php echo esc_html($donor_title); ?></h3>
											  					<p class="donor-count"><?php echo esc_html($donor_count); ?></p>
											  				</div>
											  			</div>
										  			</div>
													</div>
												</div>
				          			<?php
				          			endwhile;
										    wp_reset_postdata();
										    endif;
				          			} ?>
					            </div>
				          	</div>
				          </div>
				        </div>
				      </div>
		        </div>
					<?php
					}
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
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Unique_Slider() );
}
