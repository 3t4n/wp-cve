<?php

use AbsoluteAddons\Controls\Group_Control_ABSP_Foreground;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

$suffix = '_five';

$this->start_controls_section(
	'section_style_team_body'.$suffix,
	[
		'label'     => esc_html__( 'Body Area', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'five' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_body_tab'.$suffix
);

$this->start_controls_tab(
	'section_style_team_body_normal_tab'.$suffix,
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'section_style_team_body_background'.$suffix,
		'label'          => __( 'Background', 'absolute-addons' ),
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'default' => 'classic',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-team.element-five .absp-team-item .absp-team-thumb',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'      => 'body_border'.$suffix,
		'label'     => __( 'Body Border', 'absolute-addons' ),
		'selectors' => [
			'{{WRAPPER}}  .absp-team.element-five ',
		],
	]
);

$this->add_responsive_control(
	'body_section_border_radius'.$suffix,
	[
		'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-five' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'body_box_shadow'.$suffix,
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}}  .absp-team.element-five ',
	]
);

$this->add_responsive_control(
	'body_section_padding'.$suffix,
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-five' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin'.$suffix,
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_body_hover_tab'.$suffix,
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'section_style_team_body_background_hover'.$suffix,
		'label'    => __( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-team.element-five .absp-team-item .absp-team-thumb:hover',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'body_border_hover'.$suffix,
		'label'    => __('Body Border', 'absolute-addons'),
		'selector' => '{{WRAPPER}} .absp-team.element-five:hover',

	]
);

$this->add_responsive_control(
	'body_section_border_radius_hover'.$suffix,
	[
		'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'body_box_shadow_hover'.$suffix,
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-team.element-five:hover',
	]
);

$this->add_responsive_control(
	'body_section_padding_hover'.$suffix,
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin_hover'.$suffix,
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	'section_style_team_Member_Name'.$suffix,
	array(
		'label'     => esc_html__('Name', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'five' ],
	)

);

$this->start_controls_tabs(
	'section_style_team_name_tab'.$suffix
);

$this->start_controls_tab(
	'section_style_team_name_normal_tab'.$suffix,
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__('Name Typography', 'absolute-addons'),
		'name'        => 'first_name_typography'.$suffix,
		'selector'    => '{{WRAPPER}} .absp-team.element-five .absp-team-title',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'first_name_color'.$suffix,
	[
		'label'       => esc_html__('Fist Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team.element-five .absp-team-title' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select First Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_padding'.$suffix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five .absp-team-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'name_section_margin'.$suffix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five .absp-team-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_Member_hover_Name_tab'.$suffix,
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	[
		'label'       => esc_html__(' Name Typography', 'absolute-addons'),
		'name'        => 'first_name_typography_hover'.$suffix,
		'selector'    => '{{WRAPPER}} .absp-team.element-five .absp-team-title:hover',
		'description' => esc_html__('Select Name Font Style.', 'absolute-addons'),
	]
);

$this->add_control(
	'first_name_color_hover'.$suffix,
	[
		'label'       => esc_html__(' Name Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => [
			'{{WRAPPER}} .absp-team.element-five:hover .absp-team-title' => 'color: {{VALUE}}',
		],
		'description' => esc_html__('Select First Name Text color.', 'absolute-addons'),
	]
);

$this->add_responsive_control(
	'name_section_padding_hover'.$suffix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five:hover .absp-team-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'name_section_margin_hover'.$suffix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five:hover .absp-team-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	'section_style_team_designation'.$suffix,
	[
		'label'     => __('Designation', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'five' ],
	]
);

$this->start_controls_tabs(
	'section_style_team_designation_tab'.$suffix
);

$this->start_controls_tab(
	'section_style_team_designation_normal_tab'.$suffix,
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography'.$suffix,
		'selector' => '{{WRAPPER}} .absp-team.element-five .absp-team-designation',
	)
);

$this->add_group_control(
	Group_Control_ABSP_Foreground::get_type(),
	[
		'name'           => 'designation_color'.$suffix,
		'fields_options' => [
			'color_type' => [
				'default' => 'classic',
				'label'   => _x( 'Designation Color', 'Background Control', 'absolute-addons' ),
			],
		],
		'selector'       => '{{WRAPPER}}   .absp-team.element-five .absp-team-designation',
	]
);

$this->add_control(
	'designation_alignment_style_five',
	[
		'label'     => __('Alignment', 'absolute-addons'),
		'type'      => Controls_Manager::CHOOSE,
		'options'   => [
			'left'  => [
				'title' => __('Left', 'absolute-addons'),
				'icon'  => 'fa fa-align-left',
			],
			'right' => [
				'title' => __('Right', 'absolute-addons'),
				'icon'  => 'fa fa-align-right',
			],
		],
		'default'   => 'left',
		'toggle'    => true,
		'condition' => [ 'team_style_variation' => 'five' ],
	]
);

$this->add_control(
		'designation_section_position_top_five',
		[
			'label'      => __('Top', 'absolute-addons'),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%' ],
			'range'      => [
				'%' => [
					'min' => -100,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .absp-team.element-five .absp-team-designation' => 'top: {{size}}{{UNIT}} ;',
			],
			'condition'  => [ 'team_style_variation' => 'five' ],
		]
	);

$this->add_responsive_control(
	'designation_section_padding'.$suffix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five .absp-team-designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'designation_section_margin'.$suffix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-five .absp-team-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'section_style_team_designation_hover_tab'.$suffix,
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__('Designation Typography', 'absolute-addons'),
		'name'     => 'designation_typography_hover'.$suffix,
		'selector' => '{{WRAPPER}} .absp-team.element-five:hover .absp-team-designation',
	)
);

$this->add_group_control(
	Group_Control_ABSP_Foreground::get_type(),
	[
		'name'           => 'designation_color_hover'.$suffix,
		'fields_options' => [
			'color_type' => [
				'default' => 'classic',
				'label'   => _x( 'Designation Color', 'Background Control', 'absolute-addons' ),
			],
			'color'      => [
				'default' => '#fff',
			],
		],
		'selector'       => '{{WRAPPER}}  .absp-team.element-five:hover .absp-team-designation',

	]
);

$this->add_control(
	'designation_alignment_style_five_hover',
	[
		'label'     => __('Alignment', 'absolute-addons'),
		'type'      => Controls_Manager::CHOOSE,
		'options'   => [
			'left'  => [
				'title' => __('Left', 'absolute-addons'),
				'icon'  => 'fa fa-align-left',
			],
			'right' => [
				'title' => __('Right', 'absolute-addons'),
				'icon'  => 'fa fa-align-right',
			],
		],
		'default'   => 'left',
		'toggle'    => true,
		'condition' => [ 'team_style_variation' => 'five' ],
	]
);

$this->add_control(
	'designation_section_position_top_hover',
	[
		'label'      => __('Top', 'absolute-addons'),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ '%' ],
		'range'      => [
			'%' => [
				'min' => -100,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five:hover .absp-team-designation' => 'top: {{size}}{{UNIT}} ;',
		],
		'condition'  => [ 'team_style_variation' => 'five' ],
	]
);

$this->add_responsive_control(
	'designation_section_padding_hover'.$suffix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-five:hover .absp-team-designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'designation_section_margin_hover'.$suffix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five:hover .absp-team-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

// Separator

$this->start_controls_section(
	'section_name_team_separator'.$suffix,
	[
		'label'     => esc_html__('Separator', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'five' ],
	]
);

$this->add_control(
	'separator_enable'.$suffix,
	[
		'label'     => __('Enable Separator', 'absolute-addons'),
		'type'      => Controls_Manager::SELECT,
		'options'   => array(
			'yes' => esc_html__('Yes', 'absolute-addons'),
			'no'  => esc_html__('No', 'absolute-addons'),

		),
		'default'   => 'yes',
		'condition' => [ 'team_style_variation' => 'five' ],
	]
);

$this->add_control(
	'separator_color'.$suffix,
	[
		'label'       => esc_html__(' Separator Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => array(
			'{{WRAPPER}} .absp-team.element-five .absp-team-content::after' => 'background: {{VALUE}}',
		),
		'description' => esc_html__('Select Separator color.', 'absolute-addons'),
		'condition'   => [ 'separator_enable_five' => 'yes' ],
	]
);

$this->add_control(
	'separator_width'.$suffix,
	[
		'label'      => __('Width', 'absolute-addons'),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 0,
				'max' => 1000,
			],
		],

		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five .absp-team-content::after' => 'width: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [ 'separator_enable_five' => 'yes' ],
	]
);

