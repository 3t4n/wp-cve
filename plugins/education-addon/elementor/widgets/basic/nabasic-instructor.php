<?php
/*
 * Elementor Education Addon Instructor Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_instructor'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Instructor extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_instructor';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Instructor', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-person';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Instructor widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_instructor',
			[
				'label' => __( 'Instructor Item', 'education-addon' ),
			]
		);
		$this->add_control(
			'instructor_style',
			[
				'label' => esc_html__( 'Instructor Style', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'education-addon' ),
					'two' => esc_html__( 'Style Two', 'education-addon' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your style.', 'education-addon' ),
			]
		);
		$this->add_control(
			'instructor_image',
			[
				'label' => esc_html__( 'Content Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'education-addon'),
				'separator' => 'before',
			]
		);
		$this->add_control(
			'image_link',
			[
				'label' => esc_html__( 'Image Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'instructor_name',
			[
				'label' => esc_html__( 'Name', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Neil Armstrong', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'name_link',
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
		$this->add_control(
			'designation',
			[
				'label' => esc_html__( 'Designation', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Lead Programme Instructor', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Stamboard, 1st Floor, Los Vegas - 45', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->start_controls_tabs(
			'contact_links',
			[
				'label' => esc_html__( 'Contact Links', 'education-addon' ),
				'condition' => [
					'instructor_style' => 'two',
				],
				'separator' => 'before',
			]
		);
			$this->start_controls_tab(
				'linkone',
				[
					'label' => esc_html__( 'Link One', 'education-addon' ),
				]
			);
			$this->add_control(
				'icon_one',
				[
					'label' => esc_html__( 'Icon', 'education-addon' ),
					'type' => Controls_Manager::ICON,
					'options' => NAEDU_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
				]
			);
			$this->add_control(
				'link_one',
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
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'linktwo',
				[
					'label' => esc_html__( 'Link Two', 'education-addon' ),
				]
			);
			$this->add_control(
				'icon_two',
				[
					'label' => esc_html__( 'Icon', 'education-addon' ),
					'type' => Controls_Manager::ICON,
					'options' => NAEDU_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
				]
			);
			$this->add_control(
				'link_two',
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
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$repeater = new Repeater();
		$repeater->add_control(
			'social_icon',
			[
				'label' => esc_html__( 'Social Icon', 'education-addon' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEDU_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-facebook-square',
			]
		);
		$repeater->add_control(
			'icon_link',
			[
				'label' => esc_html__( 'Icon Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'listItems_groups',
			[
				'label' => esc_html__( 'Social Iocns', 'education-addon' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ social_icon }}}',
				'prevent_empty' => false,
				'separator' => 'before',
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
						'{{WRAPPER}} .naedu-instructor figure' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naedu-instructor figure' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naedu-instructor figure' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-instructor figure' => 'background-color: {{VALUE}};',
					],
				]
			);			
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-instructor figure',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-instructor figure',
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
						'{{WRAPPER}} .naedu-instructor h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .naedu-instructor h3',
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
							'{{WRAPPER}} .naedu-instructor h3, {{WRAPPER}} .naedu-instructor h3 a' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .naedu-instructor h3 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Designation
			$this->start_controls_section(
				'section_designation_style',
				[
					'label' => esc_html__( 'Designation', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'designation_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-instructor h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'designation_typography',
					'selector' => '{{WRAPPER}} .naedu-instructor h4',
				]
			);
			$this->add_control(
				'designation_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-instructor h4' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .naedu-instructor p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .naedu-instructor p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-instructor p' => 'color: {{VALUE}};',
					],
				]
			);			
			$this->end_controls_section();// end: Section

		// Contact Links
			$this->start_controls_section(
				'section_btn_style',
				[
					'label' => esc_html__( 'Contact Links', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'instructor_style' => 'two',
					],
				]
			);
			$this->add_control(
				'btn_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .contact-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .contact-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'btn_typography',
					'selector' => '{{WRAPPER}} .contact-link',
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
							'{{WRAPPER}} .contact-link' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .contact-link' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .contact-link',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .contact-link',
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
							'{{WRAPPER}} .contact-link:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .contact-link:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .contact-link:hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_hover_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .contact-link:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Icon
			$this->start_controls_section(
				'section_sicon_style',
				[
					'label' => esc_html__( 'Icon', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'sicon_btop_color',
				[
					'label' => esc_html__( 'Border Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .social-link' => 'border-color: {{VALUE}};',
					],
					'condition' => [
						'instructor_style' => 'two',
					],
				]
			);
			$this->add_control(
				'social_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .instructor-auther a, {{WRAPPER}} .social-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .instructor-auther a, {{WRAPPER}} .social-link a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'sicon_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .instructor-auther a, {{WRAPPER}} .social-link a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'sicon_size',
				[
					'label' => esc_html__( 'Icon Size', 'education-addon' ),
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
						'{{WRAPPER}} .instructor-auther a, {{WRAPPER}} .social-link a' => 'font-size:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'sicon_style' );
				$this->start_controls_tab(
					'sicon_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'sicon_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .instructor-auther a, {{WRAPPER}} .social-link a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'sicon_bg',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .instructor-auther a, {{WRAPPER}} .social-link a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'sicon_border',
					[
						'label' => esc_html__( 'Border Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .instructor-auther a, {{WRAPPER}} .social-link a' => 'border-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'sicon_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'sicon_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .instructor-auther a:hover, {{WRAPPER}} .social-link a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'sicon_bg_hov',
					[
						'label' => esc_html__( 'Background Hover Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .instructor-auther a:hover, {{WRAPPER}} .social-link a:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'sicon_hover_border',
					[
						'label' => esc_html__( 'Border Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .instructor-auther a:hover, {{WRAPPER}} .social-link a:hover' => 'border-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs			
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Instructor widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Instructor query
		$settings = $this->get_settings_for_display();
		$instructor_style = !empty( $settings['instructor_style'] ) ? $settings['instructor_style'] : '';

		$instructor_image = !empty( $settings['instructor_image']['id'] ) ? $settings['instructor_image']['id'] : '';
		$image_link = !empty( $settings['image_link']['url'] ) ? esc_url($settings['image_link']['url']) : '';
		$image_link_external = !empty( $image_link['is_external'] ) ? 'target="_blank"' : '';
		$image_link_nofollow = !empty( $image_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$image_link_attr = !empty( $image_link['url'] ) ?  $image_link_external.' '.$image_link_nofollow : '';

		$instructor_name = !empty( $settings['instructor_name'] ) ? $settings['instructor_name'] : '';
		$name_link = !empty( $settings['name_link']['url'] ) ? esc_url($settings['name_link']['url']) : '';
		$name_link_external = !empty( $name_link['is_external'] ) ? 'target="_blank"' : '';
		$name_link_nofollow = !empty( $name_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$name_link_attr = !empty( $name_link['url'] ) ?  $name_link_external.' '.$name_link_nofollow : '';

		$designation = !empty( $settings['designation'] ) ? $settings['designation'] : '';
		$content = !empty( $settings['content'] ) ? $settings['content'] : '';

		$icon_one = !empty( $settings['icon_one'] ) ? $settings['icon_one'] : '';
		$link_one = !empty( $settings['link_one']['url'] ) ? esc_url($settings['link_one']['url']) : '';
		$link_one_external = !empty( $link_one['is_external'] ) ? 'target="_blank"' : '';
		$link_one_nofollow = !empty( $link_one['nofollow'] ) ? 'rel="nofollow"' : '';
		$link_one_attr = !empty( $link_one['url'] ) ?  $link_one_external.' '.$link_one_nofollow : '';

		$icon_two = !empty( $settings['icon_two'] ) ? $settings['icon_two'] : '';
		$link_two = !empty( $settings['link_two']['url'] ) ? esc_url($settings['link_two']['url']) : '';
		$link_two_external = !empty( $link_two['is_external'] ) ? 'target="_blank"' : '';
		$link_two_nofollow = !empty( $link_two['nofollow'] ) ? 'rel="nofollow"' : '';
		$link_two_attr = !empty( $link_two['url'] ) ?  $link_two_external.' '.$link_two_nofollow : '';

		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';

		$image_url = wp_get_attachment_url( $instructor_image );
		$image_link = $image_link ? '<a href="'.esc_url($image_link).'" '.$image_link_attr.'><img src="'.esc_url($image_url).'" alt="Instructor"></a>' : '<img src="'.esc_url($image_url).'" alt="Instructor">';
		$image = $image_url ? $image_link : '';

		$name_link = $name_link ? '<a href="'.esc_url($name_link).'" '.$name_link_attr.'>'.$instructor_name.'</a>' : $instructor_name;
		$instructor_name = $instructor_name ? '<h3>'.$name_link.'</h3>' : '';
		$designation = $designation ? '<h4>'.$designation.'</h4>' : '';
		$content = $content ? '<p>'.$content.'</p>' : '';

		$icon_one = $icon_one ? '<i class="'.$icon_one.'"></i>' : '<i class="fas fa-envelope"></i>';
		$link_one = $link_one ? '<a href="'.esc_url($link_one).'" class="contact-link" '.$link_one_attr.'>'.$icon_one.'</a>' : '';
		$icon_two = $icon_two ? '<i class="'.$icon_two.'"></i>' : '<i class="fas fa-phone-alt"></i>';
		$link_two = $link_two ? '<a href="'.esc_url($link_two).'" class="contact-link" '.$link_two_attr.'>'.$icon_two.'</a>' : '';

		if ($instructor_style === 'two') {
			$style_cls = ' instructor-style-two';
		} else {
			$style_cls = '';
		}

		$output = '<div class="naedu-instructor'.$style_cls.'">';
		if ($instructor_style === 'two') {
	    $output .= '<figure>
				            '.$link_one.$link_two.'
				            <div class="naedu-image">'.$image.'</div>
				            <figcaption>
				              '.$instructor_name.$designation.$content.'
				              <div class="social-link">';
			                	// Group Param Output
												if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
												  foreach ( $listItems_groups as $each_list ) {
												  $icon_link = !empty( $each_list['icon_link'] ) ? $each_list['icon_link'] : '';
													$link_url = !empty( $icon_link['url'] ) ? esc_url($icon_link['url']) : '';
													$link_external = !empty( $icon_link['is_external'] ) ? 'target="_blank"' : '';
													$link_nofollow = !empty( $icon_link['nofollow'] ) ? 'rel="nofollow"' : '';
													$link_attr = !empty( $icon_link['url'] ) ?  $link_external.' '.$link_nofollow : '';
												  $social_icon = !empty( $each_list['social_icon'] ) ? $each_list['social_icon'] : '';
													$icon = $social_icon ? '<i class="'.esc_attr($social_icon).'"></i>' : '';

												  $output .= '<a href="'.$link_url.'" '.$link_attr.'>'.$icon.'</a>';
												} }
          $output .= '</div>
				            </figcaption>
				          </figure>';
	  } else {
	  	$output .= '<figure>
				            '.$image.'
				            <figcaption>
				              <div class="instructor-info">'.$instructor_name.$designation.$content.'</div>
				              <div class="instructor-auther">
				                <ul>';
				                	// Group Param Output
													if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
													  foreach ( $listItems_groups as $each_list ) {
													  $icon_link = !empty( $each_list['icon_link'] ) ? $each_list['icon_link'] : '';
														$link_url = !empty( $icon_link['url'] ) ? esc_url($icon_link['url']) : '';
														$link_external = !empty( $icon_link['is_external'] ) ? 'target="_blank"' : '';
														$link_nofollow = !empty( $icon_link['nofollow'] ) ? 'rel="nofollow"' : '';
														$link_attr = !empty( $icon_link['url'] ) ?  $link_external.' '.$link_nofollow : '';
													  $social_icon = !empty( $each_list['social_icon'] ) ? $each_list['social_icon'] : '';
														$icon = $social_icon ? '<i class="'.esc_attr($social_icon).'"></i>' : '';

													  $output .= '<li><a href="'.$link_url.'" '.$link_attr.'>'.$icon.'</a></li>';
													} }
            $output .= '</ul>
				              </div>
				            </figcaption>
				          </figure>';
	  }
	  $output .= '</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Instructor() );

} // enable & disable
