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
	'section_style_team_body_thirteen',
	[
		'label'     => esc_html__( 'Team Item Image', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'thirteen' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_body_tab_thirteen'
);

$this->start_controls_tab(
	'section_style_team_body_normal_tab_thirteen',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'body_section_padding_thirteen',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team .member_image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin_thirteen',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team .member_image ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'team_image_border_radius_thirteen',
	[
		'label'      => esc_html__( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team-item .absp-team-inner .image-area .image-wrapper .member_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}}  .absp-team-item .absp-team-inner .image-area .overlay-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}}  .absp-team-item .absp-team-inner .image-area .image-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}}   .absp-team-item .absp-team-inner .image-area .image-wrapper::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_body_hover_tab_thirteen',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'team_hover_overly_background',
		'fields_options' => [
			'background' => [
				'label' => __('Overly One Background', 'absolute-addons'),
			],
		],
		'types'          => [ 'classic', 'gradient', 'video' ],
		'selector'       => '{{WRAPPER}} .absp-team-item .absp-team-inner .image-area .image-wrapper::before',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'team_hover_overly_two_background',
		'fields_options' => [
			'background' => [
				'label' => __('Overly Two Background', 'absolute-addons'),
			],
		],
		'types'          => [ 'classic', 'gradient', 'video' ],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-team-item .image-area .overlay-bg',
	]
);

$this->add_responsive_control(
	'circle_position',
	[
		'label'      => __( 'Circle Position', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1000,
				'step' => 5,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item:hover .image-area .overlay-bg' => 'transform: translateX( {{SIZE}}{{UNIT}});',
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
	'section_style_team_Member_thirteen_Name',
	array(
		'label'     => esc_html__('Name', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'thirteen' ],
	)
);

$this->start_controls_tabs(
	'section_style_team_name_thirteen_tab'
);

$this->start_controls_tab(
	'section_style_team_name_normal_thirteen_tab',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('Name Typography', 'absolute-addons'),
		'name'        => 'name_thirteen_typography',
		'selector'    => '{{WRAPPER}} .absp-team-item .absp-team-inner .data .title',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'name_thirteen_color',
	[
		'label'       => esc_html__(' Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team-item .absp-team-inner .data .title' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_thirteen_padding',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .absp-team-inner .data .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'name_section_thirteen_margin',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .absp-team-inner .data .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_Member_hover_Name_tab_thirteen',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('Name Typography', 'absolute-addons'),
		'name'        => 'name_typography_hover_thirteen',
		'selector'    => '{{WRAPPER}} .absp-team-item .absp-team-inner .data .title:hover',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'name_color_hover_thirteen',
	[
		'label'       => esc_html__(' Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team-item .absp-team-inner .data .title:hover' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_padding_hover_thirteen',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .absp-team-inner .data .title:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'name_section_margin_hover_thirteen',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .absp-team-inner .data .title:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	'section_style_team_designation_thirteen',
	[
		'label'     => __('Designation', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'thirteen' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_designation_tab_thirteen'
);

$this->start_controls_tab(
	'section_style_team_designation_normal_tab_thirteen',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography_thirteen',
		'selector' => '{{WRAPPER}} .absp-team .absp-team-item .designation',
	)
);

$this->add_group_control(
	Group_Control_ABSP_Foreground::get_type(),
	[
		'name'           => 'designation_color_thirteen',
		'fields_options' => [
			'color_type' => [
				'default' => 'gradient',
				'label'   => _x( 'Designation Color', 'Background Control', 'absolute-addons' ),
			],
			'color'      => [
				'default' => '#BE6EFF',
			],
			'color_b'    => [
				'default' => '#4641FF',
			],
		],
		'selector'       => '{{WRAPPER}}  .absp-wrapper .absp-team > .absp-team-item  .designation',
	]
);

$this->add_responsive_control(
	'designation_section_padding_thirteen',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-wrapper .absp-team > .absp-team-item  .designation ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'designation_section_margin_thirteen',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-wrapper .absp-team > .absp-team-item  .designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_designation_hover_tab_thirteen',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography_hover_thirteen',
		'selector' => '{{WRAPPER}} .absp-team .absp-team-item .designation:hover',
	)
);

$this->add_group_control(
	Group_Control_ABSP_Foreground::get_type(),
	[
		'name'           => 'designation_color_hover_thirteen',
		'fields_options' => [
			'color_type' => [
				'default' => 'gradient',
				'label'   => _x( 'Designation Color', 'Background Control', 'absolute-addons' ),
			],
			'color'      => [
				'default' => '#000',
			],
			'color_b'    => [
				'default' => '#eee',
			],
		],
		'selector'       => '{{WRAPPER}}  .absp-wrapper .absp-team > .absp-team-item  .designation:hover',

	]
);

$this->add_responsive_control(
	'designation_section_padding_hover_thirteen',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-wrapper .absp-team > .absp-team-item  .designation:hover ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'designation_section_margin_hover_thirteen',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-wrapper .absp-team > .absp-team-item  .designation:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

// Social Icon Area

$this->start_controls_section(
	'section_style_team_social_media_thirteen',
	[
		'label'     => __('Social Icon Area', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'thirteen' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_social_media_tab_thirteen'
);

$this->start_controls_tab(
	'section_style_team_social_media_normal_thirteen_tab',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'social_icon_box_shadow_thirteen',
		'label'    => __('Box Shadow', 'absolute-addons'),
		'selector' => '{{WRAPPER}} .absp-team-item .absp-team-inner .image-area .social_icons ul li a',
	]
);

$this->add_responsive_control(
	'social_icon_border_radius_thirteen',
	[
		'label'      => esc_html__('Border Radius', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			' {{WRAPPER}} .absp-team-item .absp-team-inner .image-area .social_icons ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_padding_thirteen',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team-item .absp-team-inner .image-area .social_icons ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_margin_thirteen',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .absp-team-inner .image-area .social_icons ul li a ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_social_media_hover_tab_thirteen',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'social_icon_box_shadow_hover_thirteen',
		'label'    => __('Box Shadow', 'absolute-addons'),
		'selector' => '{{WRAPPER}} .absp-team-item .absp-team-inner .image-area .social_icons ul li a:hover ',
	]
);

$this->add_responsive_control(
	'social_icon_border_radius_hover_thirteen',
	[
		'label'      => esc_html__('Border Radius', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team-item .absp-team-inner .image-area .social_icons ul li a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_padding_hover_thirteen',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team-item .absp-team-inner .image-area .social_icons ul li a:hover ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_margin_hover_thirteen',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team-item .absp-team-inner .image-area .social_icons ul li a:hover ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();



