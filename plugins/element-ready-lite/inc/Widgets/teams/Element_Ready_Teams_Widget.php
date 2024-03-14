<?php

namespace Element_Ready\Widgets\teams;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Element_Ready_Teams_Widget extends Widget_Base
{

	public function get_name()
	{
		return 'Element_Ready_Teams_Widget';
	}

	public function get_title()
	{
		return esc_html__('ER Team', 'element-ready-lite');
	}

	public function get_icon()
	{
		return 'eicon-person';
	}

	public function get_categories()
	{
		return array('element-ready-addons');
	}

	public function get_keywords()
	{
		return ['Team', 'Team Slider', 'Teams', 'Team Member'];
	}

	public function get_script_depends()
	{
		return [
			'owl-carousel',
			'element-ready-core',
		];
	}
	public function get_style_depends()
	{

		wp_register_style('eready-team-box', ELEMENT_READY_ROOT_CSS . 'widgets/team.css');

		return [
			'owl-carousel', 'eready-team-box'
		];
	}

	public static function team_style()
	{
		return apply_filters('element_ready_teams_style_presets', [
			'team__style__1'   => 'Team Style 1',
			'team__style__2'   => 'Team Style 2',
			'team__style__3'   => 'Team Style 3',
			'pro_team__style__4'      => 'Team Style 4 - PRO',
			'pro_team__style__5'      => 'Team Style 5 - PRO',
			'pro_team__style__6'      => 'Team Style 6 - PRO',
			'pro_team__style__7'      => 'Team Style 7 - PRO',
			'pro_team__style__8'      => 'Team Style 8 - PRO',
			'pro_team__style__9'      => 'Team Style 9 - PRO',
			'pro_team__style__10'     => 'Team Style 10 - PRO',
			'pro_team__style__11'     => 'Team Style 11 - PRO',
			'pro_team__custom__style' => 'Team Custom Style - PRO',
		]);
	}

