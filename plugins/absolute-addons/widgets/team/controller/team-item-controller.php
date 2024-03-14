<?php
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}


use Elementor\Controls_Manager;
use Elementor\Repeater;

$this->start_controls_section(
	'section_team_member',
	array(
		'label' => __( 'Team Member', 'absolute-addons' ),
	)
);

$this->add_control(
	'team_member_image',
	array(
		'label'   => esc_html__( 'Team Member Image', 'absolute-addons' ),
		'type'    => Controls_Manager::MEDIA,
		'default' => [
			'url' => absp_get_default_placeholder(),
		],
	)
);

$this->add_control(
	'team_member_name',
	array(
		'label'       => esc_html__( 'Name', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => __( 'Jone Doe', 'absolute-addons' ),
		'placeholder' => __( 'Type your Name', 'absolute-addons' ),
		'conditions'  => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'team_style_variation',
					'operator' => '!==',
					'value'    => 'two',
				],
				[
					'name'     => 'team_style_variation',
					'operator' => '!==',
					'value'    => 'six',
				],
				[
					'name'     => 'team_style_variation',
					'operator' => '!==',
					'value'    => 'twenty-five',
				],

			],

		],
	)
);

$this->add_control(
	'team_member_first_name',
	array(
		'label'       => esc_html__( 'First Name', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => __( 'Jone ', 'absolute-addons' ),
		'placeholder' => __( 'Type your First Name', 'absolute-addons' ),
		'conditions'  => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'six',
				],
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'twenty-five',
				],
			],

		],
	)
);

$this->add_control(
	'team_member_last_name',
	array(
		'label'       => esc_html__( 'Last Name', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => __( 'Doe', 'absolute-addons' ),
		'placeholder' => __( 'Type your Last Name', 'absolute-addons' ),
		'conditions'  => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'six',
				],
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'twenty-five',
				],
			],
		],
	)
);

$this->add_control(
	'team_member_designation',
	array(
		'label'   => esc_html__( 'Member designation', 'absolute-addons' ),
		'type'    => Controls_Manager::TEXT,
		'default' => 'CEO',
	)
);

$this->add_control(
	'team_member_about_label',
	[
		'label'       => __( 'About label', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => __( 'About me:', 'absolute-addons' ),
		'placeholder' => __( 'Type your title here', 'absolute-addons' ),
		'condition'   => [ 'team_style_variation' => 'two' ],
	]
);

$this->add_control(
	'team_member_about',
	array(
		'label'      => esc_html__( 'About', 'absolute-addons' ),
		'type'       => Controls_Manager::WYSIWYG,
		'default'    => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima.Hello',
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'five',

				],
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'six',
				],
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'eight',
				],
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'nine',
				],
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'four',
				],
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'twenty-five',
				],
			],
		],
	)
);

$this->end_controls_section();

// Add Social Profile Control
$this->start_controls_section(
	'team_member_social_profile',
	array(
		'label'      => __( 'Social Profile', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_CONTENT,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'team_style_variation',
					'operator' => '!==',
					'value'    => 'nine',

				],
			],
		],
	) );

