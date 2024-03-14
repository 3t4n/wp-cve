<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'tag_section',
	[
		'label'      => esc_html__( 'Tag', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'four',
				],
			],
		],
	]
);

$this->start_controls_tabs( 'tag_section_tabs' );

// Tag Title Tab
$this->start_controls_tab(
	'tag_title_tab',
	[
		'label' => esc_html__( 'Tag Title', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'tag_typography',
		'label'    => esc_html__( 'Tag Title Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-quote-title',
	]
);

$this->add_control(
	'tag_color',
	[
		'label'     => esc_html__( 'Tag Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-quote-title' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'tag_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-quote-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'tag_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-quote-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

// Tag Title Tab
$this->start_controls_tab(
	'tag_year_tab',
	[
		'label' => esc_html__( 'Year Tag', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'tag_year_typography',
		'label'    => esc_html__( 'Tag Year Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-quote-text',
	]
);

$this->add_control(
	'tag_year_color',
	[
		'label'     => esc_html__( 'Tag Year Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-quote-text' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'tag_year_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-quote-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'tag_year_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-quote-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

