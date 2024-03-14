<?php
/*
 * @Package A
 */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

use AbsoluteAddons\Controls\Group_Control_ABSP_Foreground;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'section_style_team_body',
	[
		'label'     => esc_html__( 'Body Area', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'one' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_body_tab'
);

$this->start_controls_tab(
	'section_style_team_body_normal_tab',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'section_style_team_body_background',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient', 'video' ],
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-team',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'body_border',
		'label'    => __( 'Body Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-team',
	]
);

$this->add_responsive_control(
	'body_section_border_radius',
	[
		'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'body_box_shadow',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-team',
	]
);

$this->add_responsive_control(
	'body_section_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_body_hover_tab',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'section_style_team_body_background_hover',
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient', 'video' ],
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-team:hover',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'body_border_hover',
		'label'    => __('Body Border', 'absolute-addons'),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-team:hover',
	]
);

$this->add_responsive_control(
	'body_section_border_radius_hover',
	[
		'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'body_box_shadow_hover',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-team:hover',
	]
);

$this->add_responsive_control(
	'body_section_padding_hover',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin_hover',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	'section_style_team_Member_Name',
	array(
		'label'     => esc_html__('Name', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'one' ],
	)
);

$this->start_controls_tabs(
	'section_style_team_name_tab'
);

$this->start_controls_tab(
	'section_style_team_name_normal_tab',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('Name Typography', 'absolute-addons'),
		'name'        => 'name_typography',
		'selector'    => '{{WRAPPER}} .absp-team.element-one .absp-team-item .holder .data .title',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'name_color',
	[
		'label'       => esc_html__(' Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team.element-one .absp-team-item .holder .data .title' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_padding',
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
	'name_section_margin',
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
	'section_style_team_Member_hover_Name_tab',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('Name Typography', 'absolute-addons'),
		'name'        => 'name_typography_hover',
		'selector'    => '{{WRAPPER}} .absp-team.element-one .absp-team-item .holder .data .title:hover',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'name_color_hover',
	[
		'label'       => esc_html__(' Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team.element-one .absp-team-item .holder .data .title:hover' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_padding_hover',
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
	'name_section_margin_hover',
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
	'section_style_team_designation',
	[
		'label'     => __('Designation', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'one' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_designation_tab'
);

$this->start_controls_tab(
	'section_style_team_designation_normal_tab',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography',
		'selector' => '{{WRAPPER}} .absp-team .absp-team-item .designation',
	)
);

$this->add_group_control(
	Group_Control_ABSP_Foreground::get_type(),
	[
		'name'           => 'designation_color',
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
	'designation_section_padding',
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
	'designation_section_margin',
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
	'section_style_team_designation_hover_tab',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography_hover',
		'selector' => '{{WRAPPER}} .absp-team .absp-team-item .designation:hover',
	)
);

$this->add_group_control(
	Group_Control_ABSP_Foreground::get_type(),
	[
		'name'           => 'designation_color_hover',
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
	'designation_section_padding_hover',
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
	'designation_section_margin_hover',
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

// Separator

$this->start_controls_section(
	'section_name_team_separator',
	[
		'label'     => esc_html__('Separator', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'one' ],
	]
);

$this->add_control(
	'separator_enable',
	[
		'label'     => __('Enable Separator', 'absolute-addons'),
		'type'      => Controls_Manager::SELECT,
		'options'   => array(
			'true'  => esc_html__('Yes', 'absolute-addons'),
			'false' => esc_html__('No', 'absolute-addons'),

		),
		'default'   => 'true',
		'condition' => [ 'team_style_variation' => 'one' ],
	]
);

$this->add_control(
	'separator_color',
	[
		'label'       => esc_html__(' Separator Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => array(
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > hr.separator::after' => 'border-color: {{VALUE}}',
			'{{WRAPPER}} .absp-team-item  .separator_area .separator' => 'fill: {{VALUE}}',
			'{{WRAPPER}} .absp-team-item .holder .data .designation .separator' => 'fill: {{VALUE}}',
			'{{WRAPPER}} .absp-team-item .absp-team-top-area .top-area-wrapper .content-area h2::before' => 'border-color: {{VALUE}}',
		),
		'description' => esc_html__('Select Separator color.', 'absolute-addons'),
		'conditions'  => [
			'terms' => [
				[
					'name'     => 'separator_enable',
					'operator' => '==',
					'value'    => [
						'true',
					],
				],
			],
		],
	]
);

$this->add_control(
	'separator_width',
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
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > hr.separator::after' => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-team .absp-team-item .separator_area' => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-team-item .holder .data .designation svg' => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-team-item .absp-team-top-area .top-area-wrapper .content-area h2:before' => 'width: {{SIZE}}{{UNIT}};',
		],
		'conditions' => [
			'terms' => [
				[
					'name'     => 'separator_enable',
					'operator' => '==',
					'value'    => [
						'true',
					],
				],
			],
		],
	]
);

$this->add_responsive_control(
	'separator_section_padding',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > hr.separator, {{WRAPPER}} .absp-team-item .holder .data .designation svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'conditions' => [
			'terms' => [
				[
					'name'     => 'separator_enable',
					'operator' => '==',
					'value'    => [
						'true',
					],
				],
			],
		],
	]
);

$this->add_responsive_control(
	'separator_section_margin',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > hr.separator, {{WRAPPER}} .absp-team-item .holder .data .designation svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'conditions' => [
			'terms' => [
				[
					'name'     => 'separator_enable',
					'operator' => '==',
					'value'    => [
						'true',
					],
				],
			],
		],
	]
);

$this->end_controls_section();

// Content Style

$this->start_controls_section(
	'section_style_team_content_one',
	[
		'label'     => __('Content', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'one' ],
	]
);

$this->add_control(
	'content_area_background',
	[
		'label'     => esc_html__(' Content Background', 'absolute-addons'),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-team-item  .about' => 'background: {{VALUE}}',

		],

	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	array(
		'label'    => esc_html__('Content Typography', 'absolute-addons'),
		'name'     => 'content_typography',
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .about ,{{WRAPPER}} .absp-team-item .holder .data p ',

	)
);

$this->add_control(
	'content_color',
	array(
		'label'       => esc_html__(' Content Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => array(
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .about, {{WRAPPER}} .absp-team-item .holder .data p  ' => 'color: {{VALUE}}',
		),
		'description' => esc_html__('Select Content Text color.', 'absolute-addons'),
	)
);

$this->add_responsive_control(
	'content_section_padding',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .about ,{{WRAPPER}} .absp-team-item .holder .data p ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'content_section_margin',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .about ,{{WRAPPER}} .absp-team-item .holder .data p ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

// Social Icon Area

$this->start_controls_section(
	'section_style_team_social_media',
	[
		'label'     => __('Social Icon Area', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'one' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_social_media_tab'
);

$this->start_controls_tab(
	'section_style_team_social_media_normal_tab',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'social_icon_box_shadow',
		'label'    => __('Box Shadow', 'absolute-addons'),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul li a',
	]
);

$this->add_control(
	'Icon_size',
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
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons  ul li  a' => 'font-size: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .social_icons ul li a' => 'font-size: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};weight: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_icon_border_radius',
	[
		'label'      => esc_html__('Border Radius', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			' {{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_padding',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_margin',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul li a ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_social_media_hover_tab',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'social_icon_box_shadow_hover',
		'label'    => __('Box Shadow', 'absolute-addons'),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul li a:hover ',
	]
);

$this->add_control(
	'Icon_size_hover',
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

			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul li a:hover' => 'font-size: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};weight: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_icon_border_radius_hover',
	[
		'label'      => esc_html__('Border Radius', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul li a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_padding_hover',
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul li a:hover ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_margin_hover',
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul li a:hover ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();




