<?php

use Elementor\Controls_Manager;
use Elementor\Repeater;

$this->start_controls_section(
	'section_content_two',
	array(
		'label'     => __( 'Content', 'absolute-addons' ),
		'condition' => [
			'absolute_list_group' => [ 'two', 'seven', 'twelve' ],
		],
	)
);

$repeater = new Repeater();

$repeater->add_control(
	'title_link_select',
	[
		'label'   => esc_html__( 'Title Select', 'absolute-addons' ),
		'type'    => Controls_Manager::SELECT,
		'options' => [
			'yes'  => esc_html__( 'With Link Title', 'absolute-addons' ),
			'none' => esc_html__( 'Without Title', 'absolute-addons' ),
		],
		'default' => 'yes',
	]
);

$repeater->add_control(
	'list_group_title',
	[
		'label'       => esc_html__( 'Title', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'label_block' => true,
		'default'     => esc_html__( 'Stackworks Solution', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'link_url',
	[
		'label'         => esc_html__( 'Link', 'absolute-addons' ),
		'type'          => Controls_Manager::URL,
		'placeholder'   => esc_html__( 'https://your-link.com', 'absolute-addons' ),
		'show_external' => true,
		'default'       => [
			'url'         => '',
			'is_external' => true,
			'nofollow'    => true,
		],
		'condition'     => [
			'title_link_select' => [ 'yes' ],
		],
	]
);

$repeater->add_control(
	'list_group_icon_list',
	[
		'label'            => esc_html__( 'List Icon', 'absolute-addons' ),
		'type'             => Controls_Manager::ICONS,
		'fa4compatibility' => 'absolute-addons',
		'default'          => [
			'value'   => 'fas fa-chart-area',
			'library' => 'solid',
		],
	]
);

$repeater->add_control(
	'list_group_icon',
	[
		'label'            => esc_html__( 'Icon', 'absolute-addons' ),
		'type'             => Controls_Manager::ICONS,
		'fa4compatibility' => 'absolute-addons',
		'default'          => [
			'value'   => 'fas fa-angle-right',
			'library' => 'solid',
		],
	]
);

$this->add_control(
	'list_group_repeater_two',
	[
		'label'       => esc_html__( 'List Group', 'absolute-addons' ),
		'type'        => Controls_Manager::REPEATER,
		'show_label'  => true,
		'fields'      => $repeater->get_controls(),
		'default'     => [
			[
				'list_group_title'     => esc_html__( 'Stackworks Solution', 'absolute-addons' ),
				'list_group_icon_list' => [ 'value' => 'fas fa-chart-area' ],
			],
			[
				'list_group_title'     => esc_html__( 'Social Media Covering', 'absolute-addons' ),
				'list_group_icon_list' => [ 'value' => 'fas fa-shopping-basket' ],
			],
			[
				'list_group_title'     => esc_html__( 'Communication Design', 'absolute-addons' ),
				'list_group_icon_list' => [ 'value' => 'fas fa-envelope-open' ],
			],
			[
				'list_group_title'     => esc_html__( 'Human Resources', 'absolute-addons' ),
				'list_group_icon_list' => [ 'value' => 'fas fa-rocket' ],
			],
		],
		'title_field' => '{{{ list_group_title }}}',
		'condition'   => [
			'absolute_list_group' => [ 'two', 'seven', 'twelve' ],
		],
	]
);

$this->end_controls_section();
