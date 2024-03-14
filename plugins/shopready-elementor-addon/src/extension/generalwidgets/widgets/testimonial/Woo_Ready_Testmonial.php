<?php

namespace Shop_Ready\extension\generalwidgets\widgets\testimonial;

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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Woo_Ready_Testmonial extends \Shop_Ready\extension\generalwidgets\Widget_Base {


	public static function testmonial_style(){
		return apply_filters( 'woo_ready_testimonial_style_presets', [
			
			'tesmonial_style_1'  => 'Testmonial Style 1',
			'tesmonial_style_2'  => 'Testmonial Style 2',
			'tesmonial_style_3'  => 'Testmonial Style 3',

		]);
	}

	protected function register_controls() {

		/******************************
		 * 	CONTENT SECTION
		 ******************************/
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
			// Type
			$this->add_control(
				'testmonial_style',
				[
					'label'   => esc_html__( 'Testmonial Type', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'tesmonial_style_1',
					'options' => self::testmonial_style(),
				]
			);

			// Icon Toggle
			$this->add_control(
				'show_icon',
				[
					'label'        => esc_html__( 'Show Quotation Icon ?', 'shopready-elementor-addon' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Show', 'shopready-elementor-addon' ),
					'label_off'    => esc_html__( 'Hide', 'shopready-elementor-addon' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);

			// Icon Type
			$this->add_control(
				'icon_type',
				[
					'label'   => esc_html__( 'Icon Type', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'font_icon',
					'options' => [
						'font_icon'  => esc_html__( 'SVG / Font Icon', 'shopready-elementor-addon' ),
						'image_icon' => esc_html__( 'Image Icon', 'shopready-elementor-addon' ),
					],
					'condition' => [
						'show_icon' => 'yes',
					],
				]
			);

			// Font Icon
			$this->add_control(
				'font_icon',
				[
					'label'     => esc_html__( 'SVG / Font Icons', 'shopready-elementor-addon' ),
					'type'      => Controls_Manager::ICONS,
					'label_block' => true,
					'default' => [
						'value' => 'fas fa-quote-right',
						'library' => 'solid',
					],
					'condition' => [
						'icon_type' => 'font_icon',
						'show_icon' => 'yes',
					],
				]
			);

			// Image Icon
			$this->add_control(
				'image_icon',
				[
					'label'   => esc_html__( 'Image Icon', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'condition' => [
						'icon_type' => 'image_icon',
						'show_icon' => 'yes',
					],
				]
			);

			$repeater = new Repeater();

			// Title
			$repeater->add_control(
				'title',
				[
					'label'       => esc_html__( 'Title', 'shopready-elementor-addon' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Title', 'shopready-elementor-addon' ),
				]
			);

			// Title Tag
			$repeater->add_control(
				'title_tag',
				[
					'label'   => esc_html__( 'Title HTML Tag', 'elementor' ),
					'type'    => Controls_Manager::SELECT,
					'options' => [
						'h1'   => 'H1',
						'h2'   => 'H2',
						'h3'   => 'H3',
						'h4'   => 'H4',
						'h5'   => 'H5',
						'h6'   => 'H6',
						'div'  => 'div',
						'span' => 'span',
						'p'    => 'p',
					],
					'default'   => 'h3',
					'condition' => [
						'title!' => '',
					],
				]
			);

			// Subtitle
			$repeater->add_control(
				'subtitle',
				[
					'label'       => esc_html__( 'Subtitle', 'shopready-elementor-addon' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Subtitle', 'shopready-elementor-addon' ),
				]
			);

			// Subtitle Position
			$repeater->add_control(
				'subtitle_position',
				[
					'label'   => esc_html__( 'Subtitle Position', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'after_title',
					'options' => [
						'before_title' => esc_html__( 'Before title', 'shopready-elementor-addon' ),
						'after_title'  => esc_html__( 'After Title', 'shopready-elementor-addon' ),
					],
					'condition' => [
						'subtitle!' => '',
					]
				]
			);

			// Member Name
			$repeater->add_control(
				'member_thumb',
				[
					'label'       => esc_html__( 'Testmonial Author Thumb', 'shopready-elementor-addon' ),
					'type'        => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
				]
			);

			$repeater->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' => 'member_thumb_size',
					'default' => 'thumbnail',
				]
			);

			// Member Name
			$repeater->add_control(
				'member_name',
				[
					'label'       => esc_html__( 'Testmonial Author Name', 'shopready-elementor-addon' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Member Name', 'shopready-elementor-addon' ),
				]
			);

			// Member Designation
			$repeater->add_control(
				'designation',
				[
					'label'       => esc_html__( 'Designation', 'shopready-elementor-addon' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Designation Or Company', 'shopready-elementor-addon' ),
				]
			);

			// Description
			$repeater->add_control(
				'description',
				[
					'label'       => esc_html__( 'Description', 'shopready-elementor-addon' ),
					'type'        => Controls_Manager::WYSIWYG,
					'placeholder' => esc_html__( 'Description.', 'shopready-elementor-addon' ),
				]
			);
			$this->add_control(
				'testmonial_content',
				[
					'label' => esc_html__( 'Testmonial Items', 'shopready-elementor-addon' ),
					'type' => Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [
						[
							'member_name' => esc_html__( 'Abdur Rahman', 'shopready-elementor-addon' ),
							'designation' => esc_html__( 'Web Developer' ),
							'description' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga quos pariatur tempore nihil quisquam tempora odio et mollitia. Ea facere expedita beatae nesciunt vero aliquam similique eius veritatis unde eligendi.', 'shopready-elementor-addon' ),
						],
						[
							'member_name' => esc_html__( 'Abdur Rahman', 'shopready-elementor-addon' ),
							'designation' => esc_html__( 'Web Developer' ),
							'description' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga quos pariatur tempore nihil quisquam tempora odio et mollitia. Ea facere expedita beatae nesciunt vero aliquam similique eius veritatis unde eligendi.', 'shopready-elementor-addon' ),
						],
						[
							'member_name' => esc_html__( 'Abdur Rahman', 'shopready-elementor-addon' ),
							'designation' => esc_html__( 'Web Developer' ),
							'description' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga quos pariatur tempore nihil quisquam tempora odio et mollitia. Ea facere expedita beatae nesciunt vero aliquam similique eius veritatis unde eligendi.', 'shopready-elementor-addon' ),
						],
					],
					'title_field' => '{{{ member_name }}}',
				]
			);
		$this->end_controls_section();

		/******************************
		 * 	SLIDER OPTIONS SECTION
		 ******************************/
		$this->start_controls_section(
			'options_section',
			[
				'label'     => esc_html__( 'Slider Options', 'shopready-elementor-addon' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
			]
		);
			$this->add_control(
				'item_on_large',
				[
					'label' => esc_html__( 'Item In large Device', 'shopready-elementor-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 10,
							'step' => 0.1,
						],
					],
					'default' => [
						'size' => 3,
					],
				]
			);
			$this->add_control(
				'item_on_medium',
				[
					'label' => esc_html__( 'Item In Medium Device', 'shopready-elementor-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 10,
							'step' => 0.1,
						],
					],
					'default' => [
						'size' => 3,
					],
				]
			);
			$this->add_control(
				'item_on_tablet',
				[
					'label' => esc_html__( 'Item In Tablet Device', 'shopready-elementor-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 10,
							'step' => 0.1,
						],
					],
					'default' => [
						'size' => 2,
					],
				]
			);
			$this->add_control(
				'item_on_mobile',
				[
					'label' => esc_html__( 'Item In Mobile Device', 'shopready-elementor-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 10,
							'step' => 1,
						],
					],
					'default' => [
						'size' => 1,
					],
				]
			);
			$this->add_control(
				'stage_padding',
				[
					'label' => esc_html__( 'Stage Padding', 'shopready-elementor-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
					],
					'default' => [
						'size' => 0,
					],
				]
			);
			$this->add_control(
				'item_margin',
				[
					'label' => esc_html__( 'Item Margin', 'shopready-elementor-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						],
					],
					'default' => [
						'size' => 0,
					],
				]
			);
			$this->add_control(
				'autoplay',
				[
					'label'   => esc_html__( 'Slide Autoplay', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'true',
					'options' => [
						'true'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
						'false' => esc_html__( 'No', 'shopready-elementor-addon' ),
					],
				]
			);
			$this->add_control(
				'autoplaytimeout',
				[
					'label' => esc_html__( 'Autoplay Timeout', 'shopready-elementor-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 500,
							'max' => 10000,
							'step' => 100,
						],
					],
					'default' => [
						'size' => 3000,
					],
				]
			);
			$this->add_control(
				'slide_speed',
				[
					'label' => esc_html__( 'Slide Speed', 'shopready-elementor-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 500,
							'max' => 10000,
							'step' => 100,
						],
					],
					'default' => [
						'size' => 1000,
					],
				]
			);
			$this->add_control(
				'slide_animation',
				[
					'label'   => esc_html__( 'Slide Animation', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'no',
					'options' => [
						'yes' => esc_html__( 'Yes', 'shopready-elementor-addon' ),
						'no'      => esc_html__( 'No', 'shopready-elementor-addon' ),
					],
				]
			);
			$this->add_control(
				'slide_animate_in',
				[
					'label'   => esc_html__( 'Slide Animate In', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'fadeIn',
					'options' => [
						'bounce'             => esc_html__('bounce','shopready-elementor-addon'),
						'flash'              => esc_html__('flash','shopready-elementor-addon'),
						'pulse'              => esc_html__('pulse','shopready-elementor-addon'),
						'rubberBand'         => esc_html__('rubberBand','shopready-elementor-addon'),
						'shake'              => esc_html__('shake','shopready-elementor-addon'),
						'headShake'          => esc_html__('headShake','shopready-elementor-addon'),
						'swing'              => esc_html__('swing','shopready-elementor-addon'),
						'tada'               => esc_html__('tada','shopready-elementor-addon'),
						'wobble'             => esc_html__('wobble','shopready-elementor-addon'),
						'jello'              => esc_html__('jello','shopready-elementor-addon'),
						'heartBeat'          => esc_html__('heartBeat','shopready-elementor-addon'),
						'bounceIn'           => esc_html__('bounceIn','shopready-elementor-addon'),
						'bounceInDown'       => esc_html__('bounceInDown','shopready-elementor-addon'),
						'bounceInLeft'       => esc_html__('bounceInLeft','shopready-elementor-addon'),
						'bounceInRight'      => esc_html__('bounceInRight','shopready-elementor-addon'),
						'bounceInUp'         => esc_html__('bounceInUp','shopready-elementor-addon'),
						'bounceOut'          => esc_html__('bounceOut','shopready-elementor-addon'),
						'bounceOutDown'      => esc_html__('bounceOutDown','shopready-elementor-addon'),
						'bounceOutLeft'      => esc_html__('bounceOutLeft','shopready-elementor-addon'),
						'bounceOutRight'     => esc_html__('bounceOutRight','shopready-elementor-addon'),
						'bounceOutUp'        => esc_html__('bounceOutUp','shopready-elementor-addon'),
						'fadeIn'             => esc_html__('fadeIn','shopready-elementor-addon'),
						'fadeInDown'         => esc_html__('fadeInDown','shopready-elementor-addon'),
						'fadeInDownBig'      => esc_html__('fadeInDownBig','shopready-elementor-addon'),
						'fadeInLeft'         => esc_html__('fadeInLeft','shopready-elementor-addon'),
						'fadeInLeftBig'      => esc_html__('fadeInLeftBig','shopready-elementor-addon'),
						'fadeInRight'        => esc_html__('fadeInRight','shopready-elementor-addon'),
						'fadeInRightBig'     => esc_html__('fadeInRightBig','shopready-elementor-addon'),
						'fadeInUp'           => esc_html__('fadeInUp','shopready-elementor-addon'),
						'fadeInUpBig'        => esc_html__('fadeInUpBig','shopready-elementor-addon'),
						'fadeOut'            => esc_html__('fadeOut','shopready-elementor-addon'),
						'fadeOutDown'        => esc_html__('fadeOutDown','shopready-elementor-addon'),
						'fadeOutDownBig'     => esc_html__('fadeOutDownBig','shopready-elementor-addon'),
						'fadeOutLeft'        => esc_html__('fadeOutLeft','shopready-elementor-addon'),
						'fadeOutLeftBig'     => esc_html__('fadeOutLeftBig','shopready-elementor-addon'),
						'fadeOutRight'       => esc_html__('fadeOutRight','shopready-elementor-addon'),
						'fadeOutRightBig'    => esc_html__('fadeOutRightBig','shopready-elementor-addon'),
						'fadeOutUp'          => esc_html__('fadeOutUp','shopready-elementor-addon'),
						'fadeOutUpBig'       => esc_html__('fadeOutUpBig','shopready-elementor-addon'),
						'flip'               => esc_html__('flip','shopready-elementor-addon'),
						'flipInX'            => esc_html__('flipInX','shopready-elementor-addon'),
						'flipInY'            => esc_html__('flipInY','shopready-elementor-addon'),
						'flipOutX'           => esc_html__('flipOutX','shopready-elementor-addon'),
						'flipOutY'           => esc_html__('flipOutY','shopready-elementor-addon'),
						'lightSpeedIn'       => esc_html__('lightSpeedIn','shopready-elementor-addon'),
						'lightSpeedOut'      => esc_html__('lightSpeedOut','shopready-elementor-addon'),
						'rotateIn'           => esc_html__('rotateIn','shopready-elementor-addon'),
						'rotateInDownLeft'   => esc_html__('rotateInDownLeft','shopready-elementor-addon'),
						'rotateInDownRight'  => esc_html__('rotateInDownRight','shopready-elementor-addon'),
						'rotateInUpLeft'     => esc_html__('rotateInUpLeft','shopready-elementor-addon'),
						'rotateInUpRight'    => esc_html__('rotateInUpRight','shopready-elementor-addon'),
						'rotateOut'          => esc_html__('rotateOut','shopready-elementor-addon'),
						'rotateOutDownLeft'  => esc_html__('rotateOutDownLeft','shopready-elementor-addon'),
						'rotateOutDownRight' => esc_html__('rotateOutDownRight','shopready-elementor-addon'),
						'rotateOutUpLeft'    => esc_html__('rotateOutUpLeft','shopready-elementor-addon'),
						'rotateOutUpRight'   => esc_html__('rotateOutUpRight','shopready-elementor-addon'),
						'hinge'              => esc_html__('hinge','shopready-elementor-addon'),
						'jackInTheBox'       => esc_html__('jackInTheBox','shopready-elementor-addon'),
						'rollIn'             => esc_html__('rollIn','shopready-elementor-addon'),
						'rollOut'            => esc_html__('rollOut','shopready-elementor-addon'),
						'zoomIn'             => esc_html__('zoomIn','shopready-elementor-addon'),
						'zoomInDown'         => esc_html__('zoomInDown','shopready-elementor-addon'),
						'zoomInLeft'         => esc_html__('zoomInLeft','shopready-elementor-addon'),
						'zoomInRight'        => esc_html__('zoomInRight','shopready-elementor-addon'),
						'zoomInUp'           => esc_html__('zoomInUp','shopready-elementor-addon'),
						'zoomOut'            => esc_html__('zoomOut','shopready-elementor-addon'),
						'zoomOutDown'        => esc_html__('zoomOutDown','shopready-elementor-addon'),
						'zoomOutLeft'        => esc_html__('zoomOutLeft','shopready-elementor-addon'),
						'zoomOutRight'       => esc_html__('zoomOutRight','shopready-elementor-addon'),
						'zoomOutUp'          => esc_html__('zoomOutUp','shopready-elementor-addon'),
						'slideInDown'        => esc_html__('slideInDown','shopready-elementor-addon'),
						'slideInLeft'        => esc_html__('slideInLeft','shopready-elementor-addon'),
						'slideInRight'       => esc_html__('slideInRight','shopready-elementor-addon'),
						'slideInUp'          => esc_html__('slideInUp','shopready-elementor-addon'),
						'slideOutDown'       => esc_html__('slideOutDown','shopready-elementor-addon'),
						'slideOutLeft'       => esc_html__('slideOutLeft','shopready-elementor-addon'),
						'slideOutRight'      => esc_html__('slideOutRight','shopready-elementor-addon'),
						'slideOutUp'         => esc_html__('slideOutUp','shopready-elementor-addon'),
					],
					'condition' => [
						'slide_animation' => 'yes',
					]
				]
			);
			$this->add_control(
				'slide_animate_out',
				[
					'label'   => esc_html__( 'Slide Animate Out', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'fadeOut',
					'options' => [
						'bounce'             => esc_html__('bounce','shopready-elementor-addon'),
						'flash'              => esc_html__('flash','shopready-elementor-addon'),
						'pulse'              => esc_html__('pulse','shopready-elementor-addon'),
						'rubberBand'         => esc_html__('rubberBand','shopready-elementor-addon'),
						'shake'              => esc_html__('shake','shopready-elementor-addon'),
						'headShake'          => esc_html__('headShake','shopready-elementor-addon'),
						'swing'              => esc_html__('swing','shopready-elementor-addon'),
						'tada'               => esc_html__('tada','shopready-elementor-addon'),
						'wobble'             => esc_html__('wobble','shopready-elementor-addon'),
						'jello'              => esc_html__('jello','shopready-elementor-addon'),
						'heartBeat'          => esc_html__('heartBeat','shopready-elementor-addon'),
						'bounceIn'           => esc_html__('bounceIn','shopready-elementor-addon'),
						'bounceInDown'       => esc_html__('bounceInDown','shopready-elementor-addon'),
						'bounceInLeft'       => esc_html__('bounceInLeft','shopready-elementor-addon'),
						'bounceInRight'      => esc_html__('bounceInRight','shopready-elementor-addon'),
						'bounceInUp'         => esc_html__('bounceInUp','shopready-elementor-addon'),
						'bounceOut'          => esc_html__('bounceOut','shopready-elementor-addon'),
						'bounceOutDown'      => esc_html__('bounceOutDown','shopready-elementor-addon'),
						'bounceOutLeft'      => esc_html__('bounceOutLeft','shopready-elementor-addon'),
						'bounceOutRight'     => esc_html__('bounceOutRight','shopready-elementor-addon'),
						'bounceOutUp'        => esc_html__('bounceOutUp','shopready-elementor-addon'),
						'fadeIn'             => esc_html__('fadeIn','shopready-elementor-addon'),
						'fadeInDown'         => esc_html__('fadeInDown','shopready-elementor-addon'),
						'fadeInDownBig'      => esc_html__('fadeInDownBig','shopready-elementor-addon'),
						'fadeInLeft'         => esc_html__('fadeInLeft','shopready-elementor-addon'),
						'fadeInLeftBig'      => esc_html__('fadeInLeftBig','shopready-elementor-addon'),
						'fadeInRight'        => esc_html__('fadeInRight','shopready-elementor-addon'),
						'fadeInRightBig'     => esc_html__('fadeInRightBig','shopready-elementor-addon'),
						'fadeInUp'           => esc_html__('fadeInUp','shopready-elementor-addon'),
						'fadeInUpBig'        => esc_html__('fadeInUpBig','shopready-elementor-addon'),
						'fadeOut'            => esc_html__('fadeOut','shopready-elementor-addon'),
						'fadeOutDown'        => esc_html__('fadeOutDown','shopready-elementor-addon'),
						'fadeOutDownBig'     => esc_html__('fadeOutDownBig','shopready-elementor-addon'),
						'fadeOutLeft'        => esc_html__('fadeOutLeft','shopready-elementor-addon'),
						'fadeOutLeftBig'     => esc_html__('fadeOutLeftBig','shopready-elementor-addon'),
						'fadeOutRight'       => esc_html__('fadeOutRight','shopready-elementor-addon'),
						'fadeOutRightBig'    => esc_html__('fadeOutRightBig','shopready-elementor-addon'),
						'fadeOutUp'          => esc_html__('fadeOutUp','shopready-elementor-addon'),
						'fadeOutUpBig'       => esc_html__('fadeOutUpBig','shopready-elementor-addon'),
						'flip'               => esc_html__('flip','shopready-elementor-addon'),
						'flipInX'            => esc_html__('flipInX','shopready-elementor-addon'),
						'flipInY'            => esc_html__('flipInY','shopready-elementor-addon'),
						'flipOutX'           => esc_html__('flipOutX','shopready-elementor-addon'),
						'flipOutY'           => esc_html__('flipOutY','shopready-elementor-addon'),
						'lightSpeedIn'       => esc_html__('lightSpeedIn','shopready-elementor-addon'),
						'lightSpeedOut'      => esc_html__('lightSpeedOut','shopready-elementor-addon'),
						'rotateIn'           => esc_html__('rotateIn','shopready-elementor-addon'),
						'rotateInDownLeft'   => esc_html__('rotateInDownLeft','shopready-elementor-addon'),
						'rotateInDownRight'  => esc_html__('rotateInDownRight','shopready-elementor-addon'),
						'rotateInUpLeft'     => esc_html__('rotateInUpLeft','shopready-elementor-addon'),
						'rotateInUpRight'    => esc_html__('rotateInUpRight','shopready-elementor-addon'),
						'rotateOut'          => esc_html__('rotateOut','shopready-elementor-addon'),
						'rotateOutDownLeft'  => esc_html__('rotateOutDownLeft','shopready-elementor-addon'),
						'rotateOutDownRight' => esc_html__('rotateOutDownRight','shopready-elementor-addon'),
						'rotateOutUpLeft'    => esc_html__('rotateOutUpLeft','shopready-elementor-addon'),
						'rotateOutUpRight'   => esc_html__('rotateOutUpRight','shopready-elementor-addon'),
						'hinge'              => esc_html__('hinge','shopready-elementor-addon'),
						'jackInTheBox'       => esc_html__('jackInTheBox','shopready-elementor-addon'),
						'rollIn'             => esc_html__('rollIn','shopready-elementor-addon'),
						'rollOut'            => esc_html__('rollOut','shopready-elementor-addon'),
						'zoomIn'             => esc_html__('zoomIn','shopready-elementor-addon'),
						'zoomInDown'         => esc_html__('zoomInDown','shopready-elementor-addon'),
						'zoomInLeft'         => esc_html__('zoomInLeft','shopready-elementor-addon'),
						'zoomInRight'        => esc_html__('zoomInRight','shopready-elementor-addon'),
						'zoomInUp'           => esc_html__('zoomInUp','shopready-elementor-addon'),
						'zoomOut'            => esc_html__('zoomOut','shopready-elementor-addon'),
						'zoomOutDown'        => esc_html__('zoomOutDown','shopready-elementor-addon'),
						'zoomOutLeft'        => esc_html__('zoomOutLeft','shopready-elementor-addon'),
						'zoomOutRight'       => esc_html__('zoomOutRight','shopready-elementor-addon'),
						'zoomOutUp'          => esc_html__('zoomOutUp','shopready-elementor-addon'),
						'slideInDown'        => esc_html__('slideInDown','shopready-elementor-addon'),
						'slideInLeft'        => esc_html__('slideInLeft','shopready-elementor-addon'),
						'slideInRight'       => esc_html__('slideInRight','shopready-elementor-addon'),
						'slideInUp'          => esc_html__('slideInUp','shopready-elementor-addon'),
						'slideOutDown'       => esc_html__('slideOutDown','shopready-elementor-addon'),
						'slideOutLeft'       => esc_html__('slideOutLeft','shopready-elementor-addon'),
						'slideOutRight'      => esc_html__('slideOutRight','shopready-elementor-addon'),
						'slideOutUp'         => esc_html__('slideOutUp','shopready-elementor-addon'),
					],
					'condition' => [
						'slide_animation' => 'yes',
					]
				]
			);
			$this->add_control(
				'nav',
				[
					'label'   => esc_html__( 'Show Navigation', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'false',
					'options' => [
						'true'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
						'false' => esc_html__( 'No', 'shopready-elementor-addon' ),
					],
				]
			);
			$this->add_control(
				'nav_position',
				[
					'label'   => esc_html__( 'Navigation Position', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'outside_vertical_center_nav',
					'options' => [
						'inside_vertical_center_nav'  => esc_html__( 'Inside Vertical Center', 'shopready-elementor-addon' ),
						'outside_vertical_center_nav' => esc_html__( 'Outside Vertical Center', 'shopready-elementor-addon' ),
						'top_left_nav'                => esc_html__( 'Top Left', 'shopready-elementor-addon' ),
						'top_center_nav'              => esc_html__( 'Top Center', 'shopready-elementor-addon' ),
						'top_right_nav'               => esc_html__( 'Top Right', 'shopready-elementor-addon' ),
						'bottom_left_nav'             => esc_html__( 'Bottom Left', 'shopready-elementor-addon' ),
						'bottom_center_nav'           => esc_html__( 'Bottom Center', 'shopready-elementor-addon' ),
						'bottom_right_nav'            => esc_html__( 'Bottom Right', 'shopready-elementor-addon' ),
					],
					'condition' => [
						'nav' => 'true',
					],
				]
			);
			$this->add_control(
				'next_icon',
				[
					'label'     => esc_html__( 'Nav Next Icon', 'shopready-elementor-addon' ),
					'type'      => Controls_Manager::ICON,
					'label_block' => true,
					'default'   => 'fa fa-angle-right',
					'condition' => [
						'nav' => 'true',
					],
				]
			);
			$this->add_control(
				'prev_icon',
				[
					'label'     => esc_html__( 'Nav Prev Icon', 'shopready-elementor-addon' ),
					'type'      => Controls_Manager::ICON,
					'label_block' => true,
					'default'   => 'fa fa-angle-left',
					'condition' => [
						'nav' => 'true',
					],
				]
			);
			$this->add_control(
				'dots',
				[
					'label'   => esc_html__( 'Slide Dots', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'false',
					'options' => [
						'true'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
						'false' => esc_html__( 'No', 'shopready-elementor-addon' ),
					],
				]
			);
			$this->add_control(
				'loop',
				[
					'label'   => esc_html__( 'Slide Loop', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'true',
					'options' => [
						'true'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
						'false' => esc_html__( 'No', 'shopready-elementor-addon' ),
					],
				]
			);
			$this->add_control(
				'hover_pause',
				[
					'label'   => esc_html__( 'Pause On Hover', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'true',
					'options' => [
						'true'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
						'false' => esc_html__( 'No', 'shopready-elementor-addon' ),
					],
				]
			);
			$this->add_control(
				'center',
				[
					'label'   => esc_html__( 'Slide Center Mode', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'false',
					'options' => [
						'true'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
						'false' => esc_html__( 'No', 'shopready-elementor-addon' ),
					],
				]
			);
			$this->add_control(
				'rtl',
				[
					'label'   => esc_html__( 'Direction RTL', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'false',
					'options' => [
						'true'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
						'false' => esc_html__( 'No', 'shopready-elementor-addon' ),
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
				'label' => esc_html__( 'Slider Nav Warp', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
			$this->add_group_control(
				Group_Control_Background:: get_type(),
				[
					'name'     => 'slider_nav_warp_background',
					'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav',
				]
			);
			$this->add_group_control(
				Group_Control_Border:: get_type(),
				[
					'name'     => 'slider_nav_warp_border',
					'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
					'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
				]
			);
			$this->add_control(
				'slider_nav_warp_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow:: get_type(),
				[
					'name'     => 'slider_nav_warp_shadow',
					'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
				]
			);
			$this->add_responsive_control(
				'slider_nav_warp_display',
				[
					'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
						'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
						'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
						'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
						'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
						'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
					],
					'selectors' => [
						'{{WRAPPER}} .sldier-content-area .owl-nav' => 'display: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'slider_nav_warp_position',
				[
					'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					
					'options' => [
						'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
						'absolute' => esc_html__( 'Absulute', 'shopready-elementor-addon' ),
						'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
						'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
					],
					'selectors' => [
						'{{WRAPPER}} .sldier-content-area .owl-nav' => 'position: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'slider_nav_warp_position_from_left',
				[
					'label'      => esc_html__( 'From Left', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'slider_nav_warp_position' => ['absolute','relative']
					],
				]
			);
			$this->add_responsive_control(
				'slider_nav_warp_position_from_right',
				[
					'label'      => esc_html__( 'From Right', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'slider_nav_warp_position' => ['absolute','relative']
					],
				]
			);
			$this->add_responsive_control(
				'slider_nav_warp_position_from_top',
				[
					'label'      => esc_html__( 'From Top', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'slider_nav_warp_position' => ['absolute','relative']
					],
				]
			);
			$this->add_responsive_control(
				'slider_nav_warp_position_from_bottom',
				[
					'label'      => esc_html__( 'From Bottom', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'slider_nav_warp_position' => ['absolute','relative']
					],
				]
			);
			$this->add_responsive_control(
				'slider_nav_warp_align',
				[
					'label'   => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-right',
						],
						'justify' => [
							'title' => esc_html__( 'Justify', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-justify',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .sldier-content-area .owl-nav' => 'text-align: {{VALUE}};',
					],
					'default' => '',
				]
			);
			$this->add_responsive_control(
				'slider_nav_warp_width',
				[
					'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
			$this->add_responsive_control(
				'slider_nav_warp_height',
				[
					'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
			$this->add_control(
				'slider_nav_warp_opacity',
				[
					'label' => esc_html__( 'Opacity', 'shopready-elementor-addon' ),
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
			$this->add_control(
				'slider_nav_warp_zindex',
				[
					'label'     => esc_html__( 'Z-Index', 'shopready-elementor-addon' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => -99,
					'max'       => 99,
					'step'      => 1,
					'selectors' => [
						'{{WRAPPER}} .sldier-content-area .owl-nav' => 'z-index: {{SIZE}};',
					],
				]
			);
			$this->add_responsive_control(
				'slider_nav_warp_margin',
				[
					'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .sldier-content-area .owl-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'slider_nav_warp_padding',
				[
					'label'      => esc_html__( 'Padding', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
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
				'label' => esc_html__( 'Slider Nav Button', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
			$this->start_controls_tabs( 'slide_button_tab_style' );
			$this->start_controls_tab(
				'slide_button_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'shopready-elementor-addon' ),
				]
			);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name'      => 'slide_button_typography',
						'selector'  => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
					]
				);
				$this->add_control(
					'slide_button_hr1',
					[
						'type' => Controls_Manager::DIVIDER,
					]
				);
				$this->add_control(
					'slide_button_color',
					[
						'label'     => esc_html__( 'Color', 'shopready-elementor-addon' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Background:: get_type(),
					[
						'name'     => 'slide_button_background',
						'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
						'types'    => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
					]
				);
				$this->add_control(
					'slide_button_hr2',
					[
						'type' => Controls_Manager::DIVIDER,
					]
				);
				$this->add_group_control(
					Group_Control_Border:: get_type(),
					[
						'name'     => 'slide_button_border',
						'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
						'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
					]
				);
				$this->add_control(
					'slide_button_radius',
					[
						'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors'  => [
							'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow:: get_type(),
					[
						'name'     => 'slide_button_shadow',
						'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
					]
				);
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
					'label' => esc_html__( 'Hover', 'shopready-elementor-addon' ),
				]
			);
				$this->add_control(
					'hover_slide_button_color',
					[
						'label'     => esc_html__( 'Color', 'shopready-elementor-addon' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .sldier-content-area .owl-nav > div:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Background:: get_type(),
					[
						'name'     => 'hover_slide_button_background',
						'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
						'types'    => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div:hover',
					]
				);
				$this->add_control(
					'slide_button_hr4',
					[
						'type' => Controls_Manager::DIVIDER,
					]
				);
				$this->add_group_control(
					Group_Control_Border:: get_type(),
					[
						'name'     => 'hover_slide_button_border',
						'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
						'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div:hover',
					]
				);
				$this->add_control(
					'hover_slide_button_radius',
					[
						'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors'  => [
							'{{WRAPPER}} .sldier-content-area .owl-nav > div:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow:: get_type(),
					[
						'name'     => 'hover_slide_button_shadow',
						'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div:hover',
					]
				);
				$this->add_control(
					'slide_button_hr9',
					[
						'type' => Controls_Manager::DIVIDER,
					]
				);
			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_control(
				'slide_button_width',
				[
					'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
			$this->add_control(
				'slide_button_height',
				[
					'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
			$this->add_control(
				'slide_button_hr5',
				[
					'type' => Controls_Manager::DIVIDER,
				]
			);
			$this->add_responsive_control(
				'slide_button_display',
				[
					'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',				
					'options' => [
						'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
						'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
						'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
						'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
						'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
						'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
					],
					'selectors' => [
						'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'display: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'slide_button_align',
				[
					'label'   => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-right',
						],
						'justify' => [
							'title' => esc_html__( 'Justify', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-justify',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'text-align: {{VALUE}};',
					],
					'default' => '',
				]
			);
			$this->add_control(
				'slide_button_hr6',
				[
					'type' => Controls_Manager::DIVIDER,
				]
			);
			$this->add_responsive_control(
				'slide_button_position',
				[
					'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',				
					'options' => [
						'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
						'absolute' => esc_html__( 'Absulute', 'shopready-elementor-addon' ),
						'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
						'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
					],
					'selectors' => [
						'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'position: {{VALUE}};',
					],
				]
			);
			$this->start_controls_tabs( 'slide_button_item_tab_style');
			$this->start_controls_tab(
				'slide_button_left_nav_tab',
				[
					'label' => esc_html__( 'Left Button', 'shopready-elementor-addon' ),
				]
			);
				$this->add_responsive_control(
					'slide_button_position_from_left',
					[
						'label'      => esc_html__( 'From Left', 'shopready-elementor-addon' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-prev' => 'left: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
							'slide_button_position' => ['absolute','relative']
						],
					]
				);
				$this->add_responsive_control(
					'slide_button_position_from_bottom',
					[
						'label'      => esc_html__( 'From Top', 'shopready-elementor-addon' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-prev' => 'top: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
							'slide_button_position' => ['absolute','relative']
						],
					]
				);
				$this->add_responsive_control(
					'slide_button_left_margin',
					[
						'label'      => esc_html__( 'Margin Left Button', 'shopready-elementor-addon' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors'  => [
							'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-prev' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
			$this->end_controls_tab();
			$this->start_controls_tab(
				'slide_button_right_nav_tab',
				[
					'label' => esc_html__( 'Right Button', 'shopready-elementor-addon' ),
				]
			);
				$this->add_responsive_control(
					'slide_button_position_from_right',
					[
						'label'      => esc_html__( 'From Right', 'shopready-elementor-addon' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-next' => 'right: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
							'slide_button_position' => ['absolute','relative']
						],
					]
				);
				$this->add_responsive_control(
					'slide_button_position_from_top',
					[
						'label'      => esc_html__( 'From Top', 'shopready-elementor-addon' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
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
							'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-next' => 'top: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
							'slide_button_position' => ['absolute','relative']
						],
					]
				);
				$this->add_responsive_control(
					'slide_button_right_margin',
					[
						'label'      => esc_html__( 'Margin Right Button', 'shopready-elementor-addon' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors'  => [
							'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->add_control(
				'slide_button_hr7',
				[
					'type' => Controls_Manager::DIVIDER,
				]
			);
			$this->add_control(
				'slide_button_transition',
				[
					'label'      => esc_html__( 'Transition', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
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
			$this->add_control(
				'slide_button_hr8',
				[
					'type' => Controls_Manager::DIVIDER,
				]
			);
			$this->add_responsive_control(
				'slide_button_padding',
				[
					'label'      => esc_html__( 'Padding', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
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
				'label' => esc_html__( 'Slide Dots Style', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
			$this->start_controls_tabs( 'button_tab_style' );
				$this->start_controls_tab(
					'slide_dots_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'shopready-elementor-addon' ),
					]
				);
					$this->add_control(
						'slide_dots_width',
						[
							'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
					$this->add_control(
						'slide_dots_height',
						[
							'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
					$this->add_group_control(
						Group_Control_Background:: get_type(),
						[
							'name'     => 'slide_dots_background',
							'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div',
						]
					);
					$this->add_group_control(
						Group_Control_Border:: get_type(),
						[
							'name'     => 'slide_dots_border',
							'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
							'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div',
						]
					);
					$this->add_control(
						'slide_dots_radius',
						[
							'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .sldier-content-area .owl-dots > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow:: get_type(),
						[
							'name'     => 'slide_dots_shadow',
							'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div',
						]
					);
					$this->add_control(
						'slide_dots_hr',
						[
							'type' => Controls_Manager::DIVIDER,
						]
					);
					$this->add_control(
						'slide_dots_align',
						[
							'label'   => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::CHOOSE,
							'options' => [
								'left' => [
									'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-left',
								],
								'center' => [
									'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-center',
								],
								'right' => [
									'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-right',
								],
								'justify' => [
									'title' => esc_html__( 'Justify', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-justify',
								],
							],
							'selectors' => [
								'{{WRAPPER}} .sldier-content-area .owl-dots' => 'text-align: {{VALUE}};',
							],
							'default' => '',
						]
					);
					$this->add_control(
						'slide_dots_transition',
						[
							'label'      => esc_html__( 'Transition', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
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
					$this->add_responsive_control(
						'slide_dots_margin',
						[
							'label'      => esc_html__( 'Dot Item Margin', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .sldier-content-area .owl-dots > div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'slide_dots_warp_margin',
						[
							'label'      => esc_html__( 'Dot Warp Margin', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .sldier-content-area .owl-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
				$this->end_controls_tab();
				$this->start_controls_tab(
					'slide_dots_hover_tab',
					[
						'label' => esc_html__( 'Hover & Active', 'shopready-elementor-addon' ),
					]
				);
					$this->add_control(
						'hover_slide_dots_width',
						[
							'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
					$this->add_control(
						'hover_slide_dots_height',
						[
							'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
					$this->add_group_control(
						Group_Control_Background:: get_type(),
						[
							'name'     => 'hover_slide_dots_background',
							'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active',
						]
					);
					$this->add_group_control(
						Group_Control_Border:: get_type(),
						[
							'name'     => 'hover_slide_dots_border',
							'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
							'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active',
						]
					);
					$this->add_control(
						'hover_slide_dots_radius',
						[
							'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow:: get_type(),
						[
							'name'     => 'hover_slide_dots_shadow',
							'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active',
						]
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();
		$this->end_controls_section();
		/*----------------------------
			DOTS BUTTON STYLE END
		-----------------------------*/

		/*********************************
		 * 		STYLE SECTION
		 *********************************/

		/*----------------------------
			THUMB SLIDER STYLE
		-----------------------------*/
		$this->start_controls_section(
			'thumb_slider_section',
			[
				'label' => esc_html__( 'Thumbs Slider', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'testmonial_style' => 'tesmonial_style_13',
				],
			]
		);
			$this->add_responsive_control(
				'thumbs_slider_width',
				[
					'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'{{WRAPPER}} .testmonial__thumb__content' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'thumbs_slider_height',
				[
					'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'{{WRAPPER}} .testmonial__thumb__content' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background:: get_type(),
				[
					'name'     => 'thumbs_slider_background',
					'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .testmonial__thumb__content',
				]
			);
			$this->add_group_control(
				Group_Control_Border:: get_type(),
				[
					'name'     => 'thumbs_slider_border',
					'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
					'selector' => '{{WRAPPER}} .testmonial__thumb__content',
				]
			);
			$this->add_control(
				'thumbs_slider_border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .testmonial__thumb__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow:: get_type(),
				[
					'name'     => 'thumbs_slider_shadow',
					'selector' => '{{WRAPPER}} .testmonial__thumb__content',
				]
			);
			$this->add_responsive_control(
				'thumbs_slider_display',
				[
					'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
						'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
						'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
						'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
						'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
						'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
					],
					'selectors' => [
						'{{WRAPPER}} .testmonial__thumb__content' => 'display: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'thumbs_slider_padding',
				[
					'label'      => esc_html__( 'Padding', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .testmonial__thumb__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'thumbs_slider_margin',
				[
					'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .testmonial__thumb__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'thumbs_slider_align',
				[
					'label'   => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-right',
						],
						'justify' => [
							'title' => esc_html__( 'Justify', 'shopready-elementor-addon' ),
							'icon'  => 'fa fa-align-justify',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .testmonial__thumb__content_area' => 'text-align: {{VALUE}};',
					],
					'default' => '',
				]
			);
		$this->end_controls_section();
		/*----------------------------
			THUMB SLIDER STYLE END
		-----------------------------*/

		/*----------------------------
			ICON STYLE
		-----------------------------*/
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => esc_html__( 'Icon', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->start_controls_tabs( 'icon_tab_style' );
				$this->start_controls_tab(
					'icon_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'shopready-elementor-addon' ),
					]
				);
					$this->add_group_control(
						Group_Control_Typography:: get_type(),
						[
							'name'      => 'icon_typography',
							'selector'  => '{{WRAPPER}} .testmonial__quote',
							'condition' => [
								'icon_type' => ['font_icon']
							],
						]
					);
					$this->add_responsive_control(
						'icon_image_size',
						[
							'label'      => esc_html__( 'SVG / Image Icon Size', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .testmonial__quote img' => 'width: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} .testmonial__quote svg' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Css_Filter:: get_type(),
						[
							'name'      => 'icon_image_filters',
							'selector'  => '{{WRAPPER}} .testmonial__quote img',
							'condition' => [
								'icon_type' => ['image_icon']
							],
						]
					);
					$this->add_control(
						'icon_color',
						[
							'label'     => esc_html__( 'Color', 'shopready-elementor-addon' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .testmonial__quote' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Background:: get_type(),
						[
							'name'     => 'icon_background',
							'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .testmonial__quote',
						]
					);
					$this->add_control(
						'icon_hr2',
						[
							'type' => Controls_Manager::DIVIDER,
						]
					);
					$this->add_group_control(
						Group_Control_Border:: get_type(),
						[
							'name'     => 'icon_border',
							'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
							'selector' => '{{WRAPPER}} .testmonial__quote',
						]
					);
					$this->add_control(
						'icon_radius',
						[
							'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .testmonial__quote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow:: get_type(),
						[
							'name'     => 'icon_shadow',
							'selector' => '{{WRAPPER}} .testmonial__quote',
						]
					);
					$this->add_control(
						'icon_hr3',
						[
							'type' => Controls_Manager::DIVIDER,
						]
					);
					$this->add_control(
						'icon_width',
						[
							'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .testmonial__quote' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_control(
						'icon_height',
						[
							'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .testmonial__quote' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_control(
						'icon_hr5',
						[
							'type' => Controls_Manager::DIVIDER,
						]
					);
					$this->add_responsive_control(
						'icon_display',
						[
							'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',				
							'options' => [
								'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
								'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
								'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
								'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
								'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .testmonial__quote' => 'display: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'icon_align',
						[
							'label'   => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::CHOOSE,
							'options' => [
								'left' => [
									'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-left',
								],
								'center' => [
									'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-center',
								],
								'right' => [
									'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-right',
								],
								'justify' => [
									'title' => esc_html__( 'Justify', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-justify',
								],
							],
							'selectors' => [
								'{{WRAPPER}} .testmonial__quote' => 'text-align: {{VALUE}};',
							],
							'default' => '',
						]
					);
					$this->add_control(
						'icon_hr6',
						[
							'type' => Controls_Manager::DIVIDER,
						]
					);
					$this->add_responsive_control(
						'icon_position',
						[
							'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',				
							'options' => [
								'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'absolute' => esc_html__( 'Absulute', 'shopready-elementor-addon' ),
								'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
								'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .testmonial__quote' => 'position: {{VALUE}};',
							],
						]
					);
					$this->add_responsive_control(
						'icon_position_from_left',
						[
							'label'      => esc_html__( 'From Left', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .testmonial__quote' => 'left: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'icon_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'icon_position_from_right',
						[
							'label'      => esc_html__( 'From Right', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .testmonial__quote' => 'right: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'icon_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'icon_position_from_top',
						[
							'label'      => esc_html__( 'From Top', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .testmonial__quote' => 'top: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'icon_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'icon_position_from_bottom',
						[
							'label'      => esc_html__( 'From Bottom', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .testmonial__quote' => 'bottom: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'icon_position' => ['absolute','relative']
							],
						]
					);
					$this->add_control(
						'icon_transition',
						[
							'label'      => esc_html__( 'Transition', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
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
								'{{WRAPPER}} .testmonial__quote,{{WRAPPER}} .testmonial__quote img' => 'transition: {{SIZE}}s;',
							],
						]
					);
					$this->add_control(
						'icon_hr7',
						[
							'type' => Controls_Manager::DIVIDER,
						]
					);
					$this->add_responsive_control(
						'icon_margin',
						[
							'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .testmonial__quote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_control(
						'icon_hr8',
						[
							'type' => Controls_Manager::DIVIDER,
						]
					);
					$this->add_responsive_control(
						'icon_padding',
						[
							'label'      => esc_html__( 'Padding', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .testmonial__quote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
				$this->end_controls_tab();

				$this->start_controls_tab(
					'icon_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'shopready-elementor-addon' ),
					]
				);
					$this->add_group_control(
						Group_Control_Css_Filter:: get_type(),
						[
							'name'      => 'hover_icon_image_filters',
							'selector'  => '{{WRAPPER}} .single__testmonial:hover .testmonial__quote img',
							'condition' => [
								'icon_type' => ['image_icon']
							],
						]
					);
					$this->add_control(
						'hover_icon_color',
						[
							'label'     => esc_html__( 'Color', 'shopready-elementor-addon' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:hover .testmonial__quote, {{WRAPPER}} :focus .testmonial__quote' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Background:: get_type(),
						[
							'name'     => 'hover_icon_background',
							'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .single__testmonial:hover .testmonial__quote,{{WRAPPER}} :focus .testmonial__quote',
						]
					);
					$this->add_control(
						'icon_hr4',
						[
							'type' => Controls_Manager::DIVIDER,
						]
					);
					$this->add_group_control(
						Group_Control_Border:: get_type(),
						[
							'name'     => 'hover_icon_border',
							'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
							'selector' => '{{WRAPPER}} :hover .testmonial__quote,{{WRAPPER}} :hover .testmonial__quote',
						]
					);
					$this->add_control(
						'hover_icon_radius',
						[
							'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .single__testmonial:hover .testmonial__quote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow:: get_type(),
						[
							'name'     => 'hover_icon_shadow',
							'selector' => '{{WRAPPER}} .single__testmonial:hover .testmonial__quote',
						]
					);
					$this->add_control(
						'icon_hover_animation',
						[
							'label'    => esc_html__( 'Hover Animation', 'shopready-elementor-addon' ),
							'type'     => Controls_Manager::HOVER_ANIMATION,
							'selector' => '{{WRAPPER}} :hover .testmonial__quote',
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
		$this->end_controls_section();
		/*----------------------------
			ICON STYLE END
		-----------------------------*/

		/*----------------------------
			TITLE STYLE
		-----------------------------*/
		$this->start_controls_section(
			'title_style_section',
			[
				'label' => esc_html__( 'Title', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->start_controls_tabs( 'title_tab_style' );
				$this->start_controls_tab(
					'title_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'shopready-elementor-addon' ),
					]
				);
					$this->add_group_control(
						Group_Control_Typography:: get_type(),
						[
							'name'     => 'title_typography',
							'selector' => '{{WRAPPER}} .testmonial__title',
						]
					);
					$this->add_control(
						'title_text_color',
						[
							'label'     => esc_html__( 'Color', 'shopready-elementor-addon' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .testmonial__title, {{WRAPPER}} .testmonial__title a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_responsive_control(
						'title_margin',
						[
							'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .testmonial__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
				$this->end_controls_tab();
				$this->start_controls_tab(
					'title_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'shopready-elementor-addon' ),
					]
				);
					$this->add_control(
						'hover_title_color',
						[
							'label'     => esc_html__( 'Link Color', 'shopready-elementor-addon' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .testmonial__title a:hover, {{WRAPPER}} .testmonial__title a:focus' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'box_hover_title_color',
						[
							'label'     => esc_html__( 'Box Hover Color', 'shopready-elementor-addon' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} :hover .testmonial__title a, {{WRAPPER}} :focus .testmonial__title a, {{WRAPPER}} :hover .testmonial__title' => 'color: {{VALUE}};',
							],
						]
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();
		$this->end_controls_section();
		/*----------------------------
			TITLE STYLE END
		-----------------------------*/

		/*----------------------------
			SUBTITLE STYLE
		-----------------------------*/
		$this->start_controls_section(
			'subtitle_style_section',
			[
				'label' => esc_html__( 'Subtitle', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->add_group_control(
				Group_Control_Typography:: get_type(),
				[
					'name'     => 'subtitle_typography',
					'selector' => '{{WRAPPER}} .testmonial__subtitle',
				]
			);
			$this->add_control(
				'subtitle_color',
				[
					'label'  => esc_html__( 'Color', 'shopready-elementor-addon' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testmonial__subtitle' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'box_hover_subtitle_color',
				[
					'label'  => esc_html__( 'Box Hover Color', 'shopready-elementor-addon' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .single__testmonial:hover .testmonial__subtitle' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'subtitle_margin',
				[
					'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .testmonial__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		$this->end_controls_section();
		/*----------------------------
			SUBTITLE STYLE END
		-----------------------------*/

		/*----------------------------
			THUMB / DESI WARP STYLE
		-----------------------------*/
		$this->start_controls_section(
			'thumb_desi_warp_section',
			[
				'label' => esc_html__( 'Thumb & Designation Warp', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->add_responsive_control(
				'thumb_and_desi_width',
				[
					'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'{{WRAPPER}} .author__thumb__designation' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'thumb_and_desi_height',
				[
					'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'{{WRAPPER}} .author__thumb__designation' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background:: get_type(),
				[
					'name'     => 'thumb_and_desi_background',
					'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .author__thumb__designation',
				]
			);
			$this->add_group_control(
				Group_Control_Border:: get_type(),
				[
					'name'     => 'thumb_and_desi_border',
					'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
					'selector' => '{{WRAPPER}} .author__thumb__designation',
				]
			);
			$this->add_control(
				'thumb_and_desi_border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .author__thumb__designation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow:: get_type(),
				[
					'name'     => 'thumb_and_desi_shadow',
					'selector' => '{{WRAPPER}} .author__thumb__designation',
				]
			);
			$this->add_responsive_control(
				'thumb_and_desi_display',
				[
					'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
						'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
						'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
						'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
						'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
						'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
					],
					'selectors' => [
						'{{WRAPPER}} .author__thumb__designation' => 'display: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'thumb_and_desi_position',
				[
					'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					
					'options' => [
						'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
						'absolute' => esc_html__( 'Absulute', 'shopready-elementor-addon' ),
						'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
						'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
					],
					'selectors' => [
						'{{WRAPPER}} .author__thumb__designation' => 'position: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'thumb_and_desi_position_from_left',
				[
					'label'      => esc_html__( 'From Left', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'{{WRAPPER}} .author__thumb__designation' => 'left: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'thumb_and_desi_position' => ['absolute','relative']
					],
				]
			);
			$this->add_responsive_control(
				'thumb_and_desi_position_from_right',
				[
					'label'      => esc_html__( 'From Right', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'{{WRAPPER}} .author__thumb__designation' => 'right: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'thumb_and_desi_position' => ['absolute','relative']
					],
				]
			);
			$this->add_responsive_control(
				'thumb_and_desi_position_from_top',
				[
					'label'      => esc_html__( 'From Top', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'{{WRAPPER}} .author__thumb__designation' => 'top: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'thumb_and_desi_position' => ['absolute','relative']
					],
				]
			);
			$this->add_responsive_control(
				'thumb_and_desi_position_from_bottom',
				[
					'label'      => esc_html__( 'From Bottom', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
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
						'{{WRAPPER}} .author__thumb__designation' => 'bottom: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'thumb_and_desi_position' => ['absolute','relative']
					],
				]
			);
			$this->add_responsive_control(
				'thumb_and_desi_padding',
				[
					'label'      => esc_html__( 'Padding', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .author__thumb__designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'thumb_and_desi_margin',
				[
					'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .author__thumb__designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		$this->end_controls_section();
		/*----------------------------
			THUMB / DESI WARP STYLE END
		-----------------------------*/

		/*----------------------------
			THUMB STYLE
		-----------------------------*/
		$this->start_controls_section(
			'thumb_style_section',
			[
				'label' => esc_html__( 'Author Thumb', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->start_controls_tabs(
				'thumb_style_tab'
			);
				$this->start_controls_tab(
					'thum_image_warp_tab',
					[
						'label' => esc_html__( 'Tumb Warp', 'shopready-elementor-addon' ),
					]
				);
					$this->add_responsive_control(
						'thumb_width',
						[
							'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .author__thumb' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'thumb_height',
						[
							'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .author__thumb' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Background:: get_type(),
						[
							'name'     => 'thumb_background',
							'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .author__thumb',
						]
					);
					$this->add_group_control(
						Group_Control_Border:: get_type(),
						[
							'name'     => 'thumb_border',
							'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
							'selector' => '{{WRAPPER}} .author__thumb',
						]
					);
					$this->add_control(
						'thumb_border_radius',
						[
							'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .author__thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow:: get_type(),
						[
							'name'     => 'thumb_shadow',
							'selector' => '{{WRAPPER}} .author__thumb',
						]
					);
					$this->add_responsive_control(
						'thumb_display',
						[
							'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',
							'options' => [
								'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
								'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
								'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
								'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
								'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .author__thumb' => 'display: {{VALUE}};',
							],
						]
					);
					$this->add_responsive_control(
						'thumb_position',
						[
							'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',
							
							'options' => [
								'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'absolute' => esc_html__( 'Absulute', 'shopready-elementor-addon' ),
								'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
								'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .author__thumb' => 'position: {{VALUE}};',
							],
						]
					);
					$this->add_responsive_control(
						'thumb_position_from_left',
						[
							'label'      => esc_html__( 'From Left', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .author__thumb' => 'left: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'thumb_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'thumb_position_from_right',
						[
							'label'      => esc_html__( 'From Right', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .author__thumb' => 'right: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'thumb_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'thumb_position_from_top',
						[
							'label'      => esc_html__( 'From Top', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .author__thumb' => 'top: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'thumb_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'thumb_position_from_bottom',
						[
							'label'      => esc_html__( 'From Bottom', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .author__thumb' => 'bottom: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'thumb_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'thumb_padding',
						[
							'label'      => esc_html__( 'Padding', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .author__thumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'thumb_margin',
						[
							'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .author__thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
				$this->end_controls_tab();
				$this->start_controls_tab(
					'thumb_image_tab',
					[
						'label' => esc_html__( 'Thumb Image', 'shopready-elementor-addon' ),
					]
				);
					$this->add_group_control(
						Group_Control_Border:: get_type(),
						[
							'name'     => 'thumb_image_border',
							'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
							'selector' => '{{WRAPPER}} .author__thumb img',
						]
					);
					$this->add_control(
						'thumb_image_border_radius',
						[
							'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .author__thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow:: get_type(),
						[
							'name'     => 'thumb_image_shadow',
							'selector' => '{{WRAPPER}} .author__thumb img',
						]
					);
					$this->add_responsive_control(
						'thumb_image_width',
						[
							'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .author__thumb img' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'thumb_image_height',
						[
							'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .author__thumb img' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();
		$this->end_controls_section();
		/*----------------------------
			THUMB STYLE END
		-----------------------------*/
		$this->start_controls_section(
			'testimonila_pro_container_style_saction',
			[
				'label' => esc_html__( 'Testimonial Wrap', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'testmonial_style' => 'tesmonial_style_3',
				],
			]
		); 

		$this->add_responsive_control(
			'author__testimonial_style_z_index',
			[
				'label'      => esc_html__( 'Z-index', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => -100,
						'max'  => 1000,
						'step' => 1,
					],
					
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'z-index: {{SIZE}};',
				],
				
			]
		);

		$this->add_responsive_control(
			'author__testimonial_style_opacity',
			[
				'label'      => esc_html__( 'Opacity', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					],
					
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'opacity: {{SIZE}};',
				],
				
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'author__testimonial_style_background',
				'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content',
			]
		);

		$this->add_responsive_control(
			'author__testimonial_style_margin',
			[
				'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'author__testimonial_style_padding',
			[
				'label'      => esc_html__( 'Padding', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'author__testimonial_style_position',
			[
				'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
				'type'    => Controls_Manager::SELECT,				
				'options' => [
					'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
					'absolute' => esc_html__( 'Absolute', 'shopready-elementor-addon' ),
					'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
					'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
				],
				'selectors' => [
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'author__testimonial_style_position_from_left',
			[
				'label'      => esc_html__( 'From Left', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'author__testimonial_style_position!' => ['initial','static']
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'author__testimonial_style_position_from_right',
			[
				'label'      => esc_html__( 'From Right', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'author__testimonial_style_position!' => ['initial','static']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'author__testimonial_style_position_from_top',
			[
				'label'      => esc_html__( 'From Top', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'author__testimonial_style_position!' => ['initial','static']
				],
			]
		);

		// Postion From Bottom
		$this->add_responsive_control(
			'author__testimonial_style_position_from_bottom',
			[
				'label'      => esc_html__( 'From Bottom', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position!' => ['initial','static']
				],
			]
		);

		$this->end_controls_section();

		/*----------------------------
			BOX BEFORE / AFTER
		-----------------------------*/
		$this->start_controls_section(
			'testimonial_box__before_after_style_section',
			[
				'label' => esc_html__( 'Testimonial Before / After', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'testmonial_style' => 'tesmonial_style_3',
				],
			]
		);
			$this->start_controls_tabs( 'testimonial_box__before_after_tab_style' );
				$this->start_controls_tab(
					'testimonial_box__before_tab',
					[
						'label' => esc_html__( 'BEFORE', 'shopready-elementor-addon' ),
					]
				);
 

					$this->add_group_control(
						Group_Control_Background:: get_type(),
						[
							'name'     => 'testimonial_box__before_background',
							'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before',
						]
					);
					$this->add_responsive_control(
						'testimonial_box__before_display',
						[
							'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',
							'options' => [
								'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
								'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
								'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
								'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
								'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'display: {{VALUE}};content:" ";',
							],
						]
					);

					$this->add_responsive_control(
						'testimonial_box__before_position',
						[
							'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',				
							'options' => [
								'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'absolute' => esc_html__( 'Absulute', 'shopready-elementor-addon' ),
								'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
								'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'position: {{VALUE}};',
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__before_position_from_left',
						[
							'label'      => esc_html__( 'From Left', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'left: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'testimonial_box__before_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__before_position_from_right',
						[
							'label'      => esc_html__( 'From Right', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'right: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'testimonial_box__before_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__before_position_from_top',
						[
							'label'      => esc_html__( 'From Top', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'top: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'testimonial_box__before_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__before_position_from_bottom',
						[
							'label'      => esc_html__( 'From Bottom', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'bottom: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'testimonial_box__before_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__before_align',
						[
							'label'   => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::CHOOSE,
							'options' => [
								'text-align:left' => [
									'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-left',
								],
								'margin: 0 auto' => [
									'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-center',
								],
								'float:right' => [
									'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-right',
								],
								'text-align:justify' => [
									'title' => esc_html__( 'Justify', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-justify',
								],
							],
							'selectors' => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => '{{VALUE}};',
							],
							'default' => 'text-align:left',
						]
					);
					$this->add_responsive_control(
						'testimonial_box__before_width',
						[
							'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__before_height',
						[
							'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_control(
						'testimonial_box__before_opacity',
						[
							'label' => esc_html__( 'Opacity', 'shopready-elementor-addon' ),
							'type'  => Controls_Manager::SLIDER,
							'range' => [
								'px' => [
									'max'  => 1,
									'min'  => 0.10,
									'step' => 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'opacity: {{SIZE}};',
							],
						]
					);
					$this->add_control(
						'testimonial_box__before_zindex',
						[
							'label'     => esc_html__( 'Z-Index', 'shopready-elementor-addon' ),
							'type'      => Controls_Manager::NUMBER,
							'min'       => -99,
							'max'       => 99,
							'step'      => 1,
							'selectors' => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'z-index: {{SIZE}};',
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__before_margin',
						[
							'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__before_border_radius',
						[
							'label'      => esc_html__( 'Border  Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
				$this->end_controls_tab();
				$this->start_controls_tab(
					'testimonial_box__after_tab',
					[
						'label' => esc_html__( 'AFTER', 'shopready-elementor-addon' ),
					]
				);
                
					$this->add_group_control(
						Group_Control_Background:: get_type(),
						[
							'name'     => 'testimonial_box__after_background',
							'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after',
						]
					);
					$this->add_responsive_control(
						'testimonial_box__after_display',
						[
							'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',
							'options' => [
								'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
								'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
								'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
								'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
								'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'display: {{VALUE}};content:" ";',
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__after_position',
						[
							'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',
							
							'options' => [
								'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'absolute' => esc_html__( 'Absulute', 'shopready-elementor-addon' ),
								'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
								'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'position: {{VALUE}};',
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__after_position_from_left',
						[
							'label'      => esc_html__( 'From Left', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'left: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'testimonial_box__after_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__after_position_from_right',
						[
							'label'      => esc_html__( 'From Right', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'right: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'testimonial_box__after_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__after_position_from_top',
						[
							'label'      => esc_html__( 'From Top', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'top: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'testimonial_box__after_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__after_position_from_bottom',
						[
							'label'      => esc_html__( 'From Bottom', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'bottom: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'testimonial_box__after_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__after_align',
						[
							'label'   => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::CHOOSE,
							'options' => [
								'text-align:left' => [
									'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-left',
								],
								'margin: 0 auto' => [
									'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-center',
								],
								'float:right' => [
									'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-right',
								],
								'text-align:justify' => [
									'title' => esc_html__( 'Justify', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-justify',
								],
							],
							'selectors' => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => '{{VALUE}};',
							],
							'default' => 'text-align:left',
						]
					);
					$this->add_responsive_control(
						'testimonial_box__after_width',
						[
							'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__after_height',
						[
							'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_control(
						'testimonial_box__after_opacity',
						[
							'label' => esc_html__( 'Opacity', 'shopready-elementor-addon' ),
							'type'  => Controls_Manager::SLIDER,
							'range' => [
								'px' => [
									'max'  => 1,
									'min'  => 0.10,
									'step' => 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'opacity: {{SIZE}};',
							],
						]
					);
					$this->add_control(
						'testimonial_box__after_zindex',
						[
							'label'     => esc_html__( 'Z-Index', 'shopready-elementor-addon' ),
							'type'      => Controls_Manager::NUMBER,
							'min'       => -99,
							'max'       => 99,
							'step'      => 1,
							'selectors' => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'z-index: {{SIZE}};',
							],
						]
					);
					$this->add_responsive_control(
						'testimonial_box__after_margin',
						[
							'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'testimonial_box__after_border__radius',
						[
							'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

				$this->end_controls_tab();
			$this->end_controls_tabs();
		$this->end_controls_section();
		/*----------------------------
			BOX BEFORE / AFTER END
		-----------------------------*/
		/*----------------------------
			DESCRIPTION STYLE
		-----------------------------*/
		$this->start_controls_section(
			'description_style_section',
			[
				'label' => esc_html__( 'Description', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->add_group_control(
				Group_Control_Typography:: get_type(),
				[
					'name'     => 'description_typography',
					'selector' => '{{WRAPPER}} .testmonial__description',
				]
			);
			$this->add_control(
				'description_color',
				[
					'label'  => esc_html__( 'Color', 'shopready-elementor-addon' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testmonial__description' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'description_bg__color',
				[
					'label'  => esc_html__( 'Background', 'shopready-elementor-addon' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .testmonial__description' => 'background: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'box_hover_description_color',
				[
					'label'  => esc_html__( 'Box Hover Color', 'shopready-elementor-addon' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .single__testmonial:hover .testmonial__description' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'description_margin',
				[
					'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .testmonial__description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		$this->end_controls_section();
		/*----------------------------
			DESCRIPTION STYLE END
		-----------------------------*/

		/*----------------------------
			NAME STYLE
		-----------------------------*/
		$this->start_controls_section(
			'name_style_section',
			[
				'label' => esc_html__( 'Name', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->add_group_control(
				Group_Control_Typography:: get_type(),
				[
					'name'     => 'name_typography',
					'selector' => '{{WRAPPER}} .author__name',
				]
			);
			$this->add_control(
				'name_color',
				[
					'label'  => esc_html__( 'Color', 'shopready-elementor-addon' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .author__name' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'box_hover_name_color',
				[
					'label'  => esc_html__( 'Box Hover Color', 'shopready-elementor-addon' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .single__testmonial:hover .author__name' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'name_display',
				[
					'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
						'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
						'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
						'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
						'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
						'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
					],
					'selectors' => [
						'{{WRAPPER}} .author__name' => 'display: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'name_margin',
				[
					'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .author__name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => esc_html__( 'Designation Or Company', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->add_group_control(
				Group_Control_Typography:: get_type(),
				[
					'name'     => 'designation_typography',
					'selector' => '{{WRAPPER}} .author__designation',
				]
			);
			$this->add_control(
				'designation_color',
				[
					'label'  => esc_html__( 'Color', 'shopready-elementor-addon' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .author__designation' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'box_hover_designation_color',
				[
					'label'  => esc_html__( 'Box Hover Color', 'shopready-elementor-addon' ),
					'type'   => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .single__testmonial:hover .author__designation' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'designation_display',
				[
					'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
						'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
						'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
						'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
						'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
						'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
					],
					'selectors' => [
						'{{WRAPPER}} .author__designation' => 'display: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'designation_margin',
				[
					'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors'  => [
						'{{WRAPPER}} .author__designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		$this->end_controls_section();
		/*----------------------------
			DESIGNATION STYLE END
		-----------------------------*/

		/*----------------------------
			BOX STYLE
		-----------------------------*/
		$this->start_controls_section(
			'box_style_section',
			[
				'label' => esc_html__( 'Box', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->start_controls_tabs('box_style_tabs');
				$this->start_controls_tab(
					'box_style_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'shopready-elementor-addon' ),
					]
				);  

					$this->add_responsive_control(
						'box_style_tabs_display',
						[
							'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,			
							'options' => [
								'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
								'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
								'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
								'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
								'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial' => 'display: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'_section___box_style_flex_direction_display',
						[
							'label' => esc_html__( 'Flex Direction', 'shopready-elementor-addon' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'default' => '',
							'options' => [
								'column'         => esc_html__( 'Column', 'shopready-elementor-addon' ),
								'row'            => esc_html__( 'Row', 'shopready-elementor-addon' ),
								'column-reverse' => esc_html__( 'Column Reverse', 'shopready-elementor-addon' ),
								'row-reverse'    => esc_html__( 'Row Reverse', 'shopready-elementor-addon' ),
								'revert'         => esc_html__( 'Revert', 'shopready-elementor-addon' ),
								'none'           => esc_html__( 'None', 'shopready-elementor-addon' ),
								''               => esc_html__( 'inherit', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial' => 'flex-direction: {{VALUE}};'
							],
							'condition' => [ 'box_style_tabs_display' => ['flex','inline-flex'] ]
						]
						
					);
		
					$this->add_responsive_control(
						'_section_box_align_section_e__flex_align',
						[
							'label' => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'default' => '',
							'options' => [
								'flex-start'    => esc_html__( 'Left', 'shopready-elementor-addon' ),
								'flex-end'      => esc_html__( 'Right', 'shopready-elementor-addon' ),
								'center'        => esc_html__( 'Center', 'shopready-elementor-addon' ),
								'space-around'  => esc_html__( 'Space Around', 'shopready-elementor-addon' ),
								'space-between' => esc_html__( 'Space Between', 'shopready-elementor-addon' ),
								'space-evenly'  => esc_html__( 'Space Evenly', 'shopready-elementor-addon' ),
								''              => esc_html__( 'inherit', 'shopready-elementor-addon' ),
							],
							'condition' => [ 'box_style_tabs_display' => ['flex','inline-flex'] ],
		
							'selectors' => [
								'{{WRAPPER}} .single__testmonial' => 'justify-content: {{VALUE}};'
						   ],
						]
						
					);
		
					$this->add_responsive_control(
						'_section_box_align_items_section_e__flex_align',
						[
							'label' => esc_html__( 'Align Items', 'shopready-elementor-addon' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'default' => '',
							'options' => [

								'flex-start' => esc_html__( 'Left', 'shopready-elementor-addon' ),
								'flex-end'   => esc_html__( 'Right', 'shopready-elementor-addon' ),
								'center'     => esc_html__( 'Center', 'shopready-elementor-addon' ),
								'baseline'   => esc_html__( 'Baseline', 'shopready-elementor-addon' ),
								''           => esc_html__( 'inherit', 'shopready-elementor-addon' ),
							],
							'condition' => [ 'box_style_tabs_display' => ['flex','inline-flex'] ],
		
							'selectors' => [
								'{{WRAPPER}} .single__testmonial' => 'align-items: {{VALUE}};'
						   ],
						]
						
					);
		

					$this->add_group_control(
						Group_Control_Typography:: get_type(),
						[
							'name'     => 'typography',
							'selector' => '{{WRAPPER}} .single__testmonial',
						]
					);
					$this->add_control(
						'box_color',
						[
							'label'  => esc_html__( 'Color', 'shopready-elementor-addon' ),
							'type'   => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .single__testmonial' => 'color: {{VALUE}}',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Background:: get_type(),
						[
							'name'     => 'box_background',
							'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .single__testmonial',
						]
					);
					$this->add_group_control(
						Group_Control_Border:: get_type(),
						[
							'name'     => 'box_border',
							'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
							'selector' => '{{WRAPPER}} .single__testmonial',
						]
					);
					$this->add_control(
						'box_radius',
						[
							'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .single__testmonial' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow:: get_type(),
						[
							'name'     => 'box_box_shadow',
							'selector' => '{{WRAPPER}} .single__testmonial',
						]
					);
					$this->add_responsive_control(
						'box_align',
						[
							'label'   => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::CHOOSE,
							'options' => [
								'left' => [
									'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-left',
								],
								'center' => [
									'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-center',
								],
								'right' => [
									'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-right',
								],
								'justify' => [
									'title' => esc_html__( 'Justify', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-justify',
								],
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial' => 'text-align: {{VALUE}};',
							],
							'default' => '',
						]
					);
					$this->add_control(
						'box_transition',
						[
							'label'      => esc_html__( 'Transition', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
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
								'{{WRAPPER}} .single__testmonial' => 'transition: {{SIZE}}s;',
							],
						]
					);
					$this->add_responsive_control(
						'box_position',
						[
							'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => 'initial',
							
							'options' => [
								'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'absolute' => esc_html__( 'Absulute', 'shopready-elementor-addon' ),
								'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
								'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial' => 'position: {{VALUE}};',
							],
						]
					);
					$this->add_responsive_control(
						'box_padding',
						[
							'label'      => esc_html__( 'Padding', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .single__testmonial' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'box_margin',
						[
							'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .single__testmonial' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
				$this->end_controls_tab();
				$this->start_controls_tab(
					'box_style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'plugin-name' ),
					]
				);
					$this->add_control(
						'hover_box_color',
						[
							'label'  => esc_html__( 'Box Hover Color', 'shopready-elementor-addon' ),
							'type'   => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:hover' => 'color: {{VALUE}}',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Background:: get_type(),
						[
							'name'     => 'hover_box_background',
							'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .single__testmonial:hover',
						]
					);
					$this->add_group_control(
						Group_Control_Border:: get_type(),
						[
							'name'     => 'hover_box_border',
							'label'    => esc_html__( 'Border', 'shopready-elementor-addon' ),
							'selector' => '{{WRAPPER}} .single__testmonial:hover',
						]
					);
					$this->add_control(
						'hover_box_radius',
						[
							'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .single__testmonial:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow:: get_type(),
						[
							'name'     => 'hover_box_box_shadow',
							'selector' => '{{WRAPPER}} .single__testmonial:hover',
						]
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();
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
				'label' => esc_html__( 'Before / After', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->start_controls_tabs( 'box_before_after_tab_style' );
				$this->start_controls_tab(
					'box_before_tab',
					[
						'label' => esc_html__( 'BEFORE', 'shopready-elementor-addon' ),
					]
				);
					$this->add_group_control(
						Group_Control_Background:: get_type(),
						[
							'name'     => 'box_before_background',
							'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .single__testmonial:before',
						]
					);
					$this->add_responsive_control(
						'box_before_display',
						[
							'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',
							'options' => [
								'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
								'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
								'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
								'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
								'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:before' => 'display: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'box_before_position',
						[
							'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',				
							'options' => [
								'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'absolute' => esc_html__( 'Absulute', 'shopready-elementor-addon' ),
								'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
								'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:before' => 'position: {{VALUE}};',
							],
						]
					);
					$this->add_responsive_control(
						'box_before_position_from_left',
						[
							'label'      => esc_html__( 'From Left', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:before' => 'left: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'box_before_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'box_before_position_from_right',
						[
							'label'      => esc_html__( 'From Right', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:before' => 'right: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'box_before_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'box_before_position_from_top',
						[
							'label'      => esc_html__( 'From Top', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:before' => 'top: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'box_before_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'box_before_position_from_bottom',
						[
							'label'      => esc_html__( 'From Bottom', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:before' => 'bottom: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'box_before_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'box_before_align',
						[
							'label'   => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::CHOOSE,
							'options' => [
								'text-align:left' => [
									'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-left',
								],
								'margin: 0 auto' => [
									'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-center',
								],
								'float:right' => [
									'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-right',
								],
								'text-align:justify' => [
									'title' => esc_html__( 'Justify', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-justify',
								],
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:before' => '{{VALUE}};',
							],
							'default' => 'text-align:left',
						]
					);
					$this->add_responsive_control(
						'box_before_width',
						[
							'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:before' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'box_before_height',
						[
							'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:before' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_control(
						'box_before_opacity',
						[
							'label' => esc_html__( 'Opacity', 'shopready-elementor-addon' ),
							'type'  => Controls_Manager::SLIDER,
							'range' => [
								'px' => [
									'max'  => 1,
									'min'  => 0.10,
									'step' => 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:before' => 'opacity: {{SIZE}};',
							],
						]
					);
					$this->add_control(
						'box_before_zindex',
						[
							'label'     => esc_html__( 'Z-Index', 'shopready-elementor-addon' ),
							'type'      => Controls_Manager::NUMBER,
							'min'       => -99,
							'max'       => 99,
							'step'      => 1,
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:before' => 'z-index: {{SIZE}};',
							],
						]
					);
					$this->add_responsive_control(
						'box_before_margin',
						[
							'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .single__testmonial:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'box_before_border_radius',
						[
							'label'      => esc_html__( 'Border  Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .single__testmonial:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
				$this->end_controls_tab();
				$this->start_controls_tab(
					'box_after_tab',
					[
						'label' => esc_html__( 'AFTER', 'shopready-elementor-addon' ),
					]
				);
					$this->add_group_control(
						Group_Control_Background:: get_type(),
						[
							'name'     => 'box_after_background',
							'label'    => esc_html__( 'Background', 'shopready-elementor-addon' ),
							'types'    => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .single__testmonial:after',
						]
					);
					$this->add_responsive_control(
						'box_after_display',
						[
							'label'   => esc_html__( 'Display', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',
							'options' => [
								'initial'      => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'block'        => esc_html__( 'Block', 'shopready-elementor-addon' ),
								'inline-block' => esc_html__( 'Inline Block', 'shopready-elementor-addon' ),
								'flex'         => esc_html__( 'Flex', 'shopready-elementor-addon' ),
								'inline-flex'  => esc_html__( 'Inline Flex', 'shopready-elementor-addon' ),
								'none'         => esc_html__( 'none', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:after' => 'display: {{VALUE}};',
							],
						]
					);
					$this->add_responsive_control(
						'box_after_position',
						[
							'label'   => esc_html__( 'Position', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::SELECT,
							'default' => '',
							
							'options' => [
								'initial'  => esc_html__( 'Initial', 'shopready-elementor-addon' ),
								'absolute' => esc_html__( 'Absulute', 'shopready-elementor-addon' ),
								'relative' => esc_html__( 'Relative', 'shopready-elementor-addon' ),
								'static'   => esc_html__( 'Static', 'shopready-elementor-addon' ),
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:after' => 'position: {{VALUE}};',
							],
						]
					);
					$this->add_responsive_control(
						'box_after_position_from_left',
						[
							'label'      => esc_html__( 'From Left', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:after' => 'left: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'box_after_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'box_after_position_from_right',
						[
							'label'      => esc_html__( 'From Right', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:after' => 'right: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'box_after_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'box_after_position_from_top',
						[
							'label'      => esc_html__( 'From Top', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:after' => 'top: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'box_after_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'box_after_position_from_bottom',
						[
							'label'      => esc_html__( 'From Bottom', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:after' => 'bottom: {{SIZE}}{{UNIT}};',
							],
							'condition' => [
								'box_after_position' => ['absolute','relative']
							],
						]
					);
					$this->add_responsive_control(
						'box_after_align',
						[
							'label'   => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
							'type'    => Controls_Manager::CHOOSE,
							'options' => [
								'text-align:left' => [
									'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-left',
								],
								'margin: 0 auto' => [
									'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-center',
								],
								'float:right' => [
									'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-right',
								],
								'text-align:justify' => [
									'title' => esc_html__( 'Justify', 'shopready-elementor-addon' ),
									'icon'  => 'fa fa-align-justify',
								],
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:after' => '{{VALUE}};',
							],
							'default' => 'text-align:left',
						]
					);
					$this->add_responsive_control(
						'box_after_width',
						[
							'label'      => esc_html__( 'Width', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:after' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_responsive_control(
						'box_after_height',
						[
							'label'      => esc_html__( 'Height', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
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
								'{{WRAPPER}} .single__testmonial:after' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);
					$this->add_control(
						'box_after_opacity',
						[
							'label' => esc_html__( 'Opacity', 'shopready-elementor-addon' ),
							'type'  => Controls_Manager::SLIDER,
							'range' => [
								'px' => [
									'max'  => 1,
									'min'  => 0.10,
									'step' => 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:after' => 'opacity: {{SIZE}};',
							],
						]
					);
					$this->add_control(
						'box_after_zindex',
						[
							'label'     => esc_html__( 'Z-Index', 'shopready-elementor-addon' ),
							'type'      => Controls_Manager::NUMBER,
							'min'       => -99,
							'max'       => 99,
							'step'      => 1,
							'selectors' => [
								'{{WRAPPER}} .single__testmonial:after' => 'z-index: {{SIZE}};',
							],
						]
					);
					$this->add_responsive_control(
						'box_after_margin',
						[
							'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .single__testmonial:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'box_after_border__radius',
						[
							'label'      => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors'  => [
								'{{WRAPPER}} .single__testmonial:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	
	protected function html() {

		$settings = $this->get_settings_for_display();
      
		// Icon Condition
		if ( 'yes' == $settings['show_icon'] ) {
			if ( 'font_icon' == $settings['icon_type'] && !empty( $settings['font_icon'] ) ) {
				$icon = '<div class="testmonial__quote">'.shop_ready_render_icons( $settings['font_icon'] ).'</div>';
			}elseif( 'image_icon' == $settings['icon_type'] && !empty( $settings['image_icon'] ) ){
				$icon_array = $settings['image_icon'];
				$icon_link = wp_get_attachment_image_url( $icon_array['id'], 'thumbnail' );
				$icon = '<div class="testmonial__quote"><img src="'. esc_url( $icon_link ) .'" alt="" /></div>';
			}
		}else{
			$icon = '';
		}

		// Title Tag
		if ( !empty( $settings['title_tag'] ) ) {
			$title_tag = $settings['title_tag'];
		}else{
			$title_tag = 'h3';
		}

		// Title
		if ( !empty( $settings['title'] ) ) {
			$title = '<'.$title_tag.' class="testmonial__title">'.esc_html( $settings['title'] ).'</'.$title_tag.'>';
		}else{
			$title = '';
		}

		// Subtitle
		if ( !empty( $settings['subtitle'] ) ) {
			$subtitle = '<div class="testmonial__subtitle">'.esc_html( $settings['subtitle'] ).'</div>';
		}else{
			$subtitle = '';
		}

		// Member Thumb
		if ( !empty( $settings['testmonial_content']['member_thumb'] ) ) {
			$thumb_array = $settings['testmonial_content']['member_thumb'];
			$thumb_link = wp_get_attachment_image_url( $icon_array['id'], 'thumbnail' );
			$author_thumb = '<div class="author__thumb"><img src="'. esc_url( $thumb_link ) .'" alt="" /></div>';
		}

		// Member Name
		if ( !empty( $settings['testmonial_content']['member_name'] ) ) {
			$author_name = '<h4 class="author__name">'.esc_html( $settings['testmonial_content']['member_name'] ).'</h4>';
		}else{
			$author_name = '';
		}

		// Description
		if ( !empty( $settings['testmonial_content']['description'] ) ) {
			$description = '<div class="testmonial__description">'.wpautop( $settings['testmonial_content']['description'] ).'</div>';
		}

		// Designation
		if ( !empty( $settings['testmonial_content']['designation'] ) ) {
			$designation = '<p class="author__designation">'.wpautop( $settings['testmonial_content']['designation'] ).'</p>';
		}

		if ( !empty($settings['subtitle_position']) ) {
			// Title Condition
			if ( 'before_title' == $settings['subtitle_position'] ) {
				$title_subtitle = $subtitle . $title;
			}elseif( 'after_title' == $settings['subtitle_position'] ){
				$title_subtitle = $title . $subtitle;
			}elseif( empty($settings['subtitle']) ){
				$title_subtitle = $title . $subtitle;
			}
		}else{
			$title_subtitle = $title . $subtitle;
		}

		/*----------------------------
		CONTENT WITH FOR LOOP
		------------------------------*/
		$all_testmonial = '';
		for ($i=0; $i <= count($settings['testmonial_content']) ; $i++) {
			// Member Thumb
			if ( !empty( $settings['testmonial_content'][$i]['member_thumb'] ) ) {
				$thumb_array = $settings['testmonial_content'][$i]['member_thumb'];
				$thumb_link = wp_get_attachment_image_url( $thumb_array['id'], 'thumbnail' );
				$author_thumb = '<div class="author__thumb"><img src="'. esc_url( $thumb_link ) .'" alt="" /></div>';
				$all_testmonial .= $author_thumb;
			}

			// Member Name
			if ( !empty( $settings['testmonial_content'][$i]['member_name'] ) ) {
				$author_name = '<h4 class="author__name">'.esc_html( $settings['testmonial_content'][$i]['member_name'] ).'</h4>';
				$all_testmonial .= $author_name;
			}

			// Description
			if ( !empty( $settings['testmonial_content'][$i]['description'] ) ) {
				$description = '<div class="testmonial__description">'.wpautop( $settings['testmonial_content'][$i]['description'] ).'</div>';
				$all_testmonial .= $description;
			}

			// Designation
			if ( !empty( $settings['testmonial_content'][$i]['designation'] ) ) {
				$designation = '<p class="author__designation">'.wpautop( $settings['testmonial_content'][$i]['designation'] ).'</p>';
				$all_testmonial .= $designation;
			}
		}
	

		/*-----------------------------
			CONTENT WITH FOREACH LOOP
		------------------------------*/
	$testmonial_content = '';
	$testimonial_tumb_contnet = '';
	if ($settings['testmonial_content']) {
		if ( 'tesmonial_style_10' == $settings['testmonial_style'] || 'tesmonial_style_11' == $settings['testmonial_style'] || 'tesmonial_style_12' == $settings['testmonial_style'] ) {
			foreach( $settings['testmonial_content'] as $single_testmonial ){

				$testmonial_content .='
					<div class="single__testmonial">';

					if ( !empty( $single_testmonial['member_thumb'] ) ) {

						$thumb_array = $single_testmonial['member_thumb'];
						$thumb_link  = wp_get_attachment_image_url( $thumb_array['id'], 'thumbnail' );
						$thumb_link  = Group_Control_Image_Size::get_attachment_image_src( $thumb_array['id'], 'member_thumb_size', $single_testmonial );
						if ( !empty( $thumb_link ) ) {
							$testmonial_content .='<div class="author__thumb"><img src="'. esc_url( $thumb_link ) .'" alt="'.esc_attr( get_the_title() ).'" /></div>';
						}else{
							$testmonial_content .='<div class="author__thumb"><img src="'. esc_url( $single_testmonial['member_thumb']['url'] ) .'" alt="" /></div>';
						}
					}
					if( !empty( $single_testmonial['description'] ) ){

						$testmonial_content .='
					    <div class="author__content">';
							$testmonial_content .='<div class="testmonial__description">'.wpautop( $single_testmonial['description'] ).'</div>';
						$testmonial_content .='
						</div>';
					}
					if( !empty( $single_testmonial['member_name'] ) ){

						$testmonial_content .='
						<div class="author__thumb__designation__warp">
							<div class="author__thumb__designation">';

							if ( !empty( $icon ) ) {
							    $testmonial_content .=$icon;
							}
							if( !empty( $single_testmonial['member_name'] ) ){
								$testmonial_content .='
								<h4 class="author__name">'.esc_html( $single_testmonial['member_name'] ).'</h4>';
							}
							if( !empty( $single_testmonial['designation'] ) ){
								$testmonial_content .='
								<p class="author__designation">'.esc_html( $single_testmonial['designation'] ).'</p>';
							}

							$testmonial_content .='
							</div>
						</div>';
					}
				$testmonial_content .='
				</div>';
			}
		}elseif( 'tesmonial_style_13' == $settings['testmonial_style'] ){

			foreach( $settings['testmonial_content'] as $single_testmonial ){
				if ( !empty( $single_testmonial['member_thumb'] ) ) {

					$thumb_array = $single_testmonial['member_thumb'];
					$thumb_link  = wp_get_attachment_image_url( $thumb_array['id'], 'thumbnail' );
					$thumb_link  = Group_Control_Image_Size::get_attachment_image_src( $thumb_array['id'], 'member_thumb_size', $single_testmonial );
					if ( !empty( $thumb_link ) ) {
						$testimonial_tumb_contnet .='<div class="author__thumb"><img src="'. esc_url( $thumb_link ) .'" alt="'.esc_attr( get_the_title() ).'" /></div>';
					}else{
						$testimonial_tumb_contnet .='<div class="author__thumb"><img src="'. esc_url( $single_testmonial['member_thumb']['url'] ) .'" alt="" /></div>';
					}
				}


				$testmonial_content .='
					<div class="single__testmonial">';

					if ( !empty( $icon ) ) {
					    $testmonial_content .=$icon;
					}
					if( !empty( $single_testmonial['description'] ) ){

						$testmonial_content .='
					    <div class="author__content">';
							$testmonial_content .='<div class="testmonial__description">'.wpautop( $single_testmonial['description'] ).'</div>';
						$testmonial_content .='
						</div>';
					}
					if( !empty( $single_testmonial['member_name'] ) ){

						$testmonial_content .='
						<div class="author__thumb__designation__warp">
							<div class="author__thumb__designation">';

							if( !empty( $single_testmonial['member_name'] ) ){
								$testmonial_content .='
								<h4 class="author__name">'.esc_html( $single_testmonial['member_name'] ).'</h4>';
							}
							if( !empty( $single_testmonial['designation'] ) ){
								$testmonial_content .='
								<p class="author__designation">'.esc_html( $single_testmonial['designation'] ).'</p>';
							}

							$testmonial_content .='
							</div>
						</div>';
					}
				$testmonial_content .='
				</div>';
			}
		}elseif( 'tesmonial_style_14' == $settings['testmonial_style'] ){
			
			foreach( $settings['testmonial_content'] as $single_testmonial ){

				$testmonial_content .='
					<div class="single__testmonial">';
$testmonial_content .='<div class="wr-col no-padding">';
					if ( !empty( $icon ) ) {
					    $testmonial_content .=$icon;
					}
					if( !empty( $single_testmonial['description'] ) ){

						$testmonial_content .='
					    <div class="author__content">';
							$testmonial_content .='<div class="testmonial__description">'.wpautop( $single_testmonial['description'] ).'</div>';
						$testmonial_content .='
						</div>';
					}
					if( !empty( $single_testmonial['member_name'] ) ){

						$testmonial_content .='
						<div class="author__thumb__designation__warp">
							<div class="author__thumb__designation">';

							if( !empty( $single_testmonial['member_name'] ) ){
								$testmonial_content .='
								<h4 class="author__name">'.esc_html( $single_testmonial['member_name'] ).'</h4>';
							}
							if( !empty( $single_testmonial['designation'] ) ){
								$testmonial_content .='
								<p class="author__designation">'.esc_html( $single_testmonial['designation'] ).'</p>';
							}

							$testmonial_content .='
							</div>
						</div>';
					}
$testmonial_content .='</div>';

$testmonial_content .='<div class="wr-col no-padding">';
					if ( !empty( $single_testmonial['member_thumb'] ) ) {

						$thumb_array = $single_testmonial['member_thumb'];
						$thumb_link  = wp_get_attachment_image_url( $thumb_array['id'], 'thumbnail' );
						$thumb_link  = Group_Control_Image_Size::get_attachment_image_src( $thumb_array['id'], 'member_thumb_size', $single_testmonial );
						if ( !empty( $thumb_link ) ) {
							$testmonial_content .='<div class="author__thumb"><img src="'. esc_url( $thumb_link ) .'" alt="'.esc_attr( get_the_title() ).'" /></div>';
						}else{
							$testmonial_content .='<div class="author__thumb"><img src="'. esc_url( $single_testmonial['member_thumb']['url'] ) .'" alt="" /></div>';
						}
					}
$testmonial_content .='</div>';

				$testmonial_content .='
				</div>';
			}

		}elseif( 'tesmonial_style_15' == $settings['testmonial_style'] ){
			foreach( $settings['testmonial_content'] as $single_testmonial ){

				$testmonial_content .='
					<div class="single__testmonial">';

$testmonial_content .='<div class="wr-col no-padding">';
					if ( !empty( $single_testmonial['member_thumb'] ) ) {

						$thumb_array = $single_testmonial['member_thumb'];
						$thumb_link  = wp_get_attachment_image_url( $thumb_array['id'], 'thumbnail' );
						$thumb_link  = Group_Control_Image_Size::get_attachment_image_src( $thumb_array['id'], 'member_thumb_size', $single_testmonial );
						if ( !empty( $thumb_link ) ) {
							$testmonial_content .='<div class="author__thumb__warp"><div class="author__thumb"><img src="'. esc_url( $thumb_link ) .'" alt="'.esc_attr( get_the_title() ).'" /></div></div>';
						}else{
							$testmonial_content .='<div class="author__thumb__warp"><div class="author__thumb"><img src="'. esc_url( $single_testmonial['member_thumb']['url'] ) .'" alt="" /></div></div>';
						}
					}
$testmonial_content .='</div>';

$testmonial_content .='<div class="wr-col no-padding">';
					if ( !empty( $icon ) ) {
					    $testmonial_content .=$icon;
					}
					if( !empty( $single_testmonial['description'] ) ){

						$testmonial_content .='
					    <div class="author__content">';
							$testmonial_content .='<div class="testmonial__description">'.wpautop( $single_testmonial['description'] ).'</div>';
						$testmonial_content .='
						</div>';
					}
					if( !empty( $single_testmonial['member_name'] ) ){

						$testmonial_content .='
						<div class="author__thumb__designation__warp">
							<div class="author__thumb__designation">';

							if( !empty( $single_testmonial['member_name'] ) ){
								$testmonial_content .='
								<h4 class="author__name">'.esc_html( $single_testmonial['member_name'] ).'</h4>';
							}
							if( !empty( $single_testmonial['designation'] ) ){
								$testmonial_content .='
								<p class="author__designation">'.esc_html( $single_testmonial['designation'] ).'</p>';
							}

							$testmonial_content .='
							</div>
						</div>';
					}
$testmonial_content .='</div>';

				$testmonial_content .='
				</div>';
			}
		}else{
			foreach( $settings['testmonial_content'] as $single_testmonial ){

				$testmonial_content .='
					<div class="single__testmonial">';
					if( !empty( $single_testmonial['description'] ) ){
						$testmonial_content .='
					    <div class="author__content">';
						    if ( !empty( $icon ) ) {
						        $testmonial_content .=$icon;
						    }
							$testmonial_content .='<div class="testmonial__description">'.wpautop( $single_testmonial['description'] ).'</div>';
						$testmonial_content .='
						</div>';
					}
					if( !empty( $single_testmonial['member_thumb'] ) || !empty( $single_testmonial['member_name'] ) ){

						if( !empty( $single_testmonial['member_thumb'] ) ){

							$testmonial_content .='
							<div class="author__thumb__designation__warp">
								<div class="author__thumb__designation">';
								if ( !empty( $single_testmonial['member_thumb'] ) ) {
									$thumb_array = $single_testmonial['member_thumb'];
									$thumb_link  = wp_get_attachment_image_url( $thumb_array['id'], 'thumbnail' );
									$thumb_link  = Group_Control_Image_Size::get_attachment_image_src( $thumb_array['id'], 'member_thumb_size', $single_testmonial );
									if ( !empty( $thumb_link ) ) {
										$testmonial_content .='<div class="author__thumb"><img src="'. esc_url( $thumb_link ) .'" alt="'.esc_attr( get_the_title() ).'" /></div>';
									}else{
										$testmonial_content .='<div class="author__thumb"><img src="'. esc_url( $single_testmonial['member_thumb']['url'] ) .'" alt="" /></div>';
									}
								}

								if( !empty( $single_testmonial['member_name'] ) ){
									$testmonial_content .='
									<h4 class="author__name">'.esc_html( $single_testmonial['member_name'] ).'</h4>';
								}
								if( !empty( $single_testmonial['designation'] ) ){
									$testmonial_content .='
									<p class="author__designation">'.esc_html( $single_testmonial['designation'] ).'</p>';
								}
								$testmonial_content .='
								</div>
							</div>';
					    }
					}
				$testmonial_content .='
				</div>';
			}
		}
	}
		// Slider Attr
		$this->add_render_attribute( 'testmonial_carousel_attr', 'class', 'woo-ready-testmonial-carousel' );
		if ( count( $settings['testmonial_content'] ) > 1 ) {
			$this->add_render_attribute( 'testmonial_carousel_attr', 'class', 'woo-ready-carousel-active owl-carousel' );

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
				'nav'               => ( 'true' == $settings['nav'] ) ? true : false,
				'nav_position'      => $settings['nav_position'],
				'next_icon'         => $settings['next_icon'],
				'prev_icon'         => $settings['prev_icon'],
				'dots'              => ( 'true' == $settings['dots'] ) ? true : false,
				'loop'              => ( 'true' == $settings['loop'] ) ? true : false,
				'hover_pause'       => ( 'true' == $settings['hover_pause'] ) ? true : false,
				'center'            => ( 'true' == $settings['center'] ) ? true : false,
				'rtl'               => ( 'true' == $settings['rtl'] ) ? true : false,
			];

			$this->add_render_attribute( 'testmonial_carousel_attr', 'data-settings', wp_json_encode( $options ) );
		}else{
			$this->add_render_attribute( 'testmonial_carousel_attr', 'class', 'testmonial-grid' );
		}

		// Parent Attr.
		$this->add_render_attribute('sldier_parent_attr','class','sldier-content-area');
		$this->add_render_attribute('sldier_parent_attr','class',$settings['testmonial_style']);
		$this->add_render_attribute('sldier_parent_attr','class',$settings['nav_position']);
	?>

<?php if ( 'tesmonial_style_13' == $settings['testmonial_style'] ): ?>
<div class="testmonial__thumb__content_area">
    <div class="testmonial__thumb__content">
        <div class="testmonial__thumb__content__slider">
            <?php echo  isset( $testimonial_tumb_contnet ) ? wp_kses_post($testimonial_tumb_contnet): ''; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div <?php echo wp_kses_post($this->get_render_attribute_string('sldier_parent_attr')); ?>>
    <div <?php echo wp_kses_post($this->get_render_attribute_string('testmonial_carousel_attr')); ?>>
        <?php echo  isset( $testmonial_content ) ? wp_kses_post($testmonial_content  ): ''; ?>
    </div>
</div>

<?php

	}

	protected function content_template() {}

}