$this->add_responsive_control(
	'separator_section_padding'.$suffix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five .absp-team-content::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [ 'separator_enable_five' => 'yes' ],
	]
);

$this->add_responsive_control(
	'separator_section_margin'.$suffix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five .absp-team-content::after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [ 'separator_enable_five' => 'yes' ],
	]
);

$this->end_controls_section();

// Content Style

$this->start_controls_section(
	'section_style_team_content'.$suffix,
	[
		'label'     => __('Content', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'five' ],
	]
);

$this->add_group_control(
	Group_Control_Typography:: get_type(),
	array(
		'label'    => esc_html__('Content Typography', 'absolute-addons'),
		'name'     => 'content_typography'.$suffix,
		'selector' => '{{WRAPPER}} .absp-team.element-five .absp-team-content, {{WRAPPER}} .absp-team.element-five .absp-team-content p',

	)
);

$this->add_control(
	'content_color'.$suffix,
	array(
		'label'       => esc_html__(' Content Color', 'absolute-addons'),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => array(
			'{{WRAPPER}} .absp-team.element-five .absp-team-content p' => 'color: {{VALUE}}',
		),
		'description' => esc_html__('Select Content Text color.', 'absolute-addons'),
	)
);

$this->add_responsive_control(
	'content_section_padding'.$suffix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five .absp-team-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'content_section_margin'.$suffix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five .absp-team-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

// Social Icon Area

$this->start_controls_section(
	'section_style_team_social_media'.$suffix,
	[
		'label'     => __('Social Icon Area', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [ 'team_style_variation' => 'five' ],
	]
);

$this->add_control(
	'Icon_size'.$suffix,
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
			'{{WRAPPER}}  .absp-team.element-five .absp-team-social a' => 'font-size: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_padding'.$suffix,
	[
		'label'      => esc_html__('Padding', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}}  .absp-team.element-five .absp-team-social a ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'social_section_margin'.$suffix,
	[
		'label'      => esc_html__('Margin', 'absolute-addons'),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-team.element-five .absp-team-social a ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();




