<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'skill_bar_section_title',
	[
		'label' => __( 'Title and Icon', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'skill_bar_title_typography',
		'selector' => '{{WRAPPER}} .ab-progress .ab-progress-title',
	]
);

$this->add_responsive_control(
	'skill_bar_title_padding',
	[
		'label'      => esc_html__( 'Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .ab-progress .ab-progress-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'skill_bar_section_number',
	[
		'label' => __( 'Number', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'skill_bar_number_typography',
		'selector' => '{{WRAPPER}} .ab-progress :is(.ab-progress-indicator-inner, .ab-progress-arrow)',
	]
);

$this->add_responsive_control(
	'skill_bar_number_padding',
	[
		'label'      => esc_html__( 'Number Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .ab-progress .ab-progress-indicator-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
