<?php
/*
 * Elementor Primary Addon for Elementor Testimonials Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'pafe_bw_settings' )['napafe_testimonials'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Primary_Addon_Testimonials extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'prim_basic_testimonials';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Testimonials', 'primary-addon-for-elementor' );
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
		return ['prim-basic-category'];
	}

	/**
	 * Register Primary Addon for Elementor Testimonials widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_testimonials',
			[
				'label' => __( 'Testimonials Item', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'testimonial_style',
			[
				'label' => esc_html__( 'Testimonials Style', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'primary-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two', 'primary-addon-for-elementor' ),
					'three' => esc_html__( 'Style Three', 'primary-addon-for-elementor' ),
					'four' => esc_html__( 'Style Four', 'primary-addon-for-elementor' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your style.', 'primary-addon-for-elementor' ),
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'testimonial_title',
			[
				'label' => esc_html__( 'Name', 'restaurant-elementor-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Cathrine Wagner', 'restaurant-elementor-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'restaurant-elementor-addon' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'testimonial_title_link',
			[
				'label' => esc_html__( 'Name Link', 'restaurant-elementor-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'testimonial_icon',
			[
				'label' => esc_html__( 'Select Quote Icon', 'restaurant-elementor-addon' ),
				'type' => Controls_Manager::ICON,
				'options' => NAPAE_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-quote-right',
			]
		);
		$repeater->add_control(
			'testimonial_image',
			[
				'label' => esc_html__( 'Upload Image', 'restaurant-elementor-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'restaurant-elementor-addon'),
			]
		);
		$repeater->add_control(
			'rating',
			[
				'label' => esc_html__( 'Customer Rating', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => esc_html__( '1', 'primary-addon-for-elementor' ),
					'2' => esc_html__( '2', 'primary-addon-for-elementor' ),
					'3' => esc_html__( '3', 'primary-addon-for-elementor' ),
					'4' => esc_html__( '4', 'primary-addon-for-elementor' ),
					'5' => esc_html__( '5', 'primary-addon-for-elementor' ),
				],
				'default' => '3',
				'description' => esc_html__( 'Select your rating.', 'primary-addon-for-elementor' ),
			]
		);
		$repeater->add_control(
			'testimonial_designation',
			[
				'label' => esc_html__( 'Designation Text', 'restaurant-elementor-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'General Manager', 'restaurant-elementor-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'restaurant-elementor-addon' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'testimonial_content',
			[
				'label' => esc_html__( 'Content', 'restaurant-elementor-addon' ),
				'default' => esc_html__( 'The ship set ground on there sure to get a smile.', 'restaurant-elementor-addon' ),
				'placeholder' => esc_html__( 'Type your content here', 'restaurant-elementor-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);
		$this->add_control(
			'testimonials_groups',
			[
				'label' => esc_html__( 'Testimonials Items', 'restaurant-elementor-addon' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'testimonial_title' => esc_html__( 'Cathrine Wagner', 'restaurant-elementor-addon' ),
						'testimonial_content' => esc_html__( 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', 'restaurant-elementor-addon' ),
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ testimonial_title }}}',
			]
		);
		$this->add_responsive_control(
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
				'selectors' => [
					'{{WRAPPER}} .napae-testimonial-item' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();// end: Section

		// Carousel Options
			$this->start_controls_section(
				'section_carousel',
				[
					'label' => esc_html__( 'Carousel Options', 'restaurant-elementor-addon' ),
				]
			);
			$this->add_responsive_control(
				'carousel_items',
				[
					'label' => esc_html__( 'How many items?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 100,
					'step' => 1,
					'default' => 1,
					'description' => esc_html__( 'Enter the number of items to show.', 'restaurant-elementor-addon' ),
				]
			);
			$this->add_control(
				'carousel_margin',
				[
					'label' => __( 'Space Between Items', 'restaurant-elementor-addon' ),
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
					'label' => __( 'Auto Play Timeout', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 5000,
				]
			);
			$this->add_control(
				'carousel_loop',
				[
					'label' => esc_html__( 'Need Loop?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Continuously moving carousel, if enabled.', 'restaurant-elementor-addon' ),
					'default' => 'true',
				]
			);
			$this->add_control(
				'carousel_dots',
				[
					'label' => esc_html__( 'Need Dots?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want Carousel Dots, enable it.', 'restaurant-elementor-addon' ),
					'default' => 'true',
				]
			);
			$this->add_control(
				'carousel_nav',
				[
					'label' => esc_html__( 'Need Navigation?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want Carousel Navigation, enable it.', 'restaurant-elementor-addon' ),
					'default' => 'true',
				]
			);
			$this->add_control(
				'carousel_autoplay',
				[
					'label' => esc_html__( 'Need Autoplay?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want to start Carousel automatically, enable it.', 'restaurant-elementor-addon' ),
					'default' => 'true',
				]
			);
			$this->add_control(
				'carousel_animate_out',
				[
					'label' => esc_html__( 'Animate Out', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'CSS3 animation out.', 'restaurant-elementor-addon' ),
				]
			);
			$this->add_control(
				'carousel_mousedrag',
				[
					'label' => esc_html__( 'Need Mouse Drag?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'If you want to disable Mouse Drag, check it.', 'restaurant-elementor-addon' ),
				]
			);
			$this->add_control(
				'carousel_autowidth',
				[
					'label' => esc_html__( 'Need Auto Width?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Adjust Auto Width automatically for each carousel items.', 'restaurant-elementor-addon' ),
				]
			);
			$this->add_control(
				'carousel_autoheight',
				[
					'label' => esc_html__( 'Need Auto Height?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'description' => esc_html__( 'Adjust Auto Height automatically for each carousel items.', 'restaurant-elementor-addon' ),
				]
			);
			$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-testimonials-wrap, {{WRAPPER}} .testimonials-style-two .napae-testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-testimonials-wrap, {{WRAPPER}} .testimonials-style-two .napae-testimonial-item' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'secn_abg_color',
				[
					'label' => esc_html__( 'Author Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testimonials-style-four .napae-author-wrap' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'testimonial_style' => 'four',
					],
				]
			);
			$this->add_control(
				'secn_abg_h_color',
				[
					'label' => esc_html__( 'Background Hover Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testimonials-style-four .napae-testimonials-wrap:hover .napae-author-wrap' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'testimonial_style' => 'four',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-testimonials-wrap, {{WRAPPER}} .testimonials-style-two .napae-testimonial-item',
					'condition' => [
						'testimonial_style' => 'two',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-testimonials-wrap, {{WRAPPER}} .testimonials-style-two .napae-testimonial-item',
				]
			);
			$this->end_controls_section();// end: Section

		// Quote Icon
			$this->start_controls_section(
				'section_icon_style',
				[
					'label' => esc_html__( 'Quote Icon', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-testimonials-wrap .napae-icon' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bg_color',
				[
					'label' => esc_html__( 'Icon Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-testimonials-wrap .napae-icon' => 'background: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bdr_radi',
				[
					'label' => __( 'Border Radius', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-testimonials-wrap .napae-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'icon_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-testimonials-wrap .napae-icon',
				]
			);
			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'icon_text_shadow',
					'label' => esc_html__( 'Icon Shadow', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-testimonials-wrap .napae-icon i',
				]
			);
			$this->add_responsive_control(
				'icon_width',
				[
					'label' => esc_html__( 'Icon width', 'primary-addon-for-elementor' ),
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
						'{{WRAPPER}} .napae-testimonials-wrap .napae-icon' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'icon_lheight',
				[
					'label' => esc_html__( 'Icon Line Height', 'primary-addon-for-elementor' ),
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
						'{{WRAPPER}} .napae-testimonials-wrap .napae-icon i' => 'line-height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'icon_size',
				[
					'label' => esc_html__( 'Icon Size', 'primary-addon-for-elementor' ),
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
						'{{WRAPPER}} .napae-testimonials-wrap .napae-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'icon_left',
				[
					'label' => esc_html__( 'Icon Left', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .napae-testimonials-wrap .napae-icon' => 'left: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'icon_right',
				[
					'label' => esc_html__( 'Icon Right', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .napae-testimonials-wrap .napae-icon' => 'right: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'icon_top',
				[
					'label' => esc_html__( 'Icon Top', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .napae-testimonials-wrap .napae-icon' => 'top: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'icon_bottom',
				[
					'label' => esc_html__( 'Icon Bottom', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .napae-testimonials-wrap .napae-icon' => 'bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Image
			$this->start_controls_section(
				'section_image_style',
				[
					'label' => esc_html__( 'Image', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'image_padding',
				[
					'label' => __( 'Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-testimonial-item .napae-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'image_border_radius',
				[
					'label' => __( 'Border Radius', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-testimonial-item .napae-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'image_border',
					'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-testimonial-item .napae-image img',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'image_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-testimonial-item .napae-image img',
				]
			);
			$this->add_control(
				'image_width',
				[
					'label' => esc_html__( 'Image width', 'primary-addon-for-elementor' ),
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
						'{{WRAPPER}} .napae-testimonial-item .napae-image img' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'title_padding',
				[
					'label' => __( 'Title Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-testimonial-item h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
					'name' => 'sastestimonial_title_typography',
					'selector' => '{{WRAPPER}} .napae-testimonial-item h4',
				]
			);
			$this->start_controls_tabs( 'testimonials_title_style' );
				$this->start_controls_tab(
					'title_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-testimonial-item h4, {{WRAPPER}} .napae-testimonial-item h4 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'title_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_hov_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-testimonial-item h4 a:hover' => 'color: {{VALUE}};',
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
					'label' => esc_html__( 'Designation', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
						'name' => 'subtitle_typography',
						'selector' => '{{WRAPPER}} .napae-testimonial-item .napae-author-wrap p, {{WRAPPER}} .napae-author-wrap p, {{WRAPPER}} .napae-testimonial-item h4 span',
					]
				);
				$this->add_control(
					'subtitle_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-testimonial-item .napae-author-wrap p, {{WRAPPER}} .napae-author-wrap p, {{WRAPPER}} .napae-testimonial-item h4 span' => 'color: {{VALUE}};',
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
						'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
						'name' => 'content_typography',
						'selector' => '{{WRAPPER}} .napae-testimonial-item p',
					]
				);
				$this->add_control(
					'content_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-testimonial-item p' => 'color: {{VALUE}};',
						],
					]
				);
			$this->end_controls_section();// end: Section

		// Star Rating
			$this->start_controls_section(
				'section_star_style',
				[
					'label' => esc_html__( 'Star Rating', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'star_padding',
				[
					'label' => __( 'Star Rating Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-customer-rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'star_margin',
				[
					'label' => __( 'Star Margin', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-customer-rating i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'testimonials_star_style' );
				$this->start_controls_tab(
					'star_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'star_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-customer-rating .fa-star-o' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'star_hover',
					[
						'label' => esc_html__( 'Active', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'star_hov_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-customer-rating' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Navigation
			$this->start_controls_section(
				'section_navigation_style',
				[
					'label' => esc_html__( 'Navigation', 'event-elementor-addon' ),
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
					'label' => esc_html__( 'Size', 'event-elementor-addon' ),
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
						'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:before' => 'font-size: calc({{SIZE}}{{UNIT}} - 16px);line-height: calc({{SIZE}}{{UNIT}} - 20px);',
					],
				]
			);
			$this->start_controls_tabs( 'nav_arrow_style' );
				$this->start_controls_tab(
					'nav_arrow_normal',
					[
						'label' => esc_html__( 'Normal', 'event-elementor-addon' ),
					]
				);
				$this->add_control(
					'nav_arrow_color',
					[
						'label' => esc_html__( 'Color', 'event-elementor-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'event-elementor-addon' ),
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
						'label' => esc_html__( 'Border', 'event-elementor-addon' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'nav_arrow_hover',
					[
						'label' => esc_html__( 'Hover', 'event-elementor-addon' ),
					]
				);
				$this->add_control(
					'nav_arrow_hov_color',
					[
						'label' => esc_html__( 'Color', 'event-elementor-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'event-elementor-addon' ),
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
						'label' => esc_html__( 'Border', 'event-elementor-addon' ),
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
					'label' => esc_html__( 'Dots', 'event-elementor-addon' ),
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
					'label' => esc_html__( 'Size', 'event-elementor-addon' ),
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
					'label' => __( 'Margin', 'event-elementor-addon' ),
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
						'label' => esc_html__( 'Normal', 'event-elementor-addon' ),
					]
				);
				$this->add_control(
					'dots_color',
					[
						'label' => esc_html__( 'Background Color', 'event-elementor-addon' ),
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
						'label' => esc_html__( 'Border', 'event-elementor-addon' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-dot',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'dots_active',
					[
						'label' => esc_html__( 'Active', 'event-elementor-addon' ),
					]
				);
				$this->add_control(
					'dots_active_color',
					[
						'label' => esc_html__( 'Background Color', 'event-elementor-addon' ),
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
						'label' => esc_html__( 'Border', 'event-elementor-addon' ),
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
		$testimonials_groups = !empty( $settings['testimonials_groups'] ) ? $settings['testimonials_groups'] : '';

		// Carousel
			$carousel_items = !empty( $settings['carousel_items'] ) ? $settings['carousel_items'] : '';
			$carousel_items_tablet = !empty( $settings['carousel_items_tablet'] ) ? $settings['carousel_items_tablet'] : '';
			$carousel_items_mobile = !empty( $settings['carousel_items_mobile'] ) ? $settings['carousel_items_mobile'] : '';
			$carousel_margin = !empty( $settings['carousel_margin']['size'] ) ? $settings['carousel_margin']['size'] : '';
			$carousel_autoplay_timeout = !empty( $settings['carousel_autoplay_timeout'] ) ? $settings['carousel_autoplay_timeout'] : '';

			$carousel_loop  = ( isset( $settings['carousel_loop'] ) && ( 'true' == $settings['carousel_loop'] ) ) ? true : false;
			$carousel_dots  = ( isset( $settings['carousel_dots'] ) && ( 'true' == $settings['carousel_dots'] ) ) ? true : false;
			$carousel_nav  = ( isset( $settings['carousel_nav'] ) && ( 'true' == $settings['carousel_nav'] ) ) ? true : false;
			$carousel_autoplay  = ( isset( $settings['carousel_autoplay'] ) && ( 'true' == $settings['carousel_autoplay'] ) ) ? true : false;
			$carousel_animate_out  = ( isset( $settings['carousel_animate_out'] ) && ( 'true' == $settings['carousel_animate_out'] ) ) ? true : false;
			$carousel_mousedrag  = ( isset( $settings['carousel_mousedrag'] ) && ( 'true' == $settings['carousel_mousedrag'] ) ) ? $settings['carousel_mousedrag'] : 'false';
			$carousel_autowidth  = ( isset( $settings['carousel_autowidth'] ) && ( 'true' == $settings['carousel_autowidth'] ) ) ? true : false;
			$carousel_autoheight  = ( isset( $settings['carousel_autoheight'] ) && ( 'true' == $settings['carousel_autoheight'] ) ) ? true : false;

		// Carousel Data's
			$carousel_items = $carousel_items ? ' data-items="'. $carousel_items .'"' : ' data-items="1"';
			$carousel_tablet = $carousel_items_tablet ? ' data-items-tablet="'. $carousel_items_tablet .'"' : ' data-items-tablet="1"';
			$carousel_mobile = $carousel_items_mobile ? ' data-items-mobile-landscape="'. $carousel_items_mobile .'"' : ' data-items-mobile-landscape="1"';
			$carousel_small_mobile = $carousel_items_mobile ? ' data-items-mobile-portrait="'. $carousel_items_mobile .'"' : ' data-items-mobile-portrait="1"';
			$carousel_margin = $carousel_margin ? ' data-margin="'. $carousel_margin .'"' : ' data-margin="0"';
			$carousel_autoplay_timeout = $carousel_autoplay_timeout ? ' data-autoplay-timeout="'. $carousel_autoplay_timeout .'"' : '';
			$carousel_loop = ('true' == $carousel_loop) ? ' data-loop="true"' : ' data-loop="false"';
			$carousel_dots = ('true' == $carousel_dots) ? ' data-dots="true"' : ' data-dots="false"';
			$carousel_nav = ('true' == $carousel_nav) ? ' data-nav="true"' : ' data-nav="false"';
			$carousel_autoplay = ('true' == $carousel_autoplay) ? ' data-autoplay="true"' : ' data-autoplay="false"';
			$carousel_animate_out = ('true' == $carousel_animate_out) ? ' data-animateout="true"' : ' data-animateout="false"';
			$carousel_mousedrag = ('true' == $carousel_mousedrag) ? ' data-mouse-drag="true"' : ' data-mouse-drag="false"';
			$carousel_autowidth = ('true' == $carousel_autowidth) ? ' data-auto-width="true"' : ' data-auto-width="false"';
			$carousel_autoheight = ('true' == $carousel_autoheight) ? ' data-auto-height="true"' : ' data-auto-width="false"';

		if ($testimonial_style === 'two') {
			$style_cls = ' testimonials-style-two';
		} elseif ($testimonial_style === 'four') {
			$style_cls = ' testimonials-style-four';
		} else {
			$style_cls = '';
		}

		$output = '<div class="napae-testimonials'.$style_cls.'">';
		if ($testimonial_style === 'two') { $output .= '<div class="napae-testimonial-item">'; }
		$output .= '<div class="owl-carousel" '. $carousel_items . $carousel_tablet . $carousel_mobile . $carousel_small_mobile . $carousel_margin . $carousel_autoplay_timeout . $carousel_loop . $carousel_dots . $carousel_nav . $carousel_autoplay . $carousel_animate_out . $carousel_mousedrag . $carousel_autowidth . $carousel_autoheight .'>';

			if ( !empty( $testimonials_groups ) && is_array( $testimonials_groups ) ) {
				// Group Param Output
				foreach ( $testimonials_groups as $each_testimonial ) {
					$testimonial_icon = !empty( $each_testimonial['testimonial_icon'] ) ? $each_testimonial['testimonial_icon'] : '';
					$testimonial_image = !empty( $each_testimonial['testimonial_image']['id'] ) ? $each_testimonial['testimonial_image']['id'] : '';
					$testimonial_title = !empty( $each_testimonial['testimonial_title'] ) ? $each_testimonial['testimonial_title'] : '';

					$testimonial_link = !empty( $each_testimonial['testimonial_title_link']['url'] ) ? esc_url($each_testimonial['testimonial_title_link']['url']) : '';
					$testimonial_link_external = !empty( $testimonial_link['is_external'] ) ? 'target="_blank"' : '';
					$testimonial_link_nofollow = !empty( $testimonial_link['nofollow'] ) ? 'rel="nofollow"' : '';
					$testimonial_link_attr = !empty( $testimonial_link['url'] ) ?  $testimonial_link_external.' '.$testimonial_link_nofollow : '';

					$rating = !empty( $each_testimonial['rating'] ) ? $each_testimonial['rating'] : '';
					$testimonial_designation = !empty( $each_testimonial['testimonial_designation'] ) ? $each_testimonial['testimonial_designation'] : '';
					$testimonial_content = !empty( $each_testimonial['testimonial_content'] ) ? $each_testimonial['testimonial_content'] : '';

					$image_url = wp_get_attachment_url( $testimonial_image );
					$image = $image_url ? '<div class="napae-image"><img src="'.esc_url($image_url).'" alt="'.esc_attr($testimonial_title).'"></div>' : '';
					$icon = $testimonial_icon ? '<div class="napae-icon"><i class="'.esc_attr($testimonial_icon).'"></i></div>' : '';

	 			  $title_link = !empty( $testimonial_link ) ? '<a href="'.esc_url($testimonial_link).'" '.$testimonial_link_attr.'>'.esc_html($testimonial_title).'</a>' : esc_html($testimonial_title);
					$title = $testimonial_title ? '<h4 class="napae-author-name">'.$title_link.'</h4>' : '';
					$designation = $testimonial_designation ? '<p>'.esc_html($testimonial_designation).'</p>' : '';
					$desi = $testimonial_designation ? '<span>'.esc_html($testimonial_designation).'</span>' : '';
					$desiC = $testimonial_designation ? ', <span>'.esc_html($testimonial_designation).'</span>' : '';
					$titleThree = $testimonial_title ? '<h4 class="napae-author-name">'.$title_link.$desiC.'</h4>' : '<h4 class="napae-author-name">'.$desi.'</h4>';
					$content = $testimonial_content ? '<p>'.esc_html($testimonial_content).'</p>' : '';
					if ($testimonial_style === 'two') {
						$output .= '<div class="item">
						              '.$image.'
						              <div class="napae-testimonial-info">
							              <div class="napae-customer-rating">';
						                	for( $i=1; $i<= $rating; $i++) {
										            $output .= '<i class="fa fa-star"></i>';
										          }
										          for( $i=5; $i > $rating; $i--) {
										            $output .= '<i class="fa fa-star-o"></i>';
										          }
						                $output .= '</div>
						                '.$content.'
						                <div class="napae-author-wrap">
						                  '.$title.$designation.'
						                </div>
						              </div>
						            </div>';
					} elseif ($testimonial_style === 'three') {
					  $output .= '<div class="item">
							            <div class="napae-testimonials-wrap">
							              '.$icon.'
							              <div class="napae-testimonial-item">'.$image.$content.'
							                <div class="napae-author-wrap">
							                  '.$titleThree.'
							                </div>
							                <div class="napae-customer-rating">';
							                	for( $i=1; $i<= $rating; $i++) {
											            $output .= '<i class="fa fa-star"></i>';
											          }
											          for( $i=5; $i > $rating; $i--) {
											            $output .= '<i class="fa fa-star-o"></i>';
											          }
							                $output .= '</div>
							              </div>
							            </div>
							          </div>';
					} elseif ($testimonial_style === 'four') {
					  $output .= '<div class="item">
							            <div class="napae-testimonials-wrap">
							              <div class="napae-testimonial-item">
							              	<div class="napae-testimonial-content">
							              	'.$content.'
							                <div class="napae-customer-rating">';
							                	for( $i=1; $i<= $rating; $i++) {
											            $output .= '<i class="fa fa-star"></i>';
											          }
											          for( $i=5; $i > $rating; $i--) {
											            $output .= '<i class="fa fa-star-o"></i>';
											          }
							                $output .= '</div></div>
							                <div class="napae-author-wrap">
							              		'.$icon.$image.'<div class="napae-author-info">'.$title.$designation.'</div>
							                </div>
							              </div>
							            </div>
							          </div>';
					} else {
					  $output .= '<div class="item">
							            <div class="napae-testimonials-wrap">
							              '.$icon.'
							              <div class="napae-testimonial-item">'.$image.'
							                <div class="napae-customer-rating">';
							                	for( $i=1; $i<= $rating; $i++) {
											            $output .= '<i class="fa fa-star"></i>';
											          }
											          for( $i=5; $i > $rating; $i--) {
											            $output .= '<i class="fa fa-star-o"></i>';
											          }
							                $output .= '</div>
							                '.$content.'
							                <div class="napae-author-wrap">
							                  '.$title.$designation.'
							                </div>
							              </div>
							            </div>
							          </div>';
					}
				}
			}
		$output .= '</div>';
		if ($testimonial_style === 'two') { $output .= '</div>'; }
		$output .= '</div>';

		echo $output;

		if ( Plugin::$instance->editor->is_edit_mode() ) : ?>
		<script type="text/javascript">
	    jQuery(document).ready(function($) {
				$('.owl-carousel').each( function() {
			    var $carousel = $(this);
			    var $items = ($carousel.data('items') !== undefined) ? $carousel.data('items') : 1;
			    var $items_tablet = ($carousel.data('items-tablet') !== undefined) ? $carousel.data('items-tablet') : 1;
			    var $items_mobile_landscape = ($carousel.data('items-mobile-landscape') !== undefined) ? $carousel.data('items-mobile-landscape') : 1;
			    var $items_mobile_portrait = ($carousel.data('items-mobile-portrait') !== undefined) ? $carousel.data('items-mobile-portrait') : 1;
			    $carousel.owlCarousel ({
			      loop : ($carousel.data('loop') !== undefined) ? $carousel.data('loop') : true,
			      items : $carousel.data('items'),
			      margin : ($carousel.data('margin') !== undefined) ? $carousel.data('margin') : 0,
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
			        },
			        992 : {
			          items : $items,
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

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Primary_Addon_Testimonials() );

} // enable & disable
