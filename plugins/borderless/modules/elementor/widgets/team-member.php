<?php

namespace Borderless\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Css_Filter;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Team_Member extends Widget_Base {
	
	public function get_name() {
		return 'borderless-elementor-team-member';
	}
	
	public function get_title() {
		return esc_html__( 'Team Member', 'borderless');
	}
	
	public function get_icon() {
		return 'borderless-icon-team-member';
	}
	
	public function get_categories() {
		return [ 'borderless' ];
	}

	public function get_keywords()
	{
        return [
			'team',
			'member',
			'team member',
			'person',
			'card',
			'meet the team',
			'team builder',
			'our team',
			'borderless',
			'borderless team member',
			'borderless team members'
		];
    }

	public function get_custom_help_url()
	{
        return 'https://wpborderless.com/';
    }
	
	protected function _register_controls() {


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Avatar
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_section_team_member_avatar',
			[
				'label' => esc_html__( 'Picture', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'borderless_team_member_avatar',
			[
				'label' => __( 'Upload Picture', 'borderless' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'borderless_team_member_avatar',
				'default' => 'large',
				'separator' => 'none',
			]
		);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_section_team_member_content',
			[
				'label' => esc_html__( 'Content', 'borderless')
			]
		);

		$this->add_control(
			'borderless_team_member_name',
			[
				'label' => esc_html__( 'Name', 'borderless'),
				'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => esc_html__( 'John Doe', 'borderless'),
			]
		);

		$this->add_control(
			'borderless_team_member_job',
			[
				'label' => esc_html__( 'Job Position', 'borderless'),
				'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => esc_html__( 'Full Stack Web Developer', 'borderless'),
			]
		);

		$this->add_control(
			'borderless_team_member_description',
			[
				'label' => esc_html__( 'Description', 'borderless'),
				'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => esc_html__( 'Add team member description here. Remove the text if not necessary.', 'borderless'),
			]
		);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Social Profiles
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_section_team_member_social_profiles',
			[
				'label' => esc_html__( 'Social Profiles', 'borderless')
			]
		);

	  $this->add_control(
		  'borderless_team_member_enable_social_profiles',
		  [
			  'label' => esc_html__( 'Enable Social Profiles?', 'borderless'),
			  'type' => Controls_Manager::SWITCHER,
			  'default' => 'yes',
		  ]
	  );

	  $repeater = new Repeater();

		$repeater->add_control(
			'social_icon',
			[
				'label' => __( 'Icon', 'borderless' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'social',
				'default' => [
					'value' => 'fab fa-wordpress',
					'library' => 'fa-brands',
				],
				'recommended' => [
					'fa-brands' => [
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'deviantart',
						'digg',
						'dribbble',
						'elementor',
						'facebook',
						'flickr',
						'foursquare',
						'free-code-camp',
						'github',
						'gitlab',
						'globe',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mix',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'shopping-cart',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'telegram',
						'thumb-tack',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'viber',
						'vimeo',
						'vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					],
					'fa-solid' => [
						'envelope',
						'link',
						'rss',
					],
				],
			]
		);

	$repeater->add_control(
		'borderless_team_member_social_profiles_link',
		[
			'name' => 'link',
			'label' => esc_html__( 'Link', 'borderless'),
			'type' => Controls_Manager::URL,
			'dynamic'   => ['active' => true],
			'label_block' => true,
			'default' => [
				'url' => '',
				'is_external' => 'true',
			],
			'placeholder' => esc_html__( 'Place URL here', 'borderless'),
		]
	);

	$repeater->add_control(
		'borderless_team_member_social_profiles_item_icon_color',
		[
			'label' => __( 'Color', 'borderless' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'default',
			'options' => [
				'default' => __( 'Official Color', 'borderless' ),
				'custom' => __( 'Custom', 'borderless' ),
			],
		]
	);

	$repeater->add_control(
		'borderless_team_member_social_profiles_item_icon_primary_color',
		[
			'label' => __( 'Primary Color', 'borderless' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'borderless_team_member_social_profiles_item_icon_color' => 'custom',
			],
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon' => 'background-color: {{VALUE}};',
			],
		]
	);

	$repeater->add_control(
		'borderless_team_member_social_profiles_item_icon_secondary_color',
		[
			'label' => __( 'Secondary Color', 'borderless' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'borderless_team_member_social_profiles_item_icon_color' => 'custom',
			],
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon i' => 'color: {{VALUE}};',
				'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon svg' => 'fill: {{VALUE}};',
			],
		]
	);

	$this->add_control(
		'borderless_team_member_social_profiles_links',
		[
			'label' => __( 'Social Icons', 'borderless' ),
			'type' => Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'condition' => [
				'borderless_team_member_enable_social_profiles!' => '',
			],
			'default' => [
				[
					'social_icon' => [
						'value' => 'fab fa-facebook',
						'library' => 'fa-brands',
					],
				],
				[
					'social_icon' => [
						'value' => 'fab fa-twitter',
						'library' => 'fa-brands',
					],
				],
				[
					'social_icon' => [
						'value' => 'fab fa-youtube',
						'library' => 'fa-brands',
					],
				],
			],
			'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( social_icon, social, true, migrated, true ) }}}',
		]
	);

	$this->add_control(
		'borderless_team_members_social_profiles_view',
		[
			'label' => __( 'View', 'borderless' ),
			'type' => Controls_Manager::HIDDEN,
			'condition' => [
				'borderless_team_member_enable_social_profiles!' => '',
			],
			'default' => 'traditional',
		]
	);

	$this->end_controls_section();


	/*-----------------------------------------------------------------------------------*/
	/*  *.  Avatar - Style
	/*-----------------------------------------------------------------------------------*/

	$this->start_controls_section(
		'borderless_section_team_members_image_styles',
		[
			'label' => esc_html__( 'Picture', 'borderless'),
			'tab' => Controls_Manager::TAB_STYLE
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_image_width',
		[
			'label' => esc_html__( 'Width', 'borderless'),
			'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img' => 'width:{{SIZE}}{{UNIT}};',
			],
		]
	);

	do_action('borderless/team_member_circle_controls', $this);

	$this->add_responsive_control(
		'borderless_team_members_image_max_width',
		[
			'label' => __( 'Max Width', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'default' => [
				'unit' => '%',
			],
			'tablet_default' => [
				'unit' => '%',
			],
			'mobile_default' => [
				'unit' => '%',
			],
			'size_units' => [ '%', 'px', 'vw' ],
			'range' => [
				'%' => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
				'vw' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img' => 'max-width:{{SIZE}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_image_height',
		[
			'label' => __( 'Height', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'default' => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', 'vh' ],
			'range' => [
				'px' => [
					'min' => 1,
					'max' => 500,
				],
				'vh' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img' => 'height: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_image_margin',
		[
			'label' => esc_html__( 'Margin', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_image_padding',
		[
			'label' => esc_html__( 'Padding', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_image_object_fit',
		[
			'label' => __( 'Object Fit', 'borderless' ),
			'type' => Controls_Manager::SELECT,
			'condition' => [
				'height[size]!' => '',
			],
			'options' => [
				'' => __( 'Default', 'borderless' ),
				'fill' => __( 'Fill', 'borderless' ),
				'cover' => __( 'Cover', 'borderless' ),
				'contain' => __( 'Contain', 'borderless' ),
			],
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img' => 'object-fit: {{VALUE}};',
			],
		]
	);

	$this->add_control(
		'borderless_team_members_image_separator_panel_style',
		[
			'type' => Controls_Manager::DIVIDER,
			'style' => 'thick',
		]
	);

	$this->start_controls_tabs( 'borderless_team_members_image_effects' );

	$this->start_controls_tab( 'normal',
		[
			'label' => __( 'Normal', 'borderless' ),
		]
	);

	$this->add_control(
		'borderless_team_members_image_opacity',
		[
			'label' => __( 'Opacity', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'px' => [
					'max' => 1,
					'min' => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img' => 'opacity: {{SIZE}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Css_Filter::get_type(),
		[
			'name' => 'borderless_team_members_image_css_filters',
			'selector' => '{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img',
		]
	);

	$this->end_controls_tab();

	$this->start_controls_tab( 'hover',
		[
			'label' => __( 'Hover', 'borderless' ),
		]
	);

	$this->add_control(
		'borderless_team_members_image_opacity_hover',
		[
			'label' => __( 'Opacity', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'px' => [
					'max' => 1,
					'min' => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure:hover img' => 'opacity: {{SIZE}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Css_Filter::get_type(),
		[
			'name' => 'borderless_team_members_image_css_filters_hover',
			'selector' => '{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure:hover img',
		]
	);

	$this->add_control(
		'borderless_team_members_image_background_hover_transition',
		[
			'label' => __( 'Transition Duration', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'px' => [
					'max' => 3,
					'step' => 0.1,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img' => 'transition-duration: {{SIZE}}s',
			],
		]
	);

	$this->add_control(
		'borderless_team_members_image_hover_animation',
		[
			'label' => __( 'Hover Animation', 'borderless' ),
			'type' => Controls_Manager::HOVER_ANIMATION,
		]
	);

	$this->end_controls_tab();

	$this->end_controls_tabs();

	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'borderless_team_members_image_border',
			'selector' => '{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img',
			'separator' => 'before',
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_image_border_radius',
		[
			'label' => __( 'Border Radius', 'borderless' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Box_Shadow::get_type(),
		[
			'name' => 'borderless_team_members_image_box_shadow',
			'exclude' => [
				'box_shadow_position',
			],
			'selector' => '{{WRAPPER}} .borderless-elementor-team-member-social-profiles figure img',
		]
	);

	$this->end_controls_section();


	/*-----------------------------------------------------------------------------------*/
	/*  *.  Content - Style
	/*-----------------------------------------------------------------------------------*/

	$this->start_controls_section(
		'borderless_section_team_members_styles_general',
		[
			'label' => esc_html__( 'Content', 'borderless'),
			'tab' => Controls_Manager::TAB_STYLE
		]
	);

	$this->add_control(
		'content_card_style',
		[
			'label' => __( 'Content Card', 'borderless'),
			'type' => Controls_Manager::HEADING,
			'separator'	=> 'before'
		]
	);

	$this->add_control(
		'content_card_height',
		[
			'label' => esc_html__( 'Height', 'borderless'),
			'type' => Controls_Manager::SLIDER,
			'size_units'	=> [ 'px', 'em' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
				],
				'em'	=> [
					'min'	=> 0,
					'max'	=> 200
				]
			],
			'default'	=> [
				'unit'	=> 'px',
				'size'	=> 'auto'
			],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-content' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$this->add_control(
		'borderless_team_members_background',
		[
			'label' => esc_html__( 'Content Background Color', 'borderless'),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-content' => 'background-color: {{VALUE}};',
			],
		]
	);

	

	$start = is_rtl() ? 'end' : 'start';
	$end = is_rtl() ? 'start' : 'end';

	$this->add_responsive_control(
		'borderless_team_members_content_align',
		[
			'label' => __( 'Alignment', 'borderless' ),
			'type' => Controls_Manager::CHOOSE,
			'condition' => [
				'borderless_team_member_enable_social_profiles!' => '',
			],
			'options' => [
				'left'    => [
					'title' => __( 'Left', 'borderless' ),
					'icon' => 'eicon-text-align-left',
				],
				'center' => [
					'title' => __( 'Center', 'borderless' ),
					'icon' => 'eicon-text-align-center',
				],
				'right' => [
					'title' => __( 'Right', 'borderless' ),
					'icon' => 'eicon-text-align-right',
				],
			],
			'prefix_class' => 'e-grid-align-',
			'default' => 'center',
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-content' => 'text-align: {{VALUE}}',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_content_margin',
		[
			'label' => esc_html__( 'Margin', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_content_padding',
		[
			'label' => esc_html__( 'Padding', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'borderless_team_members_border',
			'label' => esc_html__( 'Border', 'borderless'),
			'selector' => '{{WRAPPER}} .borderless-elementor-team-member-content',
		]
	);

	$this->add_control(
		'borderless_team_members_border_radius',
		[
			'label' => esc_html__( 'Border Radius', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
			],
		]
	);

	$this->end_controls_section();


	/*-----------------------------------------------------------------------------------*/
	/*  *.  Social Profiles - Style
	/*-----------------------------------------------------------------------------------*/

	$this->start_controls_section(
		'borderless_section_team_members_social_profiles_styles',
		[
			'label' => esc_html__( 'Social Profiles', 'borderless'),
			'tab' => Controls_Manager::TAB_STYLE
		]
	);

	$this->add_control(
		'borderless_team_members_social_profiles_icon_color',
		[
			'label' => __( 'Color', 'borderless' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'default',
			'options' => [
				'default' => __( 'Official Color', 'borderless' ),
				'custom' => __( 'Custom', 'borderless' ),
			],
		]
	);

	$this->add_control(
		'borderless_team_members_social_profiles_icon_primary_color',
		[
			'label' => __( 'Primary Color', 'borderless' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'borderless_team_members_social_profiles_icon_color' => 'custom',
			],
			'selectors' => [
				'{{WRAPPER}} .elementor-social-icon' => 'background-color: {{VALUE}};',
			],
		]
	);

	$this->add_control(
		'borderless_team_members_social_profiles_icon_secondary_color',
		[
			'label' => __( 'Secondary Color', 'borderless' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				'borderless_team_members_social_profiles_icon_color' => 'custom',
			],
			'selectors' => [
				'{{WRAPPER}} .elementor-social-icon i' => 'color: {{VALUE}};',
				'{{WRAPPER}} .elementor-social-icon svg' => 'fill: {{VALUE}};',
			],
		]
	);

	$this->add_control(
		'borderless_team_members_social_profiles_hover_primary_color',
		[
			'label' => __( 'Hover Primary Color', 'borderless' ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'condition' => [
				'borderless_team_members_social_profiles_icon_color' => 'custom',
			],
			'selectors' => [
				'{{WRAPPER}} .elementor-social-icon:hover' => 'background-color: {{VALUE}};',
			],
		]
	);

	$this->add_control(
		'borderless_team_members_social_profiles_hover_secondary_color',
		[
			'label' => __( 'Hover Secondary Color', 'borderless' ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'condition' => [
				'borderless_team_members_social_profiles_icon_color' => 'custom',
			],
			'selectors' => [
				'{{WRAPPER}} .elementor-social-icon:hover i' => 'color: {{VALUE}};',
				'{{WRAPPER}} .elementor-social-icon:hover svg' => 'fill: {{VALUE}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_social_profiles_icon_size',
		[
			'label' => __( 'Size', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}}' => '--icon-size: {{SIZE}}{{UNIT}}',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_social_profiles_icon_padding',
		[
			'label' => __( 'Padding', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'selectors' => [
				'{{WRAPPER}} .elementor-social-icon' => '--icon-padding: {{SIZE}}{{UNIT}}',
			],
			'default' => [
				'unit' => 'em',
			],
			'tablet_default' => [
				'unit' => 'em',
			],
			'mobile_default' => [
				'unit' => 'em',
			],
			'range' => [
				'em' => [
					'min' => 0,
					'max' => 5,
				],
			],
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_social_profiles_icon_spacing',
		[
			'label' => __( 'Spacing', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default' => [
				'size' => 5,
			],
			'selectors' => [
				'{{WRAPPER}}' => '--grid-column-gap: {{SIZE}}{{UNIT}}',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_social_profiles_row_gap',
		[
			'label' => __( 'Rows Gap', 'borderless' ),
			'type' => Controls_Manager::SLIDER,
			'default' => [
				'size' => 0,
			],
			'selectors' => [
				'{{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' => 'image_border',
			'selector' => '{{WRAPPER}} .elementor-social-icon',
			'separator' => 'before',
		]
	);

	$this->add_control(
		'borderless_team_members_social_profiles_hover_border_color',
		[
			'label' => __( 'Hover Color', 'borderless' ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'condition' => [
				'image_border_border!' => '',
			],
			'selectors' => [
				'{{WRAPPER}} .elementor-social-icon:hover' => 'border-color: {{VALUE}};',
			],
		]
	);

	$this->add_control(
		'borderless_team_members_social_profiles_border_radius',
		[
			'label' => __( 'Border Radius', 'borderless' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_control(
		'borderless_team_members_social_profiles_shape',
		[
			'label' => __( 'Shape', 'borderless' ),
			'type' => Controls_Manager::SELECT,
			'condition' => [
				'borderless_team_member_enable_social_profiles!' => '',
			],
			'default' => 'rounded',
			'options' => [
				'rounded' => __( 'Rounded', 'borderless' ),
				'square' => __( 'Square', 'borderless' ),
				'circle' => __( 'Circle', 'borderless' ),
			],
			'prefix_class' => 'elementor-shape-',
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_social_profiles_columns',
		[
			'label' => __( 'Columns', 'borderless' ),
			'type' => Controls_Manager::SELECT,
			'condition' => [
				'borderless_team_member_enable_social_profiles!' => '',
			],
			'default' => '0',
			'options' => [
				'0' => __( 'Auto', 'borderless' ),
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
			],
			'prefix_class' => 'elementor-grid%s-',
			'selectors' => [
				'{{WRAPPER}}' => '--grid-template-columns: repeat({{VALUE}}, auto);',
			],
		]
	);

	$this->add_control(
		'borderless_team_members_social_profiles_hover_animation',
		[
			'label' => __( 'Hover Animation', 'borderless' ),
			'type' => Controls_Manager::HOVER_ANIMATION,
		]
	);

	$this->add_control(
		'borderless_team_members_social_profiles_style',
		[
			'label' => __( 'Social Profiles Section', 'borderless'),
			'type' => Controls_Manager::HEADING,
			'separator'	=> 'before'
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_social_profiles_margin',
		[
			'label' => esc_html__( 'Margin', 'borderless'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .borderless-elementor-team-member-profiles' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'borderless_team_members_social_profiles_padding',
		[
			'label'      => esc_html__( 'Padding', 'borderless'),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%', 'rem' ],
			'selectors'  => [
				'{{WRAPPER}} .borderless-elementor-team-member-profiles' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->end_controls_section();

	}
	
	protected function render() {

		$settings = $this->get_settings_for_display();
		$fallback_defaults = [
			'fa fa-facebook',
			'fa fa-twitter',
			'fa fa-google-plus',
		];

		$class_animation = '';

		if ( ! empty( $settings['borderless_team_members_social_profiles_hover_animation'] ) ) {
			$class_animation = ' elementor-animation-' . $settings['borderless_team_members_social_profiles_hover_animation'];
		}

		$migration_allowed = Icons_Manager::is_migration_allowed();

		echo'<div class="borderless-elementor-team-member">';

		if ( !empty( $settings['borderless_team_member_avatar']['url'] ) ) {
			echo'
			<div class="borderless-elementor-team-member-social-profiles">  
				<figure>
					<img src="'.$settings['borderless_team_member_avatar']['url'].'" width="500" height="600">
				</figure>
           	</div>
			'; 
		}

		echo'<div class="borderless-elementor-team-member-content">';
		if ( ! empty( $settings['borderless_team_member_name'] ) ) {
			echo'<h3 class="borderless-elementor-team-member-name">'.$settings['borderless_team_member_name'].'</h3>';
		}
		if ( ! empty( $settings['borderless_team_member_job'] ) ) {
			echo'<h4 class="borderless-elementor-team-member-job">'.$settings['borderless_team_member_job'].'</h4>';
		}
		if ( ! empty( $settings['borderless_team_member_description'] ) ) {
			echo'<p class="borderless-elementor-team-member-description">'.$settings['borderless_team_member_description'].'</p>';
		}		

		echo'<div class="borderless-elementor-team-member-profiles elementor-social-icons-wrapper elementor-grid">'; 
		foreach ( $settings['borderless_team_member_social_profiles_links'] as $index => $item ) {
			$migrated = isset( $item['__fa4_migrated']['social_icon'] );
			$is_new = empty( $item['social'] ) && $migration_allowed;
			$social = '';

			// add old default
			if ( empty( $item['social'] ) && ! $migration_allowed ) {
				$item['social'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-wordpress';
			}

			if ( ! empty( $item['social'] ) ) {
				$social = str_replace( 'fa fa-', '', $item['social'] );
			}

			if ( ( $is_new || $migrated ) && 'svg' !== $item['social_icon']['library'] ) {
				$social = explode( ' ', $item['social_icon']['value'], 2 );
				if ( empty( $social[1] ) ) {
					$social = '';
				} else {
					$social = str_replace( 'fa-', '', $social[1] );
				}
			}
			if ( 'svg' === $item['social_icon']['library'] ) {
				$social = get_post_meta( $item['social_icon']['value']['id'], '_wp_attachment_image_alt', true );
			}

			$link_key = 'link_' . $index;

			$this->add_render_attribute( $link_key, 'class', [
				'elementor-icon',
				'elementor-social-icon',
				'elementor-social-icon-' . $social . $class_animation,
				'elementor-repeater-item-' . $item['_id'],
			] );

			$this->add_link_attributes( $link_key, $item['borderless_team_member_social_profiles_link'] );
			?>
			<div class="elementor-grid-item">
				<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
					<span class="elementor-screen-only"><?php echo ucwords( $social ); ?></span>
					<?php
					if ( $is_new || $migrated ) {
						Icons_Manager::render_icon( $item['social_icon'] );
					} else { ?>
						<i class="<?php echo esc_attr( $item['social'] ); ?>"></i>
					<?php } ?>
				</a>
			</div>
		<?php } ?>
	</div>
	</div>
	</div>
	<?php

	}
	
	protected function _content_template() {

    }
	
	
}