$this->add_control(
	'team_member_social_profile_title',
	[
		'label'       => __( 'Social Title', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => __( 'Connect me:', 'absolute-addons' ),
		'placeholder' => __( 'Type your title here', 'absolute-addons' ),
		'condition'   => [ 'team_style_variation' => 'two' ],
	]
);

$repeater = new Repeater();

$repeater->add_control(
	'team_member_social_icon',
	array(
		'label'            => esc_html__( 'Select Icon', 'absolute-addons' ),
		'type'             => Controls_Manager::ICONS,
		'fa4compatibility' => 'absolute-addons',
		'default'          => [
			'value'   => 'fa fa-facebook',
			'library' => 'solid',
		],

	)
);

$repeater->add_control(
	'team_member_social_icon_url',
	array(
		'label'       => esc_html__( 'Type Url', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => __( 'Type your social profile link', 'absolute-addons' ),
		'default'     => '#',
	)
);

$repeater->start_controls_tabs(
	'team_social'
);

$repeater->start_controls_tab(
	'team_social_normal_tab',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'team_member_social_icon_color',
	array(
		'label'     => esc_html__( 'Select Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-team.element-one > .absp-team-item > .holder > .data > .social_icons ul {{CURRENT_ITEM}} a' => 'color:{{VALUE}}',
			'{{WRAPPER}} .absp-wrapper .absp-team.element-two  .social-link ul {{CURRENT_ITEM}} a'                                       => 'color:{{VALUE}}',
			'{{WRAPPER}} .absp-team-item .absp-team-inner .image-area .social_icons ul {{CURRENT_ITEM}} a'                               => 'color:{{VALUE}}',
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-item .absp-team-social  {{CURRENT_ITEM}} a'                            => 'color:{{VALUE}}',
			'{{WRAPPER}} .absp-team .absp-team-item .absp-team-social  {{CURRENT_ITEM}} a'                                               => 'color:{{VALUE}}',
			'{{WRAPPER}} .absp-team .absp-team-item .absp-team-social  {{CURRENT_ITEM}} a i'                                               => 'color:{{VALUE}}',
		],
	)
);

$repeater->add_control(
	'team_member_social_icon_bgcolor',
	array(
		'label'     => esc_html__( 'Select Icon Background Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-team.element-one > .absp-team-item > .holder > .data > .social_icons ul {{CURRENT_ITEM}} a' => 'background:{{VALUE}}',
			'{{WRAPPER}} .absp-wrapper .absp-team.element-two  .social-link ul {{CURRENT_ITEM}} a'                                       => 'background:{{VALUE}}',
			'{{WRAPPER}} .absp-team-item .absp-team-inner .image-area .social_icons ul {{CURRENT_ITEM}} a'                               => 'background:{{VALUE}}',
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-item .absp-team-social {{CURRENT_ITEM}} a'                             => 'background:{{VALUE}}',
			'{{WRAPPER}} .absp-team .absp-team-item .absp-team-social {{CURRENT_ITEM}} a'                                                => 'background:{{VALUE}}',
			'{{WRAPPER}} .absp-team .absp-team-item .absp-team-social {{CURRENT_ITEM}} a i'                                                => 'background:{{VALUE}}',
		],
	)
);

$repeater->end_controls_tab();

$repeater->start_controls_tab(
	'team_social_hover_tab',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$repeater->add_control(
	'team_member_social_icon_color_hover',
	array(
		'label'     => esc_html__( 'Icon Hover Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-team.element-one > .absp-team-item > .holder > .data > .social_icons ul {{CURRENT_ITEM}} a:hover' => 'color:{{VALUE}}',
			'{{WRAPPER}} .absp-wrapper .absp-team.element-two  .social-link ul {{CURRENT_ITEM}} a:hover'                                       => 'color:{{VALUE}}',
			'{{WRAPPER}} .absp-team-item .absp-team-inner .image-area .social_icons ul {{CURRENT_ITEM}} a:hover'                               => 'color:{{VALUE}}',
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-item .absp-team-social  {{CURRENT_ITEM}} a:hover'                            => 'color:{{VALUE}}',
			'{{WRAPPER}} .absp-team .absp-team-item .absp-team-social  {{CURRENT_ITEM}} a:hover'                                               => 'color:{{VALUE}}',
			'{{WRAPPER}} .absp-team .absp-team-item .absp-team-social  {{CURRENT_ITEM}} a:hover i'                                               => 'color:{{VALUE}}',
		],

	)
);

$repeater->add_control(
	'team_member_social_icon_bgcolor_hover',
	array(
		'label'     => esc_html__( 'Icon Hover Background Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-team.element-one > .absp-team-item > .holder > .data > .social_icons ul {{CURRENT_ITEM}} a:hover' => 'background:{{VALUE}}',
			'{{WRAPPER}} .absp-wrapper .absp-team.element-two  .social-link ul {{CURRENT_ITEM}} a:hover'                                       => 'background:{{VALUE}}',
			'{{WRAPPER}} .absp-team-item .absp-team-inner .image-area .social_icons ul {{CURRENT_ITEM}} a:hover'                               => 'background:{{VALUE}}',
			'{{WRAPPER}} .absp-team.element-twenty-one .absp-team-item .absp-team-social  {{CURRENT_ITEM}} a:hover'                            => 'background:{{VALUE}}',
			'{{WRAPPER}} .absp-team .absp-team-item .absp-team-social  {{CURRENT_ITEM}} a:hover'                                               => 'background:{{VALUE}}',
			'{{WRAPPER}} .absp-team .absp-team-item .absp-team-social  {{CURRENT_ITEM}} a:hover i'                                               => 'background:{{VALUE}}',
		],

	)
);

$repeater->end_controls_tab();

$repeater->end_controls_tabs();

$this->add_control(
	'team_member_social_media',
	array(
		'label'       => esc_html__( 'Social Media', 'absolute-addons' ),
		'type'        => Controls_Manager::REPEATER,
		'fields'      => $repeater->get_controls(),
		'title_field' => '<i class="{{{team_member_social_icon.value}}}" aria-hidden="true"></i>  {{{team_member_social_icon.value}}}',
		'default'     => [
			[
				'team_member_social_icon_url'           => '#',
				'team_member_social_icon'               => [
					'library' => 'solid',
					'value'   => 'fa fa-facebook',
				],
				'team_member_social_icon_color'         => '#fff',
				'team_member_social_icon_bgcolor'       => '#4267B2',
				'team_member_social_icon_color_hover'   => '#fff',
				'team_member_social_icon_bgcolor_hover' => '#3870E3',
			],
			[
				'team_member_social_icon_url'           => '#',
				'team_member_social_icon'               => [
					'library' => 'solid',
					'value'   => 'fa fa-twitter',
				],
				'team_member_social_icon_color'         => '#fff',
				'team_member_social_icon_bgcolor'       => '#1EC8FF',
				'team_member_social_icon_color_hover'   => '#fff',
				'team_member_social_icon_bgcolor_hover' => '#1EC8Fe',
			],
			[
				'team_member_social_icon_url'           => '#',
				'team_member_social_icon'               => [
					'value' => 'fa fa-linkedin',
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

/****************/
$this->start_controls_section(
	'team_member_button_section',
	array(
		'label'      => __( 'Button', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_CONTENT,
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'nine',
				],

			],
		],
	) );

$this->add_control(
	'team_member_button_text',
	array(
		'label'       => esc_html__( 'Button Text', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => __( 'Type Here', 'absolute-addons' ),
		'default'     => __( 'Click Here', 'absolute-addons' ),
	)
);

$this->add_control(
	'team_member_button_url',
	array(
		'label'       => esc_html__( 'Type Url', 'absolute-addons' ),
		'type'        => Controls_Manager::URL,
		'placeholder' => __( 'Type your social profile link', 'absolute-addons' ),
	)
);

$this->end_controls_section();
