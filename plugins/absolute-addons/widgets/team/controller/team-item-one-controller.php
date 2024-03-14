<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

$this->start_controls_section(
	'section_team_member',
	array(
		'label'     => __('Team Member', 'absolute-addons'),
		'condition' => [ 'team_style_variation' => 'one' ],
	)
);

$this->add_control(
	'team_member_image',
	array(
		'label'     => esc_html__('Team Member Image', 'absolute-addons'),
		'type'      => Controls_Manager::MEDIA,
		'default'   => [
			'url' => absp_get_default_placeholder(),
		],
		'condition' => [ 'team_style_variation' => 'one' ],
	)
);

$this->add_control(
	'team_member_name',
	[
		'label'       => esc_html__('Name', 'absolute-addons'),
		'type'        => Controls_Manager::TEXT,
		'default'     => __('Linda Willis', 'absolute-addons'),
		'placeholder' => __('Type your Name', 'absolute-addons'),
		'condition'   => [ 'team_style_variation' => 'one' ],
	]
);

$this->add_control(
	'team_member_designation',
	[
		'label'   => esc_html__('Member designation', 'absolute-addons'),
		'type'    => Controls_Manager::TEXT,
		'default' => 'UI DESIGNER',
	]
);

$this->add_control(
	'team_member_about',
	array(
		'label'     => esc_html__('About', 'absolute-addons'),
		'type'      => Controls_Manager::WYSIWYG,
		'default'   => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.',
		'condition' => [ 'team_style_variation' => 'one' ],
	)
);

$this->end_controls_section();

//Add Social Profile Control
$this->start_controls_section(
	'team_member_social_profile',
	array(
		'label'     => __('Social Profile', 'absolute-addons'),
		'tab'       => Controls_Manager::TAB_CONTENT,
		'condition' => [ 'team_style_variation' => 'one' ],
	));

$repeater = new Repeater();

$repeater->add_control(
	'team_member_social_icon',
	array(
		'label'            => esc_html__('Select Icon', 'absolute-addons'),
		'type'             => Controls_Manager::ICONS,
		'fa4compatibility' => 'absolute-addons',
		'default'          => [
			'value' => ' absp absp-facebook',
		],

	)
);

$repeater->add_control(
	'team_member_social_icon_url',
	array(
		'label'       => esc_html__('Type Url', 'absolute-addons'),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => __('Type your social profile link', 'absolute-addons'),
		'default'     => '#',
	)
);

$repeater->start_controls_tabs(
	'team_social'
);

$repeater->start_controls_tab(
	'team_social_normal_tab',
	[
		'label' => __('Normal', 'absolute-addons'),
	]
);

$repeater->add_control(
	'team_member_social_icon_color',
	array(
		'label'     => esc_html__('Select Icon Color', 'absolute-addons'),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul {{CURRENT_ITEM}} a' => 'color:{{VALUE}}',
		],

	)
);

$repeater->add_control(
	'team_member_social_icon_bgcolor',
	array(
		'label'     => esc_html__('Select Icon Background Color', 'absolute-addons'),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul {{CURRENT_ITEM}} a' => 'background:{{VALUE}}',
		],
	)
);

$repeater->end_controls_tab();

$repeater->start_controls_tab(
	'team_social_hover_tab',
	[
		'label' => __('Hover', 'absolute-addons'),
	]
);

$repeater->add_control(
	'team_member_social_icon_color_hover',
	array(
		'label'     => esc_html__('Icon Hover Color', 'absolute-addons'),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul {{CURRENT_ITEM}} a:hover' => 'color:{{VALUE}}',
		],

	)
);

$repeater->add_control(
	'team_member_social_icon_bgcolor_hover',
	array(
		'label'     => esc_html__('Icon Hover Background Color', 'absolute-addons'),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-team > .absp-team-item > .holder > .data > .social_icons ul {{CURRENT_ITEM}} a:hover' => 'background:{{VALUE}}',
		],
	)
);

$repeater->end_controls_tab();

$repeater->end_controls_tabs();

$this->add_control(
	'team_member_social_media',
	array(
		'label'       => esc_html__('Social Media', 'absolute-addons'),
		'type'        => Controls_Manager::REPEATER,
		'fields'      => $repeater->get_controls(),
		'title_field' => '{{{ elementor.helpers.renderIcon( this, team_member_social_icon, { "aria-hidden": "true" }, "i", "panel" ) }}} {{{team_member_social_icon.value}}}',
		'default'     => [
			[
				'team_member_social_icon_url'           => __('#', 'absolute-addons'),
				'team_member_social_icon'               => [
					'value' => ' absp absp-facebook',
				],
				'team_member_social_icon_color'         => '#fff',
				'team_member_social_icon_bgcolor'       => '#4267B2',
				'team_member_social_icon_color_hover'   => '#fff',
				'team_member_social_icon_bgcolor_hover' => '#3870E3',
			],
			[
				'team_member_social_icon_url'           => __('#', 'absolute-addons'),
				'team_member_social_icon'               => [
					'value' => ' absp absp-twitter',
				],
				'team_member_social_icon_color'         => '#fff',
				'team_member_social_icon_bgcolor'       => '#1EC8FF',
				'team_member_social_icon_color_hover'   => '#fff',
				'team_member_social_icon_bgcolor_hover' => '#1EC8Fe',
			],
			[
				'team_member_social_icon_url'           => __('#', 'absolute-addons'),
				'team_member_social_icon'               => [
					'value' => ' absp absp-linkedin',
				],
				'team_member_social_icon_color'         => '#fff',
				'team_member_social_icon_bgcolor'       => '#007DA5',
				'team_member_social_icon_color_hover'   => '#fff',
				'team_member_social_icon_bgcolor_hover' => '#007DA6',
			],
		],
	)
);

$this->end_controls_section();


