<?php
defined('ABSPATH') || exit;
use Elementor\Controls_Manager;

$this->start_controls_section(
	'count_down_separator',
	[
		'label'     => esc_html__( 'Separator', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'countdown_separator' => 'yes',
		],
	]
);

$this->add_control(
	'countdown_separator_color',
	[
		'label'     => esc_html__( 'Separator Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'condition' => [
			'countdown_separator' => 'yes',
		],
		'selectors' => [
			'{{WRAPPER}} .absp-countdown-separator , {{WRAPPER}} .absp-countdown-separator' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'countdown_separator_size',
	[
		'label'     => __( 'Separator Size', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'selectors' => [
			'{{WRAPPER}} .absp-countdown-separator' => 'font-size: {{SIZE}}px;',
		],
		'condition' => [
			'countdown_separator' => 'yes',
		],
	]
);

$this->end_controls_section();
