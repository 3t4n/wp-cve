<?php
/*
 * Elementor Medical Addon for Elementor Valuable Box Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Unique_Valuable_Box extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_unique_valuable';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Valuable Box', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-info-box';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-unique-category'];
	}

	/**
	 * Register Medical Addon for Elementor Valuable Box widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_valuable',
			[
				'label' => __( 'Valuable Box Item', 'medical-addon-for-elementor' ),
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'upload_type',
			[
				'label' => __( 'Icon Type', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'image' => esc_html__( 'Image', 'medical-addon-for-elementor' ),
					'icon' => esc_html__( 'Icon', 'medical-addon-for-elementor' ),
				],
				'default' => 'image',
			]
		);
		$repeater->add_control(
			'valuable_image',
			[
				'label' => esc_html__( 'Upload Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'upload_type' => 'image',
				],
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your icon image.', 'medical-addon-for-elementor'),
			]
		);
		$repeater->add_control(
			'valuable_icon',
			[
				'label' => esc_html__( 'Select Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-cog',
				'condition' => [
					'upload_type' => 'icon',
				],
			]
		);
		$repeater->add_control(
			'valuable_title',
			[
				'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Doctors Timetable', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'title_link',
			[
				'label' => esc_html__( 'Title Link', 'medical-elementor-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'need_hours',
			[
				'label' => esc_html__( 'Need Hours?', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'medical-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'medical-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$repeater->start_controls_tabs(
			'hours',
			[
				'label' => esc_html__( 'Hours', 'medical-addon-for-elementor' ),
				'condition' => [
					'need_hours' => 'true',
				],
			]
		);
			$repeater->start_controls_tab(
				'sun',
				[
					'label' => esc_html__( 'Sun', 'medical-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'sunday',
				[
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Sunday', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'sunday_hr',
				[
					'label' => esc_html__( 'Hour', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( '8.00 - 17.00', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type hour text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->end_controls_tab();  // end:Normal tab
			$repeater->start_controls_tab(
				'mon',
				[
					'label' => esc_html__( 'Mon', 'medical-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'monday',
				[
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Monday', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'monday_hr',
				[
					'label' => esc_html__( 'Hour', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( '8.00 - 17.00', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type hour text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->end_controls_tab();  // end:Normal tab
			$repeater->start_controls_tab(
				'tue',
				[
					'label' => esc_html__( 'Tue', 'medical-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'tuesday',
				[
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Tuesday', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'tuesday_hr',
				[
					'label' => esc_html__( 'Hour', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( '8.00 - 17.00', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type hour text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->end_controls_tab();  // end:Normal tab
			$repeater->start_controls_tab(
				'wed',
				[
					'label' => esc_html__( 'Wed', 'medical-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'wednesday',
				[
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Wednesday', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'wednesday_hr',
				[
					'label' => esc_html__( 'Hour', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( '8.00 - 17.00', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type hour text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->end_controls_tab();  // end:Normal tab
			$repeater->start_controls_tab(
				'thu',
				[
					'label' => esc_html__( 'Thu', 'medical-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'thursday',
				[
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Thursday', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'thursday_hr',
				[
					'label' => esc_html__( 'Hour', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( '8.00 - 17.00', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type hour text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->end_controls_tab();  // end:Normal tab
			$repeater->start_controls_tab(
				'fri',
				[
					'label' => esc_html__( 'Fri', 'medical-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'friday',
				[
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Friday', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'friday_hr',
				[
					'label' => esc_html__( 'Hour', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( '8.00 - 17.00', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type hour text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->end_controls_tab();  // end:Normal tab
			$repeater->start_controls_tab(
				'sat',
				[
					'label' => esc_html__( 'Sat', 'medical-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'saturday',
				[
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Saturday', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'saturday_hr',
				[
					'label' => esc_html__( 'Hour', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( '8.00 - 17.00', 'medical-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type hour text here', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$repeater->end_controls_tab();  // end:Normal tab			
		$repeater->end_controls_tabs(); // end tabs
		$repeater->add_control(
			'valuable_content',
			[
				'label' => esc_html__( 'Content', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'need_hours!' => 'true',
				],
			]
		);
		$repeater->add_control(
			'more_icon',
			[
				'label' => esc_html__( 'More Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-arrow-right',
			]
		);
		$repeater->add_control(
			'more_link',
			[
				'label' => esc_html__( 'More Link', 'medical-elementor-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'back_color',
			[
				'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
			]
		);
		$this->add_control(
			'valuable_groups',
			[
				'label' => esc_html__( 'Valuable Box Items', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'valuable_title' => esc_html__( 'Doctors Timetable', 'medical-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ valuable_title }}}',
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'section_style',
				[
					'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'valuable_section_margin',
				[
					'label' => __( 'Margin', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-valuable-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'valuable_section_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-valuable-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Icon
			$this->start_controls_section(
				'icon_style',
				[
					'label' => esc_html__( 'Icon', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'icon_size',
				[
					'label' => esc_html__( 'Icon Size', 'medical-addon-for-elementor' ),
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
						'{{WRAPPER}} .namep-valuable-item .namep-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-valuable-item .namep-icon i' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'valuable_title_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-valuable-item h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sastool_title_typography',
					'selector' => '{{WRAPPER}} .namep-valuable-item h4',
				]
			);
			$this->start_controls_tabs( 'title_style' );
				$this->start_controls_tab(
					'title_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-valuable-item h4, {{WRAPPER}} .namep-valuable-item h4 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'title_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_hover_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-valuable-item h4 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Content', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'valuable_cont_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-valuable-item p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sastool_content_typography',
					'selector' => '{{WRAPPER}} .namep-valuable-item p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-valuable-item p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Icon
			$this->start_controls_section(
				'nav_style',
				[
					'label' => esc_html__( 'Icon', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'process_style' => array('one'),
					],
				]
			);
			$this->add_control(
				'nav_width',
				[
					'label' => esc_html__( 'Width', 'medical-addon-for-elementor' ),
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
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .slick-vertical-slider .slick-arrow' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'nav_size',
				[
					'label' => esc_html__( 'Icon', 'medical-addon-for-elementor' ),
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
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .slick-vertical-slider .slick-arrow:before' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'navi_style' );
				$this->start_controls_tab(
					'ico_secn_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'nav_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .slick-vertical-slider .slick-arrow' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .slick-vertical-slider .slick-arrow' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .slick-vertical-slider .slick-arrow',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'ico_secn_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'nav_hov_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .slick-vertical-slider .slick-arrow:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_hov_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .slick-vertical-slider .slick-arrow:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_hov_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .slick-vertical-slider .slick-arrow:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs

			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Valuable Box widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Valuable Box query
		$settings = $this->get_settings_for_display();
		$valuable = $this->get_settings_for_display( 'valuable_groups' );
		$valuable_style = !empty( $settings['valuable_style'] ) ? $settings['valuable_style'] : '';

		$output = '<div class="namep-valuable-wrap"><div class="nich-row">';
		// Group Param Output
		foreach ( $valuable as $each_logo ) {
			$upload_type = !empty( $each_logo['upload_type'] ) ? $each_logo['upload_type'] : '';
			$valuable_title = !empty( $each_logo['valuable_title'] ) ? $each_logo['valuable_title'] : '';
			$title_link = !empty( $each_logo['title_link']['url'] ) ? esc_url($each_logo['title_link']['url']) : '';
			$title_link_external = !empty( $title_link['is_external'] ) ? 'target="_blank"' : '';
			$title_link_nofollow = !empty( $title_link['nofollow'] ) ? 'rel="nofollow"' : '';
			$title_link_attr = !empty( $title_link['url'] ) ?  $title_link_external.' '.$title_link_nofollow : '';

			$valuable_image = !empty( $each_logo['valuable_image']['id'] ) ? $each_logo['valuable_image']['id'] : '';
			$valuable_icon = !empty( $each_logo['valuable_icon'] ) ? $each_logo['valuable_icon'] : '';
			$valuable_content = !empty( $each_logo['valuable_content'] ) ? $each_logo['valuable_content'] : '';

			$more_icon = !empty( $each_logo['more_icon'] ) ? $each_logo['more_icon'] : '';
			$more_link = !empty( $each_logo['more_link']['url'] ) ? esc_url($each_logo['more_link']['url']) : '';
			$more_link_external = !empty( $more_link['is_external'] ) ? 'target="_blank"' : '';
			$more_link_nofollow = !empty( $more_link['nofollow'] ) ? 'rel="nofollow"' : '';
			$more_link_attr = !empty( $more_link['url'] ) ?  $more_link_external.' '.$more_link_nofollow : '';
			$back_color = !empty( $each_logo['back_color'] ) ? $each_logo['back_color'] : '';

			$image_url = wp_get_attachment_url( $valuable_image );
			$valuable_image = $image_url ? '<img src="'.esc_url($image_url).'" alt="'.esc_attr($valuable_title).'">' : '';
			$valuable_icon = $valuable_icon ? '<i class="'.esc_attr($valuable_icon).'"></i>' : '';

			$need_hours = !empty( $each_logo['need_hours'] ) ? $each_logo['need_hours'] : '';
			$sunday = !empty( $each_logo['sunday'] ) ? $each_logo['sunday'] : '';
			$sunday_hr = !empty( $each_logo['sunday_hr'] ) ? $each_logo['sunday_hr'] : '';
			$sunday_hr = $sunday_hr ? '<span>'.$sunday_hr.'</span>' : '';
			$sunday = $sunday ? '<div class="item"><div class="hours-item">'.$sunday.$sunday_hr.'</div></div>' : '';

			$monday = !empty( $each_logo['monday'] ) ? $each_logo['monday'] : '';
			$monday_hr = !empty( $each_logo['monday_hr'] ) ? $each_logo['monday_hr'] : '';
			$monday_hr = $monday_hr ? '<span>'.$monday_hr.'</span>' : '';
			$monday = $monday ? '<div class="item"><div class="hours-item">'.$monday.$monday_hr.'</div></div>' : '';

			$tuesday = !empty( $each_logo['tuesday'] ) ? $each_logo['tuesday'] : '';
			$tuesday_hr = !empty( $each_logo['tuesday_hr'] ) ? $each_logo['tuesday_hr'] : '';
			$tuesday_hr = $tuesday_hr ? '<span>'.$tuesday_hr.'</span>' : '';
			$tuesday = $tuesday ? '<div class="item"><div class="hours-item">'.$tuesday.$tuesday_hr.'</div></div>' : '';

			$wednesday = !empty( $each_logo['wednesday'] ) ? $each_logo['wednesday'] : '';
			$wednesday_hr = !empty( $each_logo['wednesday_hr'] ) ? $each_logo['wednesday_hr'] : '';
			$wednesday_hr = $wednesday_hr ? '<span>'.$wednesday_hr.'</span>' : '';
			$wednesday = $wednesday ? '<div class="item"><div class="hours-item">'.$wednesday.$wednesday_hr.'</div></div>' : '';

			$thursday = !empty( $each_logo['thursday'] ) ? $each_logo['thursday'] : '';
			$thursday_hr = !empty( $each_logo['thursday_hr'] ) ? $each_logo['thursday_hr'] : '';
			$thursday_hr = $thursday_hr ? '<span>'.$thursday_hr.'</span>' : '';
			$thursday = $thursday ? '<div class="item"><div class="hours-item">'.$thursday.$thursday_hr.'</div></div>' : '';

			$friday = !empty( $each_logo['friday'] ) ? $each_logo['friday'] : '';
			$friday_hr = !empty( $each_logo['friday_hr'] ) ? $each_logo['friday_hr'] : '';
			$friday_hr = $friday_hr ? '<span>'.$friday_hr.'</span>' : '';
			$friday = $friday ? '<div class="item"><div class="hours-item">'.$friday.$friday_hr.'</div></div>' : '';

			$saturday = !empty( $each_logo['saturday'] ) ? $each_logo['saturday'] : '';
			$saturday_hr = !empty( $each_logo['saturday_hr'] ) ? $each_logo['saturday_hr'] : '';
			$saturday_hr = $saturday_hr ? '<span>'.$saturday_hr.'</span>' : '';
			$saturday = $saturday ? '<div class="item"><div class="hours-item">'.$saturday.$saturday_hr.'</div></div>' : '';

			if ($upload_type === 'icon'){
			  $icon_main = $valuable_icon;
			} else {
			  $icon_main = $valuable_image;
			}

	  	$title_link = !empty( $title_link ) ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'.esc_html($valuable_title).'</a>' : esc_html($valuable_title);
	  	$title = !empty( $valuable_title ) ? '<h4 class="valuable-title">'.$title_link.'</h4>' : '';
			$content = $valuable_content ? '<p>'.esc_html($valuable_content).'</p>' : '';
			$more_icon = $more_icon ? '<i class="'.esc_attr($more_icon).'"></i>' : '';
	  	$more_link = !empty( $more_link ) ? '<a href="'.esc_url($more_link).'" class="namep-rounded-link" '.$more_link_attr.'>'.$more_icon.'</a>' : '';
	  	$back_color = $back_color ? ' style="background-color: '.$back_color.';"' : '';

	  	if ($need_hours) {
      	$col_cls = '';
      } else {
      	$col_cls = ' nich-col-md-6';
      }

		  $output .= '<div class="nich-col-lg-4'.$col_cls.'">
			              <div class="namep-valuable-item namep-item"'.$back_color.'>
			                <div class="namep-valuable-inner">
			                  <div class="namep-icon">'.$icon_main.'</div>
			                  '.$title;
			                  if ($need_hours) {
			                  	$output .= '<div class="slick-vertical-slider">'.$sunday.$monday.$tuesday.$wednesday.$thursday.$friday.$saturday.'</div>';
			                  } else {
			                  	$output .= $content;
			                  }
			                  $output .= $more_link.'
			                </div>
			              </div>
			            </div>';
		}
		$output .= '</div></div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Unique_Valuable_Box() );
