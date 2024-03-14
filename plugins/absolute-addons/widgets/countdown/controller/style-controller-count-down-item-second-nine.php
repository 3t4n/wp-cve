<?php
defined('ABSPATH') || exit;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

	$this->add_group_control(
		Group_Control_Background::get_type(),
		[
			'name'           => 'count_down_box_background_second_nine',
			'label'          => esc_html__( 'Box Section Background', 'absolute-addons' ),
			'label_block'    => true,
			'types'          => [ 'classic', 'gradient' ],
			'fields_options' => [
				'background' => [
					'label' => 'Box Section Background',
				],
			],
			'selector'       => '{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-seconds-wrapper.absp-countdown-flex-inner.absp-countdown-enable .absp-countdown-inner .absp-countdown-digits-second',
			'condition'      => [
				'absolute_countdown' => 'nine',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name'      => 'count_down_box_border_second_nine',
			'label'     => esc_html__( 'Body Border', 'absolute-addons' ),
			'selector'  => '{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-seconds-wrapper.absp-countdown-flex-inner.absp-countdown-enable .absp-countdown-inner .absp-countdown-digits-second',
			'condition' => [
				'absolute_countdown' => 'nine',
			],
		]
	);

	$this->add_responsive_control(
		'count_down_box_border_radius_second_nine',
		[
			'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-seconds-wrapper.absp-countdown-flex-inner.absp-countdown-enable .absp-countdown-inner .absp-countdown-digits-second' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'absolute_countdown' => 'nine',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Box_Shadow::get_type(),
		[
			'name'      => 'count_down_box_box_shadow_second_nine',
			'label'     => esc_html__( 'Box Shadow', 'absolute-addons' ),
			'selector'  => '{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-seconds-wrapper.absp-countdown-flex-inner.absp-countdown-enable .absp-countdown-inner .absp-countdown-digits-second',
			'condition' => [
				'absolute_countdown' => 'nine',
			],
		]
	);

	$this->add_responsive_control(
		'count_down_box_padding_second_nine',
		[
			'label'      => esc_html__( 'Padding', 'absolute-addons' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-seconds-wrapper.absp-countdown-flex-inner.absp-countdown-enable .absp-countdown-inner .absp-countdown-digits-second' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'absolute_countdown' => 'nine',
			],
		]
	);

	$this->add_responsive_control(
		'count_down_box_margin_second_nine',
		[
			'label'      => esc_html__( 'Margin', 'absolute-addons' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-seconds-wrapper.absp-countdown-flex-inner.absp-countdown-enable .absp-countdown-inner .absp-countdown-digits-second' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'absolute_countdown' => 'nine',
			],
		]
	);