	protected function register_controls()
	{

		/******************************
		 * 	CONTENT SECTION
		 ******************************/
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__('Content', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// Type
		$this->add_control(
			'team_style',
			[
				'label'   => esc_html__('Team Style', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'team_style_1',
				'options' => self::team_style(),
			]
		);

		$repeater = new Repeater();

		// Member Name
		$repeater->add_control(
			'member_thumb',
			[
				'label'   => esc_html__('Member Thumb', 'element-ready-lite'),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'member_thumb_size',
				'default' => 'full',
			]
		);

		// Member Name
		$repeater->add_control(
			'member_name',
			[
				'label'       => esc_html__('Member Name', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Member Name', 'element-ready-lite'),
			]
		);

		// Member Designation
		$repeater->add_control(
			'designation',
			[
				'label'       => esc_html__('Designation', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Designation Or Company', 'element-ready-lite'),
			]
		);

		// Description
		$repeater->add_control(
			'description',
			[
				'label'       => esc_html__('Description', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__('Description.', 'element-ready-lite'),
			]
		);

		// Socials
		$repeater->add_control(
			'add_social',
			[
				'label'        => esc_html__('Add Social ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'element-ready-lite'),
				'label_off'    => esc_html__('No', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		// Facebook
		$repeater->add_control(
			'facebook_url',
			[
				'label'         => esc_html__('Facebook Url', 'element-ready-lite'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__('https://www.facebook.com/Elementsready', 'element-ready-lite'),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition' => [
					'add_social' => 'yes',
				]
			]
		);

		// Twitter
		$repeater->add_control(
			'twitter_url',
			[
				'label'         => esc_html__('Twitter Url', 'element-ready-lite'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__('https://www.facebook.com/Elementsready', 'element-ready-lite'),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition' => [
					'add_social' => 'yes',
				]
			]
		);

		// Google
		$repeater->add_control(
			'google_url',
			[
				'label'         => esc_html__('Google Plus Url', 'element-ready-lite'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__('https://www.facebook.com/Elementsready', 'element-ready-lite'),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition' => [
					'add_social' => 'yes',
				]
			]
		);

		// Youtube
		$repeater->add_control(
			'youtube_url',
			[
				'label'         => esc_html__('Youtube Url', 'element-ready-lite'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__('https://www.youtube.com/channel/UC2p1RnLjtlbRy0YGFzyy4Hw', 'element-ready-lite'),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition' => [
					'add_social' => 'yes',
				]
			]
		);

		// vimeo
		$repeater->add_control(
			'vimeo_url',
			[
				'label'         => esc_html__('Vimeo Url', 'element-ready-lite'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__('https://www.youtube.com/channel/UC2p1RnLjtlbRy0YGFzyy4Hw', 'element-ready-lite'),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition' => [
					'add_social' => 'yes',
				]
			]
		);

		// Instagram
		$repeater->add_control(
			'instagram_url',
			[
				'label'         => esc_html__('Instagram Url', 'element-ready-lite'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__('https://instagram.com', 'element-ready-lite'),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition' => [
					'add_social' => 'yes',
				]
			]
		);

		// linkedin
		$repeater->add_control(
			'linkedin_url',
			[
				'label'         => esc_html__('Linkedin Url', 'element-ready-lite'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__('https://linkedin.com/', 'element-ready-lite'),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition' => [
					'add_social' => 'yes',
				]
			]
		);

		// pinterest
		$repeater->add_control(
			'pinterest_url',
			[
				'label'         => esc_html__('Pinterest Url', 'element-ready-lite'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__('https://pinterest.com/', 'element-ready-lite'),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition' => [
					'add_social' => 'yes',
				]
			]
		);

		// Items
		$this->add_control(
			'team_content',
			[
				'label'   => esc_html__('Members Items', 'element-ready-lite'),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [
					[
						'member_name' => esc_html__('Jhon Wick', 'element-ready-lite'),
						'designation' => esc_html__('Web Developer'),
					],
					[
						'member_name' => esc_html__('Niloy Khan', 'element-ready-lite'),
						'designation' => esc_html__('Web Developer'),
					],
					[
						'member_name' => esc_html__('Abdur Rohman', 'element-ready-lite'),
						'designation' => esc_html__('CEO'),
					],
					[
						'member_name' => esc_html__('Imon Ahmed', 'element-ready-lite'),
						'designation' => esc_html__('Research Specialist'),
					],
				],
				'title_field' => '{{{ member_name }}}',
			]
		);

		$this->add_control(
			'slider_on',
			[
				'label'        => esc_html__('Slider On ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'element-ready-lite'),
				'label_off'    => esc_html__('No', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->end_controls_section();


		/******************************
		 * 	SLIDER OPTIONS SECTION
		 ******************************/
		$this->start_controls_section(
			'options_section',
			[
				'label' => esc_html__('Slider Options', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'slider_on' => 'yes',
				]
			]
		);

		// Item On Large ( 1920px )
		$this->add_control(
			'item_on_large',
			[
				'label'      => esc_html__('Item In large Device', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 3,
				],
			]
		);

		// Item On Medium ( 1200px )
		$this->add_control(
			'item_on_medium',
			[
				'label'      => esc_html__('Item In Medium Device', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 3,
				],
			]
		);

		// Item On Tablet ( 780px )
		$this->add_control(
			'item_on_tablet',
			[
				'label'      => esc_html__('Item In Tablet Device', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 2,
				],
			]
		);

		// Item On Large ( 480px )
		$this->add_control(
			'item_on_mobile',
			[
				'label'      => esc_html__('Item In Mobile Device', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 1,
				],
			]
		);

		// Stage Padding
		$this->add_control(
			'stage_padding',
			[
				'label'      => esc_html__('Stage Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
			]
		);

		// Item Margin
		$this->add_control(
			'item_margin',
			[
				'label'      => esc_html__('Item Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
			]
		);

		// Slide Autoplay
		$this->add_control(
			'autoplay',
			[
				'label'   => esc_html__('Slide Autoplay', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'true',
				'options' => [
					'true'  => esc_html__('Yes', 'element-ready-lite'),
					'false' => esc_html__('No', 'element-ready-lite'),
				],
			]
		);

		// Autoplay Timeout
		$this->add_control(
			'autoplaytimeout',
			[
				'label'      => esc_html__('Autoplay Timeout', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 500,
						'max'  => 10000,
						'step' => 100,
					],
				],
				'default' => [
					'size' => 3000,
				],
			]
		);

		// Slide Speed
		$this->add_control(
			'slide_speed',
			[
				'label'      => esc_html__('Slide Speed', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 500,
						'max'  => 10000,
						'step' => 100,
					],
				],
				'default' => [
					'size' => 1000,
				],
			]
		);

		// Slide Animation
		$this->add_control(
			'slide_animation',
			[
				'label'   => esc_html__('Slide Animation', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes' => esc_html__('Yes', 'element-ready-lite'),
					'no'  => esc_html__('No', 'element-ready-lite'),
				],
			]
		);

		// Slide In Animation
		$this->add_control(
			'slide_animate_in',
			[
				'label'   => esc_html__('Slide Animate In', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fadeIn',
				'options' => [
					'bounce'             => esc_html__('bounce', 'element-ready-lite'),
					'flash'              => esc_html__('flash', 'element-ready-lite'),
					'pulse'              => esc_html__('pulse', 'element-ready-lite'),
					'rubberBand'         => esc_html__('rubberBand', 'element-ready-lite'),
					'shake'              => esc_html__('shake', 'element-ready-lite'),
					'headShake'          => esc_html__('headShake', 'element-ready-lite'),
					'swing'              => esc_html__('swing', 'element-ready-lite'),
					'tada'               => esc_html__('tada', 'element-ready-lite'),
					'wobble'             => esc_html__('wobble', 'element-ready-lite'),
					'jello'              => esc_html__('jello', 'element-ready-lite'),
					'heartBeat'          => esc_html__('heartBeat', 'element-ready-lite'),
					'bounceIn'           => esc_html__('bounceIn', 'element-ready-lite'),
					'bounceInDown'       => esc_html__('bounceInDown', 'element-ready-lite'),
					'bounceInLeft'       => esc_html__('bounceInLeft', 'element-ready-lite'),
					'bounceInRight'      => esc_html__('bounceInRight', 'element-ready-lite'),
					'bounceInUp'         => esc_html__('bounceInUp', 'element-ready-lite'),
					'bounceOut'          => esc_html__('bounceOut', 'element-ready-lite'),
					'bounceOutDown'      => esc_html__('bounceOutDown', 'element-ready-lite'),
					'bounceOutLeft'      => esc_html__('bounceOutLeft', 'element-ready-lite'),
					'bounceOutRight'     => esc_html__('bounceOutRight', 'element-ready-lite'),
					'bounceOutUp'        => esc_html__('bounceOutUp', 'element-ready-lite'),
					'fadeIn'             => esc_html__('fadeIn', 'element-ready-lite'),
					'fadeInDown'         => esc_html__('fadeInDown', 'element-ready-lite'),
					'fadeInDownBig'      => esc_html__('fadeInDownBig', 'element-ready-lite'),
					'fadeInLeft'         => esc_html__('fadeInLeft', 'element-ready-lite'),
					'fadeInLeftBig'      => esc_html__('fadeInLeftBig', 'element-ready-lite'),
					'fadeInRight'        => esc_html__('fadeInRight', 'element-ready-lite'),
					'fadeInRightBig'     => esc_html__('fadeInRightBig', 'element-ready-lite'),
					'fadeInUp'           => esc_html__('fadeInUp', 'element-ready-lite'),
					'fadeInUpBig'        => esc_html__('fadeInUpBig', 'element-ready-lite'),
					'fadeOut'            => esc_html__('fadeOut', 'element-ready-lite'),
					'fadeOutDown'        => esc_html__('fadeOutDown', 'element-ready-lite'),
					'fadeOutDownBig'     => esc_html__('fadeOutDownBig', 'element-ready-lite'),
					'fadeOutLeft'        => esc_html__('fadeOutLeft', 'element-ready-lite'),
					'fadeOutLeftBig'     => esc_html__('fadeOutLeftBig', 'element-ready-lite'),
					'fadeOutRight'       => esc_html__('fadeOutRight', 'element-ready-lite'),
					'fadeOutRightBig'    => esc_html__('fadeOutRightBig', 'element-ready-lite'),
					'fadeOutUp'          => esc_html__('fadeOutUp', 'element-ready-lite'),
					'fadeOutUpBig'       => esc_html__('fadeOutUpBig', 'element-ready-lite'),
					'flip'               => esc_html__('flip', 'element-ready-lite'),
					'flipInX'            => esc_html__('flipInX', 'element-ready-lite'),
					'flipInY'            => esc_html__('flipInY', 'element-ready-lite'),
					'flipOutX'           => esc_html__('flipOutX', 'element-ready-lite'),
					'flipOutY'           => esc_html__('flipOutY', 'element-ready-lite'),
					'lightSpeedIn'       => esc_html__('lightSpeedIn', 'element-ready-lite'),
					'lightSpeedOut'      => esc_html__('lightSpeedOut', 'element-ready-lite'),
					'rotateIn'           => esc_html__('rotateIn', 'element-ready-lite'),
					'rotateInDownLeft'   => esc_html__('rotateInDownLeft', 'element-ready-lite'),
					'rotateInDownRight'  => esc_html__('rotateInDownRight', 'element-ready-lite'),
					'rotateInUpLeft'     => esc_html__('rotateInUpLeft', 'element-ready-lite'),
					'rotateInUpRight'    => esc_html__('rotateInUpRight', 'element-ready-lite'),
					'rotateOut'          => esc_html__('rotateOut', 'element-ready-lite'),
					'rotateOutDownLeft'  => esc_html__('rotateOutDownLeft', 'element-ready-lite'),
					'rotateOutDownRight' => esc_html__('rotateOutDownRight', 'element-ready-lite'),
					'rotateOutUpLeft'    => esc_html__('rotateOutUpLeft', 'element-ready-lite'),
					'rotateOutUpRight'   => esc_html__('rotateOutUpRight', 'element-ready-lite'),
					'hinge'              => esc_html__('hinge', 'element-ready-lite'),
					'jackInTheBox'       => esc_html__('jackInTheBox', 'element-ready-lite'),
					'rollIn'             => esc_html__('rollIn', 'element-ready-lite'),
					'rollOut'            => esc_html__('rollOut', 'element-ready-lite'),
					'zoomIn'             => esc_html__('zoomIn', 'element-ready-lite'),
					'zoomInDown'         => esc_html__('zoomInDown', 'element-ready-lite'),
					'zoomInLeft'         => esc_html__('zoomInLeft', 'element-ready-lite'),
					'zoomInRight'        => esc_html__('zoomInRight', 'element-ready-lite'),
					'zoomInUp'           => esc_html__('zoomInUp', 'element-ready-lite'),
					'zoomOut'            => esc_html__('zoomOut', 'element-ready-lite'),
					'zoomOutDown'        => esc_html__('zoomOutDown', 'element-ready-lite'),
					'zoomOutLeft'        => esc_html__('zoomOutLeft', 'element-ready-lite'),
					'zoomOutRight'       => esc_html__('zoomOutRight', 'element-ready-lite'),
					'zoomOutUp'          => esc_html__('zoomOutUp', 'element-ready-lite'),
					'slideInDown'        => esc_html__('slideInDown', 'element-ready-lite'),
					'slideInLeft'        => esc_html__('slideInLeft', 'element-ready-lite'),
					'slideInRight'       => esc_html__('slideInRight', 'element-ready-lite'),
					'slideInUp'          => esc_html__('slideInUp', 'element-ready-lite'),
					'slideOutDown'       => esc_html__('slideOutDown', 'element-ready-lite'),
					'slideOutLeft'       => esc_html__('slideOutLeft', 'element-ready-lite'),
					'slideOutRight'      => esc_html__('slideOutRight', 'element-ready-lite'),
					'slideOutUp'         => esc_html__('slideOutUp', 'element-ready-lite'),
				],
				'condition' => [
					'slide_animation' => 'yes',
				]
			]
		);

		// Slide Out Animation
		$this->add_control(
			'slide_animate_out',
			[
				'label'   => esc_html__('Slide Animate Out', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fadeOut',
				'options' => [
					'bounce'             => esc_html__('bounce', 'element-ready-lite'),
					'flash'              => esc_html__('flash', 'element-ready-lite'),
					'pulse'              => esc_html__('pulse', 'element-ready-lite'),
					'rubberBand'         => esc_html__('rubberBand', 'element-ready-lite'),
					'shake'              => esc_html__('shake', 'element-ready-lite'),
					'headShake'          => esc_html__('headShake', 'element-ready-lite'),
					'swing'              => esc_html__('swing', 'element-ready-lite'),
					'tada'               => esc_html__('tada', 'element-ready-lite'),
					'wobble'             => esc_html__('wobble', 'element-ready-lite'),
					'jello'              => esc_html__('jello', 'element-ready-lite'),
					'heartBeat'          => esc_html__('heartBeat', 'element-ready-lite'),
					'bounceIn'           => esc_html__('bounceIn', 'element-ready-lite'),
					'bounceInDown'       => esc_html__('bounceInDown', 'element-ready-lite'),
					'bounceInLeft'       => esc_html__('bounceInLeft', 'element-ready-lite'),
					'bounceInRight'      => esc_html__('bounceInRight', 'element-ready-lite'),
					'bounceInUp'         => esc_html__('bounceInUp', 'element-ready-lite'),
					'bounceOut'          => esc_html__('bounceOut', 'element-ready-lite'),
					'bounceOutDown'      => esc_html__('bounceOutDown', 'element-ready-lite'),
					'bounceOutLeft'      => esc_html__('bounceOutLeft', 'element-ready-lite'),
					'bounceOutRight'     => esc_html__('bounceOutRight', 'element-ready-lite'),
					'bounceOutUp'        => esc_html__('bounceOutUp', 'element-ready-lite'),
					'fadeIn'             => esc_html__('fadeIn', 'element-ready-lite'),
					'fadeInDown'         => esc_html__('fadeInDown', 'element-ready-lite'),
					'fadeInDownBig'      => esc_html__('fadeInDownBig', 'element-ready-lite'),
					'fadeInLeft'         => esc_html__('fadeInLeft', 'element-ready-lite'),
					'fadeInLeftBig'      => esc_html__('fadeInLeftBig', 'element-ready-lite'),
					'fadeInRight'        => esc_html__('fadeInRight', 'element-ready-lite'),
					'fadeInRightBig'     => esc_html__('fadeInRightBig', 'element-ready-lite'),
					'fadeInUp'           => esc_html__('fadeInUp', 'element-ready-lite'),
					'fadeInUpBig'        => esc_html__('fadeInUpBig', 'element-ready-lite'),
					'fadeOut'            => esc_html__('fadeOut', 'element-ready-lite'),
					'fadeOutDown'        => esc_html__('fadeOutDown', 'element-ready-lite'),
					'fadeOutDownBig'     => esc_html__('fadeOutDownBig', 'element-ready-lite'),
					'fadeOutLeft'        => esc_html__('fadeOutLeft', 'element-ready-lite'),
					'fadeOutLeftBig'     => esc_html__('fadeOutLeftBig', 'element-ready-lite'),
					'fadeOutRight'       => esc_html__('fadeOutRight', 'element-ready-lite'),
					'fadeOutRightBig'    => esc_html__('fadeOutRightBig', 'element-ready-lite'),
					'fadeOutUp'          => esc_html__('fadeOutUp', 'element-ready-lite'),
					'fadeOutUpBig'       => esc_html__('fadeOutUpBig', 'element-ready-lite'),
					'flip'               => esc_html__('flip', 'element-ready-lite'),
					'flipInX'            => esc_html__('flipInX', 'element-ready-lite'),
					'flipInY'            => esc_html__('flipInY', 'element-ready-lite'),
					'flipOutX'           => esc_html__('flipOutX', 'element-ready-lite'),
					'flipOutY'           => esc_html__('flipOutY', 'element-ready-lite'),
					'lightSpeedIn'       => esc_html__('lightSpeedIn', 'element-ready-lite'),
					'lightSpeedOut'      => esc_html__('lightSpeedOut', 'element-ready-lite'),
					'rotateIn'           => esc_html__('rotateIn', 'element-ready-lite'),
					'rotateInDownLeft'   => esc_html__('rotateInDownLeft', 'element-ready-lite'),
					'rotateInDownRight'  => esc_html__('rotateInDownRight', 'element-ready-lite'),
					'rotateInUpLeft'     => esc_html__('rotateInUpLeft', 'element-ready-lite'),
					'rotateInUpRight'    => esc_html__('rotateInUpRight', 'element-ready-lite'),
					'rotateOut'          => esc_html__('rotateOut', 'element-ready-lite'),
					'rotateOutDownLeft'  => esc_html__('rotateOutDownLeft', 'element-ready-lite'),
					'rotateOutDownRight' => esc_html__('rotateOutDownRight', 'element-ready-lite'),
					'rotateOutUpLeft'    => esc_html__('rotateOutUpLeft', 'element-ready-lite'),
					'rotateOutUpRight'   => esc_html__('rotateOutUpRight', 'element-ready-lite'),
					'hinge'              => esc_html__('hinge', 'element-ready-lite'),
					'jackInTheBox'       => esc_html__('jackInTheBox', 'element-ready-lite'),
					'rollIn'             => esc_html__('rollIn', 'element-ready-lite'),
					'rollOut'            => esc_html__('rollOut', 'element-ready-lite'),
					'zoomIn'             => esc_html__('zoomIn', 'element-ready-lite'),
					'zoomInDown'         => esc_html__('zoomInDown', 'element-ready-lite'),
					'zoomInLeft'         => esc_html__('zoomInLeft', 'element-ready-lite'),
					'zoomInRight'        => esc_html__('zoomInRight', 'element-ready-lite'),
					'zoomInUp'           => esc_html__('zoomInUp', 'element-ready-lite'),
					'zoomOut'            => esc_html__('zoomOut', 'element-ready-lite'),
					'zoomOutDown'        => esc_html__('zoomOutDown', 'element-ready-lite'),
					'zoomOutLeft'        => esc_html__('zoomOutLeft', 'element-ready-lite'),
					'zoomOutRight'       => esc_html__('zoomOutRight', 'element-ready-lite'),
					'zoomOutUp'          => esc_html__('zoomOutUp', 'element-ready-lite'),
					'slideInDown'        => esc_html__('slideInDown', 'element-ready-lite'),
					'slideInLeft'        => esc_html__('slideInLeft', 'element-ready-lite'),
					'slideInRight'       => esc_html__('slideInRight', 'element-ready-lite'),
					'slideInUp'          => esc_html__('slideInUp', 'element-ready-lite'),
					'slideOutDown'       => esc_html__('slideOutDown', 'element-ready-lite'),
					'slideOutLeft'       => esc_html__('slideOutLeft', 'element-ready-lite'),
					'slideOutRight'      => esc_html__('slideOutRight', 'element-ready-lite'),
					'slideOutUp'         => esc_html__('slideOutUp', 'element-ready-lite'),
				],
				'condition' => [
					'slide_animation' => 'yes',
				]
			]
		);

		// Slide Navigation
		$this->add_control(
			'nav',
			[
				'label'   => esc_html__('Show Navigation', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'false',
				'options' => [
					'true'  => esc_html__('Yes', 'element-ready-lite'),
					'false' => esc_html__('No', 'element-ready-lite'),
				],
			]
		);

		// Navigation Position
		$this->add_control(
			'nav_position',
			[
				'label'   => esc_html__('Navigation Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'outside_vertical_center_nav',
				'options' => [
					'inside_vertical_center_nav'  => esc_html__('Inside Vertical Center', 'element-ready-lite'),
					'outside_vertical_center_nav' => esc_html__('Outside Vertical Center', 'element-ready-lite'),
					'inside_center_nav'           => esc_html__('Inside Center', 'element-ready-lite'),
					'top_left_nav'                => esc_html__('Top Left', 'element-ready-lite'),
					'top_center_nav'              => esc_html__('Top Center', 'element-ready-lite'),
					'top_right_nav'               => esc_html__('Top Right', 'element-ready-lite'),
					'bottom_left_nav'             => esc_html__('Bottom Left', 'element-ready-lite'),
					'bottom_center_nav'           => esc_html__('Bottom Center', 'element-ready-lite'),
					'bottom_right_nav'            => esc_html__('Bottom Right', 'element-ready-lite'),
				],
				'condition' => [
					'nav' => 'true',
				],
			]
		);

		// Slide Next Icon
		$this->add_control(
			'next_icon',
			[
				'label'       => esc_html__('Nav Next Icon', 'element-ready-lite'),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => 'fa fa-angle-right',
				'condition'   => [
					'nav' => 'true',
				],
			]
		);

		// Slide Prev Icon
		$this->add_control(
			'prev_icon',
			[
				'label'       => esc_html__('Nav Prev Icon', 'element-ready-lite'),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => 'fa fa-angle-left',
				'condition'   => [
					'nav' => 'true',
				],
			]
		);

		// Slide Dots
		$this->add_control(
			'dots',
			[
				'label'   => esc_html__('Slide Dots', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'false',
				'options' => [
					'true'  => esc_html__('Yes', 'element-ready-lite'),
					'false' => esc_html__('No', 'element-ready-lite'),
				],
			]
		);

		// Slide Loop
		$this->add_control(
			'loop',
			[
				'label'   => esc_html__('Slide Loop', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'true',
				'options' => [
					'true'  => esc_html__('Yes', 'element-ready-lite'),
					'false' => esc_html__('No', 'element-ready-lite'),
				],
			]
		);

		// Slide Loop
		$this->add_control(
			'hover_pause',
			[
				'label'   => esc_html__('Pause On Hover', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'true',
				'options' => [
					'true'  => esc_html__('Yes', 'element-ready-lite'),
					'false' => esc_html__('No', 'element-ready-lite'),
				],
			]
		);

		// Slide Center
		$this->add_control(
			'center',
			[
				'label'   => esc_html__('Slide Center Mode', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'false',
				'options' => [
					'true'  => esc_html__('Yes', 'element-ready-lite'),
					'false' => esc_html__('No', 'element-ready-lite'),
				],
			]
		);

		// Slide Center
		$this->add_control(
			'rtl',
			[
				'label'   => esc_html__('Direction RTL', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'false',
				'options' => [
					'true'  => esc_html__('Yes', 'element-ready-lite'),
					'false' => esc_html__('No', 'element-ready-lite'),
				],
			]
		);

		$this->end_controls_section();

		/*----------------------------
			SLIDER NAV WARP
		-----------------------------*/
		$this->start_controls_section(
			'slider_control_warp_style_section',
			[
				'label' => esc_html__('Slider Nav Warp Style', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'slider_nav_warp_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'slider_nav_warp_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
			]
		);

		// Border Radius
		$this->add_control(
			'slider_nav_warp_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'slider_nav_warp_shadow',
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
			]
		);

		// Display;
		$this->add_responsive_control(
			'slider_nav_warp_display',
			[
				'label'   => esc_html__('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'      => esc_html__('Initial', 'element-ready-lite'),
					'block'        => esc_html__('Block', 'element-ready-lite'),
					'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
					'flex'         => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'none'         => esc_html__('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'display: {{VALUE}};',
				],
			]
		);

		// Before Postion
		$this->add_responsive_control(
			'slider_nav_warp_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',

				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'slider_nav_warp_position_from_left',
			[
				'label'      => esc_html__('From Left', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_warp_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'slider_nav_warp_position_from_right',
			[
				'label'      => esc_html__('From Right', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_warp_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'slider_nav_warp_position_from_top',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_warp_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Bottom
		$this->add_responsive_control(
			'slider_nav_warp_position_from_bottom',
			[
				'label'      => esc_html__('From Bottom', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_warp_position' => ['absolute', 'relative']
				],
			]
		);

		// Align
		$this->add_responsive_control(
			'slider_nav_warp_align',
			[
				'label'   => esc_html__('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'element-ready-lite'),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'element-ready-lite'),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'element-ready-lite'),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__('Justify', 'element-ready-lite'),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'text-align: {{VALUE}};',
				],
				'default' => '',
			]
		);

		// Width
		$this->add_responsive_control(
			'slider_nav_warp_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height
		$this->add_responsive_control(
			'slider_nav_warp_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Opacity
		$this->add_control(
			'slider_nav_warp_opacity',
			[
				'label' => esc_html__('Opacity', 'element-ready-lite'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'opacity: {{SIZE}};',
				],
			]
		);

		// Z-Index
		$this->add_control(
			'slider_nav_warp_zindex',
			[
				'label'     => esc_html__('Z-Index', 'element-ready-lite'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => -99,
				'max'       => 99,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'z-index: {{SIZE}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'slider_nav_warp_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'slider_nav_warp_padding',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			SLIDER NAV WARP END
		-----------------------------*/

		/*----------------------------
			CONTROL BUTTON STYLE
		-----------------------------*/
		$this->start_controls_section(
			'slider_control_style_section',
			[
				'label' => esc_html__('Slider Nav Button Style', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// Typgraphy
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'slide_button_typography',
				'selector'  => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
			]
		);

		// Hr
		$this->add_control(
			'slide_button_hr1',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->start_controls_tabs('slide_button_tab_style');
		$this->start_controls_tab(
			'slide_button_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);

		// Color
		$this->add_control(
			'slide_button_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'color: {{VALUE}};',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'slide_button_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
			]
		);

		// Hr
		$this->add_control(
			'slide_button_hr2',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'slide_button_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
			]
		);

		// Radius
		$this->add_control(
			'slide_button_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'slide_button_shadow',
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
			]
		);

		// Hr
		$this->add_control(
			'slide_button_hr3',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'slide_button_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);

		// Hover Color
		$this->add_control(
			'hover_slide_button_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div:hover' => 'color: {{VALUE}};',
				],
			]
		);

		// Hover Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_slide_button_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div:hover',
			]
		);

		// Hr
		$this->add_control(
			'slide_button_hr4',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Hover Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_slide_button_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div:hover',
			]
		);

		// Hover Radius
		$this->add_control(
			'hover_slide_button_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Hover Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_slide_button_shadow',
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div:hover',
			]
		);

		// Hover Animation
		/*$this->add_control(
			'slide_button_hover_animation',
			[
				'label'    => esc_html__( 'Hover Animation', 'element-ready-lite' ),
				'type'     => Controls_Manager::HOVER_ANIMATION,
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div:hover',
			]
		);*/

		$this->add_control(
			'slide_button_hr9',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Width
		$this->add_control(
			'slide_button_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height
		$this->add_control(
			'slide_button_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Hr
		$this->add_control(
			'slide_button_hr5',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Display;
		$this->add_responsive_control(
			'slide_button_display',
			[
				'label'   => esc_html__('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'      => esc_html__('Initial', 'element-ready-lite'),
					'block'        => esc_html__('Block', 'element-ready-lite'),
					'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
					'flex'         => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'none'         => esc_html__('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'display: {{VALUE}};',
				],
			]
		);

		// Alignment
		$this->add_control(
			'slide_button_align',
			[
				'label'   => esc_html__('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'element-ready-lite'),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'element-ready-lite'),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'element-ready-lite'),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__('Justify', 'element-ready-lite'),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'text-align: {{VALUE}};',
				],
				'default' => '',
			]
		);

		// Hr
		$this->add_control(
			'slide_button_hr6',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);


		// Postion
		$this->add_responsive_control(
			'slide_button_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'position: {{VALUE}};',
				],
			]
		);

		/*$this->start_controls_tabs( 'slide_button_item_tab_style', [
		    'condition' => [
		        'slide_button_position' => ['absolute','relative']
		    ],
		] );*/
		$this->start_controls_tabs('slide_button_item_tab_style');
		$this->start_controls_tab(
			'slide_button_left_nav_tab',
			[
				'label' => esc_html__('Left Button', 'element-ready-lite'),
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'slide_button_position_from_left',
			[
				'label'      => esc_html__('From Left', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slide_button_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion Bottom Top
		$this->add_responsive_control(
			'slide_button_position_from_bottom',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-prev' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slide_button_position' => ['absolute', 'relative']
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'slide_button_left_margin',
			[
				'label'      => esc_html__('Margin Left Button', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-prev' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'slide_button_right_nav_tab',
			[
				'label' => esc_html__('Right Button', 'element-ready-lite'),
			]
		);


		// Postion From Right
		$this->add_responsive_control(
			'slide_button_position_from_right',
			[
				'label'      => esc_html__('From Right', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-next' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slide_button_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'slide_button_position_from_top',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-next' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slide_button_position' => ['absolute', 'relative']
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'slide_button_right_margin',
			[
				'label'      => esc_html__('Margin Right Button', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Hr
		$this->add_control(
			'slide_button_hr7',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Transition
		$this->add_control(
			'slide_button_transition',
			[
				'label'      => esc_html__('Transition', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0.1,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0.3,
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'transition: {{SIZE}}s;',
				],
			]
		);


		// Hr
		$this->add_control(
			'slide_button_hr8',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Padding
		$this->add_responsive_control(
			'slide_button_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			CONTROL BUTTON STYLE END
		-----------------------------*/

		/*----------------------------
			DOTS BUTTON STYLE
		-----------------------------*/
		$this->start_controls_section(
			'slide_dots_button_style_section',
			[
				'label' => esc_html__('Slide Dots Style', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);


		$this->start_controls_tabs('button_tab_style');
		$this->start_controls_tab(
			'slide_dots_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);

		// Button Width
		$this->add_control(
			'slide_dots_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-dots > div' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Button Height
		$this->add_control(
			'slide_dots_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-dots > div' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Button Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'slide_dots_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div',
			]
		);

		// Button Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'slide_dots_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div',
			]
		);

		// Button Radius
		$this->add_control(
			'slide_dots_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-dots > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Button Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'slide_dots_shadow',
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'slide_dots_hover_tab',
			[
				'label' => esc_html__('Hover & Active', 'element-ready-lite'),
			]
		);
		// Button Width
		$this->add_responsive_control(
			'hover_slide_dots_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Button Height
		$this->add_responsive_control(
			'hover_slide_dots_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Button Hover BG
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_slide_dots_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active',
			]
		);

		// Button Radius
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_slide_dots_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active',
			]
		);

		// Button Hover Radius
		$this->add_control(
			'hover_slide_dots_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Button Hover Box Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_slide_dots_shadow',
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active',
			]
		);


		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Button Hr
		$this->add_control(
			'slide_dots_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Alignment
		$this->add_responsive_control(
			'slide_dots_align',
			[
				'label'   => esc_html__('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'element-ready-lite'),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'element-ready-lite'),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'element-ready-lite'),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__('Justify', 'element-ready-lite'),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-dots' => 'text-align: {{VALUE}};',
				],
				'default' => '',
			]
		);

		// Transition
		$this->add_control(
			'slide_dots_transition',
			[
				'label'      => esc_html__('Transition', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0.1,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0.3,
				],
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-dots > div' => 'transition: {{SIZE}}s;',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'slide_dots_margin',
			[
				'label'      => esc_html__('Dot Item Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-dots > div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'slide_dots_warp_margin',
			[
				'label'      => esc_html__('Dot Warp Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .sldier-content-area .owl-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			DOTS BUTTON STYLE END
		-----------------------------*/

		/*********************************
		 * 		STYLE SECTION
		 *********************************/

		/*----------------------------
			THUMB STYLE
		-----------------------------*/
		$this->start_controls_section(
			'thumb_style_section',
			[
				'label' => esc_html__('Author Thumb', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'thumb_style_tab'
		);

		$this->start_controls_tab(
			'thum_image_warp_tab',
			[
				'label' => esc_html__('Tumb Warp', 'element-ready-lite'),
			]
		);

		// Width
		$this->add_responsive_control(
			'thumb_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height
		$this->add_responsive_control(
			'thumb_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'thumb_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .member__thumb',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'thumb_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .member__thumb',
			]
		);

		// Radius
		$this->add_control(
			'thumb_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'thumb_shadow',
				'selector' => '{{WRAPPER}} .member__thumb',
			]
		);
		// hover box Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'label'      => esc_html__('Hover Box Shadow', 'element-ready-lite'),
				'name'     => 'thumb_image_shadow_hover',
				'selector' => '{{WRAPPER}} .single__team:hover .member__thumb',
			]
		);
		// Display;
		$this->add_responsive_control(
			'thumb_display',
			[
				'label'   => esc_html__('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'      => esc_html__('Initial', 'element-ready-lite'),
					'block'        => esc_html__('Block', 'element-ready-lite'),
					'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
					'flex'         => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'none'         => esc_html__('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__thumb' => 'display: {{VALUE}};',
				],
			]
		);

		// Postion
		$this->add_responsive_control(
			'thumb_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',

				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__thumb' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'thumb_position_from_left',
			[
				'label'      => esc_html__('From Left', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__thumb' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'thumb_position_from_right',
			[
				'label'      => esc_html__('From Right', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__thumb' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'thumb_position_from_top',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__thumb' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Bottom
		$this->add_responsive_control(
			'thumb_position_from_bottom',
			[
				'label'      => esc_html__('From Bottom', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__thumb' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_position' => ['absolute', 'relative']
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'thumb_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__thumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'thumb_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'thumb_image_tab',
			[
				'label' => esc_html__('Thumb Image', 'element-ready-lite'),
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'thumb_image_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .member__thumb img',
			]
		);

		// Radius
		$this->add_control(
			'thumb_image_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'thumb_image_shadow',
				'selector' => '{{WRAPPER}} .member__thumb img',
			]
		);



		// Width
		$this->add_responsive_control(
			'thumb_image_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__thumb img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height
		$this->add_responsive_control(
			'thumb_image_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__thumb img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		/*----------------------------
			THUMB STYLE END
		-----------------------------*/

		/*----------------------------
			CONTENT WRAPER STYLE
		-----------------------------*/
		$this->start_controls_section(
			'content_warp_style_section',
			[
				'label' => esc_html__('Content Warp', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('content_wraper_style_tabs');

		$this->start_controls_tab(
			'content_wraper_style_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);

		// Width
		$this->add_responsive_control(
			'content_warp_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__content__wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height
		$this->add_responsive_control(
			'content_warp_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__content__wrap' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'content_warp_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .member__content__wrap',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'content_warp_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .member__content__wrap',
			]
		);

		// Radius
		$this->add_control(
			'content_warp_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__content__wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'content_warp_shadow',
				'selector' => '{{WRAPPER}} .member__content__wrap',
			]
		);


		$this->end_controls_tab();

		// Content wraper normal style tab End

		// Content wraper hover style tab start

		$this->start_controls_tab(
			'content_wraper_style_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);
		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'content_warp_hover_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__team:hover .member__content__wrap',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'content_warp_hover_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}}:hover .member__content__wrap',
			]
		);

		// Radius
		$this->add_control(
			'content_warp_hover_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}}:hover .member__content__wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'content_warp_hover_shadow',
				'selector' => '{{WRAPPER}}:hover .member__content__wrap',
			]
		);

		$this->end_controls_tab();

		// Content wraper hover style tab End

		$this->end_controls_tabs();

		// Content wraper style tabs End

		// Display;
		$this->add_responsive_control(
			'content_warp_display',
			[
				'label'   => esc_html__('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'      => esc_html__('Initial', 'element-ready-lite'),
					'block'        => esc_html__('Block', 'element-ready-lite'),
					'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
					'flex'         => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'none'         => esc_html__('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__content__wrap' => 'display: {{VALUE}};',
				],
			]
		);


		// Postion
		$this->add_responsive_control(
			'content_warp_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',

				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__content__wrap' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'content_warp_position_from_left',
			[
				'label'      => esc_html__('From Left', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__content__wrap' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_warp_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'content_warp_position_from_right',
			[
				'label'      => esc_html__('From Right', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__content__wrap' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_warp_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'content_warp_position_from_top',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__content__wrap' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_warp_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Bottom
		$this->add_responsive_control(
			'content_warp_position_from_bottom',
			[
				'label'      => esc_html__('From Bottom', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__content__wrap' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_warp_position' => ['absolute', 'relative']
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_warp_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__content__wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'content_warp_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__content__wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/*----------------------------
			NAME STYLE
		-----------------------------*/
		$this->start_controls_section(
			'name_style_section',
			[
				'label' => esc_html__('Name', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .member__name',
			]
		);

		// Color
		$this->add_control(
			'name_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member__name' => 'color: {{VALUE}}',
				],
			]
		);

		// Box Hover Color
		$this->add_control(
			'box_hover_name_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__team:hover .member__name' => 'color: {{VALUE}}',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'name_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			NAME STYLE END
		-----------------------------*/

		/*----------------------------
			DESIGNATION STYLE
		-----------------------------*/
		$this->start_controls_section(
			'designation_style_section',
			[
				'label' => esc_html__('Designation Or Company', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'designation_typography',
				'selector' => '{{WRAPPER}} .member__designation',
			]
		);

		// Color
		$this->add_control(
			'designation_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member__designation' => 'color: {{VALUE}}',
				],
			]
		);

		// Box Hover Color
		$this->add_control(
			'box_hover_designation_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__team:hover .member__designation' => 'color: {{VALUE}}',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'designation_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			DESIGNATION STYLE END
		-----------------------------*/

		/*----------------------------
			DESCRIPTION STYLE
		-----------------------------*/
		$this->start_controls_section(
			'description_style_section',
			[
				'label' => esc_html__('Description', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Subtitle Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .member__description',
			]
		);

		// Subtitle Color
		$this->add_control(
			'description_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member__description' => 'color: {{VALUE}}',
				],
			]
		);

		// Box Hover Subtitle Color
		$this->add_control(
			'box_hover_description_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__team:hover .member__description' => 'color: {{VALUE}}',
				],
			]
		);

		// Subtitle Margin
		$this->add_responsive_control(
			'description_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			DESCRIPTION STYLE END
		-----------------------------*/

		/*----------------------------
			SOCIAL WRAPPER STYLE
		-----------------------------*/
		$this->start_controls_section(
			'social_wrap_style_section',
			[
				'label' => esc_html__('Social Warp', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Width
		$this->add_responsive_control(
			'social_wrap_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height
		$this->add_responsive_control(
			'social_wrap_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'social_wrap_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .member__socials',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'social_wrap_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .member__socials',
			]
		);

		// Radius
		$this->add_control(
			'social_wrap_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__socials' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'social_wrap_shadow',
				'selector' => '{{WRAPPER}} .member__socials',
			]
		);

		// Display;
		$this->add_responsive_control(
			'social_wrap_display',
			[
				'label'   => esc_html__('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'      => esc_html__('Initial', 'element-ready-lite'),
					'block'        => esc_html__('Block', 'element-ready-lite'),
					'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
					'flex'         => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'none'         => esc_html__('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_wrap_item_content_direction',
			[
				'label'   => esc_html__('Content Direction', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [

					'column' => esc_html__('Column', 'element-ready-lite'),
					'row' => esc_html__('Row', 'element-ready-lite'),
					'column-reverse'   => esc_html__('Column Reverse', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
					'social_wrap_display' => ['inline-flex', 'flex']
				],
			]
		);
		$this->add_responsive_control(
			'social_wrap_item_horizontal_align',
			[
				'label'   => esc_html__('Hotizontal Align', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [

					'flex-start'    => esc_html__('Left', 'element-ready-lite'),
					'flex-end'      => esc_html__('Right', 'element-ready-lite'),
					'center'        => esc_html__('Center', 'element-ready-lite'),
					'space-around'  => esc_html__('Space Around', 'element-ready-lite'),
					'space-between' => esc_html__('Space Between', 'element-ready-lite'),
					'space-evenly'  => esc_html__('Space Evenly', 'element-ready-lite'),
					''              => esc_html__('inherit', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'social_wrap_display' => ['inline-flex', 'flex']
				],
			]
		);

		$this->add_responsive_control(
			'social_wrap_item_vertical_alignt_direction',
			[
				'label'   => esc_html__('Verical Align', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [

					'flex-start' => esc_html__('Left', 'element-ready-lite'),
					'flex-end'   => esc_html__('Right', 'element-ready-lite'),
					'center'     => esc_html__('Center', 'element-ready-lite'),
					'baseline'   => esc_html__('Baseline', 'element-ready-lite'),
					''           => esc_html__('inherit', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'social_wrap_display' => ['inline-flex', 'flex']
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'social_wrap_flex_gap',
			[
				'label'      => esc_html__('Gap', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],

				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'social_wrap_display' => ['inline-flex', 'flex']
				],
			]
		);

		// Postion
		$this->add_responsive_control(
			'social_wrap_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',

				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'position: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_wrap_position_left_auto',
			[
				'label'   => esc_html__('Left Auto?', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__('No', 'element-ready-lite'),
					'auto' => esc_html__('Yes', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'left: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'social_wrap_position_from_left',
			[
				'label'      => esc_html__('From Left', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -500,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'social_wrap_position'           => ['absolute', 'relative'],
					'social_wrap_position_left_auto!' => ['auto']
				],
			]
		);

		$this->add_responsive_control(
			'social_wrap_position_right_auto',
			[
				'label'   => esc_html__('Right Auto?', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__('No', 'element-ready-lite'),
					'auto' => esc_html__('Yes', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'right: {{VALUE}};',
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'social_wrap_position_from_right',
			[
				'label'      => esc_html__('From Right', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -500,
						'max'  => 1400,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'social_wrap_position' => ['absolute', 'relative'],
					'social_wrap_position_right_auto!' => ['auto']
				],
			]
		);

		$this->add_responsive_control(
			'social_wrap_position_top_auto',
			[
				'label'   => esc_html__('Top Auto?', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__('No', 'element-ready-lite'),
					'auto' => esc_html__('Yes', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'top: {{VALUE}};',
				],
			]
		);


		// Postion From Top
		$this->add_responsive_control(
			'social_wrap_position_from_top',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -500,
						'max'  => 1200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'social_wrap_position' => ['absolute', 'relative'],
					'social_wrap_position_top_auto!' => ['auto']
				],
			]
		);

		$this->add_responsive_control(
			'social_wrap_position_bottomp_auto',
			[
				'label'   => esc_html__('Bottom Auto?', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__('No', 'element-ready-lite'),
					'auto' => esc_html__('Yes', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'bottom: {{VALUE}};',
				],
			]
		);
		// Postion From Bottom
		$this->add_responsive_control(
			'social_wrap_position_from_bottom',
			[
				'label'      => esc_html__('From Bottom', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -500,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'social_wrap_position' => ['absolute', 'relative'],
					'social_wrap_position_bottomp_auto!' => ['auto']
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'social_wrap_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__socials' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'social_wrap_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__socials' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			SOCIAL WRAPPER STYLE
		-----------------------------*/

		/*----------------------------
			SOCIAL ICON STYLE
		-----------------------------*/
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => esc_html__('Social Icons', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Typgraphy
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'icon_typography',
				'selector'  => '{{WRAPPER}} .member__socials a',
			]
		);

		// Hr
		$this->add_control(
			'icon_hr1',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);


		$this->start_controls_tabs('icon_tab_style');
		$this->start_controls_tab(
			'icon_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);

		// Color
		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .member__socials a' => 'color: {{VALUE}};',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .member__socials a',
			]
		);

		// Hr
		$this->add_control(
			'icon_hr2',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'icon_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .member__socials a',
			]
		);

		// Border Radius
		$this->add_control(
			'icon_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__socials a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_shadow',
				'selector' => '{{WRAPPER}} .member__socials a',
			]
		);

		// Hr
		$this->add_control(
			'icon_hr3',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);

		// Hover Color
		$this->add_control(
			'hover_icon_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member__socials a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		// Hover Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_icon_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .member__socials a:hover',
			]
		);

		// Hr
		$this->add_control(
			'icon_hr4',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Hover Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_icon_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .member__socials a:hover',
			]
		);

		// Hover Radius
		$this->add_control(
			'hover_icon_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__socials a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Hover Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_icon_shadow',
				'selector' => '{{WRAPPER}} .member__socials a:hover',
			]
		);

		// Hover Animation
		$this->add_control(
			'icon_hover_animation',
			[
				'label'    => esc_html__('Hover Animation', 'element-ready-lite'),
				'type'     => Controls_Manager::HOVER_ANIMATION,
				'selector' => '{{WRAPPER}} .member__socials a:hover',
			]
		);

		$this->add_control(
			'icon_hr9',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Width
		$this->add_control(
			'icon_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials a' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height
		$this->add_control(
			'icon_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials a' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Hr
		$this->add_control(
			'icon_hr5',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Display;
		$this->add_responsive_control(
			'icon_display',
			[
				'label'   => esc_html__('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'      => esc_html__('Initial', 'element-ready-lite'),
					'block'        => esc_html__('Block', 'element-ready-lite'),
					'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
					'flex'         => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'none'         => esc_html__('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials a' => 'display: {{VALUE}};',
				],
			]
		);

		// Alignment
		$this->add_control(
			'icon_align',
			[
				'label'   => esc_html__('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'element-ready-lite'),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'element-ready-lite'),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'element-ready-lite'),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__('Justify', 'element-ready-lite'),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials a' => 'text-align: {{VALUE}};',
				],
				'default' => '',
			]
		);

		// Hr
		$this->add_control(
			'icon_hr6',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Postion
		$this->add_responsive_control(
			'icon_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials a' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'icon_position_from_left',
			[
				'label'      => esc_html__('From Left', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials a' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'icon_position_from_right',
			[
				'label'      => esc_html__('From Right', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials a' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'icon_position_from_top',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials a' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Bottom
		$this->add_responsive_control(
			'icon_position_from_bottom',
			[
				'label'      => esc_html__('From Bottom', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials a' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => ['absolute', 'relative']
				],
			]
		);

		// Transition
		$this->add_control(
			'icon_transition',
			[
				'label'      => esc_html__('Transition', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0.1,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0.3,
				],
				'selectors' => [
					'{{WRAPPER}} .member__socials a' => 'transition: {{SIZE}}s;',
				],
			]
		);

		// Hr
		$this->add_control(
			'icon_hr7',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'icon_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__socials a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Hr
		$this->add_control(
			'icon_hr8',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Padding
		$this->add_responsive_control(
			'icon_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .member__socials a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			SOCIAL ICON STYLE END
		-----------------------------*/

		/*----------------------------
			BOX STYLE
		-----------------------------*/
		$this->start_controls_section(
			'box_style_section',
			[
				'label' => esc_html__('Box', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Box Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .single__team',
			]
		);

		$this->start_controls_tabs('box_style_tabs');
		$this->start_controls_tab(
			'box_style_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);

		// Box Default Color
		$this->add_control(
			'box_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__team' => 'color: {{VALUE}}',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'box_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__team',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'box_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .single__team',
			]
		);

		// Border Radius
		$this->add_control(
			'box_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__team' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_box_shadow',
				'selector' => '{{WRAPPER}} .single__team',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'box_style_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);

		// Box Hover Color
		$this->add_control(
			'hover_box_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__team:hover' => 'color: {{VALUE}}',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_box_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__team:hover',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_box_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .single__team:hover',
			]
		);

		// Border Radius
		$this->add_control(
			'hover_box_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__team:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_box_box_shadow',
				'selector' => '{{WRAPPER}} .single__team:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		// BOX DISPLAY CONTROL 
		$this->add_responsive_control(
			'team_box_wrapper_display',
			[
				'label'   => esc_html__('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					''             => esc_html__('Default', 'element-ready-lite'),
					'initial'      => esc_html__('Initial', 'element-ready-lite'),
					'block'        => esc_html__('Block', 'element-ready-lite'),
					'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
					'flex'         => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'grid'         => esc_html__('Grid', 'element-ready-lite'),
					'inline-grid'  => esc_html__('Inline-Grid', 'element-ready-lite'),
					'none'         => esc_html__('none', 'element-ready-lite'),
				],

				'selectors' => [
					'{{WRAPPER}} .element-ready-team-carousel.team-normal-grid' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'team_box_wrapper_display_grid_column',
			[
				'label'      => esc_html__('Column', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'condition' => [
					'team_box_wrapper_display' => ['grid', 'inline-grid']
				],
				'selectors' => [
					'{{WRAPPER}} .element-ready-team-carousel.team-normal-grid' => 'grid-template-columns: repeat({{SIZE}}, 1fr);',
				],
			]
		);
		$this->add_responsive_control(
			'team_box_wrapper_display_grid_gap',
			[
				'label'      => esc_html__('Gap', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'condition' => [
					'team_box_wrapper_display' => ['flex', 'inline-flex', 'grid', 'inline-grid']
				],
				'selectors' => [
					'{{WRAPPER}} .element-ready-team-carousel.team-normal-grid' => 'gap: {{SIZE}}{{UNIT}}',
				],
			]
		);
		// BOX DISPLAY DIRECTION 

		$this->add_responsive_control(
			'team_box_wrapper_display_direction',
			[
				'label'   => esc_html__('Flex-Direction', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'condition' => [
					'team_box_wrapper_display' => ['flex', 'inline-flex']
				],
				'default' => '',
				'options' => [
					''               => esc_html__('Default', 'element-ready-lite'),
					'column'         => esc_html__('Column', 'element-ready-lite'),
					'row'            => esc_html__('Row', 'element-ready-lite'),
					'column-reverse' => esc_html__('Column Reverse', 'element-ready-lite'),
					'row-reverse'    => esc_html__('Row Reverse', 'element-ready-lite'),
					'none'           => esc_html__('None', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .element-ready-team-carousel.team-normal-grid' => 'flex-direction: {{VALUE}};',
				],
			]
		);
		// BOX DISPLAY FLEX WRAPPER

		$this->add_responsive_control(
			'team_box_wrapper_display_direction_wrapper',
			[
				'label'   => esc_html__('Wrapper', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''               => esc_html__('Default', 'element-ready-lite'),
					'wrap'         => esc_html__('Wrap', 'element-ready-lite'),
					'wrap-reverse' => esc_html__('Wrap Reverse', 'element-ready-lite'),
					'nowrap'       => esc_html__('No Wrap', 'element-ready-lite'),
					'unset'        => esc_html__('Unset', 'element-ready-lite'),
					'normal'       => esc_html__('None', 'element-ready-lite'),
					'inherit'      => esc_html__('inherit', 'element-ready-lite'),
				],
				'condition' => [
					'team_box_wrapper_display' => ['flex', 'inline-flex']
				],
				'selectors' => [
					'{{WRAPPER}} .element-ready-team-carousel.team-normal-grid' => 'flex-wrap: {{VALUE}};',
				],
			]
		);

		// Box Align
		$this->add_responsive_control(
			'box_align',
			[
				'label'   => esc_html__('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'element-ready-lite'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'element-ready-lite'),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'element-ready-lite'),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__('Justify', 'element-ready-lite'),
						'icon'  => ' eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .single__team' => 'text-align: {{VALUE}};',
				],
				'default' => '',
			]
		);

		// Box Transition
		$this->add_control(
			'box_transition',
			[
				'label'      => esc_html__('Transition', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0.1,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team' => 'transition: {{SIZE}}s;',
				],
			]
		);

		// Postion
		$this->add_responsive_control(
			'box_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .single__team' => 'position: {{VALUE}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'box_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__team' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'box_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__team' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			BOX STYLE END
		-----------------------------*/

		/*----------------------------
			BOX BEFORE / AFTER
		-----------------------------*/
		$this->start_controls_section(
			'box_before_after_style_section',
			[
				'label' => esc_html__('Before / After', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('box_before_after_tab_style');
		$this->start_controls_tab(
			'box_before_tab',
			[
				'label' => esc_html__('BEFORE', 'element-ready-lite'),
			]
		);

		// Before Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'box_before_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__team:before,{{WRAPPER}} .member__thumb:before',
			]
		);

		// Before Display;
		$this->add_responsive_control(
			'box_before_display',
			[
				'label'   => esc_html__('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'      => esc_html__('Initial', 'element-ready-lite'),
					'block'        => esc_html__('Block', 'element-ready-lite'),
					'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
					'flex'         => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'none'         => esc_html__('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:before' => 'display: {{VALUE}};',
				],
			]
		);

		// Before Postion
		$this->add_responsive_control(
			'box_before_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:before' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'box_before_position_from_left',
			[
				'label'      => esc_html__('From Left', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:before' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_before_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'box_before_position_from_right',
			[
				'label'      => esc_html__('From Right', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:before' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_before_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'box_before_position_from_top',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:before' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_before_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Bottom
		$this->add_responsive_control(
			'box_before_position_from_bottom',
			[
				'label'      => esc_html__('From Bottom', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:before' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_before_position' => ['absolute', 'relative']
				],
			]
		);

		// Before Align
		$this->add_responsive_control(
			'box_before_align',
			[
				'label'   => esc_html__('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'text-align:left' => [
						'title' => esc_html__('Left', 'element-ready-lite'),
						'icon'  => 'eicon-text-align-left',
					],
					'margin: 0 auto' => [
						'title' => esc_html__('Center', 'element-ready-lite'),
						'icon'  => 'eicon-text-align-center',
					],
					'float:right' => [
						'title' => esc_html__('Right', 'element-ready-lite'),
						'icon'  => 'eicon-text-align-right',
					],
					'text-align:justify' => [
						'title' => esc_html__('Justify', 'element-ready-lite'),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:before' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);

		// Before Width
		$this->add_responsive_control(
			'box_before_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:before' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Before Height
		$this->add_responsive_control(
			'box_before_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Before Opacity
		$this->add_control(
			'box_before_opacity',
			[
				'label' => esc_html__('Opacity', 'element-ready-lite'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:before' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'box_before_opacity_Hver',
			[
				'label' => esc_html__('hover Opacity', 'element-ready-lite'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .team__style__3 .single__team:hover::before' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'team_style' => ['team__style__3']
				]
			]
		);

		// Before Z-Index
		$this->add_control(
			'box_before_zindex',
			[
				'label'     => esc_html__('Z-Index', 'element-ready-lite'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => -99,
				'max'       => 99,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .single__team:before' => 'z-index: {{SIZE}};',
				],
			]
		);

		// Before Margin
		$this->add_responsive_control(
			'box_before_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__team:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'box_after_tab',
			[
				'label' => esc_html__('AFTER', 'element-ready-lite'),
			]
		);

		// After Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'box_after_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__team:after',
			]
		);

		// After Display;
		$this->add_responsive_control(
			'box_after_display',
			[
				'label'   => esc_html__('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'      => esc_html__('Initial', 'element-ready-lite'),
					'block'        => esc_html__('Block', 'element-ready-lite'),
					'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
					'flex'         => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'none'         => esc_html__('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:after' => 'display: {{VALUE}};',
				],
			]
		);

		// After Postion
		$this->add_responsive_control(
			'box_after_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',

				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:after' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'box_after_position_from_left',
			[
				'label'      => esc_html__('From Left', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:after' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_after_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'box_after_position_from_right',
			[
				'label'      => esc_html__('From Right', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:after' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_after_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'box_after_position_from_top',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:after' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_after_position' => ['absolute', 'relative']
				],
			]
		);

		// Postion From Bottom
		$this->add_responsive_control(
			'box_after_position_from_bottom',
			[
				'label'      => esc_html__('From Bottom', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:after' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_after_position' => ['absolute', 'relative']
				],
			]
		);

		// After Align
		$this->add_responsive_control(
			'box_after_align',
			[
				'label'   => esc_html__('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'text-align:left' => [
						'title' => esc_html__('Left', 'element-ready-lite'),
						'icon'  => 'fa fa-align-left',
					],
					'margin: 0 auto' => [
						'title' => esc_html__('Center', 'element-ready-lite'),
						'icon'  => 'fa fa-align-center',
					],
					'float:right' => [
						'title' => esc_html__('Right', 'element-ready-lite'),
						'icon'  => 'fa fa-align-right',
					],
					'text-align:justify' => [
						'title' => esc_html__('Justify', 'element-ready-lite'),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:after' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);

		// After Width
		$this->add_responsive_control(
			'box_after_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:after' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// After Height
		$this->add_responsive_control(
			'box_after_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:after' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// After Opacity
		$this->add_control(
			'box_after_opacity',
			[
				'label' => esc_html__('Opacity', 'element-ready-lite'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .single__team:after' => 'opacity: {{SIZE}};',
				],
			]
		);

		// After Z-Index
		$this->add_control(
			'box_after_zindex',
			[
				'label'     => esc_html__('Z-Index', 'element-ready-lite'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => -99,
				'max'       => 99,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .single__team:after' => 'z-index: {{SIZE}};',
				],
			]
		);

		// After Margin
		$this->add_responsive_control(
			'box_after_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__team:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		/*----------------------------
			BOX BEFORE / AFTER END
		-----------------------------*/
	}

	protected function render()
	{

		$settings = $this->get_settings_for_display();

		/*-----------------------------
			CONTENT WITH FOREACH LOOP
		------------------------------*/
		$team_content = '';
		if ($settings['team_content']) {

			foreach ($settings['team_content'] as $team) {

				$team_content .= '
				<div class="single__team">';

				if (!empty($team['member_thumb'])) {

					if (!empty($team['member_thumb'])) {
						$thumb_array = $team['member_thumb'];
						$thumb_link  = wp_get_attachment_image_url($thumb_array['id'], 'full');
						$thumb_link  = Group_Control_Image_Size::get_attachment_image_src($thumb_array['id'], 'member_thumb_size', $team);
						if ($settings['team_style'] == 'team__style__6 six_v_one') {

							if (!empty($thumb_link)) {
								$team_content .= '<div class="member__thumb">
									<img src="' . esc_url($thumb_link) . '" alt="' . esc_attr(get_the_title()) . '" />';
							} else {
								$team_content .= '<div class="member__thumb">
								<img src="' . esc_url($team['member_thumb']['url']) . '" alt="" />';
							}
						} else {

							if (!empty($thumb_link)) {
								$team_content .= '<div class="member__thumb"><img src="' . esc_url($thumb_link) . '" alt="' . esc_attr(get_the_title()) . '" /></div>';
							} else {
								$team_content .= '<div class="member__thumb"><img src="' . esc_url($team['member_thumb']['url']) . '" alt="" /></div>';
							}
						}
					}
				}
				if ($settings['team_style'] == 'team__style__6 six_v_one') {
					$team_content .= '
						<div class="member__content__wrap">';
					if (!empty($team['member_name'])) {

						$team_content .= '
								<div class="member__name__designation">';
						if (!empty($team['member_name'])) {
							$team_content .= '
							<h4 class="member__name">' . esc_html($team['member_name']) . '</h4>';
						}
						if (!empty($team['designation'])) {
							$team_content .= '
							<p class="member__designation">' . esc_html($team['designation']) . '</p>';
						}

						$team_content .= '
								</div>';
					}

					if (!empty($team['description'])) {

						/*$team_content .='<div class="member__content">';*/
						$team_content .= '<div class="member__description">' . wpautop($team['description']) . '</div>';
						/*$team_content .='</div>';*/
					}

					if ('yes' == $team['add_social'] && 'team__style__9' != $settings['team_style']) {
						$team_content .= '
							<div class="member__socials">';

						$facebook_url  = $team['facebook_url'];
						$twitter_url   = $team['twitter_url'];
						$google_url    = $team['google_url'];
						$youtube_url   = $team['youtube_url'];
						$vimeo_url     = $team['vimeo_url'];
						$instagram_url = $team['instagram_url'];
						$linkedin_url  = $team['linkedin_url'];
						$pinterest_url = $team['pinterest_url'];

						// FACEBOOK
						if (!empty($facebook_url['url'])) {
							$attribute[] = 'href="' . esc_url($facebook_url['url']) . '"';
							if ($facebook_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($facebook_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-facebook"></i></a>';
							$attribute = array();
						}

						// TWITTER
						if (!empty($twitter_url['url'])) {
							$attribute[] = 'href="' . esc_url($twitter_url['url']) . '"';
							if ($twitter_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($twitter_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-twitter"></i></a>';
							$attribute = array();
						}

						// GOOGLE PLUS
						if (!empty($google_url['url'])) {
							$attribute[] = 'href="' . esc_url($google_url['url']) . '"';
							if ($google_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($google_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-google-plus"></i></a>';
							$attribute = array();
						}

						// YOUTUBE
						if (!empty($youtube_url['url'])) {
							$attribute[] = 'href="' . esc_url($youtube_url['url']) . '"';
							if ($youtube_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($youtube_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-youtube"></i></a>';
							$attribute = array();
						}

						// VIMEO
						if (!empty($vimeo_url['url'])) {
							$attribute[] = 'href="' . esc_url($vimeo_url['url']) . '"';
							if ($vimeo_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($vimeo_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-vimeo"></i></a>';
							$attribute = array();
						}

						// INSTAGRAM
						if (!empty($instagram_url['url'])) {
							$attribute[] = 'href="' . esc_url($instagram_url['url']) . '"';
							if ($instagram_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($instagram_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-instagram"></i></a>';
							$attribute = array();
						}

						// LINKEDIN
						if (!empty($linkedin_url['url'])) {
							$attribute[] = 'href="' . esc_url($linkedin_url['url']) . '"';
							if ($linkedin_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($linkedin_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-linkedin"></i></a>';
							$attribute = array();
						}

						// PINTEREST
						if (!empty($pinterest_url['url'])) {
							$attribute[] = 'href="' . esc_url($pinterest_url['url']) . '"';
							if ($pinterest_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($pinterest_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-pinterest"></i></a>';
							$attribute = array();
						}
						$team_content .= '
								</div>';
					}
					$team_content .= '
						</div></div>';
				} else {
					$team_content .= '
						<div class="member__content__wrap">';
					if (!empty($team['member_name'])) {

						$team_content .= '
								<div class="member__name__designation">';
						if (!empty($team['member_name'])) {
							$team_content .= '
							<h4 class="member__name">' . esc_html($team['member_name']) . '</h4>';
						}
						if (!empty($team['designation'])) {
							$team_content .= '
							<p class="member__designation">' . esc_html($team['designation']) . '</p>';
						}

						$team_content .= '
								</div>';
					}

					if (!empty($team['description'])) {

						/*$team_content .='<div class="member__content">';*/
						$team_content .= '<div class="member__description">' . wpautop($team['description']) . '</div>';
						/*$team_content .='</div>';*/
					}

					if ('yes' == $team['add_social'] && 'team__style__9' != $settings['team_style']) {
						$team_content .= '
								<div class="member__socials">';

						$facebook_url  = $team['facebook_url'];
						$twitter_url   = $team['twitter_url'];
						$google_url    = $team['google_url'];
						$youtube_url   = $team['youtube_url'];
						$vimeo_url     = $team['vimeo_url'];
						$instagram_url = $team['instagram_url'];
						$linkedin_url  = $team['linkedin_url'];
						$pinterest_url = $team['pinterest_url'];

						// FACEBOOK
						if (!empty($facebook_url['url'])) {
							$attribute[] = 'href="' . esc_url($facebook_url['url']) . '"';
							if ($facebook_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($facebook_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-facebook"></i></a>';
							$attribute = array();
						}

						// TWITTER
						if (!empty($twitter_url['url'])) {
							$attribute[] = 'href="' . esc_url($twitter_url['url']) . '"';
							if ($twitter_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($twitter_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-twitter"></i></a>';
							$attribute = array();
						}

						// GOOGLE PLUS
						if (!empty($google_url['url'])) {
							$attribute[] = 'href="' . esc_url($google_url['url']) . '"';
							if ($google_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($google_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-google-plus"></i></a>';
							$attribute = array();
						}

						// YOUTUBE
						if (!empty($youtube_url['url'])) {
							$attribute[] = 'href="' . esc_url($youtube_url['url']) . '"';
							if ($youtube_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($youtube_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-youtube"></i></a>';
							$attribute = array();
						}

						// VIMEO
						if (!empty($vimeo_url['url'])) {
							$attribute[] = 'href="' . esc_url($vimeo_url['url']) . '"';
							if ($vimeo_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($vimeo_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-vimeo"></i></a>';
							$attribute = array();
						}

						// INSTAGRAM
						if (!empty($instagram_url['url'])) {
							$attribute[] = 'href="' . esc_url($instagram_url['url']) . '"';
							if ($instagram_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($instagram_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-instagram"></i></a>';
							$attribute = array();
						}

						// LINKEDIN
						if (!empty($linkedin_url['url'])) {
							$attribute[] = 'href="' . esc_url($linkedin_url['url']) . '"';
							if ($linkedin_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($linkedin_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-linkedin"></i></a>';
							$attribute = array();
						}

						// PINTEREST
						if (!empty($pinterest_url['url'])) {
							$attribute[] = 'href="' . esc_url($pinterest_url['url']) . '"';
							if ($pinterest_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($pinterest_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-pinterest"></i></a>';
							$attribute = array();
						}
						$team_content .= '
								</div>';
					}
					$team_content .= '
						</div>';
				}
				if('pro_team__style__11' == $settings['team_style']){
					$team_content .= '<span class="primary-btn">
						<span class="fa-thin fa-arrow-right"></span>
					</span>';
				}

				if ('team__style__8' == $settings['team_style'] || 'team__style__9' == $settings['team_style']) :
					$team_content .= '
					<div class="team__hover__content">';

					if (!empty($team['member_thumb'])) {

						if (!empty($team['member_thumb'])) {
							$thumb_array = $team['member_thumb'];
							$thumb_link  = wp_get_attachment_image_url($thumb_array['id'], 'full');
							$thumb_link  = Group_Control_Image_Size::get_attachment_image_src($thumb_array['id'], 'member_thumb_size', $team);
							if (!empty($thumb_link)) {
								$team_content .= '<div class="member__thumb"><img src="' . esc_url($thumb_link) . '" alt="' . esc_attr(get_the_title()) . '" /></div>';
							} else {
								$team_content .= '<div class="member__thumb"><img src="' . esc_url($team['member_thumb']['url']) . '" alt="" /></div>';
							}
						}
					}
					if (!empty($team['member_name'])) {

						$team_content .= '
							<div class="member__name__designation">';
						if (!empty($team['member_name'])) {
							$team_content .= '
									<h4 class="member__name">' . esc_html($team['member_name']) . '</h4>';
						}
						if (!empty($team['designation'])) {
							$team_content .= '
									<p class="member__designation">' . esc_html($team['designation']) . '</p>';
						}

						$team_content .= '
							</div>';
					}

					if ('team__style__9' == $settings['team_style']) {

						$team_content .= '
							<div class="member__socials">';

						$facebook_url  = $team['facebook_url'];
						$twitter_url   = $team['twitter_url'];
						$google_url    = $team['google_url'];
						$youtube_url   = $team['youtube_url'];
						$vimeo_url     = $team['vimeo_url'];
						$instagram_url = $team['instagram_url'];
						$linkedin_url  = $team['linkedin_url'];
						$pinterest_url = $team['pinterest_url'];

						// FACEBOOK
						if (!empty($facebook_url['url'])) {
							$attribute[] = 'href="' . esc_url($facebook_url['url']) . '"';
							if ($facebook_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($facebook_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-facebook"></i></a>';
							$attribute = array();
						}

						// TWITTER
						if (!empty($twitter_url['url'])) {
							$attribute[] = 'href="' . esc_url($twitter_url['url']) . '"';
							if ($twitter_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($twitter_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-twitter"></i></a>';
							$attribute = array();
						}

						// GOOGLE PLUS
						if (!empty($google_url['url'])) {
							$attribute[] = 'href="' . esc_url($google_url['url']) . '"';
							if ($google_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($google_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-google-plus"></i></a>';
							$attribute = array();
						}

						// YOUTUBE
						if (!empty($youtube_url['url'])) {
							$attribute[] = 'href="' . esc_url($youtube_url['url']) . '"';
							if ($youtube_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($youtube_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-youtube"></i></a>';
							$attribute = array();
						}

						// VIMEO
						if (!empty($vimeo_url['url'])) {
							$attribute[] = 'href="' . esc_url($vimeo_url['url']) . '"';
							if ($vimeo_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($vimeo_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-vimeo"></i></a>';
							$attribute = array();
						}

						// INSTAGRAM
						if (!empty($instagram_url['url'])) {
							$attribute[] = 'href="' . esc_url($instagram_url['url']) . '"';
							if ($instagram_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($instagram_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-instagram"></i></a>';
							$attribute = array();
						}

						// LINKEDIN
						if (!empty($linkedin_url['url'])) {
							$attribute[] = 'href="' . esc_url($linkedin_url['url']) . '"';
							if ($linkedin_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($linkedin_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-linkedin"></i></a>';
							$attribute = array();
						}

						// PINTEREST
						if (!empty($pinterest_url['url'])) {
							$attribute[] = 'href="' . esc_url($pinterest_url['url']) . '"';
							if ($pinterest_url['is_external']) {
								$attribute[] = 'target="_blank"';
							}
							if ($pinterest_url['nofollow']) {
								$attribute[] = 'rel="nofollow"';
							}
							$team_content .= '<a ' . implode(' ', $attribute) . '><i class="fa fa-pinterest"></i></a>';
							$attribute = array();
						}
						$team_content .= '
							</div>';
					}


					$team_content .= '
					</div>';
				endif;

				$team_content .= '
				</div>';
			}
		}

		// Slider Attr
		$this->add_render_attribute('team_carousel_attr', 'class', 'element-ready-team-carousel');
		if (count($settings['team_content']) > 1 && 'yes' == $settings['slider_on']) {
			$this->add_render_attribute('team_carousel_attr', 'class', 'element-ready-carousel-active');

			// SLIDER OPTIONS
			$options = [
				'item_on_large'     => $settings['item_on_large']["size"],
				'item_on_medium'    => $settings['item_on_medium']["size"],
				'item_on_tablet'    => $settings['item_on_tablet']["size"],
				'item_on_mobile'    => $settings['item_on_mobile']["size"],
				'stage_padding'     => $settings['stage_padding']["size"],
				'item_margin'       => $settings['item_margin']["size"],
				'autoplay'          => ('true' == $settings['autoplay']) ? true : false,
				'autoplaytimeout'   => $settings['autoplaytimeout']["size"],
				'slide_speed'       => $settings['slide_speed']["size"],
				'slide_animation'   => $settings['slide_animation'],
				'slide_animate_in'  => $settings['slide_animate_in'],
				'slide_animate_out' => $settings['slide_animate_out'],
				'nav'               => ('true' == $settings['nav']) ? true : false,
				'nav_position'      => $settings['nav_position'],
				'next_icon'         => $settings['next_icon'],
				'prev_icon'         => $settings['prev_icon'],
				'dots'              => ('true' == $settings['dots']) ? true : false,
				'loop'              => ('true' == $settings['loop']) ? true : false,
				'hover_pause'       => ('true' == $settings['hover_pause']) ? true : false,
				'center'            => ('true' == $settings['center']) ? true : false,
				'rtl'               => ('true' == $settings['rtl']) ? true : false,
			];

			$this->add_render_attribute('team_carousel_attr', 'data-settings', wp_json_encode($options));
		} else {
			$this->add_render_attribute('team_carousel_attr', 'class', 'team-normal-grid');
		}

		// Parent Attr.
		if ('true' == $settings['nav'] || 'true' == $settings['dots']) {
			$this->add_render_attribute('sldier_parent_attr', 'class', 'sldier-content-area');
		}

		$this->add_render_attribute('sldier_parent_attr', 'class', $settings['team_style']);
		$this->add_render_attribute('sldier_parent_attr', 'class', $settings['nav_position']);
?>

		<div <?php echo $this->get_render_attribute_string('sldier_parent_attr'); ?>>
			<div <?php echo $this->get_render_attribute_string('team_carousel_attr'); ?>>
				<?php echo wp_kses_post(isset($team_content) ? $team_content : ''); ?>
			</div>
		</div>
<?php
	}
}
