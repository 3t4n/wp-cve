<?php

namespace Element_Ready\Widgets\area_title;

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

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Element_Ready_Area_Title_Widget extends Widget_Base
{


	public function get_name()
	{

		return 'Element_Ready_Area_Title_Widget';
	}

	public function get_title()
	{

		return esc_html__('ER Area Title', 'element-ready-lite');
	}

	public function get_icon()
	{

		return 'eicon-site-title';
	}

	public function get_categories()
	{

		return array('element-ready-addons');
	}

	public function get_keywords()
	{

		return ['title', 'area title', 'section title', 'page title'];
	}

	public function get_style_depends()
	{

		wp_register_style('eready-area-title', ELEMENT_READY_ROOT_CSS . 'widgets/area-title.css');
		return ['eready-area-title'];
	}

	public static function title_before_style()
	{
		return [
			'set_title_before'       => esc_html__('Set Title Before', 'element-ready-lite'),
			'set_subtitle_before'    => esc_html__('Set Subtitle Before', 'element-ready-lite'),
			'set_description_before' => esc_html__('Set Description Before', 'eleemnt-ready'),
			'set_box_before'         => esc_html__('Set Box Before', 'element-ready-lite'),
			'set_container_before'   => esc_html__('Set Container Before', 'element-ready-lite'),
		];
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
		$this->add_control(
			'show_icon',
			[
				'label'        => esc_html__('Show Icon ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'element-ready-lite'),
				'label_off'    => esc_html__('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);
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
		$this->add_control(
			'font_icon',
			[
				'label'     => esc_html__('SVG / Font Icons', 'element-ready-lite'),
				'type'      => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'label_block' => true,
				'condition' => [
					'icon_type' => 'font_icon',
					'show_icon' => 'yes',
				],
			]
		);
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
		$this->add_control(
			'show_bg_icon',
			[
				'label'        => esc_html__('Title Background Icon ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'element-ready-lite'),
				'label_off'    => esc_html__('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'   => 'before',
			]
		);
		$this->add_control(
			'bg_icon_type',
			[
				'label'   => esc_html__('Background Icon Type', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'font_icon',
				'options' => [
					'font_icon'  => esc_html__('SVG / Font Icon', 'element-ready-lite'),
					'image_icon' => esc_html__('Image Icon', 'element-ready-lite'),
				],
				'condition' => [
					'show_bg_icon' => 'yes',
				],
			]
		);
		$this->add_control(
			'bg_font_or_svg',
			[
				'label'     => esc_html__('SVG / Font Icon', 'element-ready-lite'),
				'type'      => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'label_block' => true,
				'condition' => [
					'show_bg_icon' => 'yes',
					'bg_icon_type' => 'font_icon',
				],
			]
		);
		$this->add_control(
			'bg_image_icon',
			[
				'label'   => esc_html__('Upload Image OR SVG Icon', 'element-ready-lite'),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'show_bg_icon' => 'yes',
					'bg_icon_type' => 'image_icon',
				],
			]
		);
		$this->add_control(
			'show_bg_text',
			[
				'label'        => esc_html__('Title Background Text ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'element-ready-lite'),
				'label_off'    => esc_html__('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'title_bg_text',
			[
				'label'       => esc_html__('Title Background Text', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Background Text', 'element-ready-lite'),
				'condition'   => [
					'show_bg_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__('Title', 'element-ready-lite'),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__('Title', 'element-ready-lite'),
				'separator'   => 'before',
			]
		);

		$this->add_control(
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
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label'       => esc_html__('Subtitle', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Subtitle', 'element-ready-lite'),
				'separator'   => 'before',
			]
		);

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

		$this->add_control(
			'description',
			[
				'label'       => esc_html__('Description', 'element-ready-lite'),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__('Description.', 'element-ready-lite'),
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'show_button',
			[
				'label'        => esc_html__('Show Button ?', 'element-ready-lite'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'element-ready-lite'),
				'label_off'    => esc_html__('Hide', 'element-ready-lite'),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => esc_html__('Button Title', 'element-ready-lite'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Button Text', 'element-ready-lite'),
				'condition'   => ['show_button' => 'yes'],
			]
		);

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

		$this->add_control(
			'button_icon',
			[
				'label'       => esc_html__('Icon', 'element-ready-lite'),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => '',
				'condition'   => ['show_button' => 'yes'],
			]
		);

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
					'{{WRAPPER}} .area__button .area__button_icon_right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .area__button .area__button_icon_left'  => 'margin-right: {{SIZE}}{{UNIT}};',
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
				'label'     => esc_html__('Icon', 'element-ready-lite'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_icon' => 'yes',
				],
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
				'selector'  => '{{WRAPPER}} .area__icon',
				'condition' => [
					'icon_type' => ['font_icon']
				],
			]
		);
		$this->add_responsive_control(
			'icon_image_size',
			[
				'label'      => esc_html__('SVG / Image Size', 'element-ready-lite'),
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
					'{{WRAPPER}} .area__icon img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .area__icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'icon_image_filters',
				'selector'  => '{{WRAPPER}} .area__icon img',
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
					'{{WRAPPER}} .area__icon' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__icon',
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
				'selector' => '{{WRAPPER}} .area__icon',
			]
		);
		$this->add_control(
			'icon_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_shadow',
				'selector' => '{{WRAPPER}} .area__icon',
			]
		);
		$this->add_control(
			'icon_hr3',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);


		$this->add_responsive_control(
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
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .area__icon' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
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
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .area__icon' => 'height: {{SIZE}}{{UNIT}};',
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
				'default' => 'inline-block',

				'options' => [
					'initial'      => esc_html__('Initial', 'element-ready-lite'),
					'block'        => esc_html__('Block', 'element-ready-lite'),
					'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
					'flex'         => esc_html__('Flex', 'element-ready-lite'),
					'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
					'none'         => esc_html__('none', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__icon' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
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
					'{{WRAPPER}} .area__icon' => 'text-align: {{VALUE}};',
				],
				'default' => 'center',
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
				'default' => 'initial',

				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__icon' => 'position: {{VALUE}};',
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
					'{{WRAPPER}} .area__icon' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position!' => ['initial', 'static']
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
					'{{WRAPPER}} .area__icon' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position!' => ['initial', 'static']
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
					'{{WRAPPER}} .area__icon' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position!' => ['initial', 'static']
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
					'{{WRAPPER}} .area__icon' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position!' => ['initial', 'static']
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
					'{{WRAPPER}} .area__icon,{{WRAPPER}} .area__icon img' => 'transition: {{SIZE}}s;',
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
					'{{WRAPPER}} .area__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .area__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector'  => '{{WRAPPER}} :hover .area__icon img',
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
					'{{WRAPPER}} :hover .area__icon, {{WRAPPER}} :focus .area__icon' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_icon_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} :hover .area__icon,{{WRAPPER}} :focus .area__icon',
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
				'selector' => '{{WRAPPER}} :hover .area__icon,{{WRAPPER}} :hover .area__icon',
			]
		);
		$this->add_control(
			'hover_icon_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} :hover .area__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_icon_shadow',
				'selector' => '{{WRAPPER}} .area__icon:hover',
			]
		);
		$this->add_control(
			'icon_hover_animation',
			[
				'label'    => esc_html__('Hover Animation', 'element-ready-lite'),
				'type'     => Controls_Manager::HOVER_ANIMATION,
				'selector' => '{{WRAPPER}} :hover .area__icon',
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
			BACKGROUND ICON
		-----------------------------*/
		$this->start_controls_section(
			'bgicon_style_section',
			[
				'label'     => esc_html__('Background Icon', 'element-ready-lite'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_bg_icon' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'bgicon_typography',
				'selector' => '{{WRAPPER}} .area__content .title__bg__icon',
			]
		);
		$this->add_control(
			'bgicon_text_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .area__content .title__bg__icon' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'bgicon_text_width',
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
					'size' => '100'
				],
				'selectors' => [
					'{{WRAPPER}} .area__content .title__bg__icon' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'bgicon_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__content .title__bg__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'bgicon_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__content .title__bg__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'bgicon_opacity',
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
					'{{WRAPPER}} .area__content .title__bg__icon' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->end_controls_section();
		/*----------------------------
			BACKGROUND ICON END
		-----------------------------*/

		/*----------------------------
			BACKGROUND TEXT
		-----------------------------*/
		$this->start_controls_section(
			'bgtext_style_section',
			[
				'label'     => esc_html__('Background Text', 'element-ready-lite'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_bg_text' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'bgtext_typography',
				'selector' => '{{WRAPPER}} .area__content .title__bg__text',
			]
		);
		$this->add_control(
			'bgtext_text_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .area__content .title__bg__text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_bg_text_shadow',
				'label' => esc_html__('Text Shadow', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .area__content .title__bg__text',
				'condition' => [
					'title_bg_text!' => ''
				]
			]
		);

		$this->add_responsive_control(
			'title_bg_text_custom_tab_area_css',
			[
				'label'     => esc_html__('Custom CSS', 'element-ready-lite'),
				'type'      => Controls_Manager::CODE,
				'rows'      => 20,
				'language'  => 'css',
				'selectors' => [
					'{{WRAPPER}} .area__content .title__bg__text' => '{{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'title_bg_text!' => ''
				]
			]
		);
		$this->add_responsive_control(
			'bgtext_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__content .title__bg__text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'bgtext_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__content .title__bg__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'bgtext_opacity',
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
					'{{WRAPPER}} .area__content .title__bg__text' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->end_controls_section();
		/*----------------------------
			BACKGROUND TEXT END
		-----------------------------*/

		/*----------------------------
			TITLE STYLE
		-----------------------------*/
		$this->start_controls_section(
			'title_style_section',
			[
				'label'     => esc_html__('Title', 'element-ready-lite'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => '',
				]
			]
		);
		$this->start_controls_tabs('title_tab_style');
		$this->start_controls_tab(
			'title_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);
		$this->add_control(
			'title_text_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .area__title, {{WRAPPER}} .area__title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .area__title',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'title_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__title',
			]
		);
		$this->add_responsive_control(
			'title_display',
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
					'{{WRAPPER}} .area__title' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'title_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .area__title',
			]
		);
		$this->add_responsive_control(
			'title_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .area__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .area__title a:hover, {{WRAPPER}} .area__title a:focus' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'box_hover_title_color',
			[
				'label'     => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .area__title a, {{WRAPPER}} :focus .area__title a, {{WRAPPER}} :hover .area__title' => 'color: {{VALUE}};',
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
				'label'     => esc_html__('Title Before / After', 'element-ready-lite'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => '',
				]
			]
		);
		$this->start_controls_tabs('title_before_after_tab_style');
		$this->start_controls_tab(
			'title_before_tab',
			[
				'label' => esc_html__('BEFORE', 'element-ready-lite'),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'title_before_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__title:before',
			]
		);
		$this->add_responsive_control(
			'title_before_display',
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
					'{{WRAPPER}} .area__title:before' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_before_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'relative',

				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__title:before' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_before_position_from_left',
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
					'{{WRAPPER}} .area__title:before' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_before_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'title_before_position_from_right',
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
					'{{WRAPPER}} .area__title:before' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_before_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'title_before_position_from_top',
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
					'{{WRAPPER}} .area__title:before' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_before_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'title_before_position_from_bottom',
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
					'{{WRAPPER}} .area__title:before' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_before_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'title_before_align',
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
					'{{WRAPPER}} .area__title:before' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);
		$this->add_responsive_control(
			'title_before_width',
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
					'{{WRAPPER}} .area__title:before' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_before_height',
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
					'{{WRAPPER}} .area__title:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'title_before_opacity',
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
					'{{WRAPPER}} .area__title:before' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_control(
			'title_before_zindex',
			[
				'label'     => esc_html__('Z-Index', 'element-ready-lite'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => -99,
				'max'       => 99,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .area__title:before' => 'z-index: {{SIZE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_before_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__title:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'title_after_tab',
			[
				'label' => esc_html__('AFTER', 'element-ready-lite'),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'title_after_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__title:after',
			]
		);
		$this->add_responsive_control(
			'title_after_display',
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
					'{{WRAPPER}} .area__title:after' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_after_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'relative',

				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__title:after' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_after_position_from_left',
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
					'{{WRAPPER}} .area__title:after' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_after_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'title_after_position_from_right',
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
					'{{WRAPPER}} .area__title:after' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_after_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'title_after_position_from_top',
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
					'{{WRAPPER}} .area__title:after' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_after_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'title_after_position_from_bottom',
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
					'{{WRAPPER}} .area__title:after' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_after_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'title_after_align',
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
					'{{WRAPPER}} .area__title:after' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);
		$this->add_responsive_control(
			'title_after_width',
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
					'{{WRAPPER}} .area__title:after' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_after_height',
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
					'{{WRAPPER}} .area__title:after' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'title_after_opacity',
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
					'{{WRAPPER}} .area__title:after' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_control(
			'title_after_zindex',
			[
				'label'     => esc_html__('Z-Index', 'element-ready-lite'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => -99,
				'max'       => 99,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .area__title:after' => 'z-index: {{SIZE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_after_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__title:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label'     => esc_html__('Subtitle', 'element-ready-lite'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'subtitle!' => '',
				]
			]
		);
		$this->add_control(
			'subtitle_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .area__subtitle' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .area__subtitle',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'subtitle_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__subtitle',
			]
		);
		$this->add_responsive_control(
			'subtitle_display',
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
					'{{WRAPPER}} .area__subtitle' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'subtitle_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .area__subtitle',
			]
		);
		$this->add_responsive_control(
			'subtitle_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'box_hover_subtitle_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .area__subtitle' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .area__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		/*----------------------------
			SUBTITLE STYLE END
		-----------------------------*/

		/*----------------------------
			SUBTITLE BEFORE / AFTER
		-----------------------------*/
		$this->start_controls_section(
			'subtitle_before_after_style_section',
			[
				'label'     => esc_html__('Subtitle Before / After', 'element-ready-lite'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'subtitle!' => '',
				]
			]
		);
		$this->start_controls_tabs('subtitle_before_after_tab_style');
		$this->start_controls_tab(
			'subtitle_before_tab',
			[
				'label' => esc_html__('BEFORE', 'element-ready-lite'),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'subtitle_before_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__subtitle:before',
			]
		);
		$this->add_responsive_control(
			'subtitle_before_display',
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
					'{{WRAPPER}} .area__subtitle:before' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_before_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'relative',

				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__subtitle:before' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_before_position_from_left',
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
					'{{WRAPPER}} .area__subtitle:before' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'subtitle_before_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_before_position_from_right',
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
					'{{WRAPPER}} .area__subtitle:before' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'subtitle_before_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_before_position_from_top',
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
					'{{WRAPPER}} .area__subtitle:before' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'subtitle_before_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_before_position_from_bottom',
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
					'{{WRAPPER}} .area__subtitle:before' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'subtitle_before_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_before_align',
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
					'{{WRAPPER}} .area__subtitle:before' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);
		$this->add_responsive_control(
			'subtitle_before_width',
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
					'{{WRAPPER}} .area__subtitle:before' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_before_height',
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
					'{{WRAPPER}} .area__subtitle:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'subtitle_before_opacity',
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
					'{{WRAPPER}} .area__subtitle:before' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_control(
			'subtitle_before_zindex',
			[
				'label'     => esc_html__('Z-Index', 'element-ready-lite'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => -99,
				'max'       => 99,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .area__subtitle:before' => 'z-index: {{SIZE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_before_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__subtitle:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'subtitle_after_tab',
			[
				'label' => esc_html__('AFTER', 'element-ready-lite'),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'subtitle_after_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__subtitle:after',
			]
		);
		$this->add_responsive_control(
			'subtitle_after_display',
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
					'{{WRAPPER}} .area__subtitle:after' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_after_position',
			[
				'label'   => esc_html__('Position', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'relative',

				'options' => [
					'initial'  => esc_html__('Initial', 'element-ready-lite'),
					'absolute' => esc_html__('Absolute', 'element-ready-lite'),
					'relative' => esc_html__('Relative', 'element-ready-lite'),
					'static'   => esc_html__('Static', 'element-ready-lite'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__subtitle:after' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_after_position_from_left',
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
					'{{WRAPPER}} .area__subtitle:after' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'subtitle_after_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_after_position_from_right',
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
					'{{WRAPPER}} .area__subtitle:after' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'subtitle_after_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_after_position_from_top',
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
					'{{WRAPPER}} .area__subtitle:after' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'subtitle_after_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_after_position_from_bottom',
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
					'{{WRAPPER}} .area__subtitle:after' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'subtitle_after_position!' => ['initial', 'static']
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_after_align',
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
					'{{WRAPPER}} .area__subtitle:after' => '{{VALUE}};',
				],
				'default' => 'text-align:left',
			]
		);
		$this->add_responsive_control(
			'subtitle_after_width',
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
					'{{WRAPPER}} .area__subtitle:after' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_after_height',
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
					'{{WRAPPER}} .area__subtitle:after' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'subtitle_after_opacity',
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
					'{{WRAPPER}} .area__subtitle:after' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_control(
			'subtitle_after_zindex',
			[
				'label'     => esc_html__('Z-Index', 'element-ready-lite'),
				'type'      => Controls_Manager::NUMBER,
				'min'       => -99,
				'max'       => 99,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .area__subtitle:after' => 'z-index: {{SIZE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_after_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__subtitle:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*----------------------------
			SUBTITLE BEFORE / AFTER END
		-----------------------------/*

		/*----------------------------
			BUTTON STYLE
		-----------------------------*/
		$this->start_controls_section(
			'button_style_section',
			[
				'label'     => esc_html__('Button', 'element-ready-lite'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_button' => 'yes'
				],
			]
		);
		$this->start_controls_tabs('button_tab_style');
		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);
		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} a.area__button, {{WRAPPER}} .area__button' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .area__button',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__button',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .area__button',
			]
		);
		$this->add_control(
			'button_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_shadow',
				'selector' => '{{WRAPPER}} .area__button',
			]
		);
		$this->add_control(
			'button_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'button_width',
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
					'{{WRAPPER}} .area__button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_height',
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
					'{{WRAPPER}} .area__button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_hr2',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => esc_html__('Margin', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);
		$this->add_control(
			'hover_button_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .area__button:hover, {{WRAPPER}} a.area__button:focus, {{WRAPPER}} .area__button:focus' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_button_background',
				'label'    => esc_html__('Background', 'element-ready-lite'),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__button:hover,{{WRAPPER}} .area__button:focus',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'hover_button_border',
				'label'    => esc_html__('Border', 'element-ready-lite'),
				'selector' => '{{WRAPPER}} .area__button:hover,{{WRAPPER}} .area__button:focus',
			]
		);
		$this->add_control(
			'hover_button_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .area__button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hover_button_shadow',
				'selector' => '{{WRAPPER}} .area__button:hover',
			]
		);
		$this->add_control(
			'button_hover_animation',
			[
				'label'    => esc_html__('Hover Animation', 'element-ready-lite'),
				'type'     => Controls_Manager::HOVER_ANIMATION,
				'selector' => '{{WRAPPER}} .area__button:hover',
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
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .area__content',
			]
		);
		$this->add_control(
			'box_color',
			[
				'label'  => esc_html__('Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .area__content' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'hover_box_color',
			[
				'label'  => esc_html__('Box Hover Color', 'element-ready-lite'),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .area__content' => 'color: {{VALUE}}',
				],
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
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
				'default' => 'center',
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
					'{{WRAPPER}}' => 'transition: {{SIZE}}s;',
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

		$settings = $this->get_settings_for_display();

		/*Button Link Attr*/
		if (!empty($settings['button_link']['url'])) {
			$this->add_render_attribute('more_button', 'href', esc_url($settings['button_link']['url']));

			if ($settings['button_link']['is_external']) {
				$this->add_render_attribute('more_button', 'target', '_blank');
			}

			if ($settings['button_link']['nofollow']) {
				$this->add_render_attribute('more_button', 'rel', 'nofollow');
			}
		}

		/*Button animation*/
		if ($settings['button_hover_animation']) {
			$button_animation = 'elementor-animation-' . esc_attr($settings['button_hover_animation']);
		} else {
			$button_animation = '';
		}

		/*Icon Animation*/
		if ($settings['icon_hover_animation']) {
			$icon_animation = 'elementor-animation-' . esc_attr($settings['icon_hover_animation']);
		} else {
			$icon_animation = '';
		}

		/*Icon Condition*/
		if ('yes' == $settings['show_icon']) {
			if ('font_icon' == $settings['icon_type'] && !empty($settings['font_icon'])) {
				$icon = wp_kses_post(sprintf('<div class="area__icon %s">%s</div>', esc_attr($icon_animation), element_ready_render_icons($settings['font_icon'])));
			} elseif ('image_icon' == $settings['icon_type'] && !empty($settings['image_icon'])) {
				$icon_array = $settings['image_icon'];
				$icon_link  = wp_get_attachment_image_url($icon_array['id'], 'thumbnail');
				$icon       = wp_kses_post(sprintf('<div class="area__icon %s"><img src="%s" alt="" /></div>', esc_attr($icon_animation), esc_url($icon_link)));
			}
		} else {
			$icon = '';
		}

		/*Title Background Text*/
		if (!empty($settings['title_bg_text'])) {
			$title_bg_text = wp_kses_post(sprintf('<div class="title__bg__text">%s</div>', esc_html($settings['title_bg_text'])));
		} else {
			$title_bg_text = '';
		}

		/*Title Background Icon*/
		/*if ( 'yes' == $settings['show_bg_icon'] ) {

			if ( 'font_icon' == $settings['bg_icon_type'] && !empty($settings['bg_font_or_svg']) ) {

				$title_bg_icon = '<div class="title__bg__icon">'.Icons_Manager::render_icon( $settings['bg_font_or_svg'], [ 'aria-hidden' => 'true' ] ).'</div>';

			}elseif ( 'image_icon' == $settings['bg_icon_type'] && !empty($settings['bg_image_icon']) ) {

				$icon_array    = $settings['bg_image_icon'];
				$icon_link     = wp_get_attachment_image_url( $icon_array['id'], 'thumbnail' );
				$title_bg_icon = '<div class="title__bg__icon"><img src="'. esc_url( $icon_link ) .'" alt="" /></div>';

			}
		}else{
			$title_bg_icon = '';
		}*/

		/*Title Tag*/
		if (!empty($settings['title_tag'])) {
			$title_tag = $settings['title_tag'];
		} else {
			$title_tag = 'h3';
		}

		/*Title*/
		if (!empty($settings['title'])) {
			$title = wp_kses_post(sprintf('<%s class="area__title">%s</%s>', esc_attr($title_tag), wp_kses_post($settings['title']), esc_attr($title_tag)));
		} else {
			$title = '';
		}

		/*Subtitle*/
		if (!empty($settings['subtitle'])) {
			$subtitle = wp_kses_post(sprintf('<div class="area__subtitle">%s</div>', esc_html($settings['subtitle'])));
		} else {
			$subtitle = '';
		}

		/*Description*/
		if (!empty($settings['description'])) {
			$description = wp_kses_post(sprintf('<div class="area__description">%s</div>', wpautop($settings['description'])));
		} else {
			$description = '';
		}

		/*Button*/
		if ('yes' == $settings['show_button'] && (!empty($settings['button_text']) && !empty($settings['button_link']))) {
			$button = wp_kses_post(sprintf('<a class="area__button %s" %s>%s</a>', esc_attr($button_animation), $this->get_render_attribute_string('more_button'), esc_html($settings['button_text'])));
		}

		/*Button With Icon*/
		if (!empty($settings['button_icon'])) {
			if ('left' == $settings['button_icon_align']) {
				$button = wp_kses_post(sprintf('<a class="area__button %s" %s><i class="area__button_icon_left %s"></i>%s</a>', esc_attr($button_animation), $this->get_render_attribute_string('more_button'), esc_attr($settings['button_icon']), esc_html($settings['button_text'])));
			} elseif ('right' == $settings['button_icon_align']) {
				$button = wp_kses_post(sprintf('<a class="area__button %s" %s>%s<i class="area__button_icon_right %s"></i></a>', esc_attr($button_animation), $this->get_render_attribute_string('more_button'), esc_html($settings['button_text']), esc_attr($settings['button_icon'])));
			}
		}

		/*Title Condition*/
		if ('before_title' == $settings['subtitle_position']) {
			$title_subtitle = wp_kses_post($subtitle . $title);
		} elseif ('after_title' == $settings['subtitle_position']) {
			$title_subtitle = wp_kses_post($title . $subtitle);
		} elseif (empty($settings['subtitle'])) {
			$title_subtitle = wp_kses_post($title . $subtitle);
		}

		echo '<div class="area__content">'; ?>
		<?php if ('yes' == $settings['show_bg_icon']) :  ?>
			<?php if ('font_icon' == $settings['bg_icon_type'] && !empty($settings['bg_font_or_svg'])) : ?>
				<div class="title__bg__icon"><?php echo element_ready_render_icons($settings['bg_font_or_svg']); ?></div>
			<?php elseif ('image_icon' == $settings['bg_icon_type'] && !empty($settings['bg_image_icon'])) : ?>
				<?php
				$icon_array = $settings['bg_image_icon'];
				$icon_link  = wp_get_attachment_image_url($icon_array['id'], 'thumbnail');
				echo sprintf('<div class="title__bg__icon"><img src="%s" alt="" /></div>', esc_url($icon_link));
				?>
			<?php endif; ?>
<?php endif;
		echo wp_kses_post('' . (isset($title_bg_text) ? $title_bg_text : '') . '
				' . (isset($icon) ? $icon : '') . '
				' . (isset($title_subtitle) ? $title_subtitle : '') . '
				' . (isset($description) ? $description : '') . '
				' . (isset($button) ? $button : '') . '');
		echo '</div>';
	}
	protected function content_template()
	{
	}
}
