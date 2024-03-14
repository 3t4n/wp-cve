<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

$this->start_controls_section(
	'section_content_fourteen',
	array(
		'label'     => __( 'Content', 'absolute-addons' ),
		'condition' => [
			'absolute_list_group' => [ 'fourteen' ],
		],
	)
);

$repeater = new Repeater();

$repeater->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'list_icon_bg',
		'fields_options' => [
			'background' => [
				'label' => esc_html__( 'List icon background', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'default'        => [ 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-list-group .list-group-style-fourteen {{CURRENT_ITEM}} > .list-icon',
	]
);

$repeater->add_control(
	'list_icon_color',
	[
		'label'     => esc_html__( 'Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-list-group .list-group-style-fourteen {{CURRENT_ITEM}} > .list-icon' => 'color: {{VALUE}}',
		],
	]
);

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
	'list_group_repeater_fourteen',
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
				'list_group_icon_list' => [ 'value' => 'fas fa-envelope-open' ],
			],
			[
				'list_group_title'     => esc_html__( 'Communication Design', 'absolute-addons' ),
				'list_group_icon_list' => [ 'value' => 'fas fa-paint-brush' ],
			],
			[
				'list_group_title'     => esc_html__( 'Human Resources', 'absolute-addons' ),
				'list_group_icon_list' => [ 'value' => 'fas fa-shopping-basket' ],
			],
		],
		'title_field' => '{{{ list_group_title }}}',
		'condition'   => [
			'absolute_list_group' => [ 'fourteen' ],
		],
	]
);

$this->end_controls_section();
