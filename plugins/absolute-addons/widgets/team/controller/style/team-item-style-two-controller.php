<?php

use AbsoluteAddons\Controls\Group_Control_ABSP_Foreground;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

$postfix = '_two';

$this->start_controls_section(
	'section_style_team_body'.$postfix,
	[
		'label'     => esc_html__( 'Body Area', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'two' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_body_tab'.$postfix
);

$this->start_controls_tab(
	'section_style_team_body_normal_tab'.$postfix,
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'section_style_team_body_background'.$postfix,
		'label'          => __( 'Background', 'absolute-addons' ),
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background'   => [
				'default' => 'gradient',
			],
			'color'        => [
				'default' => '#fff',
			],
			'color_stop'   => [
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
			],
			'color_b'      => [
				'default' => '#4B4BEB',
			],
			'color_b_stop' => [
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
			],
		],
		'selector'       => '{{WRAPPER}} .absp-team-item .hover-single-box',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'body_border'.$postfix,
		'label'     => __( 'Body Border', 'absolute-addons' ),
		'selectors' => [
			'{{WRAPPER}} .absp-team-item .hover-single-box',
		],
	]
);

$this->add_responsive_control(
	'body_section_border_radius'.$postfix,
	[
		'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .hover-single-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'body_box_shadow'.$postfix,
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-team-item .hover-single-box',
	]
);

$this->add_responsive_control(
	'body_section_padding'.$postfix,
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .hover-single-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin'.$postfix,
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .hover-single-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_body_hover_tab'.$postfix,
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'section_style_team_body_background_hover'.$postfix,
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .single-box',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'body_border_hover'.$postfix,
		'label'    => __('Body Border', 'absolute-addons'),
		'selector' => '{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .single-box',

	]
);

$this->add_responsive_control(
	'body_section_border_radius_hover'.$postfix,
	[
		'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .single-box'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'body_box_shadow_hover'.$postfix,
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .single-box',
	]
);

