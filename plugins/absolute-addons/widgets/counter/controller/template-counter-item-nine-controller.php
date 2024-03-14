<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$this->start_controls_section(
	'section_content_nine',
	[
		'label'     => esc_html__( 'Counter Section', 'absolute-addons' ),
		'condition' => [
			'absolute_counter' => 'nine',
		],
	]
);

$this->add_responsive_control(
	'counter_column_nine',
	[
		'label'           => esc_html__( 'Counter Column', 'absolute-addons' ),
		'type'            => Controls_Manager::SELECT,
		'default'         => '2',
		'options'         => [
			'1' => esc_html__( '1 Column', 'absolute-addons' ),
			'2' => esc_html__( '2 Column', 'absolute-addons' ),
			'3' => esc_html__( '3 Column', 'absolute-addons' ),
			'4' => esc_html__( '4 Column', 'absolute-addons' ),
			'5' => esc_html__( '5 Column', 'absolute-addons' ),
			'6' => esc_html__( '6 Column', 'absolute-addons' ),
		],
		'devices'         => [ 'desktop', 'tablet', 'mobile' ],
		'desktop_default' => 2,
		'tablet_default'  => 3,
		'mobile_default'  => 1,
		'prefix_class'    => 'counter-grid%s-',
		'style_transfer'  => true,
		'selectors'       => [
			'(desktop+){{WRAPPER}} .absp-counter .counter-item .counter-grid-col' => 'grid-template-columns: repeat({{counter_column_nine.VALUE}}, 1fr);',
			'(tablet){{WRAPPER}} .absp-counter .counter-item .counter-grid-col'   => 'grid-template-columns: repeat({{counter_column_nine_tablet.VALUE}}, 1fr);',
			'(mobile){{WRAPPER}} .absp-counter .counter-item .counter-grid-col'   => 'grid-template-columns: repeat({{counter_column_nine_mobile.VALUE}}, 1fr);',
		],
	]
);

$repeater = new \Elementor\Repeater();

$repeater->add_control(
	'counter_title_nine',
	[
		'label'       => esc_html__( 'Counter Title', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'label_block' => true,
		'default'     => esc_html__( 'Lorem Ipsum', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'counter_number_nine',
	[
		'label'   => esc_html__( 'Counter Number', 'absolute-addons' ),
		'type'    => Controls_Manager::TEXT,
		'default' => esc_html__( '20', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'counter_number_inline_color_nine',
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
	'counter_suffix_nine',
	[
		'label'       => esc_html__( 'Counter Suffix', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => esc_html__( 'Plus', 'absolute-addons' ),
		'default'     => esc_html__( 'K+', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'counter_number_speed_nine',
	[
		'label'   => esc_html__( 'Counter Number Speed', 'absolute-addons' ),
		'type'    => Controls_Manager::NUMBER,
		'default' => esc_html__( '1500', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'counter_icon_select_nine',
	[
		'label'   => esc_html__( 'Select Icon', 'absolute-addons' ),
		'type'    => Controls_Manager::SELECT,
		'options' => [
			'true'  => esc_html__( 'Yes', 'absolute-addons' ),
			'false' => esc_html__( 'No', 'absolute-addons' ),
		],
		'default' => 'true',
	]
);

$repeater->add_control(
	'counter_icon_nine',
	[
		'label'            => esc_html__( 'Icon', 'absolute-addons' ),
		'type'             => Controls_Manager::ICONS,
		'fa4compatibility' => 'absolute-addons',
		'default'          => [
			'value'   => 'fas fa-folder',
			'library' => 'solid',
		],
		'condition'        => [
			'counter_icon_select_nine' => 'true',
		],
	]
);

$repeater->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'      => 'counter_icon_bg_nine',
		'label'     => esc_html__( 'Background', 'absolute-addons' ),
		'types'     => [ 'classic', 'gradient' ],
		'default'   => [ 'classic' ],
		'selector'  => '{{WRAPPER}} .absp-counter .counter-item {{CURRENT_ITEM}} > i',
		'condition' => [
			'counter_icon_select' => 'true',
		],
	]
);

$repeater->add_control(
	'counter_number_icon_color_nine',
	[
		'label'     => esc_html__( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item {{CURRENT_ITEM}} > i' => 'color: {{VALUE}}',
		],
		'condition' => [
			'counter_icon_select' => 'true',
		],
	]
);

$this->add_control(
	'counter_repeater_nine',
	[
		'label'       => esc_html__( 'Counter Item', 'absolute-addons' ),
		'type'        => Controls_Manager::REPEATER,
		'fields'      => $repeater->get_controls(),
		'default'     => [
			[
				'counter_title_nine'               => esc_html__( 'Lorem Ipsum', 'absolute-addons' ),
				'counter_number_nine'              => esc_html__( '20', 'absolute-addons' ),
				'counter_suffix_nine'              => esc_html__( 'K+', 'absolute-addons' ),
				'counter_icon_nine'                => [ 'value' => 'fas fa-folder' ],
				'counter_number_inline_color_nine' => '#FFA528',
				'counter_icon_bg_nine'             => '#FFA528',

			],
			[
				'counter_title_nine'               => esc_html__( 'Lorem Ipsum', 'absolute-addons' ),
				'counter_number_nine'              => esc_html__( '10,650', 'absolute-addons' ),
				'counter_suffix_nine'              => '',
				'counter_icon_nine'                => [ 'value' => 'fas fa-heart' ],
				'counter_number_inline_color_nine' => '#FF7378',
				'counter_icon_bg_nine'             => '#FF7378',
			],
			[
				'counter_title_nine'               => esc_html__( 'Lorem Ipsum', 'absolute-addons' ),
				'counter_number_nine'              => esc_html__( '10', 'absolute-addons' ),
				'counter_suffix_nine'              => esc_html__( '%', 'absolute-addons' ),
				'counter_icon_nine'                => [ 'value' => 'fas fa-tag' ],
				'counter_icon_bg_nine'             => '#96D241',
				'counter_number_inline_color_nine' => '#96D241',
			],
			[
				'counter_title_nine'               => esc_html__( 'Lorem Ipsum', 'absolute-addons' ),
				'counter_number_nine'              => esc_html__( '89', 'absolute-addons' ),
				'counter_suffix_nine'              => esc_html__( '%', 'absolute-addons' ),
				'counter_icon_nine'                => [ 'value' => 'fas fa-comment' ],
				'counter_icon_bg_nine'             => '#A087FF',
				'counter_number_inline_color_nine' => '#A087FF',
			],
		],
		'title_field' => '{{{ counter_title_nine }}}',
		'condition'   => [
			'absolute_counter' => 'nine',
		],
	]
);

$this->end_controls_section();

