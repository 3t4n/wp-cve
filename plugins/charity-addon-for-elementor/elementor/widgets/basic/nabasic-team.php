<?php
/*
 * Elementor Charity Addon for Elementor Team Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Charity_Elementor_Addon_Team extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'nacharity_basic_team';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Team', 'charity-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-user-circle-o';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['nacharity-basic-category'];
	}

	/**
	 * Register Charity Addon for Elementor Team widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_team',
			[
				'label' => __( 'Team Options', 'charity-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'hover_style',
			[
				'label' => __( 'Hover Style', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'None', 'charity-addon-for-elementor' ),
					'two' => esc_html__( 'Content On Hover', 'charity-addon-for-elementor' ),
					'three' => esc_html__( 'Icon On Hover', 'charity-addon-for-elementor' ),
				],
				'default' => 'one',
			]
		);
		$this->add_control(
			'hover_effect',
			[
				'label' => esc_html__( 'Need Icon Vertical?', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
				'return_value' => 'true',
				'condition' => [
					'hover_style' => 'three',
				],
			]
		);
		$this->add_responsive_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon/Image Position', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'charity-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-up',
					],
					'none' => [
						'title' => esc_html__( 'None', 'charity-addon-for-elementor' ),
						'icon' => 'fa fa-circle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'charity-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-down',
					],
				],
				'default' => 'none',
				'condition' => [
					'hover_style' => 'three',
				],
			]
		);
		$this->add_responsive_control(
			'icon_alignment',
			[
				'label' => esc_html__( 'Icon Alignment', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'charity-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'charity-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
				'condition' => [
					'hover_effect' => 'true',
				],
			]
		);
		$this->add_control(
			'team_image',
			[
				'label' => esc_html__( 'Upload Image', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your icon image.', 'charity-addon-for-elementor'),
			]
		);
		$this->add_control(
			'image_hover',
			[
				'label' => esc_html__( 'Image Hover Effect?', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$this->add_control(
			'image_link',
			[
				'label' => esc_html__( 'Image Link', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
				'condition' => [
					'hover_style' => 'one',
				],
			]
		);
		$this->add_control(
			'team_title',
			[
				'label' => esc_html__( 'Title Text', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'William Smith', 'charity-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'team_title_link',
			[
				'label' => esc_html__( 'Title Link', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'team_subtitle',
			[
				'label' => esc_html__( 'Sub Title Text', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'CEO/ Founder', 'charity-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'team_content',
			[
				'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type your content here', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'condition' => [
					'hover_style!' => 'two',
				],
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'social_icon',
			[
				'label' => esc_html__( 'Social Icon', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NACEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-facebook-square',
			]
		);
		$repeater->add_control(
			'icon_link',
			[
				'label' => esc_html__( 'Icon Link', 'charity-addon-for-elementor' ),
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
				'label' => esc_html__( 'Social Iocns', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ social_icon }}}',
			]
		);

		$this->add_responsive_control(
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
				'selectors' => [
					'{{WRAPPER}} .nacep-team-item' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Style
		// Section
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Section', 'charity-addon-for-elementor' ),
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
					'{{WRAPPER}} .nacep-team-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'team_section_margin',
			[
				'label' => __( 'Margin', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-team-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'team_section_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-team-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'secn_style' );
			$this->start_controls_tab(
				'secn_normal',
				[
					'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-team-item' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-team-item',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-team-item',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'secn_hover',
				[
					'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-team-item.nacep-hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_hov_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-team-item.nacep-hover',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_hov_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-team-item.nacep-hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->end_controls_section();// end: Section

		// Image
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Image Max Width', 'charity-addon-for-elementor' ),
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
					'{{WRAPPER}} .nacep-team-item .nacep-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-team-item .nacep-image img, {{WRAPPER}} .nacep-team-item .nacep-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'image_margin',
			[
				'label' => __( 'Margin', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-team-item .nacep-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'img_style' );
			$this->start_controls_tab(
				'img_normal',
				[
					'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'image_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-team-item .nacep-image img',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'image_box_shadow',
					'label' => esc_html__( 'Image Box Shadow', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-team-item .nacep-image img',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'img_hover',
				[
					'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'image_hover_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-team-item.nacep-hover .nacep-image img',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'image_hover_box_shadow',
					'label' => esc_html__( 'Image Box Shadow', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-team-item.nacep-hover .nacep-image img',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Icon
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'social_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-social' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'icon_style' );
			$this->start_controls_tab(
					'icon_normal',
					[
						'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
					]
				);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-social a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bg',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-social a' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'icon_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-social a',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'icon_hover',
				[
					'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'icon_hover_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-social a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bg_hov',
				[
					'label' => esc_html__( 'Background Hover Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-social a:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'icon_hover_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-social a:hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'charity-addon-for-elementor' ),
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
					'{{WRAPPER}} .nacep-social a' => 'font-size:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_width',
			[
				'label' => esc_html__( 'Width', 'charity-addon-for-elementor' ),
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
					'{{WRAPPER}} .nacep-social a' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};line-height:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_margin',
			[
				'label' => __( 'Margin', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-social a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mate-info h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
				'name' => 'sasstp_title_typography',
				'selector' => '{{WRAPPER}} .mate-info h3',
			]
		);
		$this->start_controls_tabs( 'title_style' );
			$this->start_controls_tab(
				'title_normal',
				[
					'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .mate-info h3, {{WRAPPER}} .mate-info h3 a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'title_hover',
				[
					'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title_hover_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .mate-info h3 a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Sub Title
		$this->start_controls_section(
			'section_subtitle_style',
			[
				'label' => esc_html__( 'Sub Title', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'subtitle_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mate-info span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
				'name' => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .mate-info span',
			]
		);
		$this->add_control(
			'subtitle_color',
			[
				'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mate-info span' => 'color: {{VALUE}};',
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
		$this->add_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .mate-info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .mate-info p',
			]
		);
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mate-info p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'content_sep_color',
			[
				'label' => esc_html__( 'Seperator Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mate-info p:before' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$hover_style = !empty( $settings['hover_style'] ) ? $settings['hover_style'] : '';
		$hover_effect = !empty( $settings['hover_effect'] ) ? $settings['hover_effect'] : '';
		$team_image = !empty( $settings['team_image']['id'] ) ? $settings['team_image']['id'] : '';
		$image_link = !empty( $settings['image_link']['url'] ) ? $settings['image_link']['url'] : '';
		$image_link_external = !empty( $settings['image_link']['is_external'] ) ? 'target="_blank"' : '';
		$image_link_nofollow = !empty( $settings['image_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$image_link_attr = !empty( $image_link ) ?  $image_link_external.' '.$image_link_nofollow : '';
		$team_title = !empty( $settings['team_title'] ) ? $settings['team_title'] : '';
		$team_title_link = !empty( $settings['team_title_link']['url'] ) ? $settings['team_title_link']['url'] : '';
		$team_title_link_external = !empty( $settings['team_title_link']['is_external'] ) ? 'target="_blank"' : '';
		$team_title_link_nofollow = !empty( $settings['team_title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$team_title_link_attr = !empty( $team_title_link ) ?  $team_title_link_external.' '.$team_title_link_nofollow : '';
		$team_subtitle = !empty( $settings['team_subtitle'] ) ? $settings['team_subtitle'] : '';
		$team_content = !empty( $settings['team_content'] ) ? $settings['team_content'] : '';
		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';
		$icon_alignment = !empty( $settings['icon_alignment'] ) ? $settings['icon_alignment'] : '';
		$icon_position = !empty( $settings['icon_position'] ) ? $settings['icon_position'] : '';
		$image_hover = !empty( $settings['image_hover'] ) ? $settings['image_hover'] : '';

		// Image
		$image_url = wp_get_attachment_url( $team_image );

		$title_link = $team_title_link ? '<a href="'.esc_url($team_title_link).'" '.$team_title_link_attr.'>'.esc_html($team_title).'</a>' : esc_html($team_title);
		$title = $team_title ? '<h3 class="team-title">'.$title_link.'</h3>' : '';
		$subtitle = $team_subtitle ? '<span>'.esc_html($team_subtitle).'</span>' : '';
		$content = $team_content ? '<p>'.esc_html($team_content).'</p>' : '';

		if ($hover_style === 'two') {
			$style_class = ' style-two';
		} elseif ($hover_style === 'three') {
			$style_class = ' style-three';
		} else {
			$style_class = '';
		}
		if ($hover_effect) {
			$hover_class = ' icon-vert';
		} else {
			$hover_class = '';
		}
		if ($image_hover) {
			$img_hover = ' zoom-image';
		} else {
			$img_hover = '';
		}
		if ($icon_alignment === 'right') {
			$align_class = ' icon-right';
		} else {
			$align_class = '';
		}
		if ($icon_position === 'top') {
			$pos_class = ' pos-top';
		} elseif ($icon_position === 'bottom') {
			$pos_class = ' pos-bottom';
		} else {
			$pos_class = '';
		}
		// Turn output buffer on
		ob_start(); ?>

		<div class="nacep-team-item<?php echo esc_attr($style_class.$img_hover); ?>">
			<?php if ($team_image) { ?>
				<div class="nacep-image">
					<?php if ($image_link) { ?>
						<a href="<?php echo esc_url($image_link); ?>" <?php echo esc_attr($image_link_attr); ?>><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($team_title); ?>"></a>
					<?php } else { ?>
						<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($team_title); ?>">
					<?php } if ($hover_style === 'three') { ?>
						<div class="nacep-social rounded<?php echo esc_attr($hover_class.$align_class.$pos_class); ?>">
						<?php
						// Group Param Output
						if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
						  foreach ( $listItems_groups as $each_list ) {

						  $icon_link = !empty( $each_list['icon_link'] ) ? $each_list['icon_link'] : '';
							$link_url = !empty( $icon_link['url'] ) ? esc_url($icon_link['url']) : '';
							$link_external = !empty( $icon_link['is_external'] ) ? 'target="_blank"' : '';
							$link_nofollow = !empty( $icon_link['nofollow'] ) ? 'rel="nofollow"' : '';
							$link_attr = !empty( $icon_link['url'] ) ?  $link_external.' '.$link_nofollow : '';
						  $social_icon = !empty( $each_list['social_icon'] ) ? $each_list['social_icon'] : '';
							$icon = $social_icon ? '<i class="'.esc_attr($social_icon).'" aria-hidden="true"></i>' : '';
				   		?>

						  <a href="<?php echo esc_url($link_url); ?>" <?php echo $link_attr; ?>>
								<?php echo $icon; ?>
						  </a>

						<?php } } ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
			<div class="mate-info">
				<?php echo $title.$subtitle.$content;
				if ($hover_style !== 'three') { ?>
				<div class="nacep-social rounded">
					<?php
					// Group Param Output
					if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
					  foreach ( $listItems_groups as $each_list ) {

					  $icon_link = !empty( $each_list['icon_link'] ) ? $each_list['icon_link'] : '';
						$link_url = !empty( $icon_link['url'] ) ? esc_url($icon_link['url']) : '';
						$link_external = !empty( $icon_link['is_external'] ) ? 'target="_blank"' : '';
						$link_nofollow = !empty( $icon_link['nofollow'] ) ? 'rel="nofollow"' : '';
						$link_attr = !empty( $icon_link['url'] ) ?  $link_external.' '.$link_nofollow : '';
					  $social_icon = !empty( $each_list['social_icon'] ) ? $each_list['social_icon'] : '';
						$icon = $social_icon ? '<i class="'.esc_attr($social_icon).'" aria-hidden="true"></i>' : '';
			   		?>

					  <a href="<?php echo esc_url($link_url); ?>" <?php echo $link_attr; ?>>
							<?php echo $icon; ?>
					  </a>

					<?php } } ?>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php
		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Team() );
