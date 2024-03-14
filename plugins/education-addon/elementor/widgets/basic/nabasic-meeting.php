<?php
/*
 * Elementor Education Addon Meeting Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_meeting'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Meeting extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_meeting';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Meeting', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-instagram-video';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Meeting widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_meeting',
			[
				'label' => __( 'Meeting Item', 'education-addon' ),
			]
		);
		$this->add_control(
			'meeting_style',
			[
				'label' => esc_html__( 'Meeting Style', 'education-addon' ),
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
			'meeting_image',
			[
				'label' => esc_html__( 'Meeting Image', 'education-addon' ),
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
			'meeting_id_title',
			[
				'label' => esc_html__( 'Meeting ID Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Meeting Id : ', 'education-addon' ),
				'placeholder' => esc_html__( 'Meeting Id : ', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'meeting_id',
			[
				'label' => esc_html__( 'Meeting ID', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'xxx xxx xxxx', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'meeting_title',
			[
				'label' => esc_html__( 'Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '#1 World Class Education for Anyone, Anywhere.', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'title_link',
			[
				'label' => esc_html__( 'Title Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'host_title',
			[
				'label' => esc_html__( 'Host Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hosted by : ', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'host_image',
			[
				'label' => esc_html__( 'Host Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'education-addon'),
				'condition' => [
					'meeting_style' => 'two',
				],
			]
		);
		$this->add_control(
			'host_name',
			[
				'label' => esc_html__( 'Host Name', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Donald Logan', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'host_link',
			[
				'label' => esc_html__( 'Host Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'meeting_content',
			[
				'label' => esc_html__( 'Content', 'education-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type content text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->start_controls_tabs( 'meetmeta_style' );
			$this->start_controls_tab(
				'meetmeta_date',
				[
					'label' => esc_html__( 'Date', 'education-addon' ),
				]
			);
			$this->add_control(
				'date_icon',
				[
					'label' => esc_html__( 'Date Icon', 'education-addon' ),
					'type' => Controls_Manager::ICON,
					'options' => NAEDU_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
				]
			);
			$this->add_control(
				'meeting_date',
				[
					'label' => esc_html__( 'Date ', 'education-addon' ),
					'type' => Controls_Manager::DATE_TIME,
					'picker_options' => [
						'dateFormat' => 'M d, Y',
						'enableTime' => 'false',
					],
					'placeholder' => esc_html__( 'Aug 15, 2019', 'education-addon' ),
					'label_block' => true,
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'meetmeta_time',
				[
					'label' => esc_html__( 'Time', 'education-addon' ),
					'condition' => [
						'meeting_style' => 'two',
					],
				]
			);
			$this->add_control(
				'time_icon',
				[
					'label' => esc_html__( 'Time Icon', 'education-addon' ),
					'type' => Controls_Manager::ICON,
					'options' => NAEDU_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
				]
			);
			$this->add_control(
				'meeting_time',
				[
					'label' => esc_html__( 'Time', 'education-addon' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( '7:30 PM', 'education-addon' ),
					'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
					'label_block' => true,
				]
			);
			$this->end_controls_tab();  // end:Hover tab
			$this->start_controls_tab(
				'meetmeta_play_time',
				[
					'label' => esc_html__( 'Play Time', 'education-addon' ),
				]
			);
			$this->add_control(
				'play_time_icon',
				[
					'label' => esc_html__( 'Play Time Icon', 'education-addon' ),
					'type' => Controls_Manager::ICON,
					'options' => NAEDU_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
				]
			);
			$this->add_control(
				'meeting_play_time',
				[
					'label' => esc_html__( 'Play Time', 'education-addon' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( '2:40minutes', 'education-addon' ),
					'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
					'label_block' => true,
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->add_control(
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
		$this->add_control(
			'btn_link',
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
						'{{WRAPPER}} .naedu-meeting figure' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naedu-meeting figure' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-meeting figure' => 'background-color: {{VALUE}};',
					],
				]
			);			
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-meeting figure',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-meeting figure',
				]
			);
			$this->end_controls_section();// end: Section

		// Play Time
			$this->start_controls_section(
				'section_ptime_style',
				[
					'label' => esc_html__( 'Play Time', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'meeting_style' => 'two',
					],
				]
			);
			$this->add_responsive_control(
				'ptime_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .meeting-duration' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ptime_typography',
					'selector' => '{{WRAPPER}} .meeting-duration',
				]
			);
			$this->add_control(
				'ptime_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .meeting-duration' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'ptime_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .meeting-duration' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Meeting ID
			$this->start_controls_section(
				'meetid_style',
				[
					'label' => esc_html__( 'Meeting ID', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'meetid_padding',
				[
					'label' => __( 'Title Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-meeting h3, {{WRAPPER}} .meeting-id' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'metid_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-meeting h3, {{WRAPPER}} .meeting-id' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->start_controls_tabs( 'metid_style' );
				$this->start_controls_tab(
					'metid_ttl',
					[
						'label' => esc_html__( 'Title', 'education-addon' ),
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'education-addon' ),
						'name' => 'meetid_typography',
						'selector' => '{{WRAPPER}} .naedu-meeting h3, {{WRAPPER}} .meeting-id',
					]
				);
				$this->add_control(
					'metid_title_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-meeting h3, {{WRAPPER}} .meeting-id' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'metid_id',
					[
						'label' => esc_html__( 'ID', 'education-addon' ),
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'education-addon' ),
						'name' => 'meetid_id_typography',
						'selector' => '{{WRAPPER}} .naedu-meeting h3 span, {{WRAPPER}} .meeting-id span',
					]
				);
				$this->add_control(
					'metid_id_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-meeting h3 span, {{WRAPPER}} .meeting-id span' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
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
						'{{WRAPPER}} .naedu-meeting h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .naedu-meeting h4',
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
							'{{WRAPPER}} .naedu-meeting h4, {{WRAPPER}} .naedu-meeting h4 a' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .naedu-meeting h4 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Host
			$this->start_controls_section(
				'section_host_style',
				[
					'label' => esc_html__( 'Host', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'host_padding',
				[
					'label' => __( 'Host Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-meeting h5, {{WRAPPER}} .naedu-avatar span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'host_typography',
					'selector' => '{{WRAPPER}} .naedu-meeting h5, {{WRAPPER}} .naedu-avatar span',
				]
			);
			$this->start_controls_tabs( 'hst_style' );
				$this->start_controls_tab(
					'hst_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'hst_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-meeting h5, {{WRAPPER}} .naedu-avatar span, {{WRAPPER}} .naedu-meeting h5 a, {{WRAPPER}} .naedu-avatar span a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'hst_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'hst_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-meeting h5 a:hover, {{WRAPPER}} .naedu-avatar span a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Meta
			$this->start_controls_section(
				'section_meta_style',
				[
					'label' => esc_html__( 'Meta', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'meta_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-meeting .naedu-meta li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'meta_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-meeting .naedu-meta li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'meta_typography',
					'selector' => '{{WRAPPER}} .naedu-meeting .naedu-meta li',
				]
			);
			$this->add_control(
				'meta_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-meeting .naedu-meta li' => 'color: {{VALUE}};',
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
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-meeting p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .naedu-meeting p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-meeting p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Button
			$this->start_controls_section(
				'section_btn_style',
				[
					'label' => esc_html__( 'Button', 'education-addon' ),
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
						'{{WRAPPER}} .naedu-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naedu-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'btn_typography',
					'selector' => '{{WRAPPER}} .naedu-btn',
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
							'{{WRAPPER}} .naedu-btn' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn',
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
							'{{WRAPPER}} .naedu-btn:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn:hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_hover_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Meeting widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Meeting query
		$settings = $this->get_settings_for_display();
		$meeting_style = !empty( $settings['meeting_style'] ) ? $settings['meeting_style'] : '';
		$meeting_image = !empty( $settings['meeting_image']['id'] ) ? $settings['meeting_image']['id'] : '';
		$meeting_id_title = !empty( $settings['meeting_id_title'] ) ? $settings['meeting_id_title'] : '';
		$meeting_id = !empty( $settings['meeting_id'] ) ? $settings['meeting_id'] : '';

		$meeting_title = !empty( $settings['meeting_title'] ) ? $settings['meeting_title'] : '';
		$title_link = !empty( $settings['title_link']['url'] ) ? $settings['title_link']['url'] : '';
		$title_link_external = !empty( $settings['title_link']['is_external'] ) ? 'target="_blank"' : '';
		$title_link_nofollow = !empty( $settings['title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$title_link_attr = !empty( $title_link ) ?  $title_link_external.' '.$title_link_nofollow : '';

		$host_title = !empty( $settings['host_title'] ) ? $settings['host_title'] : '';
		$host_image = !empty( $settings['host_image']['id'] ) ? $settings['host_image']['id'] : '';
		$host_name = !empty( $settings['host_name'] ) ? $settings['host_name'] : '';
		$host_link = !empty( $settings['host_link']['url'] ) ? $settings['host_link']['url'] : '';
		$host_link_external = !empty( $settings['host_link']['is_external'] ) ? 'target="_blank"' : '';
		$host_link_nofollow = !empty( $settings['host_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$host_link_attr = !empty( $host_link ) ?  $host_link_external.' '.$host_link_nofollow : '';

		$meeting_content = !empty( $settings['meeting_content'] ) ? $settings['meeting_content'] : '';
		$date_icon = !empty( $settings['date_icon'] ) ? $settings['date_icon'] : '';
		$meeting_date = !empty( $settings['meeting_date'] ) ? $settings['meeting_date'] : '';
		$time_icon = !empty( $settings['time_icon'] ) ? $settings['time_icon'] : '';
		$meeting_time = !empty( $settings['meeting_time'] ) ? $settings['meeting_time'] : '';
		$play_time_icon = !empty( $settings['play_time_icon'] ) ? $settings['play_time_icon'] : '';
		$meeting_play_time = !empty( $settings['meeting_play_time'] ) ? $settings['meeting_play_time'] : '';

		$btn_text = !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
		$btn_link = !empty( $settings['btn_link']['url'] ) ? esc_url($settings['btn_link']['url']) : '';
		$btn_link_external = !empty( $btn_link['is_external'] ) ? 'target="_blank"' : '';
		$btn_link_nofollow = !empty( $btn_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$btn_link_attr = !empty( $btn_link['url'] ) ?  $btn_link_external.' '.$btn_link_nofollow : '';

		$image_url = wp_get_attachment_url( $meeting_image );
		$image = $image_url ? '<img src="'.esc_url($image_url).'" alt="Title">' : '';
		$button = $btn_link ? '<a href="'.esc_url($btn_link).'" class="naedu-btn" '.$btn_link_attr.'>'.esc_html($btn_text).'</a>' : '';

		$meeting_id = $meeting_id ? '<span>'.$meeting_id.'</span>' : '';
		$meeting_id_one = ($meeting_id_title || $meeting_id) ? '<h3>'.$meeting_id_title.$meeting_id.'</h3>' : '';
		$meeting_id_two = ($meeting_id_title || $meeting_id) ? '<div class="meeting-id">'.$meeting_id_title.$meeting_id.'</div>' : '';

		$title_link = $title_link ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'. esc_html($meeting_title) .'</a>' : esc_html($meeting_title);
		$title = $meeting_title ? '<h4>'.$title_link.'</h4>' : '';

		$host_name_link = $host_link ? '<a href="'.esc_url($host_link).'" '.$host_link_attr.'>'. esc_html($host_name) .'</a>' : esc_html($host_name);
		$host_image_url = wp_get_attachment_url( $host_image );
		$host_image = $host_image_url ? '<img src="'.esc_url($host_image_url).'" alt="Image">' : '';
		$host_img_link = $host_link ? '<a href="'.esc_url($host_link).'" '.$host_link_attr.'>'. $host_image .'</a>' : $host_image;
		$host_text = ($host_title || $host_name) ? '<h5>'.$host_title.$host_name_link.'</h5> ' : '';
		$host_text_two = ($host_title || $host_name) ? '<span>'.$host_title.$host_name_link.'</span> ' : '';

		$meeting_content = $meeting_content ? '<p>'.$meeting_content.'</p>' : '';

		$date_icon = $date_icon ? '<i class="'.$date_icon.'"></i>' : '<i class="fas fa-calendar-alt"></i>';
		$meeting_date = $meeting_date ? '<span>'.$meeting_date.'</span>' : '';

		$play_time_icon = $play_time_icon ? '<i class="'.$play_time_icon.'"></i>' : '<i class="fas fa-play-circle"></i>';
		$meeting_play_time_one = $meeting_play_time ? '<span>'.$meeting_play_time.'</span>' : '';
		$meeting_play_time_two = $meeting_play_time ? '<span class="meeting-duration">'.$play_time_icon.' '.$meeting_play_time.'</span>' : '';

		$time_icon = $time_icon ? '<i class="'.$time_icon.'"></i>' : '<i class="fas fa-clock"></i>';
		$meeting_time = $meeting_time ? '<span>'.$meeting_time.'</span>' : '';

		if ($meeting_style === 'two') {
			$style_cls = ' meeting-style-two';
		} else {
			$style_cls = '';
		}

		$output = '<div class="naedu-meeting'.$style_cls.'">';
		if ($meeting_style === 'two') {
	    $output .= '<figure>
				            <div class="naedu-image">'.$image.$meeting_play_time_two.'</div>
				            <figcaption>
				              <div class="meeting-info">
				                <ul class="naedu-meta">
				                  <li>'.$date_icon.$meeting_date.'</li>
				                	<li>'.$time_icon.$meeting_time.'</li>
				                </ul>
				              	'.$title.'
				                <div class="naedu-avatar">'.$host_img_link.$host_text_two.'</div>
				              	'.$meeting_content.'
				              </div>
				              <div class="meeting-auther">'.$meeting_id_two.$button.'</div>
				            </figcaption>
				          </figure>';
	  } else {
	  	$output .= '<figure>
				            <div class="naedu-image">'.$image.$button.'</div>
				            <figcaption>
				              <div class="meeting-info">
				              	'.$meeting_id_one.$title.$host_text.$meeting_content.'
				              </div>
				              <ul class="naedu-meta">
				                <li>'.$date_icon.$meeting_date.'</li>
				                <li>'.$play_time_icon.$meeting_play_time_one.'</li>
				              </ul>
				            </figcaption>
				          </figure>';
	  }
	  $output .= '</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Meeting() );

} // enable & disable
