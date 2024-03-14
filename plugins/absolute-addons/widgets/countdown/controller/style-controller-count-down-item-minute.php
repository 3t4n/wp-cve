<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'count_down_minute_settings',
	[
		'label'     => esc_html__( 'Minutes', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'countdown_show_minutes' => 'yes',
		],
	]
);

$this->start_controls_tabs(
	'count_down_minute_tabs'
);

//Count Down box tab
$this->start_controls_tab(
'count_down_minute_box_tab',
[
	'label' => esc_html__( 'Box', 'absolute-addons' ),
]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'count_down_minute_box_background',
		'label'          => esc_html__( 'Box Section Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Box Section Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-minutes-wrapper.absp-countdown-flex-inner.absp-countdown-enable',
		'conditions'     => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'five',
				],
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'nine',
				],
			],
		],
	]
);

$this->add_control(
	'coundown_minute_box_gradient_color_1',
	[
		'label'     => esc_html__( 'Box Gradient Background Color One', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'condition' => [
			'absolute_countdown' => 'five',
		],
	]
);

$this->add_control(
	'coundown_minute_box_gradient_color_2',
	[
		'label'     => esc_html__( 'Box Gradient Background Color Two', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'condition' => [
			'absolute_countdown' => 'five',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'       => 'count_down_minute_box_border',
		'label'      => esc_html__( 'Body Border', 'absolute-addons' ),
		'selector'   => '{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-minutes-wrapper.absp-countdown-flex-inner.absp-countdown-enable',
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'nine',
				],
			],
		],
	]
);

$this->add_responsive_control(
	'count_down_minute_box_border_radius',
	[
		'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-minutes-wrapper.absp-countdown-flex-inner.absp-countdown-enable' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'nine',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'       => 'count_down_minute_box_box_shadow',
		'label'      => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector'   => '{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-minutes-wrapper.absp-countdown-flex-inner.absp-countdown-enable',
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'nine',
				],
			],
		],
	]
);

$this->add_responsive_control(
	'count_down_minute_box_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-minutes-wrapper.absp-countdown-flex-inner.absp-countdown-enable' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'nine',
				],
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'one',
				],
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'two',
				],
			],
		],
	]
);

$this->add_control(
	'count_down_minute_box_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-countdown-flex-wrapper .absp-countdown-minutes-wrapper.absp-countdown-flex-inner.absp-countdown-enable' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'nine',
				],
			],
		],
	]
);

//Count down style nine minute box controller
$this->render_controller( 'style-controller-count-down-item-minute-nine' );

$this->end_controls_tab();

//Count Down Digits tab
$this->start_controls_tab(
	'count_down_minute_digit_tab',
	[
		'label' => esc_html__( 'Digits', 'absolute-addons' ),
	]
	);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'count_down_minute_digit_typography',
		'label'    => esc_html__( 'Digit Typography', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-digits-minute
        ',
	]
);

$this->add_control(
	'count_down_minute_digit_color',
	[
		'label'     => esc_html__( 'Digit Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
		{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-digits-minute
			' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'count_down_minute_digit_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-digits-minute
			' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'count_down_minute_digit_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-digits-minute
			' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

//Count Down Label tab
$this->start_controls_tab(
	'count_down_minute_label_tab',
	[
		'label' => esc_html__( 'Labels', 'absolute-addons' ),
	]
	);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'count_down_minute_label_typography',
		'label'    => esc_html__( 'Label Typography', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-label-minute',
	]
);

$this->add_control(
	'count_down_minute_label_color',
	[
		'label'     => esc_html__( 'Label Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-label-minute
			' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'count_down_minute_label_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-label-minute
			' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'count_down_minute_label_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-label-minute
			' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

