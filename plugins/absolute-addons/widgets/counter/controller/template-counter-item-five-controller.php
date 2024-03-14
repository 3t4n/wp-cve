<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$this->start_controls_section(
	'section_content_five',
	[
		'label'     => esc_html__( 'Counter Section', 'absolute-addons' ),
		'condition' => [
			'absolute_counter' => 'five',
		],
	]
);

$this->add_responsive_control(
	'counter_column_five',
	[
		'label'           => esc_html__( 'Counter Column', 'absolute-addons' ),
		'type'            => Controls_Manager::SELECT,
		'default'         => '3',
		'options'         => [
			'1' => esc_html__( '1 Column', 'absolute-addons' ),
			'2' => esc_html__( '2 Column', 'absolute-addons' ),
			'3' => esc_html__( '3 Column', 'absolute-addons' ),
			'4' => esc_html__( '4 Column', 'absolute-addons' ),
			'5' => esc_html__( '5 Column', 'absolute-addons' ),
			'6' => esc_html__( '6 Column', 'absolute-addons' ),
		],
		'devices'         => [ 'desktop', 'tablet', 'mobile' ],
		'desktop_default' => 3,
		'tablet_default'  => 3,
		'mobile_default'  => 1,
		'prefix_class'    => 'counter-grid%s-',
		'style_transfer'  => true,
		'selectors'       => [
			'(desktop+){{WRAPPER}} .absp-counter .counter-item .counter-grid-col' => 'grid-template-columns: repeat({{counter_column_five.VALUE}}, 1fr);',
			'(tablet){{WRAPPER}} .absp-counter .counter-item .counter-grid-col'   => 'grid-template-columns: repeat({{counter_column_five_tablet.VALUE}}, 1fr);',
			'(mobile){{WRAPPER}} .absp-counter .counter-item .counter-grid-col'   => 'grid-template-columns: repeat({{counter_column_five_mobile.VALUE}}, 1fr);',
		],
	]
);

$repeater = new Repeater();

$repeater->add_control(
	'counter_title_five',
	[
		'label'       => esc_html__( 'Counter Title', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'label_block' => true,
		'default'     => esc_html__( 'Project Completed', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'counter_number_five',
	[
		'label'   => esc_html__( 'Counter Number', 'absolute-addons' ),
		'type'    => Controls_Manager::TEXT,
		'default' => esc_html__( '2,530', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'counter_number_inline_color_five',
	[
		'label'     => esc_html__( 'Number Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-box > {{CURRENT_ITEM}} > h2'   => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-counter .counter-item .counter-box > {{CURRENT_ITEM}} > span' => 'color: {{VALUE}}',
		],
	]
);

$repeater->add_control(
	'counter_suffix_five',
	[
		'label'       => esc_html__( 'Counter Suffix', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => esc_html__( 'Plus', 'absolute-addons' ),
		'default'     => '',
	]
);

$repeater->add_control(
	'counter_number_speed_five',
	[
		'label'   => esc_html__( 'Counter Number Speed', 'absolute-addons' ),
		'type'    => Controls_Manager::NUMBER,
		'default' => esc_html__( '1500', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'counter_icon_select_five',
	[
		'label'   => esc_html__( 'Select Icon', 'absolute-addons' ),
		'type'    => Controls_Manager::SELECT,
		'options' => [
			'true'  => esc_html__( 'Yes', 'absolute-addons' ),
			'false' => esc_html__( 'No', 'absolute-addons' ),
		],
		'default' => 'false',
	]
);

$repeater->add_control(
	'counter_icon_five',
	[
		'label'            => esc_html__( 'Icon', 'absolute-addons' ),
		'type'             => Controls_Manager::ICONS,
		'fa4compatibility' => 'absolute-addons',
		'default'          => [
			'value'   => 'fab fa-twitter',
			'library' => 'solid',
		],
		'condition'        => [
			'counter_icon_select_five' => 'true',
		],
	]
);

$repeater->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'      => 'counter_icon_bg_five',
		'label'     => esc_html__( 'Background', 'absolute-addons' ),
		'types'     => [ 'classic', 'gradient' ],
		'selector'  => '{{WRAPPER}} .absp-counter .counter-item {{CURRENT_ITEM}} > i',
		'condition' => [
			'counter_icon_select' => 'true',
		],
	]
);

$repeater->add_control(
	'counter_number_icon_color_five',
	[
		'label'     => esc_html__( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item {{CURRENT_ITEM}} > i' => 'color: {{VALUE}}',
		],
		'condition' => [
			'counter_icon_select_five' => 'true',
		],
	]
);

$this->add_control(
	'counter_repeater_five',
	[
		'label'       => esc_html__( 'Counter Item', 'absolute-addons' ),
		'type'        => Controls_Manager::REPEATER,
		'fields'      => $repeater->get_controls(),
		'default'     => [
			[
				'counter_title_five'               => esc_html__( 'Project Completed', 'absolute-addons' ),
				'counter_number_five'              => esc_html__( '2,530', 'absolute-addons' ),
				'counter_number_inline_color_five' => '#FFC864',
			],
			[
				'counter_title_five'               => esc_html__( 'Line of Codes', 'absolute-addons' ),
				'counter_number_five'              => esc_html__( '93,976', 'absolute-addons' ),
				'counter_number_inline_color_five' => '#A5D278',
			],
			[
				'counter_title_five'               => esc_html__( 'Happy Customers', 'absolute-addons' ),
				'counter_number_five'              => esc_html__( '21,430', 'absolute-addons' ),
				'counter_number_inline_color_five' => '#82D2E6',
			],
		],
		'title_field' => '{{{ counter_title_five }}}',
		'condition'   => [
			'absolute_counter' => [ 'five' ],
		],
	]
);

$this->end_controls_section();

