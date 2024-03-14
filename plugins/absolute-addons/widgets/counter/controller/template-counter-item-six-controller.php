<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$this->start_controls_section(
	'section_content_six',
	[
		'label'     => esc_html__( 'Counter Section', 'absolute-addons' ),
		'condition' => [
			'absolute_counter' => [ 'six' ],
		],
	]
);

$this->add_responsive_control(
	'counter_column_six',
	[
		'label'           => esc_html__( 'Counter Column', 'absolute-addons' ),
		'type'            => Controls_Manager::SELECT,
		'options'         => [
			'1' => esc_html__( '1 Column', 'absolute-addons' ),
			'2' => esc_html__( '2 Column', 'absolute-addons' ),
			'3' => esc_html__( '3 Column', 'absolute-addons' ),
			'4' => esc_html__( '4 Column', 'absolute-addons' ),
			'5' => esc_html__( '5 Column', 'absolute-addons' ),
			'6' => esc_html__( '6 Column', 'absolute-addons' ),
		],
		'default'         => '3',
		'devices'         => [ 'desktop', 'tablet', 'mobile' ],
		'desktop_default' => 3,
		'tablet_default'  => 3,
		'mobile_default'  => 2,
		'prefix_class'    => 'counter-grid%s-',
		'selectors'       => [
			'(desktop+){{WRAPPER}} .counter-item .counter-grid-col' => 'grid-template-columns: repeat({{counter_column_six.VALUE}}, 1fr);',
			'(tablet){{WRAPPER}} .counter-item .counter-grid-col'   => 'grid-template-columns: repeat({{counter_column_six_tablet.VALUE}}, 1fr);',
			'(mobile){{WRAPPER}} .counter-item .counter-grid-col'   => 'grid-template-columns: repeat({{counter_column_six_mobile.VALUE}}, 1fr);',
		],
	]
);

$repeater = new \Elementor\Repeater();

$repeater->add_control(
	'counter_title_six',
	[
		'label'       => esc_html__( 'Counter Title', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'label_block' => true,
		'default'     => esc_html__( 'Building The Future', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'counter_number_six',
	[
		'label'   => esc_html__( 'Counter Number', 'absolute-addons' ),
		'type'    => Controls_Manager::TEXT,
		'default' => esc_html__( '31,00', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'counter_number_inline_color_six',
	[
		'label'     => esc_html__( 'Number Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-number-flex {{CURRENT_ITEM}} h2'   => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-counter .counter-item .counter-number-flex {{CURRENT_ITEM}} span' => 'color: {{VALUE}}',
		],
	]
);

$repeater->add_control(
	'counter_suffix_six',
	[
		'label'       => esc_html__( 'Counter Suffix', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => esc_html__( 'Plus', 'absolute-addons' ),
		'default'     => esc_html__( '+', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'counter_number_speed_six',
	[
		'label'   => esc_html__( 'Counter Number Speed', 'absolute-addons' ),
		'type'    => Controls_Manager::NUMBER,
		'default' => esc_html__( '1500', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'counter_string_number_six',
	[
		'label'   => esc_html__( 'Counter String Number', 'absolute-addons' ),
		'type'    => Controls_Manager::TEXT,
		'default' => esc_html__( '01', 'absolute-addons' ),
	]
);

$repeater->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'      => 'counter_string_bg_six',
		'label'     => esc_html__( 'Background', 'absolute-addons' ),
		'types'     => [ 'classic', 'gradient' ],
		'selector'  => '{{WRAPPER}} .absp-counter .counter-item-six .counter-box {{CURRENT_ITEM}} > span',
		'condition' => [
			'counter_string_number_six!' => '',
		],
	]
);

$repeater->add_control(
	'counter_number_string_color_six',
	[
		'label'     => esc_html__( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item-six .counter-box {{CURRENT_ITEM}} > span' => 'color: {{VALUE}}',
		],
		'condition' => [
			'counter_string_number_six!' => '',
		],
	]
);

$repeater->add_control(
	'counter_image_six',
	[
		'label'   => esc_html__( 'Image', 'absolute-addons' ),
		'type'    => Controls_Manager::MEDIA,
		'dynamic' => [
			'active' => true,
		],
		'default' => [
			'url' => absp_get_default_placeholder(),
		],
	]
);

$this->add_control(
	'counter_repeater_six',
	[
		'label'       => esc_html__( 'Counter Item', 'absolute-addons' ),
		'type'        => Controls_Manager::REPEATER,
		'fields'      => $repeater->get_controls(),
		'default'     => [
			[
				'counter_title_six'         => esc_html__( 'Building The Future', 'absolute-addons' ),
				'counter_number_six'        => esc_html__( '31,00', 'absolute-addons' ),
				'counter_string_number_six' => esc_html__( '01', 'absolute-addons' ),
			],
			[
				'counter_title_six'         => esc_html__( 'Eco-Green by Trees', 'absolute-addons' ),
				'counter_number_six'        => esc_html__( '186,000', 'absolute-addons' ),
				'counter_string_number_six' => esc_html__( '02', 'absolute-addons' ),
			],
			[
				'counter_title_six'         => esc_html__( 'Consectetuer Adipiscing', 'absolute-addons' ),
				'counter_number_six'        => esc_html__( '923,00', 'absolute-addons' ),
				'counter_string_number_six' => esc_html__( '03', 'absolute-addons' ),
			],
		],
		'title_field' => '{{{ counter_title_six }}}',
		'condition'   => [
			'absolute_counter' => [ 'six' ],
		],
	]
);

$this->end_controls_section();

