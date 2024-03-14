<?php
/*
 * @var $this
 */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

use AbsoluteAddons\Controls\Group_Control_ABSP_Foreground;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'section_style_team_body_twenty-one',
	[
		'label'     => esc_html__( 'Team Item Image', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'twenty-one' ],
	]
);

$this->add_control(
	'change_position',[
		'label'   => __('Change Position','absolute-addons'),
		'type'    => Controls_Manager::CHOOSE,
		'options' => [
			'normal'  => [
				'title' => __( 'Left', 'absolute-addons' ),
				'icon'  => 'absp absp-dotted-left',
			],

			'reverse' => [
				'title' => __( 'Right', 'absolute-addons' ),
				'icon'  => 'absp absp-dotted-right',
			],
		],
		'default' => 'reverse',
	]

);

$this->start_controls_tabs(
	'section_style_team_body_tab_twenty-one'
);

$this->start_controls_tab(
	'section_style_team_body_normal_tab_twenty-one',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'body_section_padding_twenty-one',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-thumb img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin_twenty-one',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-thumb img ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'team_image_border_radius_twenty-one',
	[
		'label'      => esc_html__( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_body_hover_tab_twenty-one',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'body_section_padding_twenty-one-hover',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-thumb img:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin_twenty-one-hover',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-thumb img:hover ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'team_image_border_radius_twenty-one-hover',
	[
		'label'      => esc_html__( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-thumb img:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-thumb:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	'section_style_team_Member_twenty-one_Name',
	array(
		'label'     => esc_html__('Name', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'twenty-one' ],
	)
);

$this->start_controls_tabs(
	'section_style_team_name_twenty-one_tab'
);

$this->start_controls_tab(
	'section_style_team_name_normal_twenty-one_tab',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('Name Typography', 'absolute-addons'),
		'name'        => 'name_twenty-one_typography',
		'selector'    => '{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-title',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'name_twenty-one_color',
	[
		'label'       => esc_html__(' Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-title' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_twenty-one_padding',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'name_section_twenty-one_margin',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_Member_hover_Name_tab_twenty-one',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('Name Typography', 'absolute-addons'),
		'name'        => 'name_typography_hover_twenty-one',
		'selector'    => '{{WRAPPER}} .absp-team-item .absp-team-inner .data .title:hover',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'name_color_hover_twenty-one',
	[
		'label'       => esc_html__(' Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-title:hover' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_padding_hover_twenty-one',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-title:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'name_section_margin_hover_twenty-one',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-title:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	'section_style_team_designation_twenty-one',
	[
		'label'     => __('Designation', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'twenty-one' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_designation_tab_twenty-one'
);

$this->start_controls_tab(
	'section_style_team_designation_normal_tab_twenty-one',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'designation_typography_twenty-one_bg',
		'fields_options' => [
			'background' => [
				'label' => esc_html__('Designation Background', 'absolute-addons'),
			],
		],
		'selector'       => '{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-designation',
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography_twenty-one',
		'selector' => '{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-designation',
	)
);

$this->add_control(
	'designation_color_twenty-one',
	[
		'label'    => esc_html__('Designation Color', 'absolute-addons'),
		'type'     => Controls_Manager::COLOR,
		'selector' => '{{WRAPPER}}  .absp-team.element-twenty-one .absp-team-info .absp-team-designation',
	]
);

$this->add_responsive_control(
	'designation_section_padding_twenty-one',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-twenty-one .absp-team-info .absp-team-designation ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'designation_section_margin_twenty-one',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_designation_hover_tab_twenty-one',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'designation_typography_twenty-one_bg_hover',
		'fields_options' => [
			'background' => [
				'label' => esc_html__('Designation Background', 'absolute-addons'),
			],
		],
		'selector'       => '{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-designation:hover',
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography_twenty-one_hover',
		'selector' => '{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-designation:hover',
	)
);

$this->add_control(
	'designation_color_twenty-one-hover',
	[
		'label'    => esc_html__('Designation Color', 'absolute-addons'),
		'type'     => Controls_Manager::COLOR,
		'selector' => '{{WRAPPER}}  .absp-team.element-twenty-one .absp-team-info .absp-team-designation:hover',
	]
);

$this->add_responsive_control(
	'designation_section_padding_twenty-one_hover',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-twenty-one .absp-team-info .absp-team-designation:hover ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'designation_section_margin_twenty-one_hover',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-info .absp-team-designation:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

// Social Icon Area

$this->start_controls_section(
	'section_style_team_social_media_twenty-one',
	[
		'label'     => __('Social Icon Area', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'twenty-one' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_social_media_tab_twenty-one'
);

$this->start_controls_tab(
	'section_style_team_social_media_normal_twenty-one_tab',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'social_icon_box_shadow_twenty-one',
		'label'    => __('Box Shadow', 'absolute-addons'),
		'selector' => '{{WRAPPER}} .absp-team.element-twenty-one .absp-team-social a',
	]
);

$this->add_responsive_control(
	'social_icon_border_radius_twenty-one',
	[
		'label'      => esc_html__('Border Radius', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			' {{WRAPPER}} .absp-team.element-twenty-one .absp-team-social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_padding_twenty-one',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-twenty-one .absp-team-social a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_margin_twenty-one',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-social a ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_social_media_hover_tab_twenty-one',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'social_icon_box_shadow_hover_twenty-one',
		'label'    => __('Box Shadow', 'absolute-addons'),
		'selector' => '{{WRAPPER}} .absp-team.element-twenty-one .absp-team-social a:hover ',
	]
);

$this->add_responsive_control(
	'social_icon_border_radius_hover_twenty-one',
	[
		'label'      => esc_html__('Border Radius', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-social a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_padding_hover_twenty-one',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-twenty-one .absp-team-social a:hover ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_margin_hover_twenty-one',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-social a:hover ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();



