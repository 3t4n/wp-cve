<?php

use AbsoluteAddons\Controls\Group_Control_ABSP_Foreground;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$postfix = '_three';

$this->start_controls_section(
	'section_style_team_body' . $postfix,
	[
		'label'     => esc_html__('Body Area', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'three' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_body_tab' . $postfix
);

$this->start_controls_tab(
	'section_style_team_body_normal_tab' . $postfix,
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'section_style_team_body_background' . $postfix,
		'label'          => __('Background', 'absolute-addons'),
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'default' => 'classic',
			],
			'color'      => [
				'default' => '#C6E0FB',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-team.element-three .absp-team-info',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'body_border' . $postfix,
		'label'     => __('Body Border', 'absolute-addons'),
		'selectors' => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-info',
		],
	]
);

$this->add_responsive_control(
	'body_section_border_radius' . $postfix,
	[
		'label'      => esc_html__('Body Section Border Radius', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'body_box_shadow' . $postfix,
		'label'    => __('Box Shadow', 'absolute-addons'),
		'selector' => '{{WRAPPER}} .absp-team.element-three .absp-team-info',
	]
);

$this->add_responsive_control(
	'body_section_padding' . $postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin' . $postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_body_hover_tab' . $postfix,
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'section_style_team_body_background_hover' . $postfix,
		'label'    => __('Background', 'absolute-addons'),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-team.element-three .absp-team-info:hover',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'body_border_hover' . $postfix,
		'label'    => __('Body Border', 'absolute-addons'),
		'selector' => '{{WRAPPER}}.absp-team.element-three .absp-team-info:hover',

	]
);

$this->add_responsive_control(
	'body_section_border_radius_hover' . $postfix,
	[
		'label'      => esc_html__('Body Section Border Radius', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}.absp-team.element-three .absp-team-info:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'body_box_shadow_hover' . $postfix,
		'label'    => __('Box Shadow', 'absolute-addons'),
		'selector' => '{{WRAPPER}} .absp-team.element-three .absp-team-info:hover',
	]
);

$this->add_responsive_control(
	'body_section_padding_hover' . $postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-info:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin_hover' . $postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-info:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	'section_style_team_Member_Name' . $postfix,
	array(
		'label'     => esc_html__('Name', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'three' ],
	)

);

$this->start_controls_tabs(
	'section_style_team_name_tab' . $postfix
);

$this->start_controls_tab(
	'section_style_team_name_normal_tab' . $postfix,
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__(' Name Typography', 'absolute-addons'),
		'name'        => 'name_typography' . $postfix,
		'selector'    => '{{WRAPPER}} .absp-team.element-three .absp-team-title',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'name_color' . $postfix,
	[
		'label'       => esc_html__(' Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-title' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select  Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_padding' . $postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'name_section_margin' . $postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_Member_hover_Name_tab' . $postfix,
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('First Name Typography', 'absolute-addons'),
		'name'        => 'name_typography_hover' . $postfix,
		'selector'    => '{{WRAPPER}} .absp-team.element-three .absp-team-title:hover',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'name_color_hover' . $postfix,
	[
		'label'       => esc_html__(' Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-title:hover' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select First Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_padding_hover' . $postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-title:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'name_section_margin_hover' . $postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-title:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	'section_style_team_designation' . $postfix,
	[
		'label'     => __('Designation', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'three' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_designation_tab' . $postfix
);

$this->start_controls_tab(
	'section_style_team_designation_normal_tab' . $postfix,
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography' . $postfix,
		'selector' => '{{WRAPPER}} .absp-team.element-three .absp-team-designation',
	)
);

$this->add_group_control(
	Group_Control_ABSP_Foreground::get_type(),
	[
		'name'           => 'designation_color' . $postfix,
		'fields_options' => [
			'color_type' => [
				'default' => 'classic',
				'label'   => _x('Designation Color', 'Background Control', 'absolute-addons'),
			],
			'color'      => [
				'default' => '#FFF',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-team.element-three .absp-team-designation',
	]
);

$this->add_responsive_control(
	'designation_section_padding' . $postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-three .absp-team-designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'designation_section_margin' . $postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_designation_hover_tab' . $postfix,
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography_hover' . $postfix,
		'selector' => '{{WRAPPER}} .absp-team.element-three .absp-team-designation',
	)
);

$this->add_group_control(
	Group_Control_ABSP_Foreground::get_type(),
	[
		'name'           => 'designation_color_hover' . $postfix,
		'fields_options' => [
			'color_type' => [
				'default' => 'classic',
				'label'   => _x('Designation Color', 'Background Control', 'absolute-addons'),
			],
			'color'      => [
				'default' => '#fff',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-team.element-three .absp-team-designation',

	]
);

$this->add_responsive_control(
	'designation_section_padding_hover' . $postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'designation_section_margin_hover' . $postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-three .absp-team-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

// Social Icon Area

$this->start_controls_section(
	'section_style_team_social_media' . $postfix,
	[
		'label'     => __('Social Icon Area', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'three' ],
	]
);

$this->add_control(
	'icon_size' . $postfix,
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
			'{{WRAPPER}} .absp-team.element-three .absp-team-social a' => 'font-size: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_padding' . $postfix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-three .absp-team-social' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_margin' . $postfix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-three .absp-team-social' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();




