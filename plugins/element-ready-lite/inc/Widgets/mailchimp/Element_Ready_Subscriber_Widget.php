<?php

namespace Element_Ready\Widgets\mailchimp;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
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

class Element_Ready_Subscriber_Widget extends Widget_Base
{

	public function get_name()
	{
		return 'Element_Ready_Subscriber_Widget';
	}

	public function get_title()
	{
		return __('ER Mailchimp', 'element-ready-lite');
	}

	public function get_icon()
	{
		return 'eicon-mailchimp';
	}

	public function get_categories()
	{
		return array('element-ready-addons');
	}

	public function get_keywords()
	{
		return ['Subscriber', 'MailChimp', 'Email Signup'];
	}

	public function get_script_depends()
	{
		return [
			'ajaxchimp',
			'element-ready-core',
		];
	}
	public function get_style_depends()
	{

		wp_register_style('eready-mailchimp-subscriber', ELEMENT_READY_ROOT_CSS . 'widgets/subscriber.css');
		return ['eready-mailchimp-subscriber'];
	}

	protected function register_controls()
	{

		/******************************
		 * 	CONTENT SECTION
		 ******************************/
		$this->start_controls_section(
			'content_section',
			[
				'label' => __('Content', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// Icon Toggle
		$this->add_control(
			'show_icon',
			[
				'label'        => __('Show Icon ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __('Show', 'element-ready-lite'),
				'label_off'    => __('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		// Icon Type
		$this->add_control(
			'icon_type',
			[
				'label'   => __('Icon Type', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'font_icon',
				'options' => [
					'font_icon'  => __('Font Icon', 'element-ready-lite'),
					'image_icon' => __('Image Icon', 'element-ready-lite'),
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
				'label'     => __('Font Icons', 'element-ready-lite'),
				'type'      => Controls_Manager::ICON,
				'label_block' => true,
				'default'   => 'fa fa-star-o',
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
				'label'   => __('Image Icon', 'element-ready-lite'),
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
				'label'       => __('Title', 'element-ready-lite'),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => __('Title', 'element-ready-lite'),
			]
		);

		// Title Tag
		$this->add_control(
			'title_tag',
			[
				'label'   => __('Title HTML Tag', 'elementor'),
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
		$this->add_control(
			'subtitle',
			[
				'label'       => __('Subtitle', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __('Subtitle', 'element-ready-lite'),
			]
		);

		// Subtitle Position
		$this->add_control(
			'subtitle_position',
			[
				'label'   => __('Subtitle Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'after_title',
				'options' => [
					'before_title' => __('Before title', 'element-ready-lite'),
					'after_title'  => __('After Title', 'element-ready-lite'),
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
				'label'       => __('Description', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __('Description.', 'element-ready-lite'),
			]
		);

		// MailChimp Subscribe Form Post Url
		$this->add_control(
			'mailchimp_post_url',
			[
				'label'       => __('MailChimp Post Url', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __('MailChimp Post Url Here.', 'element-ready-lite'),
				'description'   => wp_kses_post('(<a href="' . esc_url('https://www.tassos.gr/blog/how-to-get-mailchimp-form-submit-url') . '" target="_blank">See How To Get Post Url</a>)', 'element-ready-lite'),
			]
		);

		// MailChimp Subscribe Form Post Url
		$this->add_control(
			'placeholder_text',
			[
				'label'       => __('Email Placeholder Text', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __('mail@example.com', 'element-ready-lite'),
			]
		);

		// Button Toggle
		$this->add_control(
			'show_button',
			[
				'label'        => __('Show Button ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __('Show', 'element-ready-lite'),
				'label_off'    => __('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		// Button Title
		$this->add_control(
			'button_text',
			[
				'label'       => __('Button Title', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __('Button Text', 'element-ready-lite'),
				'condition'   => ['show_button' => 'yes'],
				'default'      => 'subscribe',
			]
		);

		// Button Icon Picker
		$this->add_control(
			'button_icon',
			[
				'label'       => __('Icon', 'element-ready-lite'),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => '',
				'condition'   => ['show_button' => 'yes'],
			]
		);

		// Button Icon Align
		$this->add_control(
			'button_icon_align',
			[
				'label'   => __('Icon Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'  => __('Before', 'element-ready-lite'),
					'right' => __('After', 'element-ready-lite'),
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
				'label' => __('Icon Spacing', 'element-ready-lite'),
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
					'{{WRAPPER}} .subscribe__btn .subscribe__btn_icon_right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .subscribe__btn .subscribe__btn_icon_left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		/*********************************
		 * 		STYLE SECTION
		 *********************************/

		/*----------------------------
			ICON STYLE
		-----------------------------*/
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => __('Icon', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Icon Typgraphy
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'icon_typography',
				'selector'  => '{{WRAPPER}} .box__icon',
				'condition' => [
					'icon_type' => ['font_icon']
				],
			]
		);

		// Icon Image Size
		$this->add_responsive_control(
			'icon_image_size',
			[
				'label'      => __('Icon Image Size', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_type' => ['image_icon']
				],
			]
		);

		// Icon Hr
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
				'label' => __('Normal', 'element-ready-lite'),
			]
		);

		// Icon Image Filter
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'icon_image_filters',
				'selector'  => '{{WRAPPER}} .box__icon img',
				'condition' => [
					'icon_type' => ['image_icon']
				],
			]
		);

		// Icon Color
		$this->add_control(
			'icon_color',
			[
				'label'     => __('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .box__icon' => 'color: {{VALUE}};',
				],
			]
		);

		// Icon Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_background',
				'label'    => __('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .box__icon',
			]
		);

		// Icon Hr
		$this->add_control(
			'icon_hr2',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Icon Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'icon_border',
				'label'    => __('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .box__icon',
			]
		);

		// Icon Radius
		$this->add_control(
			'icon_radius',
			[
				'label'      => __('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .box__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Icon Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_shadow',
				'selector' => '{{WRAPPER}} .box__icon',
			]
		);

		// Icon Hr
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
				'label' => __('Hover', 'element-ready-lite'),
			]
		);

		// Icon Image Filter
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'hover_icon_image_filters',
				'selector'  => '{{WRAPPER}} :hover .box__icon img',
				'condition' => [
					'icon_type' => ['image_icon']
				],
			]
		);

		// Box Hover Icon Color
		$this->add_control(
			'hover_icon_color',
			[
				'label'     => __('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .box__icon, {{WRAPPER}} :focus .box__icon' => 'color: {{VALUE}};',
				],
			]
		);

		// Box Hover Icon Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_icon_background',
				'label'    => __('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} :hover .box__icon,{{WRAPPER}} :focus .box__icon',
			]
		);

		// Icon Hr
		$this->add_control(
			'icon_hr4',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Icon Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_icon_border',
				'label'    => __('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} :hover .box__icon,{{WRAPPER}} :hover .box__icon',
			]
		);

		// Icon Radius
		$this->add_control(
			'hover_icon_radius',
			[
				'label'      => __('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} :hover .box__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Icon Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_icon_shadow',
				'selector' => '{{WRAPPER}} :hover .box__icon',
			]
		);

		// Icon Hover Animation
		$this->add_control(
			'icon_hover_animation',
			[
				'label'    => __('Hover Animation', 'element-ready-lite'),
				'type'     => Controls_Manager::HOVER_ANIMATION,
				'selector' => '{{WRAPPER}} :hover .box__icon',
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

		// Icon Width
		$this->add_control(
			'icon_width',
			[
				'label'      => __('Width', 'element-ready-lite'),
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
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .box__icon' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Icon Height
		$this->add_control(
			'icon_height',
			[
				'label'      => __('Height', 'element-ready-lite'),
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
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .box__icon' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Icon Hr
		$this->add_control(
			'icon_hr5',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Icon Display;
		$this->add_responsive_control(
			'icon_display',
			[
				'label'   => __('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline-block',

				'options' => [
					'initial'      => __('Initial', 'element-ready-lite'),
					'block'        => __('Block', 'element-ready-lite'),
					'inline-block' => __('Inline Block', 'element-ready-lite'),
					'flex'         => __('Flex', 'element-ready-lite'),
					'inline-flex'  => __('Inline Flex', 'element-ready-lite'),
					'none'         => __('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .box__icon' => 'display: {{VALUE}};',
				],
			]
		);

		// Icon Alignment
		$this->add_control(
			'icon_align',
			[
				'label'   => __('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'element-ready-lite'),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __('Center', 'element-ready-lite'),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __('Right', 'element-ready-lite'),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __('Justify', 'element-ready-lite'),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .box__icon' => 'text-align: {{VALUE}};',
				],
				'default' => 'center',
			]
		);

		// Icon Hr
		$this->add_control(
			'icon_hr6',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Icon Postion
		$this->add_responsive_control(
			'icon_position',
			[
				'label'   => __('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'initial',

				'options' => [
					'initial'  => __('Initial', 'element-ready-lite'),
					'absolute' => __('Absolute', 'element-ready-lite'),
					'relative' => __('Relative', 'element-ready-lite'),
					'static'   => __('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .box__icon' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'icon_position_from_left',
			[
				'label'      => __('From Left', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'icon_position_from_right',
			[
				'label'      => __('From Right', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'icon_position_from_top',
			[
				'label'      => __('From Top', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Bottom
		$this->add_responsive_control(
			'icon_position_from_bottom',
			[
				'label'      => __('From Bottom', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position!' => ['initial', 'static']
				],
			]
		);

		// Icon Transition
		$this->add_control(
			'icon_transition',
			[
				'label'      => __('Transition', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon,{{WRAPPER}} .box__icon img' => 'transition: {{SIZE}}s;',
				],
			]
		);

		// Icon Hr
		$this->add_control(
			'icon_hr7',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Icon Margin
		$this->add_responsive_control(
			'icon_margin',
			[
				'label'      => __('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .box__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Icon Hr
		$this->add_control(
			'icon_hr8',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Icon Padding
		$this->add_responsive_control(
			'icon_padding',
			[
				'label'      => __('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .box__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

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
				'label' => __('Title', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Title Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .box__title',
			]
		);

		$this->start_controls_tabs('title_tab_style');
		$this->start_controls_tab(
			'title_normal_tab',
			[
				'label' => __('Normal', 'element-ready-lite'),
			]
		);

		// Title Color
		$this->add_control(
			'title_text_color',
			[
				'label'     => __('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .box__title, {{WRAPPER}} .box__title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'title_hover_tab',
			[
				'label' => __('Hover', 'element-ready-lite'),
			]
		);

		// Title Hover Link Color
		$this->add_control(
			'hover_title_color',
			[
				'label'     => __('Link Color', 'element-ready-lite'),
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
				'label'     => __('Box Hover Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .box__title a, {{WRAPPER}} :focus .box__title a, {{WRAPPER}} :hover .box__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Title Margin
		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => __('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .box__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

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
				'label' => __('Before / After', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('title_before_after_tab_style');
		$this->start_controls_tab(
			'title_before_tab',
			[
				'label' => __('BEFORE', 'element-ready-lite'),
			]
		);

		// Title Before Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'before_background',
				'label'    => __('Background', 'plugin-domain'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .box__title:before',
			]
		);

		// Title Before Display;
		$this->add_responsive_control(
			'title_before_display',
			[
				'label'   => __('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'      => __('Initial', 'element-ready-lite'),
					'block'        => __('Block', 'element-ready-lite'),
					'inline-block' => __('Inline Block', 'element-ready-lite'),
					'flex'         => __('Flex', 'element-ready-lite'),
					'inline-flex'  => __('Inline Flex', 'element-ready-lite'),
					'none'         => __('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .box__title:before' => 'display: {{VALUE}};',
				],
			]
		);

		// Title Before Postion
		$this->add_responsive_control(
			'before_position',
			[
				'label'   => __('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'relative',

				'options' => [
					'initial'  => __('Initial', 'element-ready-lite'),
					'absolute' => __('Absolute', 'element-ready-lite'),
					'relative' => __('Relative', 'element-ready-lite'),
					'static'   => __('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .box__title:before' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'before_position_from_left',
			[
				'label'      => __('From Left', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'before_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'before_position_from_right',
			[
				'label'      => __('From Right', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'before_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'before_position_from_top',
			[
				'label'      => __('From Top', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'before_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Bottom
		$this->add_responsive_control(
			'before_position_from_bottom',
			[
				'label'      => __('From Bottom', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'before_position!' => ['initial', 'static']
				],
			]
		);

		// Title Before Align
		$this->add_responsive_control(
			'title_before_align',
			[
				'label'   => __('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'text-align:left' => [
						'title' => __('Left', 'element-ready-lite'),
						'icon'  => 'fa fa-align-left',
					],
					'margin: 0 auto' => [
						'title' => __('Center', 'element-ready-lite'),
						'icon'  => 'fa fa-align-center',
					],
					'float:right' => [
						'title' => __('Right', 'element-ready-lite'),
						'icon'  => 'fa fa-align-right',
					],
					'text-align:justify' => [
						'title' => __('Justify', 'element-ready-lite'),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .box__title:before' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);

		// Title Before Width
		$this->add_responsive_control(
			'title_before_width',
			[
				'label'      => __('Width', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__title:before' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Title Before Height
		$this->add_responsive_control(
			'title_before_height',
			[
				'label'      => __('Height', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__title:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Title Before Opacity
		$this->add_control(
			'before_opacity',
			[
				'label' => __('Opacity', 'element-ready-lite'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .box__title:before' => 'opacity: {{SIZE}};',
				],
			]
		);

		// Title Before Z-Index
		$this->add_control(
			'before_zindex',
			[
				'label'     => __('Z-Index', 'element-ready-lite'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => -99,
				'max'       => 99,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .box__title:before' => 'z-index: {{SIZE}};',
				],
			]
		);

		// Title Before Margin
		$this->add_responsive_control(
			'title_before_margin',
			[
				'label'      => __('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .box__title:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_after_tab',
			[
				'label' => __('AFTER', 'element-ready-lite'),
			]
		);

		// Title After Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'after_background',
				'label'    => __('Background', 'plugin-domain'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .box__title:after',
			]
		);

		// Title After Display;
		$this->add_responsive_control(
			'title_after_display',
			[
				'label'   => __('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial'      => __('Initial', 'element-ready-lite'),
					'block'        => __('Block', 'element-ready-lite'),
					'inline-block' => __('Inline Block', 'element-ready-lite'),
					'flex'         => __('Flex', 'element-ready-lite'),
					'inline-flex'  => __('Inline Flex', 'element-ready-lite'),
					'none'         => __('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .box__title:after' => 'display: {{VALUE}};',
				],
			]
		);

		// Title After Postion
		$this->add_responsive_control(
			'after_position',
			[
				'label'   => __('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'relative',

				'options' => [
					'initial'  => __('Initial', 'element-ready-lite'),
					'absolute' => __('Absolute', 'element-ready-lite'),
					'relative' => __('Relative', 'element-ready-lite'),
					'static'   => __('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .box__title:after' => 'position: {{VALUE}};',
				],
			]
		);

		// Postion From Left
		$this->add_responsive_control(
			'after_position_from_left',
			[
				'label'      => __('From Left', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'after_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Right
		$this->add_responsive_control(
			'after_position_from_right',
			[
				'label'      => __('From Right', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'after_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Top
		$this->add_responsive_control(
			'after_position_from_top',
			[
				'label'      => __('From Top', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'after_position!' => ['initial', 'static']
				],
			]
		);

		// Postion From Bottom
		$this->add_responsive_control(
			'after_position_from_bottom',
			[
				'label'      => __('From Bottom', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__icon' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'after_position!' => ['initial', 'static']
				],
			]
		);

		// Title After Align
		$this->add_responsive_control(
			'title_after_align',
			[
				'label'   => __('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'text-align:left' => [
						'title' => __('Left', 'element-ready-lite'),
						'icon'  => 'fa fa-align-left',
					],
					'margin: 0 auto' => [
						'title' => __('Center', 'element-ready-lite'),
						'icon'  => 'fa fa-align-center',
					],
					'float:right' => [
						'title' => __('Right', 'element-ready-lite'),
						'icon'  => 'fa fa-align-right',
					],
					'text-align:justify' => [
						'title' => __('Justify', 'element-ready-lite'),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .box__title:after' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);

		// Title After Width
		$this->add_responsive_control(
			'title_after_width',
			[
				'label'      => __('Width', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__title:after' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Title After Height
		$this->add_responsive_control(
			'title_after_height',
			[
				'label'      => __('Height', 'element-ready-lite'),
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
					'{{WRAPPER}} .box__title:after' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Title After Opacity
		$this->add_control(
			'after_opacity',
			[
				'label' => __('Opacity', 'element-ready-lite'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .box__title:after' => 'opacity: {{SIZE}};',
				],
			]
		);

		// Title After Z-Index
		$this->add_control(
			'after_zindex',
			[
				'label'     => __('Z-Index', 'element-ready-lite'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => -99,
				'max'       => 99,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .box__title:after' => 'z-index: {{SIZE}};',
				],
			]
		);

		// Title After Margin
		$this->add_responsive_control(
			'title_after_margin',
			[
				'label'      => __('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .box__title:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

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
				'label' => __('Subtitle', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
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
				'label'  => __('Color', 'element-ready-lite'),
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
				'label'  => __('Box Hover Color', 'element-ready-lite'),
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
				'label'      => __('Margin', 'element-ready-lite'),
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
			INPUT STYLE
		-----------------------------*/
		$this->start_controls_section(
			'input_style_section',
			[
				'label' => __('Input', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Display;
		$this->add_responsive_control(
			'input_display',
			[
				'label'   => __('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline-block',

				'options' => [
					'initial'      => __('Initial', 'element-ready-lite'),
					'block'        => __('Block', 'element-ready-lite'),
					'inline-block' => __('Inline Block', 'element-ready-lite'),
					'flex'         => __('Flex', 'element-ready-lite'),
					'inline-flex'  => __('Inline Flex', 'element-ready-lite'),
					'none'         => __('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .subscriber__email' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_float',
			[
				'label'   => __('Float', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'left'  =>  __('Left', 'element-ready-lite'),
					'right' =>  __('Right', 'element-ready-lite'),
					'none'  =>  __('None', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .subscriber__email' => 'float:{{VALUE}};',
				],
			]
		);

		// Button Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .subscriber__email',
			]
		);

		// Button Hr
		$this->add_control(
			'normal_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);


		$this->start_controls_tabs('input_tab_style');
		$this->start_controls_tab(
			'input_normal_tab',
			[
				'label' => __('Normal', 'element-ready-lite'),
			]
		);

		// Button Color
		$this->add_control(
			'input_color',
			[
				'label'     => __('Input Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} a.subscriber__email, {{WRAPPER}} .subscriber__email' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label'     => __('Placeholder Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} a.subscriber__email::-webkit-input-placeholder, {{WRAPPER}} .subscriber__email::-webkit-input-placeholder,{{WRAPPER}} .subscriber__email::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		// Button Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'input_background',
				'label'    => __('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .subscriber__email',
			]
		);

		// Button Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'input_border',
				'label'    => __('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .subscriber__email',
			]
		);

		// Button Radius
		$this->add_control(
			'input_radius',
			[
				'label'      => __('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .subscriber__email' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Button Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'input_shadow',
				'selector' => '{{WRAPPER}} .subscriber__email',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'input_hover_tab',
			[
				'label' => __('Hover', 'element-ready-lite'),
			]
		);

		// Button Hover Color
		$this->add_control(
			'hover_input_color',
			[
				'label'     => __('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .subscriber__email:hover, {{WRAPPER}} a.subscriber__email:focus, {{WRAPPER}} .subscriber__email:focus' => 'color: {{VALUE}};',
				],
			]
		);

		// Button Hover BG
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_input_background',
				'label'    => __('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .subscriber__email:hover,{{WRAPPER}} .subscriber__email:focus',
			]
		);

		// Button Radius
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_input_border',
				'label'    => __('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .subscriber__email:hover,{{WRAPPER}} .subscriber__email:focus',
			]
		);

		// Button Hover Radius
		$this->add_control(
			'hover_input_radius',
			[
				'label'      => __('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .subscriber__email:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Button Hover Box Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_input_shadow',
				'selector' => '{{WRAPPER}} .subscriber__email:hover',
			]
		);

		// Button Hover Animation
		$this->add_control(
			'input_hover_animation',
			[
				'label'    => __('Hover Animation', 'element-ready-lite'),
				'type'     => Controls_Manager::HOVER_ANIMATION,
				'selector' => '{{WRAPPER}} .subscriber__email:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Button Hr
		$this->add_control(
			'input_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Width
		$this->add_responsive_control(
			'input_width',
			[
				'label'      => __('Width', 'element-ready-lite'),
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
					'{{WRAPPER}} .subscriber__email' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height
		$this->add_responsive_control(
			'input_height',
			[
				'label'      => __('Height', 'element-ready-lite'),
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
					'{{WRAPPER}} .subscriber__email' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Button Hr
		$this->add_control(
			'input_hr2',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Button Margin
		$this->add_responsive_control(
			'input_margin',
			[
				'label'      => __('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .subscriber__email' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Button Padding
		$this->add_responsive_control(
			'input_padding',
			[
				'label'      => __('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .subscriber__email' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			INPUT STYLE END
		-----------------------------*/

		/*----------------------------
			BUTTON STYLE
		-----------------------------*/
		$this->start_controls_section(
			'button_style_section',
			[
				'label' => __('Button', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Display;
		$this->add_responsive_control(
			'button_display',
			[
				'label'   => __('Display', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline-block',

				'options' => [
					'initial'      => __('Initial', 'element-ready-lite'),
					'block'        => __('Block', 'element-ready-lite'),
					'inline-block' => __('Inline Block', 'element-ready-lite'),
					'flex'         => __('Flex', 'element-ready-lite'),
					'inline-flex'  => __('Inline Flex', 'element-ready-lite'),
					'none'         => __('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .subscribe__btn' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'button_float',
			[
				'label'   => __('Float', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'left'  =>  __('Left', 'element-ready-lite'),
					'right' =>  __('Right', 'element-ready-lite'),
					'none'  =>  __('None', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .subscribe__btn' => 'float:{{VALUE}};',
				],
			]
		);
		// Button Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .subscribe__btn',
			]
		);

		// Button Hr
		$this->add_control(
			'btn_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);


		$this->start_controls_tabs('button_tab_style');
		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => __('Normal', 'element-ready-lite'),
			]
		);

		// Button Color
		$this->add_control(
			'button_color',
			[
				'label'     => __('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} a.subscribe__btn, {{WRAPPER}} .subscribe__btn' => 'color: {{VALUE}};',
				],
			]
		);

		// Button Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background',
				'label'    => __('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .subscribe__btn',
			]
		);

		// Button Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'label'    => __('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .subscribe__btn',
			]
		);

		// Button Radius
		$this->add_control(
			'button_radius',
			[
				'label'      => __('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .subscribe__btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Button Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_shadow',
				'selector' => '{{WRAPPER}} .subscribe__btn',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => __('Hover', 'element-ready-lite'),
			]
		);

		// Button Hover Color
		$this->add_control(
			'hover_button_color',
			[
				'label'     => __('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .subscribe__btn:hover, {{WRAPPER}} a.subscribe__btn:focus, {{WRAPPER}} .subscribe__btn:focus' => 'color: {{VALUE}};',
				],
			]
		);

		// Button Hover BG
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_button_background',
				'label'    => __('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .subscribe__btn:hover,{{WRAPPER}} .subscribe__btn:focus',
			]
		);

		// Button Radius
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_button_border',
				'label'    => __('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .subscribe__btn:hover,{{WRAPPER}} .subscribe__btn:focus',
			]
		);

		// Button Hover Radius
		$this->add_control(
			'hover_button_radius',
			[
				'label'      => __('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .subscribe__btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Button Hover Box Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_button_shadow',
				'selector' => '{{WRAPPER}} .subscribe__btn:hover',
			]
		);

		// Button Hover Animation
		$this->add_control(
			'button_hover_animation',
			[
				'label'    => __('Hover Animation', 'element-ready-lite'),
				'type'     => Controls_Manager::HOVER_ANIMATION,
				'selector' => '{{WRAPPER}} .subscribe__btn:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Button Hr
		$this->add_control(
			'button_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Width
		$this->add_responsive_control(
			'button_width',
			[
				'label'      => __('Width', 'element-ready-lite'),
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
					'{{WRAPPER}} .subscribe__btn' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Height
		$this->add_responsive_control(
			'button_height',
			[
				'label'      => __('Height', 'element-ready-lite'),
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
					'{{WRAPPER}} .subscribe__btn' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Button Hr
		$this->add_control(
			'button_hr2',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		// Button Margin
		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => __('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .subscribe__btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Button Padding
		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => __('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .subscribe__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

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
				'label' => __('Box', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Box Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .mailchimp_from__box',
			]
		);

		// Box Default Color
		$this->add_control(
			'box_color',
			[
				'label'  => __('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mailchimp_from__box' => 'color: {{VALUE}}',
				],
			]
		);

		// Box Hover Color
		$this->add_control(
			'hover_box_color',
			[
				'label'  => __('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .mailchimp_from__box' => 'color: {{VALUE}}',
				],
			]
		);

		// Box Align
		$this->add_responsive_control(
			'box_align',
			[
				'label'   => __('Alignment', 'element-ready-lite'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'element-ready-lite'),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __('Center', 'element-ready-lite'),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __('Right', 'element-ready-lite'),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __('Justify', 'element-ready-lite'),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
				'default' => 'center',
			]
		);

		// Box Transition
		$this->add_control(
			'box_transition',
			[
				'label'      => __('Transition', 'element-ready-lite'),
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
					'{{WRAPPER}}' => 'transition: {{SIZE}}s;',
				],
			]
		);

		// Postion
		$this->add_responsive_control(
			'box_position',
			[
				'label'   => __('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'initial',

				'options' => [
					'initial'  => __('Initial', 'element-ready-lite'),
					'absolute' => __('Absolute', 'element-ready-lite'),
					'relative' => __('Relative', 'element-ready-lite'),
					'static'   => __('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}}' => 'position: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			BOX STYLE END
		-----------------------------*/
	}

	protected function render()
	{

		$r_id = rand(5655, 5874);

		$settings = $this->get_settings_for_display();


		// Button Link Attr
		if ('yes' == $settings['show_button']) {
			$this->add_render_attribute('submit_button', 'class', 'subscribe__btn');
			if (!empty($settings['button_hover_animation'])) {
				$this->add_render_attribute('submit_button', 'class', 'elementor-animation-' . esc_attr($settings['button_hover_animation']));
			}
			$this->add_render_attribute('submit_button', 'type', 'submit');
		}

		// Button animation
		if ($settings['button_hover_animation']) {
			$button_animation = 'elementor-animation-' . esc_attr($settings['button_hover_animation']);
		} else {
			$button_animation = '';
		}


		// Icon Animation
		if ($settings['icon_hover_animation']) {
			$icon_animation = 'elementor-animation-' . esc_attr($settings['icon_hover_animation']);
		} else {
			$icon_animation = '';
		}

		// Icon Condition
		if ('yes' == $settings['show_icon']) {
			if ('font_icon' == $settings['icon_type'] && !empty($settings['font_icon'])) {
				$icon = '<div class="box__icon ' . esc_attr($icon_animation) . '"><i class="' . esc_attr($settings['font_icon']) . '"></i></div>';
			} elseif ('image_icon' == $settings['icon_type'] && !empty($settings['image_icon'])) {
				$icon_array = $settings['image_icon'];
				$icon_link = wp_get_attachment_image_url($icon_array['id'], 'thumbnail');
				$icon = '<div class="box__icon ' . esc_attr($icon_animation) . '"><img src="' . esc_url($icon_link) . '" alt="" /></div>';
			}
		} else {
			$icon = '';
		}

		// Title Tag
		if (!empty($settings['title_tag'])) {
			$title_tag = $settings['title_tag'];
		} else {
			$title_tag = 'div';
		}

		// Title
		if (!empty($settings['title'])) {
			$title = '<' . $title_tag . ' class="box__title">' . wpautop($settings['title']) . '</' . $title_tag . '>';
		} else {
			$title = '';
		}

		// Subtitle
		if (!empty($settings['subtitle'])) {
			$subtitle = '<div class="box__subtitle">' . esc_html($settings['subtitle']) . '</div>';
		} else {
			$subtitle = '';
		}

		// Description
		if (!empty($settings['description'])) {
			$description = '<div class="box__description">' . wpautop($settings['description']) . '</div>';
		}

		// Button
		if ('yes' == $settings['show_button'] && !empty($settings['button_text'])) {
			$button = '<button ' . $this->get_render_attribute_string('submit_button') . '>' . esc_html($settings['button_text']) . '</button>';
		}

		// Button With Icon
		if (!empty($settings['button_icon'])) {
			if ('left' == $settings['button_icon_align']) {
				$button = '<button ' . $this->get_render_attribute_string('submit_button') . '><i class="subscribe__btn_icon_left ' . esc_attr($settings['button_icon']) . '"></i>' . esc_html($settings['button_text']) . '</button>';
			} elseif ('right' == $settings['button_icon_align']) {
				$button = '<button ' . $this->get_render_attribute_string('submit_button') . '>' . esc_html($settings['button_text']) . '<i class="subscribe__btn_icon_right ' . esc_attr($settings['button_icon']) . '"></i></button>';
			}
		}

		// Title Condition
		if ('before_title' == $settings['subtitle_position']) {
			$title_subtitle = $subtitle . $title;
		} elseif ('after_title' == $settings['subtitle_position']) {
			$title_subtitle = $title . $subtitle;
		} elseif (empty($settings['subtitle'])) {
			$title_subtitle = $title . $subtitle;
		}

		if (!empty($settings['mailchimp_post_url'])) {
			$post_url = $settings['mailchimp_post_url'];
		} else {
			$post_url = 'http://intimissibd.us14.list-manage.com/subscribe/post?u=a77a312486b6e42518623c58a&amp;id=4af1f9af4c';
		}

		if (!empty($settings['placeholder_text'])) {
			$placeholder_text = $settings['placeholder_text'];
		} else {
			$placeholder_text = 'email@example.com';
		}

		$parse_data = array(
			'random_id'   => $r_id,
			'post_url'    => $post_url,
			'placeholder' => $placeholder_text,
		);

		$form = '
		<form id="mc__form__' . $r_id . '" class="subscriber__form">
			<label class="subscribe__label" for="mc__email__' . $r_id . '"></label>
	        <input class="subscriber__email" type="email" id="mc__email__' . $r_id . '" placeholder="' . esc_attr($placeholder_text) . '">
	        ' . (isset($button) ? $button : '') . '
		</form>';

		$this->add_render_attribute('subscriber-form-attr', 'class', 'mailchimp_from__box');
		$this->add_render_attribute('subscriber-form-attr', 'data-value', wp_json_encode($parse_data));

		echo '
			<div ' . $this->get_render_attribute_string('subscriber-form-attr') . '>				
				' . (isset($icon) ? $icon : '') . '
				' . (isset($title_subtitle) ? $title_subtitle : '') . '
				' . (isset($description) ? $description : '') . '
				' . (isset($form) ? $form : '') . '
			</div>
		';
	}

	protected function content_template()
	{
	}
}
