<?php

namespace Element_Ready\Widgets\testimonial;

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

class Element_Ready_Testmonial_Widget extends Widget_Base
{

	public function get_name()
	{
		return 'Element_Ready_Testmonial_Widget';
	}

	public function get_title()
	{
		return esc_html__('ER Testmonial', 'element-ready-lite');
	}

	public function get_icon()
	{
		return 'eicon-testimonial';
	}

	public function get_categories()
	{
		return array('element-ready-addons');
	}

	public function get_keywords()
	{
		return ['Testimonial', 'Testimonial Slider', 'Testimonial', 'Review', 'Feedback'];
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

		wp_register_style('eready-testimonial', ELEMENT_READY_ROOT_CSS . 'widgets/testimonial.css');

		return [
			'owl-carousel', 'eready-testimonial'
		];
	}

	public static function testmonial_style()
	{
		return apply_filters('element_ready_testimonial_style_presets', [
			'tesmonial_style_1'          => 'Testmonial Style 1',
			'tesmonial_style_2'          => 'Testmonial Style 2',
			'tesmonial_style_3'          => 'Testmonial Style 3',
			'pro_tesmonial_style_4'      => 'Testmonial Style 4 - PRO',
			'pro_tesmonial_style_5'      => 'Testmonial Style 5 - PRO',
			'pro_tesmonial_style_6'      => 'Testmonial Style 6 - PRO',
			'pro_tesmonial_style_7'      => 'Testmonial Style 7 - PRO',
			'pro_tesmonial_style_8'      => 'Testmonial Style 8 - PRO',
			'pro_tesmonial_style_9'      => 'Testmonial Style 9 - PRO',
			'pro_tesmonial_style_10'     => 'Testmonial Style 10 - PRO',
			'pro_tesmonial_style_11'     => 'Testmonial Style 11 - PRO',
			'pro_tesmonial_style_12'     => 'Testmonial Style 12 - PRO',
			'pro_tesmonial_style_13'     => 'Upcomming Testmonial Style 13 - PRO',
			'pro_tesmonial_style_14'     => 'Upcomming Testmonial Style 14 - PRO',
			'pro_tesmonial_style_15'     => 'Upcomming Testmonial Style 15 - PRO',

			'pro_tesmonial_custom_style' => 'Upcomming Testmonial Custom Style - PRO',
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
			'testmonial_style',
			[
				'label'   => esc_html__('Testmonial Type', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'tesmonial_style_1',
				'options' => self::testmonial_style(),
			]
		);

		// Icon Toggle
		$this->add_control(
			'show_icon',
			[
				'label'        => esc_html__('Show Quotation Icon ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'element-ready-lite'),
				'label_off'    => esc_html__('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		// Icon Type
		$this->add_control(
			'icon_type',
			[
				'label'   => esc_html__('Icon Type', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'font_icon',
				'options' => [
					'font_icon'  => esc_html__('SVG / Font Icon', 'element-ready-lite'),
					'image_icon' => esc_html__('Image Icon', 'element-ready-lite'),
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
				'label'     => esc_html__('SVG / Font Icons', 'element-ready-lite'),
				'type'      => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'fas fa-quote-left',
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
				'label'   => esc_html__('Image Icon', 'element-ready-lite'),
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

		/** Right quote Icon */

		$this->add_control(
			'show_right_icon',
			[
				'label'        => esc_html__('Show Right Quotation Icon ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'element-ready-lite'),
				'label_off'    => esc_html__('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		// Icon Type
		$this->add_control(
			'icon_right_type',
			[
				'label'   => esc_html__('Right Icon Type', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'font_icon',
				'options' => [
					'font_icon'  => esc_html__('SVG / Font Icon', 'element-ready-lite'),
					'image_icon' => esc_html__('Image Icon', 'element-ready-lite'),
				],
				'condition' => [
					'show_right_icon' => 'yes',
				],
			]
		);

		// Font Icon
		$this->add_control(
			'right_font_icon',
			[
				'label'     => esc_html__('Right SVG / Font Icons', 'element-ready-lite'),
				'type'      => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'fas fa-quote-right',
					'library' => 'solid',
				],
				'condition' => [
					'icon_right_type' => 'font_icon',
					'show_right_icon' => 'yes',
				],
			]
		);

		// Image Icon
		$this->add_control(
			'right_image_icon',
			[
				'label'   => esc_html__('Image Icon', 'element-ready-lite'),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'icon_right_type' => 'image_icon',
					'show_right_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'rating_html_position',
			[
				'label'   => esc_html__('Rating Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'after_deg',
				'options' => [
					'top_right'  => esc_html__('Top Right', 'element-ready-lite'),
					'after_deg' => esc_html__('After Designation', 'element-ready-lite'),
				],

			]
		);

		/** End quote */

		$repeater = new Repeater();

		// Title
		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__('Title', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Title', 'element-ready-lite'),
			]
		);

		// Title Tag
		$repeater->add_control(
			'title_tag',
			[
				'label'   => esc_html__('Title HTML Tag', 'elementor'),
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
				'label'       => esc_html__('Sub Title', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Subtitle', 'element-ready-lite'),
			]
		);

		// Subtitle Position
		$repeater->add_control(
			'subtitle_position',
			[
				'label'   => esc_html__('Subtitle Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'after_title',
				'options' => [
					'before_title' => esc_html__('Before title', 'element-ready-lite'),
					'after_title'  => esc_html__('After Title', 'element-ready-lite'),
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
				'label'       => esc_html__('Testmonial Author Thumb', 'element-ready-lite'),
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
				'label'       => esc_html__('Testmonial Author Name', 'element-ready-lite'),
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

		$repeater->add_control(
			'client_rating',
			[
				'label' => esc_html__('Rating Count', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.5,
					],

				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],

			]
		);

		// Description
		$repeater->add_control(
			'description',
			[
				'label'       => esc_html__('Description', 'element-ready-lite'),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__('Description.', 'element-ready-lite'),
			]
		);

		$this->add_control(
			'testmonial_content',
			[
				'label' => esc_html__('Testmonial Items', 'element-ready-lite'),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'member_name' => esc_html__('M Hasan', 'element-ready-lite'),
						'designation' => esc_html__('Web Developer'),
						'description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga quos pariatur tempore nihil quisquam tempora odio et mollitia. Ea facere expedita beatae nesciunt vero aliquam similique eius veritatis unde eligendi.', 'element-ready-lite'),
						'client_rating' => 0,
					],
					[
						'member_name' => esc_html__('M Hasan', 'element-ready-lite'),
						'designation' => esc_html__('Web Developer'),
						'description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga quos pariatur tempore nihil quisquam tempora odio et mollitia. Ea facere expedita beatae nesciunt vero aliquam similique eius veritatis unde eligendi.', 'element-ready-lite'),
						'client_rating' => 0,
					],
					[
						'member_name' => esc_html__('M Hasan', 'element-ready-lite'),
						'designation' => esc_html__('Web Developer'),
						'description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga quos pariatur tempore nihil quisquam tempora odio et mollitia. Ea facere expedita beatae nesciunt vero aliquam similique eius veritatis unde eligendi.', 'element-ready-lite'),
						'client_rating' => 0,
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
				'label'     => esc_html__('Slider Options', 'element-ready-lite'),
				'tab'       => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'item_on_large',
			[
				'label' => esc_html__('Item In large Device', 'element-ready-lite'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
				'label' => esc_html__('Item In Medium Device', 'element-ready-lite'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
				'label' => esc_html__('Item In Tablet Device', 'element-ready-lite'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
				'label' => esc_html__('Item In Mobile Device', 'element-ready-lite'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
				'label' => esc_html__('Stage Padding', 'element-ready-lite'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
				'label' => esc_html__('Item Margin', 'element-ready-lite'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
				'label'   => esc_html__('Slide Autoplay', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'true',
				'options' => [
					'true'  => esc_html__('Yes', 'element-ready-lite'),
					'false' => esc_html__('No', 'element-ready-lite'),
				],
			]
		);
		$this->add_control(
			'autoplaytimeout',
			[
				'label' => esc_html__('Autoplay Timeout', 'element-ready-lite'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
				'label' => esc_html__('Slide Speed', 'element-ready-lite'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
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
				'label'   => esc_html__('Slide Animation', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes' => esc_html__('Yes', 'element-ready-lite'),
					'no'      => esc_html__('No', 'element-ready-lite'),
				],
			]
		);
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
		$this->add_control(
			'nav_position',
			[
				'label'   => esc_html__('Navigation Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'outside_vertical_center_nav',
				'options' => [
					'inside_vertical_center_nav'  => esc_html__('Inside Vertical Center', 'element-ready-lite'),
					'outside_vertical_center_nav' => esc_html__('Outside Vertical Center', 'element-ready-lite'),
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
		$this->add_control(
			'next_icon',
			[
				'label'     => esc_html__('Nav Next Icon', 'element-ready-lite'),
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
				'label'     => esc_html__('Nav Prev Icon', 'element-ready-lite'),
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
				'label'   => esc_html__('Slide Dots', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'false',
				'options' => [
					'true'  => esc_html__('Yes', 'element-ready-lite'),
					'false' => esc_html__('No', 'element-ready-lite'),
				],
			]
		);
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
				'label' => esc_html__('Slider Nav Warp', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'slider_nav_warp_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'slider_nav_warp_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
			]
		);
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
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'slider_nav_warp_shadow',
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
			]
		);
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
		$this->add_responsive_control(
			'slider_nav_warp_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
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
				'label' => esc_html__('Slider Nav Button', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->start_controls_tabs('slide_button_tab_style');
		$this->start_controls_tab(
			'slide_button_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
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
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .sldier-content-area .owl-nav > div' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'slide_button_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
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
			Group_Control_Border::get_type(),
			[
				'name'     => 'slide_button_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div',
			]
		);
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
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
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
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);
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
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_slide_button_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
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
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_slide_button_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav > div:hover',
			]
		);
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
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
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

		$this->add_responsive_control(
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
		$this->add_control(
			'slide_button_hr5',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
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
		$this->add_control(
			'slide_button_hr6',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
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
		$this->start_controls_tabs('slide_button_item_tab_style');
		$this->start_controls_tab(
			'slide_button_left_nav_tab',
			[
				'label' => esc_html__('Left Button', 'element-ready-lite'),
			]
		);
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
					'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slide_button_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-prev' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slide_button_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-next' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slide_button_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-next' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slide_button_position' => ['absolute', 'relative']
				],
			]
		);
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
		$this->add_control(
			'slide_button_hr7',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
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
		$this->add_control(
			'slide_button_hr8',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
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
		$this->add_responsive_control(
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
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'slide_dots_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'slide_dots_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div',
			]
		);
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
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
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
		$this->end_controls_tab();
		$this->start_controls_tab(
			'slide_dots_hover_tab',
			[
				'label' => esc_html__('Hover & Active', 'element-ready-lite'),
			]
		);
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
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_slide_dots_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_slide_dots_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .sldier-content-area .owl-dots > div:hover,{{WRAPPER}} .sldier-content-area .owl-dots > div.active',
			]
		);
		$this->add_responsive_control(
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
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
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
				'label' => esc_html__('Thumbs Slider', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'testmonial_style' => 'tesmonial_style_13',
				],
			]
		);
		$this->add_responsive_control(
			'thumbs_slider_width',
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
					'{{WRAPPER}} .testmonial__thumb__content' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'thumbs_slider_height',
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
					'{{WRAPPER}} .testmonial__thumb__content' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'thumbs_slider_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .testmonial__thumb__content',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'thumbs_slider_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .testmonial__thumb__content',
			]
		);
		$this->add_responsive_control(
			'thumbs_slider_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .testmonial__thumb__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'thumbs_slider_shadow',
				'selector' => '{{WRAPPER}} .testmonial__thumb__content',
			]
		);
		$this->add_responsive_control(
			'thumbs_slider_display',
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
					'{{WRAPPER}} .testmonial__thumb__content' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'thumbs_slider_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .testmonial__thumb__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'thumbs_slider_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .testmonial__thumb__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'thumbs_slider_align',
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
				'label' => esc_html__('Left Quote Icon', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs('icon_tab_style');
		$this->start_controls_tab(
			'icon_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
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
				'label'      => esc_html__('SVG / Image Icon Size', 'element-ready-lite'),
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
					'{{WRAPPER}} .testmonial__quote img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .testmonial__quote svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
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
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .testmonial__quote' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
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
			Group_Control_Border::get_type(),
			[
				'name'     => 'icon_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .testmonial__quote',
			]
		);
		$this->add_responsive_control(
			'icon_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .testmonial__quote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
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
		$this->add_responsive_control(
			'icon_responsive_width',
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
					'{{WRAPPER}} .testmonial__quote' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_responsive_height',
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
					'{{WRAPPER}} .testmonial__quote' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_responsive_align',
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
					'{{WRAPPER}} .testmonial__quote' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_position_from_left',
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
					'{{WRAPPER}} .testmonial__quote' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'icon_position_from_right',
			[
				'label'      => esc_html__('From Right', 'element-ready-lite'),
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
					'{{WRAPPER}} .testmonial__quote' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'icon_position_from_top',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
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
					'{{WRAPPER}} .testmonial__quote' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'icon_position_from_bottom',
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
					'{{WRAPPER}} .testmonial__quote' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => ['absolute', 'relative']
				],
			]
		);
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
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
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
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .testmonial__quote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
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
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__testmonial:hover .testmonial__quote, {{WRAPPER}} :focus .testmonial__quote' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_icon_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
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
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_icon_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} :hover .testmonial__quote,{{WRAPPER}} :hover .testmonial__quote',
			]
		);
		$this->add_control(
			'hover_icon_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__testmonial:hover .testmonial__quote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_icon_shadow',
				'selector' => '{{WRAPPER}} .single__testmonial:hover .testmonial__quote',
			]
		);
		$this->add_control(
			'icon_hover_animation',
			[
				'label'    => esc_html__('Hover Animation', 'element-ready-lite'),
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
			ICON STYLE
		-----------------------------*/
		$this->start_controls_section(
			'right_icon_style_section',
			[
				'label' => esc_html__('Right Quote Icon', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs('right_icon_tab_style');
		$this->start_controls_tab(
			'icon_right_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'icon_right_typography',
				'selector'  => '{{WRAPPER}} .testmonial__quote__er__qright',
				'condition' => [
					'icon_right_type' => ['font_icon']
				],
			]
		);
		$this->add_responsive_control(
			'icon_right_image_size',
			[
				'label'      => esc_html__('SVG / Image Icon Size', 'element-ready-lite'),
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
					'{{WRAPPER}} .testmonial__quote__er__qright img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .testmonial__quote__er__qright svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'right_icon_image_filters',
				'selector'  => '{{WRAPPER}} .testmonial__quote img',
				'condition' => [
					'icon_right_type' => ['image_icon']
				],
			]
		);
		$this->add_control(
			'icon_right_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_right_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .testmonial__quote__er__qright',
			]
		);
		$this->add_control(
			'icon_right_hr2',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'icon_right_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .testmonial__quote__er__qright',
			]
		);
		$this->add_responsive_control(
			'icon_right_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_right_shadow',
				'selector' => '{{WRAPPER}} .testmonial__quote__er__qright',
			]
		);
		$this->add_control(
			'icon_right_hr3',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_responsive_control(
			'icon_right_width',
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
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_right_height',
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
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_right_hr5',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_responsive_control(
			'icon_right_display',
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
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'icon_right_align',
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
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'text-align: {{VALUE}};',
				],
				'default' => '',
			]
		);
		$this->add_control(
			'icon_right_hr6',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_responsive_control(
			'icon_right_position',
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
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_right_position_from_left',
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
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_right_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'icon_right_position_from_right',
			[
				'label'      => esc_html__('From Right', 'element-ready-lite'),
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
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_right_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'icon_right_position_from_top',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
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
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_right_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'icon_right_position_from_bottom',
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
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_control(
			'icon_right_transition',
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
					'{{WRAPPER}} .testmonial__quote__er__qright,{{WRAPPER}} .testmonial__quote__er__qright img' => 'transition: {{SIZE}}s;',
				],
			]
		);
		$this->add_control(
			'icon_right_hr7',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'icon_right_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_rigt_hr8',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_responsive_control(
			'icon_right_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .testmonial__quote__er__qright' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_right_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'hover_right_icon_image_filters',
				'selector'  => '{{WRAPPER}} .single__testmonial:hover .testmonial__quote__er__qright img',
				'condition' => [
					'icon_right_type' => ['image_icon']
				],
			]
		);
		$this->add_control(
			'hover_right_icon_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__testmonial:hover .testmonial__quote__er__qright, {{WRAPPER}} :focus .testmonial__quote__er__qright' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_riight_icon_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__testmonial:hover .testmonial__quote__er__qright,{{WRAPPER}} :focus .testmonial__quote__er__qright',
			]
		);
		$this->add_control(
			'icon_right_hr4',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_right_icon_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} :hover .testmonial__quote__er__qright',
			]
		);
		$this->add_control(
			'hover_right_icon_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__testmonial:hover .testmonial__quote__er__qright' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => '_right_hover_icon_shadow',
				'selector' => '{{WRAPPER}} .single__testmonial:hover .testmonial__quote__er__qright',
			]
		);
		$this->add_control(
			'right_icon_hover_animation',
			[
				'label'    => esc_html__('Hover Animation', 'element-ready-lite'),
				'type'     => Controls_Manager::HOVER_ANIMATION,
				'selector' => '{{WRAPPER}} :hover .testmonial__quote__er__qright',
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
				'label' => esc_html__('Title', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs('title_tab_style');
		$this->start_controls_tab(
			'title_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .testmonial__title',
			]
		);
		$this->add_control(
			'title_text_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
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
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .testmonial__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'title_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);
		$this->add_control(
			'hover_title_color',
			[
				'label'     => esc_html__('Link Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .testmonial__title a:hover, {{WRAPPER}} .testmonial__title a:focus' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'box_hover_title_color',
			[
				'label'     => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .testmonial__title a, {{WRAPPER}} :focus .testmonial__title a, {{WRAPPER}} :hover .testmonial__title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'rating_icon_style_section',
			[
				'label' => esc_html__('Rating Icon', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);



		$this->add_responsive_control(
			'ad_wrap_item_horizontal_align',
			[
				'label'   => esc_html__('Hotizontal Align', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [

					'flex-start'    => esc_html__('Left', 'element-ready-lite'),
					'flex-end'      => esc_html__('Right', 'element-ready-lite'),
					'center'        => esc_html__('Center', 'element-ready-lite'),

				],
				'selectors' => [
					'{{WRAPPER}} .er-rating-testmoni' => 'justify-content: {{VALUE}};',
				],

			]
		);

		$this->add_responsive_control(
			'rating_wrap_flex_gap',
			[
				'label'      => esc_html__('Gap', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					],

				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .er-rating-testmoni' => 'gap: {{SIZE}}{{UNIT}};',
				],

			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'rating_style_typography',
				'selector' => '{{WRAPPER}} .er-rating-testmoni span',
			]
		);

		$this->add_control(
			'rating__text_color',
			[
				'label'     => esc_html__('Active Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .er-rating-testmoni span.checked' => 'color: {{VALUE}};',
				],
			]
		);



		$this->add_control(
			'rating_normal_text_color',
			[
				'label'     => esc_html__('Normal Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .er-rating-testmoni span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_style_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .er-rating-testmoni' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_style_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .er-rating-testmoni' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_wrap_position',
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
					'{{WRAPPER}} .er-rating-testmoni' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'rating_wrap_position_from_left',
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
						'min' => -50,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .er-rating-testmoni' => 'left: {{SIZE}}{{UNIT}};',
				],

			]
		);

		$this->add_responsive_control(
			'rating_wrap_position_from_top',
			[
				'label'      => esc_html__('From Top', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => -500,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -50,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .er-rating-testmoni' => 'top: {{SIZE}}{{UNIT}};',
				],

			]
		);




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
				'label' => esc_html__('Subtitle', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .testmonial__subtitle',
			]
		);
		$this->add_control(
			'subtitle_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .testmonial__subtitle' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'box_hover_subtitle_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__testmonial:hover .testmonial__subtitle' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
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
				'label' => esc_html__('Thumb & Designation Warp', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'thumb_and_desi_width',
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
					'{{WRAPPER}} .author__thumb__designation' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'thumb_and_desi_height',
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
					'{{WRAPPER}} .author__thumb__designation' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'thumb_and_desi_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .author__thumb__designation',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'thumb_and_desi_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .author__thumb__designation',
			]
		);
		$this->add_control(
			'thumb_and_desi_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .author__thumb__designation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'thumb_and_desi_shadow',
				'selector' => '{{WRAPPER}} .author__thumb__designation',
			]
		);
		$this->add_responsive_control(
			'thumb_and_desi_display',
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
					'{{WRAPPER}} .author__thumb__designation' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'thumb_and_desi_position',
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
					'{{WRAPPER}} .author__thumb__designation' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'thumb_and_desi_position_from_left',
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
					'{{WRAPPER}} .author__thumb__designation' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_and_desi_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'thumb_and_desi_position_from_right',
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
					'{{WRAPPER}} .author__thumb__designation' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_and_desi_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'thumb_and_desi_position_from_top',
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
					'{{WRAPPER}} .author__thumb__designation' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_and_desi_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'thumb_and_desi_position_from_bottom',
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
					'{{WRAPPER}} .author__thumb__designation' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_and_desi_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'thumb_and_desi_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .author__thumb__designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'thumb_and_desi_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
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
					'{{WRAPPER}} .author__thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
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
					'{{WRAPPER}} .author__thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'thumb_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .author__thumb',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'thumb_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .author__thumb',
			]
		);
		$this->add_control(
			'thumb_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .author__thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'thumb_shadow',
				'selector' => '{{WRAPPER}} .author__thumb',
			]
		);
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
					'{{WRAPPER}} .author__thumb' => 'display: {{VALUE}};',
				],
			]
		);
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
					'{{WRAPPER}} .author__thumb' => 'position: {{VALUE}};',
				],
			]
		);
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
					'{{WRAPPER}} .author__thumb' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .author__thumb' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .author__thumb' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .author__thumb' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumb_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'thumb_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .author__thumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'thumb_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .author__thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'thumb_image_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .author__thumb img',
			]
		);
		$this->add_control(
			'thumb_image_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .author__thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'thumb_image_shadow',
				'selector' => '{{WRAPPER}} .author__thumb img',
			]
		);
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
					'{{WRAPPER}} .author__thumb img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
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
				'label' => esc_html__('Testimonial Wrap', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'testmonial_style' => 'tesmonial_style_3',
				],
			]
		);

		$this->add_responsive_control(
			'author__testimonial_style_z_index',
			[
				'label'      => esc_html__('Z-index', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
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
				'label'      => esc_html__('Opacity', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
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
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content',
			]
		);

		$this->add_responsive_control(
			'author__testimonial_style_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'author__testimonial_style_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'author__testimonial_style_position',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'author__testimonial_style_position_from_left',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'author__testimonial_style_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'author__testimonial_style_position_from_right',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'author__testimonial_style_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'author__testimonial_style_position_from_top',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'author__testimonial_style_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Bottom
		$this->add_responsive_control(
			'author__testimonial_style_position_from_bottom',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position!' => ['initial', 'static']
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
				'label' => esc_html__('Testimonial Before / After', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'testmonial_style' => 'tesmonial_style_3',
				],
			]
		);
		$this->start_controls_tabs('testimonial_box__before_after_tab_style');
		$this->start_controls_tab(
			'testimonial_box__before_tab',
			[
				'label' => esc_html__('BEFORE', 'element-ready-lite'),
			]
		);


		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'testimonial_box__before_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before',
			]
		);
		$this->add_responsive_control(
			'testimonial_box__before_display',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'display: {{VALUE}};content:" ";',
				],
			]
		);

		$this->add_responsive_control(
			'testimonial_box__before_position',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__before_position_from_left',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'testimonial_box__before_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__before_position_from_right',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'testimonial_box__before_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__before_position_from_top',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'testimonial_box__before_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__before_position_from_bottom',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'testimonial_box__before_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__before_align',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);
		$this->add_responsive_control(
			'testimonial_box__before_width',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__before_height',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'testimonial_box__before_opacity',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_control(
			'testimonial_box__before_zindex',
			[
				'label'     => esc_html__('Z-Index', 'element-ready-lite'),
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
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__before_border_radius',
			[
				'label'      => esc_html__('Border  Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'testimonial_box__after_tab',
			[
				'label' => esc_html__('AFTER', 'element-ready-lite'),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'testimonial_box__after_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after',
			]
		);
		$this->add_responsive_control(
			'testimonial_box__after_display',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'display: {{VALUE}};content:" ";',
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__after_position',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__after_position_from_left',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'testimonial_box__after_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__after_position_from_right',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'testimonial_box__after_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__after_position_from_top',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'testimonial_box__after_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__after_position_from_bottom',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'testimonial_box__after_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__after_align',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);
		$this->add_responsive_control(
			'testimonial_box__after_width',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'testimonial_box__after_height',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'testimonial_box__after_opacity',
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
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_control(
			'testimonial_box__after_zindex',
			[
				'label'     => esc_html__('Z-Index', 'element-ready-lite'),
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
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .tesmonial_style_3 .single__testmonial .author__content::after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'testimonial_box__after_border__radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
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
				'label' => esc_html__('Description', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .testmonial__description',
			]
		);
		$this->add_control(
			'description_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .testmonial__description' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'description_bg__color',
			[
				'label'  => esc_html__('Background', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .testmonial__description' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_hover_description_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__testmonial:hover .testmonial__description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
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
				'label' => esc_html__('Name', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .author__name',
			]
		);
		$this->add_control(
			'name_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .author__name' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'box_hover_name_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__testmonial:hover .author__name' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'name_display',
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
					'{{WRAPPER}} .author__name' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'name_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
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
				'label' => esc_html__('Designation Or Company', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'designation_typography',
				'selector' => '{{WRAPPER}} .author__designation',
			]
		);
		$this->add_control(
			'designation_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .author__designation' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'box_hover_designation_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__testmonial:hover .author__designation' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'designation_display',
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
					'{{WRAPPER}} .author__designation' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'designation_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
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
				'label' => esc_html__('Box', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs('box_style_tabs');
		$this->start_controls_tab(
			'box_style_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);

		$this->add_responsive_control(
			'box_style_tabs_display',
			[
				'label'   => esc_html__('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'initial'      => esc_html__('Initial', 'element-ready-lite'),
					'block'        => esc_html__('Block', 'element-ready-lite'),
					'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
					'flex'         => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'none'         => esc_html__('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .single__testmonial' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'_section___box_style_flex_direction_display',
			[
				'label' => esc_html__('Flex Direction', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'column'         => esc_html__('Column', 'element-ready-lite'),
					'row'            => esc_html__('Row', 'element-ready-lite'),
					'column-reverse' => esc_html__('Column Reverse', 'element-ready-lite'),
					'row-reverse'    => esc_html__('Row Reverse', 'element-ready-lite'),
					'revert'         => esc_html__('Revert', 'element-ready-lite'),
					'none'           => esc_html__('None', 'element-ready-lite'),
					''               => esc_html__('inherit', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .single__testmonial' => 'flex-direction: {{VALUE}};'
				],
				'condition' => ['box_style_tabs_display' => ['flex', 'inline-flex']]
			]

		);

		$this->add_responsive_control(
			'_section_box_align_section_e__flex_align',
			[
				'label' => esc_html__('Alignment', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::SELECT,
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
				'condition' => ['box_style_tabs_display' => ['flex', 'inline-flex']],

				'selectors' => [
					'{{WRAPPER}} .single__testmonial' => 'justify-content: {{VALUE}};'
				],
			]

		);

		$this->add_responsive_control(
			'_section_box_align_items_section_e__flex_align',
			[
				'label' => esc_html__('Align Items', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [

					'flex-start' => esc_html__('Left', 'element-ready-lite'),
					'flex-end'   => esc_html__('Right', 'element-ready-lite'),
					'center'     => esc_html__('Center', 'element-ready-lite'),
					'baseline'   => esc_html__('Baseline', 'element-ready-lite'),
					''           => esc_html__('inherit', 'element-ready-lite'),
				],
				'condition' => ['box_style_tabs_display' => ['flex', 'inline-flex']],

				'selectors' => [
					'{{WRAPPER}} .single__testmonial' => 'align-items: {{VALUE}};'
				],
			]

		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .single__testmonial',
			]
		);
		$this->add_control(
			'box_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__testmonial' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'box_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__testmonial',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'box_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .single__testmonial',
			]
		);
		$this->add_control(
			'box_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__testmonial' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_box_shadow',
				'selector' => '{{WRAPPER}} .single__testmonial',
			]
		);
		$this->add_responsive_control(
			'box_align',
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
					'{{WRAPPER}} .single__testmonial' => 'text-align: {{VALUE}};',
				],
				'default' => '',
			]
		);
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
					'{{WRAPPER}} .single__testmonial' => 'transition: {{SIZE}}s;',
				],
			]
		);
		$this->add_responsive_control(
			'box_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'initial',

				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .single__testmonial' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'box_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__testmonial' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'box_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__testmonial' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'box_style_hover_tab',
			[
				'label' => esc_html__('Hover', 'plugin-name'),
			]
		);
		$this->add_control(
			'hover_box_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__testmonial:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_box_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__testmonial:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_box_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .single__testmonial:hover',
			]
		);
		$this->add_control(
			'hover_box_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__testmonial:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
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
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'box_before_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__testmonial:before',
			]
		);
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
					'{{WRAPPER}} .single__testmonial:before' => 'display: {{VALUE}};',
				],
			]
		);

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
					'{{WRAPPER}} .single__testmonial:before' => 'position: {{VALUE}};',
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:before' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_before_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:before' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_before_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:before' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_before_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:before' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_before_position' => ['absolute', 'relative']
				],
			]
		);
		$this->add_responsive_control(
			'box_before_align',
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
					'{{WRAPPER}} .single__testmonial:before' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);
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
					'{{WRAPPER}} .single__testmonial:before' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:before' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_control(
			'box_before_zindex',
			[
				'label'     => esc_html__('Z-Index', 'element-ready-lite'),
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
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__testmonial:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'box_before_border_radius',
			[
				'label'      => esc_html__('Border  Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__testmonial:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'box_after_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__testmonial:after',
			]
		);
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
					'{{WRAPPER}} .single__testmonial:after' => 'display: {{VALUE}};',
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:after' => 'position: {{VALUE}};',
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:after' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_after_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:after' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_after_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:after' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_after_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:after' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'box_after_position' => ['absolute', 'relative']
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:after' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);
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
					'{{WRAPPER}} .single__testmonial:after' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:after' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
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
					'{{WRAPPER}} .single__testmonial:after' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_control(
			'box_after_zindex',
			[
				'label'     => esc_html__('Z-Index', 'element-ready-lite'),
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
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__testmonial:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_after_border__radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
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

	protected function render()
	{

		$settings = $this->get_settings_for_display();
		$rating_html_position = $settings['rating_html_position'];

		// Icon Condition
		if ('yes' == $settings['show_icon']) {
			if ('font_icon' == $settings['icon_type'] && !empty($settings['font_icon'])) {
				$icon = '<div class="testmonial__quote">' . element_ready_render_icons($settings['font_icon']) . '</div>';
			} elseif ('image_icon' == $settings['icon_type'] && !empty($settings['image_icon'])) {
				$icon_array = $settings['image_icon'];
				$icon_link = wp_get_attachment_image_url($icon_array['id'], 'thumbnail');
				$icon = '<div class="testmonial__quote"><img src="' . esc_url($icon_link) . '" alt="" /></div>';
			}
		} else {
			$icon = '';
		}

		if ('yes' == $settings['show_right_icon']) {
			if ('font_icon' == $settings['icon_right_type'] && !empty($settings['right_font_icon'])) {
				$right_icon = '<div class="testmonial__quote__er__qright">' . element_ready_render_icons($settings['right_font_icon']) . '</div>';
			} elseif ('image_icon' == $settings['icon_right_type'] && !empty($settings['right_image_icon'])) {
				$icon_array = $settings['right_image_icon'];
				$icon_link = wp_get_attachment_image_url($icon_array['id'], 'thumbnail');
				$right_icon = '<div class="testmonial__quote__er__qright"><img src="' . esc_url($icon_link) . '" alt="" /></div>';
			}
		} else {
			$right_icon = '';
		}

		// Title Tag
		if (!empty($settings['title_tag'])) {
			$title_tag = $settings['title_tag'];
		} else {
			$title_tag = 'h3';
		}

		// Title
		if (!empty($settings['title'])) {
			$title = '<' . $title_tag . ' class="testmonial__title">' . esc_html($settings['title']) . '</' . $title_tag . '>';
		} else {
			$title = '';
		}

		// Subtitle
		if (!empty($settings['subtitle'])) {
			$subtitle = '<div class="testmonial__subtitle">' . esc_html($settings['subtitle']) . '</div>';
		} else {
			$subtitle = '';
		}

		// Member Thumb
		if (!empty($settings['testmonial_content']['member_thumb'])) {
			$thumb_array = $settings['testmonial_content']['member_thumb'];
			$thumb_link = wp_get_attachment_image_url($icon_array['id'], 'thumbnail');
			$author_thumb = '<div class="author__thumb"><img src="' . esc_url($thumb_link) . '" alt="" /></div>';
		}

		// Member Name
		if (!empty($settings['testmonial_content']['member_name'])) {
			$author_name = '<h4 class="author__name">' . esc_html($settings['testmonial_content']['member_name']) . '</h4>';
		} else {
			$author_name = '';
		}

		// Description
		if (!empty($settings['testmonial_content']['description'])) {
			$description = '<div class="testmonial__description">' . wpautop($settings['testmonial_content']['description']) . '</div>';
		}

		// Designation
		if (!empty($settings['testmonial_content']['designation'])) {
			$designation = '<p class="author__designation">' . wpautop($settings['testmonial_content']['designation']) . '</p>';
		}

		if (!empty($settings['subtitle_position'])) {
			// Title Condition
			if ('before_title' == $settings['subtitle_position']) {
				$title_subtitle = $subtitle . $title;
			} elseif ('after_title' == $settings['subtitle_position']) {
				$title_subtitle = $title . $subtitle;
			} elseif (empty($settings['subtitle'])) {
				$title_subtitle = $title . $subtitle;
			}
		} else {
			$title_subtitle = $title . $subtitle;
		}

		/*----------------------------
		CONTENT WITH FOR LOOP
		------------------------------*/
		$all_testmonial = '';
		for ($i = 0; $i <= count($settings['testmonial_content']); $i++) {
			// Member Thumb
			if (!empty($settings['testmonial_content'][$i]['member_thumb'])) {
				$thumb_array = $settings['testmonial_content'][$i]['member_thumb'];
				$thumb_link = wp_get_attachment_image_url($thumb_array['id'], 'thumbnail');
				$author_thumb = '<div class="author__thumb"><img src="' . esc_url($thumb_link) . '" alt="" /></div>';
				$all_testmonial .= $author_thumb;
			}

			// Member Name
			if (!empty($settings['testmonial_content'][$i]['member_name'])) {
				$author_name = '<h4 class="author__name">' . wp_kses_post($settings['testmonial_content'][$i]['member_name']) . '</h4>';
				$all_testmonial .= $author_name;
			}

			// Description
			if (!empty($settings['testmonial_content'][$i]['description'])) {
				$description = '<div class="testmonial__description er__testmonial__description">' . wp_kses_post($settings['testmonial_content'][$i]['description']) . '</div>';
				$all_testmonial .= $description;
			}

			// Designation
			if (!empty($settings['testmonial_content'][$i]['designation'])) {
				$designation = '<p class="author__designation">' . $settings['testmonial_content'][$i]['designation'] . '</p>';
				$all_testmonial .= $designation;
			}
		}


		/*-----------------------------
			CONTENT WITH FOREACH LOOP
		------------------------------*/
		$testmonial_content = '';
		$testimonial_tumb_contnet = '';
		if ($settings['testmonial_content']) {
			if ('tesmonial_style_10' == $settings['testmonial_style'] || 'tesmonial_style_11' == $settings['testmonial_style'] || 'tesmonial_style_12' == $settings['testmonial_style']) {
				foreach ($settings['testmonial_content'] as $single_testmonial) {

					$testmonial_content .= '
					<div class="single__testmonial">';

					if (!empty($single_testmonial['member_thumb'])) {

						$thumb_array = $single_testmonial['member_thumb'];
						$thumb_link  = wp_get_attachment_image_url($thumb_array['id'], 'thumbnail');
						$thumb_link  = Group_Control_Image_Size::get_attachment_image_src($thumb_array['id'], 'member_thumb_size', $single_testmonial);
						if (!empty($thumb_link)) {
							$testmonial_content .= '<div class="author__thumb"><img src="' . esc_url($thumb_link) . '" alt="' . esc_attr(get_the_title()) . '" /></div>';
						} else {
							$testmonial_content .= '<div class="author__thumb"><img src="' . esc_url($single_testmonial['member_thumb']['url']) . '" alt="" /></div>';
						}
					}
					if (!empty($single_testmonial['description'])) {
						if (isset($single_testmonial['client_rating']['size']) && $single_testmonial['client_rating']['size'] > 0 && $rating_html_position == 'top_right') {
							$testmonial_content .= $this->get_rating_markup($single_testmonial['client_rating']['size']);
						}
						$testmonial_content .= '
					    <div class="author__content">';
						$testmonial_content .= '<div class="testmonial__description er__testmonial__description">' . wpautop($single_testmonial['description']) . '</div>';
						$testmonial_content .= '
						</div>';
					}
					if (!empty($single_testmonial['member_name'])) {

						$testmonial_content .= '
						<div class="author__thumb__designation__warp">
							<div class="author__thumb__designation">';

						if (!empty($icon)) {
							$testmonial_content .= $icon;
						}
						if (!empty($single_testmonial['member_name'])) {
							$testmonial_content .= '
								<h4 class="author__name">' . esc_html($single_testmonial['member_name']) . '</h4>';
						}
						if (!empty($single_testmonial['designation'])) {
							$testmonial_content .= '
								<p class="author__designation">' . esc_html($single_testmonial['designation']) . '</p>';
						}

						if (isset($single_testmonial['client_rating']['size']) && $single_testmonial['client_rating']['size'] > 0 && $rating_html_position == 'after_deg') {
							$testmonial_content .= $this->get_rating_markup($single_testmonial['client_rating']['size']);
						}

						$testmonial_content .= '
							</div>
						</div>';
					}
					$testmonial_content .= '
				</div>';
				}
			} elseif ('tesmonial_style_13' == $settings['testmonial_style']) {

				foreach ($settings['testmonial_content'] as $single_testmonial) {
					if (!empty($single_testmonial['member_thumb'])) {

						$thumb_array = $single_testmonial['member_thumb'];
						$thumb_link  = wp_get_attachment_image_url($thumb_array['id'], 'thumbnail');
						$thumb_link  = Group_Control_Image_Size::get_attachment_image_src($thumb_array['id'], 'member_thumb_size', $single_testmonial);
						if (!empty($thumb_link)) {
							$testimonial_tumb_contnet .= '<div class="author__thumb"><img src="' . esc_url($thumb_link) . '" alt="' . esc_attr(get_the_title()) . '" /></div>';
						} else {
							$testimonial_tumb_contnet .= '<div class="author__thumb"><img src="' . esc_url($single_testmonial['member_thumb']['url']) . '" alt="" /></div>';
						}
					}


					$testmonial_content .= '
					<div class="single__testmonial">';

					if (!empty($icon)) {
						$testmonial_content .= $icon;
					}
					if (isset($single_testmonial['client_rating']['size']) && $single_testmonial['client_rating']['size'] > 0 && $rating_html_position == 'top_right') {
						$testmonial_content .= $this->get_rating_markup($single_testmonial['client_rating']['size']);
					}
					if (!empty($single_testmonial['description'])) {

						$testmonial_content .= '
					    <div class="author__content">';
						$testmonial_content .= '<div class="testmonial__description er__testmonial__description">' . wpautop($single_testmonial['description']) . '</div>';
						$testmonial_content .= '
						</div>';
					}
					if (!empty($single_testmonial['member_name'])) {

						$testmonial_content .= '
						<div class="author__thumb__designation__warp">
							<div class="author__thumb__designation">';

						if (!empty($single_testmonial['member_name'])) {
							$testmonial_content .= '
								<h4 class="author__name">' . esc_html($single_testmonial['member_name']) . '</h4>';
						}
						if (!empty($single_testmonial['designation'])) {
							$testmonial_content .= '
								<p class="author__designation">' . esc_html($single_testmonial['designation']) . '</p>';
						}

						if (isset($single_testmonial['client_rating']['size']) && $single_testmonial['client_rating']['size'] > 0 && $rating_html_position == 'after_deg') {
							$testmonial_content .= $this->get_rating_markup($single_testmonial['client_rating']['size']);
						}

						$testmonial_content .= '
							</div>
						</div>';
					}
					$testmonial_content .= '
				</div>';
				}
			} elseif ('tesmonial_style_14' == $settings['testmonial_style']) {

				foreach ($settings['testmonial_content'] as $single_testmonial) {

					$testmonial_content .= '
					<div class="single__testmonial">';
					$testmonial_content .= '<div class="col-md-6 col-xs-12 no-padding">';
					if (!empty($icon)) {
						$testmonial_content .= $icon;
					}
					if (isset($single_testmonial['client_rating']['size']) && $single_testmonial['client_rating']['size'] > 0 && $rating_html_position == 'top_right') {
						$testmonial_content .= $this->get_rating_markup($single_testmonial['client_rating']['size']);
					}
					if (!empty($single_testmonial['description'])) {

						$testmonial_content .= '
					    <div class="author__content">';
						$testmonial_content .= '<div class="testmonial__description er__testmonial__description">' . wpautop($single_testmonial['description']) . '</div>';
						$testmonial_content .= '
						</div>';
					}
					if (!empty($single_testmonial['member_name'])) {

						$testmonial_content .= '
						<div class="author__thumb__designation__warp">
							<div class="author__thumb__designation">';

						if (!empty($single_testmonial['member_name'])) {
							$testmonial_content .= '
								<h4 class="author__name">' . esc_html($single_testmonial['member_name']) . '</h4>';
						}
						if (!empty($single_testmonial['designation'])) {
							$testmonial_content .= '
								<p class="author__designation">' . esc_html($single_testmonial['designation']) . '</p>';
						}
						if (isset($single_testmonial['client_rating']['size']) && $single_testmonial['client_rating']['size'] > 0 && $rating_html_position == 'after_deg') {
							$testmonial_content .= $this->get_rating_markup($single_testmonial['client_rating']['size']);
						}
						$testmonial_content .= '
							</div>
						</div>';
					}
					$testmonial_content .= '</div>';

					$testmonial_content .= '<div class="col-md-5 col-md-offset-1 col-xs-12 no-padding">';
					if (!empty($single_testmonial['member_thumb'])) {

						$thumb_array = $single_testmonial['member_thumb'];
						$thumb_link  = wp_get_attachment_image_url($thumb_array['id'], 'thumbnail');
						$thumb_link  = Group_Control_Image_Size::get_attachment_image_src($thumb_array['id'], 'member_thumb_size', $single_testmonial);
						if (!empty($thumb_link)) {
							$testmonial_content .= '<div class="author__thumb"><img src="' . esc_url($thumb_link) . '" alt="' . esc_attr(get_the_title()) . '" /></div>';
						} else {
							$testmonial_content .= '<div class="author__thumb"><img src="' . esc_url($single_testmonial['member_thumb']['url']) . '" alt="" /></div>';
						}
					}
					$testmonial_content .= '</div>';

					$testmonial_content .= '
				</div>';
				}
			} elseif ('tesmonial_style_15' == $settings['testmonial_style']) {
				foreach ($settings['testmonial_content'] as $single_testmonial) {

					$testmonial_content .= '
					<div class="single__testmonial">';

					$testmonial_content .= '<div class="col-md-4 col-xs-12 no-padding">';
					if (!empty($single_testmonial['member_thumb'])) {

						$thumb_array = $single_testmonial['member_thumb'];
						$thumb_link  = wp_get_attachment_image_url($thumb_array['id'], 'thumbnail');
						$thumb_link  = Group_Control_Image_Size::get_attachment_image_src($thumb_array['id'], 'member_thumb_size', $single_testmonial);
						if (!empty($thumb_link)) {
							$testmonial_content .= '<div class="author__thumb__warp"><div class="author__thumb"><img src="' . esc_url($thumb_link) . '" alt="' . esc_attr(get_the_title()) . '" /></div></div>';
						} else {
							$testmonial_content .= '<div class="author__thumb__warp"><div class="author__thumb"><img src="' . esc_url($single_testmonial['member_thumb']['url']) . '" alt="" /></div></div>';
						}
					}
					$testmonial_content .= '</div>';

					$testmonial_content .= '<div class="col-md-8 col-xs-12 no-padding">';
					if (!empty($icon)) {
						$testmonial_content .= $icon;
					}
					if (isset($single_testmonial['client_rating']['size']) && $single_testmonial['client_rating']['size'] > 0 && $rating_html_position == 'top_right') {
						$testmonial_content .= $this->get_rating_markup($single_testmonial['client_rating']['size']);
					}
					if (!empty($single_testmonial['description'])) {

						$testmonial_content .= '
					    <div class="author__content">';
						$testmonial_content .= '<div class="testmonial__description er__testmonial__description">' . wpautop($single_testmonial['description']) . '</div>';
						$testmonial_content .= '
						</div>';
					}
					if (!empty($single_testmonial['member_name'])) {

						$testmonial_content .= '
						<div class="author__thumb__designation__warp">
							<div class="author__thumb__designation">';

						if (!empty($single_testmonial['member_name'])) {
							$testmonial_content .= '
								<h4 class="author__name">' . esc_html($single_testmonial['member_name']) . '</h4>';
						}
						if (!empty($single_testmonial['designation'])) {
							$testmonial_content .= '
								<p class="author__designation">' . esc_html($single_testmonial['designation']) . '</p>';
						}
						if (isset($single_testmonial['client_rating']['size']) && $single_testmonial['client_rating']['size'] > 0 && $rating_html_position == 'after_deg') {
							$testmonial_content .= $this->get_rating_markup($single_testmonial['client_rating']['size']);
						}
						$testmonial_content .= '
							</div>
						</div>';
					}
					$testmonial_content .= '</div>';

					$testmonial_content .= '
				</div>';
				}
			} else {
				foreach ($settings['testmonial_content'] as $single_testmonial) {

					$testmonial_content .= '
					<div class="single__testmonial">';
					if (!empty($single_testmonial['description'])) {
						$testmonial_content .= '
					    <div class="author__content">';
						if (!empty($icon)) {
							$testmonial_content .= $icon;
						}
						if (isset($single_testmonial['client_rating']['size']) && $single_testmonial['client_rating']['size'] > 0 && $rating_html_position == 'top_right') {
							$testmonial_content .= $this->get_rating_markup($single_testmonial['client_rating']['size']);
						}
						$testmonial_content .= '<div class="testmonial__description er__testmonial__description">' . wpautop($single_testmonial['description']) . '</div>';
						if (!empty($right_icon)) {
							$testmonial_content .= $right_icon;
						}
						$testmonial_content .= '
						</div>';
					}
					if (!empty($single_testmonial['member_thumb']) || !empty($single_testmonial['member_name'])) {

						if (!empty($single_testmonial['member_thumb'])) {

							$testmonial_content .= '
							<div class="author__thumb__designation__warp">
								<div class="author__thumb__designation">';
							if (!empty($single_testmonial['member_thumb'])) {
								$thumb_array = $single_testmonial['member_thumb'];
								$thumb_link  = wp_get_attachment_image_url($thumb_array['id'], 'thumbnail');
								$thumb_link  = Group_Control_Image_Size::get_attachment_image_src($thumb_array['id'], 'member_thumb_size', $single_testmonial);
								if (!empty($thumb_link)) {
									$testmonial_content .= '<div class="author__thumb"><img src="' . esc_url($thumb_link) . '" alt="' . esc_attr(get_the_title()) . '" /></div>';
								} else {
									$testmonial_content .= '<div class="author__thumb"><img src="' . esc_url($single_testmonial['member_thumb']['url']) . '" alt="" /></div>';
								}
							}

							if (!empty($single_testmonial['member_name'])) {
								$testmonial_content .= '
									<h4 class="author__name">' . esc_html($single_testmonial['member_name']) . '</h4>';
							}
							if (!empty($single_testmonial['designation'])) {
								$testmonial_content .= '
									<p class="author__designation">' . esc_html($single_testmonial['designation']) . '</p>';
							}
							if (isset($single_testmonial['client_rating']['size']) && $single_testmonial['client_rating']['size'] > 0 && $rating_html_position == 'after_deg') {
								$testmonial_content .= $this->get_rating_markup($single_testmonial['client_rating']['size']);
							}

							$testmonial_content .= '
								</div>
							</div>';
						}
					}
					$testmonial_content .= '
				</div>';
				}
			}
		}
		// Slider Attr
		$this->add_render_attribute('testmonial_carousel_attr', 'class', 'element-ready-testmonial-carousel');
		if (count($settings['testmonial_content']) > 1) {
			$this->add_render_attribute('testmonial_carousel_attr', 'class', 'element-ready-carousel-active');

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

			$this->add_render_attribute('testmonial_carousel_attr', 'data-settings', wp_json_encode($options));
		} else {
			$this->add_render_attribute('testmonial_carousel_attr', 'class', 'testmonial-grid');
		}

		// Parent Attr.
		$this->add_render_attribute('sldier_parent_attr', 'class', 'sldier-content-area');
		$this->add_render_attribute('sldier_parent_attr', 'class', $settings['testmonial_style']);
		$this->add_render_attribute('sldier_parent_attr', 'class', $settings['nav_position']);
?>

		<?php if ('tesmonial_style_13' == $settings['testmonial_style']) : ?>
			<div class="testmonial__thumb__content_area">
				<div class="testmonial__thumb__content">
					<div class="testmonial__thumb__content__slider">
						<?php echo wp_kses_post((isset($testimonial_tumb_contnet) ? $testimonial_tumb_contnet : '')); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div <?php echo $this->get_render_attribute_string('sldier_parent_attr'); ?>>
			<div <?php echo $this->get_render_attribute_string('testmonial_carousel_attr'); ?>>
				<?php echo wp_kses_post(wp_kses_post(isset($testmonial_content) ? $testmonial_content : '')); ?>
			</div>
		</div>

<?php

	}

	public function get_rating_markup($count = 4)
	{

		$return_content = '';
		$return_content .= '<div class="er-rating-testmoni">';
		for ($i = 0; $i < 5; $i++) {
			if ($i < $count) :
				$return_content .= '<span class="fa fa-star checked"></span>';
			else :
				$return_content .= '<span class="fa fa-star"></span>';
			endif;
		}
		$return_content .= '</div>';
		return $return_content;
	}

	protected function content_template()
	{
	}
}
