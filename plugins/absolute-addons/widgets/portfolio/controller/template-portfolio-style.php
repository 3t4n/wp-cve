<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'portfolio_filter_seven',
	[
		'label'     => esc_html__( 'Filter Style', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'enable_filter_menu' => [ 'yes' ],
		],
	]
);

$this->start_controls_tabs( 'portfolio_filter_tabs' );

$this->start_controls_tab(
	'filter_tabs_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'portfolio_filter_background',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Filter Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li a',
		'default'        => '',
	]
);

$this->add_control(
	'portfolio_filter_color',
	[
		'label'     => esc_html__( 'Filter Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li a' => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li + li::before' => 'background: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'name'     => 'portfolio_filter_border',
		'selector' => '{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li a',
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'portfolio_filter_typography',
		'selector' => '{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li a',
	]
);

$this->add_control(
	'portfolio_filter_divider',
	[
		'label'      => esc_html__( 'Portfolio Filter Divider', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li + li::before' => 'inset: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'enable_filter_menu!'     => 'no',
			'portfolio_filter_layout' => [ 'two', 'three', 'four' ],
		],
	]
);

$this->add_responsive_control(
	'portfolio_filter_padding',
	[
		'label'      => esc_html__( 'Portfolio Filter Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'portfolio_filter_margin',
	[
		'label'      => esc_html__( 'Portfolio Filter Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'filter_tabs_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'portfolio_filter_background_hover',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Filter Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li:hover a',
		'default'        => '',
	]
);

$this->add_control(
	'portfolio_filter_color_hover',
	[
		'label'     => esc_html__( 'Filter Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li:hover a' => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'name'     => 'portfolio_filter_border_hover',
		'selector' => '{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li:hover a',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'filter_tabs_active',
	[
		'label' => esc_html__( 'Active', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'portfolio_filter_background_active',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Filter Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li a.is-checked',
		'default'        => '',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'portfolio_filter_after_background_active',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Background After Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li.is-checked::after',
		'default'        => '',
		'condition'      => [
			'portfolio_filter_layout' => [ 'two', 'three' ],
		],
	]
);

$this->add_control(
	'portfolio_filter_color_active',
	[
		'label'     => esc_html__( 'Filter Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li a.is-checked' => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'name'     => 'portfolio_filter_border_active',
		'selector' => '{{WRAPPER}} .absp-portfolio :is(.layout-one, .layout-two, .layout-three, .layout-four, .layout-five, .layout-six) li a.is-checked',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'portfolio_title_seven',
	[
		'label' => esc_html__( 'Title Style', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'portfolio_title_style_typography',
		'selector' => '{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_simple, .hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content h3',
	]
);

$this->add_control(
	'portfolio_title_style_color',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_simple, .hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content h3' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'portfolio_category_seven',
	[
		'label' => esc_html__( 'Category Style', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'portfolio_category_typography',
		'selector' => '{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_simple, .hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content span',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'portfolio_category_background',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Category Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_simple, .hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content span',
		'default'        => '',
	]
);

$this->add_control(
	'portfolio_category_color',
	[
		'label'     => esc_html__( 'Category Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_simple, .hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content :is(span, span a)' => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'name'     => 'portfolio_category_border',
		'selector' => '{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_simple, .hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content :is(span, span a)',
	]
);

$this->add_responsive_control(
	'portfolio_category_radius',
	[
		'label'      => esc_html__( 'Category Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_simple, .hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'portfolio_category_padding',
	[
		'label'      => esc_html__( 'Portfolio Category Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_simple, .hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'portfolio_category_margin',
	[
		'label'      => esc_html__( 'Portfolio Category Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_simple, .hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'portfolio_btn',
	[
		'label'     => esc_html__( 'Portfolio Button', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_portfolio' => [ 'two', 'five' ],
			'portfolio_button'   => [ 'yes' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'portfolio_btn_typography',
		'selector' => '{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content .portfolio-btn',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'portfolio_btn_background',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content .portfolio-btn',
		'default'        => '',
	]
);

$this->add_control(
	'portfolio_btn_color',
	[
		'label'     => esc_html__( 'Category Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content .portfolio-btn' => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'name'     => 'portfolio_btn_border',
		'selector' => '{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content .portfolio-btn',
	]
);

$this->add_responsive_control(
	'portfolio_btn_radius',
	[
		'label'      => esc_html__( 'Button Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content .portfolio-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'portfolio_btn_padding',
	[
		'label'      => esc_html__( 'Portfolio Category Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content .portfolio-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'portfolio_btn_margin',
	[
		'label'      => esc_html__( 'Portfolio Category Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper .portfolio-content .portfolio-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'portfolio_overlay_seven',
	[
		'label' => esc_html__( 'Overlay Style', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'portfolio_overlay_background',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'Category Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-portfolio .portfolio-wrapper:is(.hover_fade, .hover_text_top_left, .hover_text_top_center, .hover_text_top_right, .hover_text_bottom_left, .hover_text_bottom_center, .hover_text_bottom_right, .hover_card_text_left, .hover_card_text_top_left, .hover_card_text_top_center, .hover_card_text_top_right, .hover_card_text_right, .hover_card_text_bottom_left, .hover_card_text_bottom_center, .hover_card_text_bottom_right, .hover_sweep_to_top, .hover_sweep_to_bottom, .hover_sweep_to_left, .hover_sweep_to_right, .hover_bounce_to_top, .hover_bounce_to_right, .hover_bounce_to_bottom, .hover_bounce_to_left, .hover_radial_out, .hover_rectangle_out, .hover_shutter_out_horizontal, .hover_shutter_out_vertical) .portfolio-content-wrapper',
		'default'        => '',
	]
);

$this->end_controls_section();
