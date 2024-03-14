<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

$this->start_controls_section(
	'content_card_review_section',
	[
		'label'     => esc_html__( 'Review Rating', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'content_card_rating' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'content_card_review_icon_size',
	[
		'label'     => esc_html__( 'Size', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => [
			'px' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors' => [
			'
			{{WRAPPER}} .elementor-star-rating,
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-product-ratting-count
			' => 'font-size: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_responsive_control(
	'content_card_review_icon_space',
	[
		'label'     => esc_html__( 'Spacing', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => [
			'px' => [
				'min' => 0,
				'max' => 50,
			],
		],
		'selectors' => [
			'body:not(.rtl) {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
			'body.rtl {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_responsive_control(
	'content_card_review_stars_color',
	[
		'label'     => esc_html__( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .elementor-star-rating i:before,
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-product-ratting-count
			' => 'color: {{VALUE}}',
		],
		'separator' => 'before',
	]
);

$this->add_control(
	'content_card_review_stars_unmarked_color',
	[
		'label'     => esc_html__( 'Unmarked Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .elementor-star-rating i' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_section();

