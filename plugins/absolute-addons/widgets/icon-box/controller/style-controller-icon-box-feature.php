<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Conditions;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

$this->start_controls_section(
	'features_section',
	[
		'label'      => esc_html__( 'Features', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'twenty',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'twenty-three',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'features_typography',
		'label'    => esc_html__( 'Features Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .info-box .icon-box-content .single-feature',
	]
);

$this->add_control(
	'features_icon_color',
	[
		'label'      => esc_html__( 'Features Icon Color', 'absolute-addons' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .info-box .icon-box-content .single-feature.list-icon:before' => 'color: {{VALUE}};',
		],
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'twenty',
				],
			],
		],
	]
);

$this->add_control(
	'features_color',
	[
		'label'      => esc_html__( 'Features Text Color', 'absolute-addons' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .info-box .icon-box-content .single-feature' => 'color: {{VALUE}};',
		],
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'twenty-three',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'features_background',
		'label'          => esc_html__( 'Features Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Features Line Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .info-box .icon-box-content .single-feature',
	]
);

$this->add_responsive_control(
	'features_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .info-box .icon-box-content .single-feature' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'features_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .info-box .icon-box-content .single-feature' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