$this->add_responsive_control(
	'body_section_padding_hover'.$postfix,
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .single-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin_hover'.$postfix,
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .single-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

/**
 * Team Member Name Style
 */

$this->start_controls_section(
	'section_style_team_Member_Name'.$postfix,
	array(
		'label'     => esc_html__('Name', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'two' ],
	)

);

$this->start_controls_tabs(
	'section_style_team_name_tab'.$postfix
);

$this->start_controls_tab(
	'section_style_team_name_normal_tab'.$postfix,
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('First Name Typography', 'absolute-addons'),
		'name'        => 'first_name_typography'.$postfix,
		'selector'    => '{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .hover-content span',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('Last Name Typography', 'absolute-addons'),
		'name'        => 'last_name_typography'.$postfix,
		'selector'    => '{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .hover-content h1',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'first_name_color'.$postfix,
	[
		'label'       => esc_html__('Fist Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .hover-content span' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select First Name Text color.', 'absolute-addons'),
	]
);

$this->add_control(
	'last_name_color'.$postfix,
	[
		'label'       => esc_html__('Last Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .hover-content h1' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select Last Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_padding'.$postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-one .absp-team-item .holder .data .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'name_section_margin'.$postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-one .absp-team-item .holder .data .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_Member_hover_Name_tab'.$postfix,
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('First Name Typography', 'absolute-addons'),
		'name'        => 'first_name_typography_hover'.$postfix,
		'selector'    => '{{WRAPPER}} .absp-team-item .hover-single-box .single-box .image-part .img-meta h5',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('Last Name Typography', 'absolute-addons'),
		'name'        => 'last_name_typography_hover'.$postfix,
		'selector'    => '{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .single-box .image-part .img-meta h2',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'first_name_color_hover'.$postfix,
	[
		'label'       => esc_html__(' Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team-item .hover-single-box .single-box .image-part .img-meta h5' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select First Name Text color.', 'absolute-addons'),
	]
);

$this->add_control(
	'last_name_color_hover'.$postfix,
	[
		'label'       => esc_html__(' Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .single-box .image-part .img-meta h2' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select Last Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_padding_hover'.$postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-one .absp-team-item .holder .data .title:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'name_section_margin_hover'.$postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-one .absp-team-item .holder .data .title:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

/**
 *  Designation Style
*/

$this->start_controls_section(
	'section_style_team_designation'.$postfix,
	[
		'label'     => __('Designation', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'two' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_designation_tab'.$postfix
);

$this->start_controls_tab(
	'section_style_team_designation_normal_tab'.$postfix,
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography'.$postfix,
		'selector' => '{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .hover-content a',
	)
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'designation_background_color'.$postfix,
		'fields_options' => [
			'background'     => [
				'default' => 'gradient',
				'label'   => _x( 'Designation background ', 'Background Control', 'absolute-addons' ),
			],
			'color'          => [
				'default' => '#a353f3',
			],
			'color_stop'     => [
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
			],
			'color_b'        => [
				'default' => '#4B4BEB',
			],
			'color_b_stop'   => [
				'default' => [
					'unit' => '%',
					'size' => 99.95,
				],
			],
			'gradient_angle' => [
				'default' => [
					'unit' => 'deg',
					'size' => 90,
				],
			],
		],
		'selector'       => '{{WRAPPER}}  .absp-team.element-two .absp-team-item .hover-single-box .hover-content a',
	]
);

$this->add_group_control(
	Group_Control_ABSP_Foreground::get_type(),
	[
		'name'           => 'designation_color'.$postfix,
		'fields_options' => [
			'color_type' => [
				'default' => 'classic',
				'label'   => _x( 'Designation Color', 'Background Control', 'absolute-addons' ),
			],
			'color'      => [
				'default' => '#FFF',
			],
		],
		'selector'       => '{{WRAPPER}}  .absp-team.element-two .absp-team-item .hover-single-box .hover-content a',
	]
);

$this->add_responsive_control(
	'designation_section_padding'.$postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .hover-content a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'designation_section_margin'.$postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-two .absp-team-item .hover-single-box .hover-content a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_designation_hover_tab'.$postfix,
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography_hover'.$postfix,
		'selector' => '{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .single-box .image-part .img-meta span',
	)
);

$this->add_group_control(
	Group_Control_ABSP_Foreground::get_type(),
	[
		'name'           => 'designation_color_hover'.$postfix,
		'fields_options' => [
			'color_type' => [
				'default' => 'classic',
				'label'   => _x( 'Designation Color', 'Background Control', 'absolute-addons' ),
			],
			'color'      => [
				'default' => '#fff',
			],
		],
		'selector'       => '{{WRAPPER}}  .absp-team.element-two .absp-team-item .hover-single-box .single-box .image-part .img-meta span',

	]
);

$this->add_responsive_control(
	'designation_section_padding_hover'.$postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-two .absp-team-item .hover-single-box .single-box .image-part .img-meta span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'designation_section_margin_hover'.$postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-two .absp-team-item .hover-single-box .single-box .image-part .img-meta span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

// Separator

$this->start_controls_section(
	'section_name_team_separator'.$postfix,
	[
		'label'     => esc_html__('Separator', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'two' ],
	]
);

$this->add_control(
	'separator_enable'.$postfix,
	[
		'label'     => __('Enable Separator', 'absolute-addons'),
		'type'      => Controls_Manager::SELECT,
		'options'   => array(
			'yes' => esc_html__('Yes', 'absolute-addons'),
			'no'  => esc_html__('No', 'absolute-addons'),

		),
		'default'   => 'yes',
		'condition' => [ 'team_style_variation' => 'two' ],
	]
);

$this->add_control(
	'separator_color'.$postfix,
	[
		'label'       => esc_html__(' Separator Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => array(
			'{{WRAPPER}} .absp-team-item .hover-single-box .single-box .border-bottom' => 'border-color: {{VALUE}}',
		),
		'description' => esc_html__('Select Separator color.', 'absolute-addons'),
		'condition'   => [ 'separator_enable_two' => 'yes' ],
	]
);

$this->add_control(
	'separator_width'.$postfix,
	[
		'label'      => __('Width', 'absolute-addons'),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ '%', 'px' ],
		'range'      => [
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
			'px' => [
				'min' => 0,
				'max' => 1000,
			],
		],
		'default'    => [
			'unit' => '%',
			'size' => 20,
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .hover-single-box .single-box .border-bottom' => 'width: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [ 'separator_enable_two' => 'yes' ],
	]
);

$this->add_responsive_control(
	'separator_section_padding'.$postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .hover-single-box .single-box .border-bottom' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [ 'separator_enable_two' => 'yes' ],
	]
);

$this->add_responsive_control(
	'separator_section_margin'.$postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .hover-single-box .single-box .border-bottom' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [ 'separator_enable_two' => 'yes' ],
	]
);

$this->end_controls_section();

// Content Style

$this->start_controls_section(
	'section_style_team_content'.$postfix,
	[
		'label'     => __('Content', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'two' ],
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	array(
		'label'    => esc_html__('Content Typography', 'absolute-addons'),
		'name'     => 'content_typography'.$postfix,
		'selector' => '{{WRAPPER}} .absp-team-item .hover-single-box .single-box .image-para .desc, {{WRAPPER}} .absp-team-item .hover-single-box .single-box .image-para .desc p ',

	)
);

$this->add_control(
	'content_color'.$postfix,
	array(
		'label'       => esc_html__(' Content Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => array(
			'{{WRAPPER}} .absp-team-item .hover-single-box .single-box .image-para .desc' => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-team-item .hover-single-box .single-box .image-para .desc p' => 'color: {{VALUE}}',
		),
		'description' => esc_html__('Select Content Text color.', 'absolute-addons'),
	)
);

$this->add_responsive_control(
	'content_section_padding'.$postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .single-box .image-para p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'content_section_margin'.$postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-two .absp-team-item .hover-single-box .single-box .image-para p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

// Social Icon Area

$this->start_controls_section(
	'section_style_team_social_media'.$postfix,
	[
		'label'     => __('Social Icon Area', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'two' ],
	]
);

$this->add_control(
	'Icon_size'.$postfix,
	[
		'label'      => __('Icon Size', 'absolute-addons'),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 0,
				'max' => 100,
			],
		],
		[
			'unit' => 'px',
			'size' => 14,
		],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team-item .hover-single-box .single-box .social-link ul li a' => 'font-size: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_padding'.$postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}   .absp-team-item .hover-single-box .single-box .social-link ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_margin'.$postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team-item .hover-single-box .single-box .social-link ul li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();




