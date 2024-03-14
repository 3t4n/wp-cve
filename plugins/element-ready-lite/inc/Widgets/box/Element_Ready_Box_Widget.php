<?php

namespace Element_Ready\Widgets\box;

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
use \Element_Ready\Base\Controls\Widget_Control\Element_ready_common_control as Content_Style;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Element_Ready_Box_Widget extends Widget_Base
{

	use Content_Style;
	public function get_name()
	{
		return 'Element_Ready_Box_Widget';
	}

	public function get_title()
	{
		return esc_html__('ER Service Box', 'element-ready-lite');
	}

	public function get_icon()
	{
		return 'eicon-icon-box';
	}

	public function get_categories()
	{
		return array('element-ready-addons');
	}

	public function get_keywords()
	{
		return ['box', 'icon box', 'text box', 'service box', 'service'];
	}

	public static function box_layout_style()
	{
		return apply_filters('element_ready_box_style_presets', [
			'single__box__layout__1'      => esc_html__('Box Style 1', 'element-ready-lite'),
			'single__box__layout__1 e-service-layout1_1'      => esc_html__('Box Style 1.1', 'element-ready-lite'),
			'single__box__layout__2'      => esc_html__('Box Style 2', 'element-ready-lite'),
			'single__box__layout__3'      => esc_html__('Box Style 3', 'element-ready-lite'),
			'single__box__layout__4'      => esc_html__('Box Style 4', 'element-ready-lite'),
			'single__box__layout__5'      => esc_html__('Box Style 5', 'element-ready-lite'),
			'pro_single__box__layout__6'      => esc_html__('Box Style 6 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__7'      => esc_html__('Box Style 7 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__8'      => esc_html__('Box Style 8 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__9'      => esc_html__('Box Style 9 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__10'     => esc_html__('Box Style 10 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__11'     => esc_html__('Box Style 11 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__12'     => esc_html__('Box Style 12 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__13'     => esc_html__('Box Style 13 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__14'     => esc_html__('Box Style 14 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__15'     => esc_html__('Box Style 15 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__16'     => esc_html__('Box Style 16 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__17'     => esc_html__('Box Style 17 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__18'     => esc_html__('Box Style 18 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__19'     => esc_html__('Box Style 19 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__20'     => esc_html__('Box Style 20 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__21'     => esc_html__('Box Style 21 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__22'     => esc_html__('Box Style 22 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__23'     => esc_html__('Box Style 23 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__24'     => esc_html__('Box Style 24 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__25'     => esc_html__('Box Style 25 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__26'     => esc_html__('Box Style 26 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__27'     => esc_html__('Box Style 27 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__28'     => esc_html__('Box Style 28 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__29'     => esc_html__('Box Style 29 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__30'     => esc_html__('Box Style 30 - PRO', 'element-ready-lite'),
			'pro_single__box__layout__custom' => esc_html__('Custom Style - PRO', 'element-ready-lite'),
		]);
	}

	public function get_style_depends()
	{

		wp_register_style('eready-single-box', ELEMENT_READY_ROOT_CSS . 'widgets/single-box.css');
		return ['eready-single-box'];
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
			'box_layout_style',
			[
				'label'   => esc_html__('Box Type', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'single__box__layout__1',
				'options' => self::box_layout_style(),
			]
		);

		$this->add_control(
			'er_add_content_wrapper_div',
			[
				'label'        => esc_html__('Content Wrapper ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('yes', 'element-ready-lite'),
				'label_off'    => esc_html__('No', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'		=> 'before',
			]
		);

		// BOX BACKGROUND ICON TOGGLE
		$this->add_control(
			'show_box_bg_text_or_icon',
			[
				'label'        => esc_html__('Background Icon / Text ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'element-ready-lite'),
				'label_off'    => esc_html__('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'		=> 'before',
			]
		);

		// Icon Type
		$this->add_control(
			'box_bg_icon_type',
			[
				'label'   => esc_html__('Icon Type', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'font_icon',
				'options' => [
					'font_icon'  => esc_html__('Font & SVG Icon', 'element-ready-lite'),
					'image_icon' => esc_html__('Image Icon', 'element-ready-lite'),
					'simple_text' => esc_html__('Simple Text', 'element-ready-lite'),
				],
				'condition' => [
					'show_box_bg_text_or_icon' => 'yes',
				],
			]
		);

		// Font Icon
		$this->add_control(
			'box_bg_font_icon',
			[
				'label'     => esc_html__('Font Icons', 'element-ready-lite'),
				'type'      => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'box_bg_icon_type'         => 'font_icon',
					'show_box_bg_text_or_icon' => 'yes',
				],
			]
		);

		// Image Icon
		$this->add_control(
			'box_bg_image_icon',
			[
				'label'   => esc_html__('Image Icon', 'element-ready-lite'),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'box_bg_icon_type' => 'image_icon',
					'show_box_bg_text_or_icon' => 'yes',
				],
			]
		);

		// Text Bg
		$this->add_control(
			'box_bg_text',
			[
				'label'   => esc_html__('Image Icon', 'element-ready-lite'),
				'type'    => Controls_Manager::TEXT,
				'placeholder' => esc_html__('01', 'element-ready-lite'),
				'condition' => [
					'box_bg_icon_type' => 'simple_text',
					'show_box_bg_text_or_icon' => 'yes',
				],
			]
		);

		// Icon Toggle
		$this->add_control(
			'show_box_image',
			[
				'label'        => esc_html__('Box Features Image ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'element-ready-lite'),
				'label_off'    => esc_html__('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'		=> 'before',
			]
		);

		// Image 
		$this->add_control(
			'box_image',
			[
				'label'   => esc_html__('Box Image', 'element-ready-lite'),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'show_box_image' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'box_image_size',
				'exclude'   => ['custom'],
				'default'   => 'large',
				'condition' => [
					'show_box_image' => 'yes',
				],
			]
		);
		$this->add_control(
			'box_image_postion',
			[
				'label'   => esc_html__('Image Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before'  => esc_html__('Before Content', 'element-ready-lite'),
					'after' => esc_html__('After Content', 'element-ready-lite'),
				],
				'condition' => [
					'show_box_image' => 'yes',
				],
			]
		);

		// Icon Toggle
		$this->add_control(
			'show_icon',
			[
				'label'        => esc_html__('Show Icon ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'element-ready-lite'),
				'label_off'    => esc_html__('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'		=> 'before',
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
					'font_icon'  => esc_html__('Font & SVG Icon', 'element-ready-lite'),
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
				'label'     => esc_html__('Font Icons', 'element-ready-lite'),
				'type'      => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'fas fa-star',
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

		// Title
		$this->add_control(
			'title',
			[
				'label'       => esc_html__('Title', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Enter Your Title', 'element-ready-lite'),
				'separator'   => 'before',
				'default'     => esc_html__('Your Title Here.', 'element-ready-lite'),
			]
		);

		// Title Tag
		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__('Title HTML Tag', 'element-ready-lite'),
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

		// Title Link
		$this->add_control(
			'title_link',
			[
				'label'         => esc_html__('Linked Title ?', 'element-ready-lite'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__('https://your-link.com', 'element-ready-lite'),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				],
				'condition' => [
					'title!' => '',
				],
			]
		);

		// Subtitle
		$this->add_control(
			'subtitle',
			[
				'label'       => esc_html__('Subtitle', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Subtitle', 'element-ready-lite'),
				'separator'		=> 'before',
			]
		);

		// Subtitle Position
		$this->add_control(
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

		// Description
		$this->add_control(
			'description',
			[
				'label'       => esc_html__('Description', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__('Description.', 'element-ready-lite'),
				'separator'   => 'before',
				'default'     => esc_html__('Type your content here what you want. then change all style for your own purpose.', 'element-ready-lite'),
			]
		);

		// Button Toggle
		$this->add_control(
			'show_button',
			[
				'label'        => esc_html__('Show Button ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'element-ready-lite'),
				'label_off'    => esc_html__('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'		=> 'before',
			]
		);

		// Button Title
		$this->add_control(
			'button_text',
			[
				'label'       => esc_html__('Button Title', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Button Text', 'element-ready-lite'),
				'condition'   => ['show_button' => 'yes'],
				'separator'		=> 'before',
			]
		);

		// Button Link
		$this->add_control(
			'button_link',
			[
				'label'         => esc_html__('Button Link', 'element-ready-lite'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__('https://your-link.com', 'element-ready-lite'),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				],
				'condition' => ['show_button' => 'yes'],
			]
		);

		// Button Icon Picker
		$this->add_control(
			'button_icon',
			[
				'label'       => esc_html__('Set Button Icon', 'element-ready-lite'),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => '',
				'condition'   => ['show_button' => 'yes'],
				'separator'		=> 'before',
			]
		);

		// Button Icon Align
		$this->add_control(
			'button_icon_align',
			[
				'label'   => esc_html__('Icon Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'  => esc_html__('Before', 'element-ready-lite'),
					'right' => esc_html__('After', 'element-ready-lite'),
				],
				'condition' => [
					'button_icon!' => '',
				],
			]
		);

		// Button Icon Margin
		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__('Icon Spacing', 'element-ready-lite'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'button_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .box__button .box__button_icon_right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .box__button .box__button_icon_left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_placement_align',
			[
				'label'   => esc_html__('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'content__left' => [
						'title' => esc_html__('Left', 'element-ready-lite'),
						'icon'  => 'eicon-h-align-left',
					],
					'content__center' => [
						'title' => esc_html__('Center', 'element-ready-lite'),
						'icon'  => 'eicon-v-align-top',
					],
					'content__right' => [
						'title' => esc_html__('Right', 'element-ready-lite'),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'separator'		=> 'before',
				'condition' => [
					'content_placement_type' => 'default',
				],
			]
		);
		$this->end_controls_section();
		/*********************************
		 		STYLE SECTION
		 **********************************/
		/*----------------------------
			ICON STYLE
		-----------------------------*/
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => esc_html__('Icon', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_icon' => 'yes',
				],
			]
		);

		$icon_opt = apply_filters('element_ready_box_icon_pro_message', $this->pro_message('icon_pro_message'), false);
		$this->run_controls($icon_opt);
		do_action('element_ready_box_icon_styles', $this);

		$this->end_controls_section();
		/*----------------------------
			ICON STYLE END
		-----------------------------*/

		/*----------------------------
			ICON BEFORE / AFTER
		-----------------------------*/
		$this->start_controls_section(
			'icon_before_after_style_section',
			[
				'label' => esc_html__('Icon ( Before / After )', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_icon' => 'yes',
				],
			]
		);
		$icon_opt = apply_filters('element_ready_box_icon_before_after_pro_message', $this->pro_message('icon_before_pro_messagte'), false);
		$this->run_controls($icon_opt);
		do_action('element_ready_box_icon_before_after_styles', $this);

		$this->end_controls_section();
		/*----------------------------
			ICON BEFORE / AFTER END
		-----------------------------*/

		/*----------------------------
			BOX BG ICON TEXXT STYLE
		-----------------------------*/
		$this->start_controls_section(
			'bg_icon_text_style_section',
			[
				'label' => esc_html__('BG ( Icon / Text )', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_box_bg_text_or_icon' => 'yes'
				]
			]
		);
		$icon_opt = apply_filters('element_ready_box_bg_tex_pro_message', $this->pro_message('bg_text_pro_message'), false);
		$this->run_controls($icon_opt);
		do_action('element_ready_box_bg_text_styles', $this);

		$this->end_controls_section();
		$this->start_controls_section(
			'lat_one_wrapper_style_section',
			[
				'label' => esc_html__('Content Wrapper', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'er_add_content_wrapper_div' => ['yes']
				]
			]
		);

		$this->add_control(
			'lat_one_wrapper_display',
			[
				'label' => esc_html__('Display', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'flex'  => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'block' => esc_html__('Block', 'element-ready-lite'),
					'' => esc_html__('None', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .e-service-content-wrapper' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lat_one_wrapper_direction',
			[
				'label' => esc_html__('Content Direction', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'column'  => esc_html__('Column', 'element-ready-lite'),
					'column-reverse'  => esc_html__('Column Reverse', 'element-ready-lite'),
					'row' => esc_html__('Row', 'element-ready-lite'),
					'row-reverse' => esc_html__('Row Reverse', 'element-ready-lite'),
					'' => esc_html__('None', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .e-service-content-wrapper' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
					'lat_one_wrapper_display' => ['flex', 'inline-flex']
				]
			]
		);

		$this->add_control(
			'lat_one_wrapper_v_align',
			[
				'label' => esc_html__('Vertical Alignment', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__('Start', 'element-ready-lite'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'element-ready-lite'),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__('End', 'element-ready-lite'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .e-service-content-wrapper' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'lat_one_wrapper_display' => ['flex', 'inline-flex']
				]
			]
		);

		$this->add_responsive_control(
			'lat_one_wrapper_between',
			[
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => esc_html__('Gap', 'element-ready-lite'),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .e-service-content-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'lat_one_wrapper_display' => ['flex', 'inline-flex']
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'lat_one_wrapper_border',
				'selector' => '{{WRAPPER}} .e-service-content-wrapper',
			]
		);
		$this->add_responsive_control(
			'lat_one__wrapper__border_radious',
			[
				'label'      => __('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .e-service-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'lat_one__wrapper__box_shadow',
				'label'    => __('Box Shadow', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .e-service-content-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'lat_one_wrapper_e_background',
				'label'    => __('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .e-service-content-wrapper',
			]
		);

		$this->add_responsive_control(
			'lat_one_wrapper__margin',
			[
				'label'      => __('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .e-service-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'lat_one_wrapper__padding',
			[
				'label'      => __('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .e-service-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
		/*----------------------------
			BOX BG ICON TEXT STYLE END
		-----------------------------*/

		/*----------------------------
			BOX BIG IMG
		-----------------------------*/
		$this->start_controls_section(
			'big_img_style_section',
			[
				'label' => esc_html__('Box Image', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_box_image' => 'yes'
				]
			]
		);
		$icon_opt = apply_filters('element_ready_box_big_thumb_pro_message', $this->pro_message('big_thumb_pro_message'), false);
		$this->run_controls($icon_opt);
		do_action('element_ready_box_big_thumb_styles', $this);

		$this->end_controls_section();
		/*----------------------------
			BOX BIG IMG END
		-----------------------------*/

		/*----------------------------
			TITLE STYLE
		-----------------------------*/
		$this->start_controls_section(
			'title_style_section',
			[
				'label' => esc_html__('Title', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => '',
				],
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
				'selector' => '{{WRAPPER}} .box__title',
			]
		);
		$this->add_control(
			'title_text_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .box__title, {{WRAPPER}} .box__title a' => 'color: {{VALUE}};',
				],
			]
		);

		$icon_opt = apply_filters('element_ready_box_title_pro_message', $this->pro_message('title_pro_message'), false);

		$this->run_controls($icon_opt);
		do_action('element_ready_box_title_styles', $this);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'title_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);
		// Title Hover Link Color
		$this->add_control(
			'hover_title_color',
			[
				'label'     => esc_html__('Link Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .box__title a:hover, {{WRAPPER}} .box__title a:focus' => 'color: {{VALUE}};',
				],
			]
		);

		// Box Hover Title Color
		$this->add_control(
			'box_hover_title_color',
			[
				'label'     => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .box__title a, {{WRAPPER}} :focus .box__title a, {{WRAPPER}} :hover .box__title' => 'color: {{VALUE}};',
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
			TITLE BEFORE / AFTER
		-----------------------------*/
		$this->start_controls_section(
			'title_before_after_style_section',
			[
				'label' => esc_html__('Title ( Before / After )', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => '',
				],
			]
		);
		$icon_opt = apply_filters('element_ready_box_title_before_after_pro_message', $this->pro_message('title_before_after_pro_message'), false);
		$this->run_controls($icon_opt);
		do_action('element_ready_box_title_before_after_styles', $this);

		$this->end_controls_section();
		/*----------------------------
			TITLE BEFORE / AFTER END
		-----------------------------*/

		/*----------------------------
			SUBTITLE STYLE
		-----------------------------*/
		$this->start_controls_section(
			'subtitle_style_section',
			[
				'label' => esc_html__('Subtitle', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'subtitle!' => '',
				],
			]
		);
		// Subtitle Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .box__subtitle',
			]
		);

		// Subtitle Color
		$this->add_control(
			'subtitle_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .box__subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		// Box Hover Subtitle Color
		$this->add_control(
			'box_hover_subtitle_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .box__subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		// Subtitle Margin
		$this->add_responsive_control(
			'subtitle_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .box__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		/*----------------------------
			SUBTITLE STYLE END
		-----------------------------*/

		/*----------------------------
			BUTTON STYLE
		-----------------------------*/
		$this->start_controls_section(
			'button_style_section',
			[
				'label' => esc_html__('Button', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);
		$icon_opt = apply_filters('element_ready_box_button_pro_message', $this->pro_message('button_pro_message'), false);
		$this->run_controls($icon_opt);
		do_action('element_ready_box_button_styles', $this);

		$this->end_controls_section();


		$this->start_controls_section(
			'box_wrapper_style_section',
			[
				'label' => esc_html__('Box Wrapper', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_box_image' => ['yes']
				]
			]
		);

		$this->start_controls_tabs('box_wrapper_tab_style');

		$this->start_controls_tab(
			'box_wrapper_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_wrapper_background',
				'label' => esc_html__('Background', 'element-ready-lite'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__box_wrap ',
			]
		);

		$this->add_responsive_control(
			'box_wrapper_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__box_wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'box_wrapper_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__box_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'box_wrapper_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .single__box_wrap',
			]
		);

		$this->add_control(
			'box_wrapper_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__box_wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_er_wra_sha_shadow',
				'label' => esc_html__('Box Shadow', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .single__box_wrap',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'box_wrapperss_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_wrapper_hover_background',
				'label' => esc_html__('Background', 'element-ready-lite'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__box_wrap:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'box_wrapper_hover_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .single__box_wrap:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_er_wra__hoversha_shadow',
				'label' => esc_html__('Box Shadow', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .single__box_wrap:hover',
			]
		);

		$this->add_control(
			'box_wrapper_transition',
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
					'size' => 0.5,
				],
				'selectors' => [
					'{{WRAPPER}} .single__box_wrap' => 'transition: {{SIZE}}s;',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		/*----------------------------
			BUTTON STYLE END
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


		$this->start_controls_tabs('box_tab_style');

		$this->start_controls_tab(
			'box_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);
		$this->add_control(
			'box_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__box .box__description' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .single__box',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_background',
				'label' => esc_html__('Background', 'element-ready-lite'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__box',
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
					'{{WRAPPER}} .single__box' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'box_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .single__box',
			]
		);
		$this->add_control(
			'box_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .single__box',
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
					'{{WRAPPER}} .single__box' => 'transition: {{SIZE}}s;',
				],
			]
		);
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
					'{{WRAPPER}} .single__box' => 'position: {{VALUE}};',
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
					'{{WRAPPER}} .single__box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .single__box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'box_height',
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
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'box_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);
		$this->add_control(
			'hover_box_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__box:hover .box__description' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'hover_box_button_color',
			[
				'label'  => esc_html__('Button Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single__box:hover .box__button' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'hover_box_background',
				'label' => esc_html__('Background', 'element-ready-lite'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .single__box:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_box_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .single__box:hover',
			]
		);
		$this->add_control(
			'hover_box_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .single__box:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_box_shadow',
				'selector' => '{{WRAPPER}} .single__box:hover',
			]
		);
		$this->add_control(
			'box_hover_height',
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
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__box:hover' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'hover_box_transform',
			[
				'label'      => esc_html__('Transform Vartically', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .single__box:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
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
				'label' => esc_html__('Box ( Before / After )', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$icon_opt = apply_filters('element_ready_box_before_after_pro_message', $this->pro_message('box_be_after_pro_message'), false);
		$this->run_controls($icon_opt);
		do_action('element_ready_box_before_after_styles', $this);

		$this->end_controls_section();
		/*----------------------------
			BOX BEFORE / AFTER END
		-----------------------------*/

		do_action('element_ready_go_pro_section', $this);
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		/*------------------------------
			BOX BACKGROUND ICON OR TEXT
		--------------------------------*/
		if ('yes' == $settings['show_box_bg_text_or_icon']) {
			if ('font_icon' == $settings['box_bg_icon_type'] && !empty($settings['box_bg_font_icon'])) {
				$box_iocn_or_text = sprintf('<div class="box__bg__icon__text">%s</div>', element_ready_render_icons($settings['box_bg_font_icon']));
			} elseif ('image_icon' == $settings['box_bg_icon_type'] && !empty($settings['box_bg_image_icon'])) {
				$icon_array = $settings['box_bg_image_icon'];
				$icon_link = wp_get_attachment_image_url($icon_array['id'], 'full');
				$box_iocn_or_text = sprintf('<div class="box__bg__icon__text"><img src="%s" alt="" /></div>', esc_url($icon_link));
			} elseif ('simple_text' == $settings['box_bg_icon_type'] && !empty($settings['box_bg_text'])) {
				$box_iocn_or_text = sprintf('<div class="box__bg__icon__text">%s</div>', esc_html($settings['box_bg_text']));
			}
		} else {
			$box_iocn_or_text = '';
		}

		/*------------------------------
			BOX FEATURES IMAGE
		--------------------------------*/
		if ('yes' == $settings['show_box_image']) {
			$box_big_img = Group_Control_Image_Size::get_attachment_image_html($settings, 'box_image_size', 'box_image');
			$box_image = wp_kses_post(sprintf('<div class="box__big__thumb">%s</div>', $box_big_img));
		} else {
			$box_image = '';
		}

		/*--------------------------
			Icon Animation
		---------------------------*/
		if (isset($settings['icon_hover_animation'])) {
			$icon_animation = 'elementor-animation-' . $settings['icon_hover_animation'];
		} else {
			$icon_animation = '';
		}
		/*---------------------------
			Icon Condition
		----------------------------*/
		if ('yes' == $settings['show_icon']) {
			if ('font_icon' == $settings['icon_type'] && !empty($settings['font_icon'])) {

				$icon = sprintf('<div class="box__icon %s">%s</div>', esc_attr($icon_animation), element_ready_render_icons($settings['font_icon']));
			} elseif ('image_icon' == $settings['icon_type'] && !empty($settings['image_icon'])) {
				$icon_array = $settings['image_icon'];
				$icon_link = wp_get_attachment_image_url($icon_array['id'], 'full');
				$icon = sprintf('<div class="box__icon %s"><img src="%s" alt="" /></div>', esc_attr($icon_animation), esc_url($icon_link));
			}
		} else {
			$icon = '';
		}

		/*-------------------------
			Title Link Attr
		--------------------------*/
		if (!empty($settings['title_link']['url'])) {
			$this->add_render_attribute('title_link', 'href', esc_url($settings['title_link']['url']));

			if ($settings['title_link']['is_external']) {
				$this->add_render_attribute('title_link', 'target', '_blank');
			}

			if ($settings['title_link']['nofollow']) {
				$this->add_render_attribute('title_link', 'rel', 'nofollow');
			}
		}

		/*---------------------------
			Title Tag
		-----------------------------*/
		if (!empty($settings['title_tag'])) {
			$title_tag = $settings['title_tag'];
		} else {
			$title_tag = 'h3';
		}


		/*---------------------------
			Title
		----------------------------*/
		if (!empty($settings['title'])) {
			if (!empty($settings['title_link']) && !empty($this->get_render_attribute_string('title_link'))) {
				$title = wp_kses_post(sprintf('<%s class="box__title"><a %s>%s</a></%s>', $title_tag, $this->get_render_attribute_string('title_link'), esc_html($settings['title']), $title_tag));
			} else {
				$title = wp_kses_post(sprintf('<%s class="box__title">%s</%s>', $title_tag, esc_html($settings['title']), $title_tag));
			}
		} else {
			$title = '';
		}

		/*----------------------------
			Subtitle
		-----------------------------*/
		if (!empty($settings['subtitle'])) {
			$subtitle = wp_kses_post(sprintf('<div class="box__subtitle">%s</div>', esc_html($settings['subtitle'])));
		} else {
			$subtitle = '';
		}

		/*----------------------------
			TITLE CONDITION
		------------------------------*/
		if (!empty($settings['subtitle_position'])) {
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
		if ($settings['er_add_content_wrapper_div'] == 'yes') {
			$title_subtitle = wp_kses_post('<div class="e-service-content-wrapper">' . $title_subtitle);
		}
		/*----------------------------
			Description
		-----------------------------*/
		if (!empty($settings['description'])) {
			$description = wp_kses_post(sprintf('<div class="box__description">%s</div>', wpautop($settings['description'])));
		} else {
			$description = '';
		}
		if ($settings['er_add_content_wrapper_div'] == 'yes') {
			$description = $description . '</div>';
		}
		/*--------------------------
			Button Link Attr
		---------------------------*/
		if (!empty($settings['button_link']['url'])) {
			$this->add_render_attribute('more_button', 'href', $settings['button_link']['url']);

			if ($settings['button_link']['is_external']) {
				$this->add_render_attribute('more_button', 'target', '_blank');
			}

			if ($settings['button_link']['nofollow']) {
				$this->add_render_attribute('more_button', 'rel', 'nofollow');
			}
		}

		/*-------------------------
			Button animation
		---------------------------*/
		if (isset($settings['button_hover_animation'])) {
			$button_animation = 'elementor-animation-' . esc_attr($settings['button_hover_animation']);
		} else {
			$button_animation = '';
		}

		/*----------------------------
			BUTTON
		-----------------------------*/
		if ('yes' == $settings['show_button'] && (!empty($settings['button_text']) && !empty($settings['button_link']))) {
			$button = '<a class="box__button ' . esc_attr($button_animation) . '" ' . $this->get_render_attribute_string('more_button') . '>' . esc_html($settings['button_text']) . '</a>';
		} else {
			$button = '';
		}

		/*-----------------------------
			BUTTON WITH ICON
		------------------------------*/
		if (!empty($settings['button_icon'])) {
			if ('left' == $settings['button_icon_align']) {
				$button = sprintf('<a class="box__button %s" %s><i class="box__button_icon_left %s"></i>%s</a>', esc_attr($button_animation), $this->get_render_attribute_string('more_button'), esc_attr($settings['button_icon']), esc_html($settings['button_text']));
			} elseif ('right' == $settings['button_icon_align']) {
				$button = sprintf('<a class="box__button %s" %s>%s<i class="box__button_icon_right %s"></i></a>', esc_attr($button_animation), $this->get_render_attribute_string('more_button'), esc_html($settings['button_text']), esc_attr($settings['button_icon']));
			}
		}

		$this->add_render_attribute('box_wrap_style_attr', 'class', 'single__box_wrap wrap__' . $settings['box_layout_style']);
		$this->add_render_attribute('box_style_attr', 'class', 'single__box');
		if ('single__box__layout__custom' != $settings['box_layout_style']) {
			$this->add_render_attribute('box_style_attr', 'class', $settings['box_layout_style']);
			$this->add_render_attribute('box_style_attr', 'class', $settings['content_placement_align']);
		}

		if ('yes' == $settings['show_box_image']) {
			if ('before' == $settings['box_image_postion']) {

				echo wp_kses_post('
					<div ' . $this->get_render_attribute_string('box_wrap_style_attr') . '>
						' . (isset($box_image) ? $box_image : '') . '
						<div ' . $this->get_render_attribute_string('box_style_attr') . '>
							' . (isset($box_iocn_or_text) ? $box_iocn_or_text : '') . '
							' . (isset($icon) ? $icon : '') . '
							' . (isset($title_subtitle) ? $title_subtitle : '') . '
							' . (isset($description) ? $description : '') . '
							' . (isset($button) ? $button : '') . '
						</div>
					</div>
				');
			} elseif ('after' == $settings['box_image_postion']) {
				echo wp_kses_post('
					<div ' . $this->get_render_attribute_string('box_wrap_style_attr') . '>
						<div ' . $this->get_render_attribute_string('box_style_attr') . '>
							' . (isset($box_iocn_or_text) ? $box_iocn_or_text : '') . '
							' . (isset($icon) ? $icon : '') . '
							' . (isset($title_subtitle) ? $title_subtitle : '') . '
							' . (isset($description) ? $description : '') . '
							' . (isset($button) ? $button : '') . '
						</div>
						' . (isset($box_image) ? $box_image : '') . '
					</div>
				');
			}
		} else {
			echo '
				<div ' . $this->get_render_attribute_string('box_style_attr') . '>
					' . (isset($box_iocn_or_text) ? $box_iocn_or_text : '') . '
					' . (isset($icon) ? $icon : '') . '
					' . (isset($title_subtitle) ? $title_subtitle : '') . '
					' . (isset($description) ? $description : '') . '
					' . (isset($button) ? $button : '') . '
				</div>
			';
		}
	}
	protected function content_template()
	{
	}
}
