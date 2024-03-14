<?php

namespace Shop_Ready\extension\generalwidgets\widgets\heading;

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

class Woo_Title extends \Shop_Ready\extension\generalwidgets\Widget_Base
{

	public function get_keywords()
	{
		return ['title', 'area title', 'section title', 'page title'];
	}

	public static function title_before_style()
	{
		return [
			'set_title_before' => esc_html__('Set Title Before', 'shopready-elementor-addon'),
			'set_subtitle_before' => esc_html__('Set Subtitle Before', 'shopready-elementor-addon'),
			'set_description_before' => esc_html__('Set Description Before', 'shopready-elementor-addon'),
			'set_box_before' => esc_html__('Set Box Before', 'shopready-elementor-addon'),
			'set_container_before' => esc_html__('Set Container Before', 'shopready-elementor-addon'),
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
				'label' => esc_html__('Content', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'show_icon',
			[
				'label' => esc_html__('Show Icon ?', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'shopready-elementor-addon'),
				'label_off' => esc_html__('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$this->add_control(
			'icon_type',
			[
				'label' => esc_html__('Icon Type', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => 'font_icon',
				'options' => [
					'font_icon' => esc_html__('SVG / Font Icon', 'shopready-elementor-addon'),
					'image_icon' => esc_html__('Image Icon', 'shopready-elementor-addon'),
				],
				'condition' => [
					'show_icon' => 'yes',
				],
			]
		);
		$this->add_control(
			'font_icon',
			[
				'label' => esc_html__('SVG / Font Icons', 'shopready-elementor-addon'),
				'type' => Controls_Manager::ICONS,
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
				'label' => esc_html__('Image Icon', 'shopready-elementor-addon'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'icon_type' => 'image_icon',
					'show_icon' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'exclude' => [],
				'include' => [],
				'default' => 'large',
				'condition' => [
					'icon_type' => 'image_icon',
					'show_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_bg_icon',
			[
				'label' => esc_html__('Title Background Icon ?', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'shopready-elementor-addon'),
				'label_off' => esc_html__('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'bg_icon_type',
			[
				'label' => esc_html__('Background Icon Type', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => 'font_icon',
				'options' => [
					'font_icon' => esc_html__('SVG / Font Icon', 'shopready-elementor-addon'),
					'image_icon' => esc_html__('Image Icon', 'shopready-elementor-addon'),
				],
				'condition' => [
					'show_bg_icon' => 'yes',
				],
			]
		);
		$this->add_control(
			'bg_font_or_svg',
			[
				'label' => esc_html__('SVG / Font Icon', 'shopready-elementor-addon'),
				'type' => Controls_Manager::ICONS,
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
				'label' => esc_html__('Upload Image OR SVG Icon', 'shopready-elementor-addon'),
				'type' => Controls_Manager::MEDIA,
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
				'label' => esc_html__('Title Background Text ?', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'shopready-elementor-addon'),
				'label_off' => esc_html__('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_bg_text',
			[
				'label' => esc_html__('Title Background Text', 'shopready-elementor-addon'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Background Text', 'shopready-elementor-addon'),
				'condition' => [
					'show_bg_text' => 'yes',
				],
			]
		);



		$this->add_control(
			'title',
			[
				'label' => esc_html__('Title', 'shopready-elementor-addon'),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__('Title', 'shopready-elementor-addon'),
				'separator' => 'before',
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__('Title HTML Tag', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
				'condition' => [
					'title!' => '',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'subtitle',
			[
				'label' => esc_html__('Subtitle', 'shopready-elementor-addon'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Subtitle', 'shopready-elementor-addon'),
				'separator' => 'before',
			]
		);
		$this->add_control(
			'subtitle_position',
			[
				'label' => esc_html__('Subtitle Position', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => 'after_title',
				'options' => [
					'before_title' => esc_html__('Before title', 'shopready-elementor-addon'),
					'after_title' => esc_html__('After Title', 'shopready-elementor-addon'),
				],
				'condition' => [
					'subtitle!' => '',
				]
			]
		);
		$this->add_control(
			'description',
			[
				'label' => esc_html__('Description', 'shopready-elementor-addon'),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__('Description.', 'shopready-elementor-addon'),
				'separator' => 'before',
			]
		);
		$this->add_control(
			'show_button',
			[
				'label' => esc_html__('Show Button ?', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'shopready-elementor-addon'),
				'label_off' => esc_html__('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'button_text',
			[
				'label' => esc_html__('Button Title', 'shopready-elementor-addon'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Button Text', 'shopready-elementor-addon'),
				'condition' => ['show_button' => 'yes'],
			]
		);
		$this->add_control(
			'button_link',
			[
				'label' => esc_html__('Button Link', 'shopready-elementor-addon'),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__('https://your-link.com', 'shopready-elementor-addon'),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => ['show_button' => 'yes'],
			]
		);
		$this->add_control(
			'button_icon',
			[
				'label' => esc_html__('Icon', 'shopready-elementor-addon'),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
				'condition' => ['show_button' => 'yes'],
			]
		);
		$this->add_control(
			'button_icon_align',
			[
				'label' => esc_html__('Icon Position', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__('Before', 'shopready-elementor-addon'),
					'right' => esc_html__('After', 'shopready-elementor-addon'),
				],
				'condition' => [
					'button_icon!' => '',
				],
			]
		);
		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__('Icon Spacing', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .area__button .area__button_icon_left' => 'margin-right: {{SIZE}}{{UNIT}};',
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
				'label' => esc_html__('Icon', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_icon' => 'yes',
				],
			]
		);
		$this->start_controls_tabs('icon_tab_style');
		$this->start_controls_tab(
			'icon_normal_tab',
			[
				'label' => esc_html__('Normal', 'shopready-elementor-addon'),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typography',
				'selector' => '{{WRAPPER}} .area__icon',
				'condition' => [
					'icon_type' => ['font_icon']
				],
			]
		);
		$this->add_responsive_control(
			'icon_image_size',
			[
				'label' => esc_html__('SVG / Image Size', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'name' => 'icon_image_filters',
				'selector' => '{{WRAPPER}} .area__icon img',
				'condition' => [
					'icon_type' => ['image_icon']
				],
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__('Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .area__icon' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'icon_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
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
				'name' => 'icon_border',
				'label' => esc_html__('Border', 'shopready-elementor-addon'),
				'selector' => '{{WRAPPER}} .area__icon',
			]
		);
		$this->add_control(
			'icon_radius',
			[
				'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_shadow',
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
				'label' => esc_html__('Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .area__icon' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_height',
			[
				'label' => esc_html__('Height', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
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
				'label' => esc_html__('Display', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline-block',

				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'block' => esc_html__('Block', 'shopready-elementor-addon'),
					'inline-block' => esc_html__('Inline Block', 'shopready-elementor-addon'),
					'flex' => esc_html__('Flex', 'shopready-elementor-addon'),
					'grid' => esc_html__('Grid', 'shopready-elementor-addon'),
					'inline-flex' => esc_html__('Inline Flex', 'shopready-elementor-addon'),
					'none' => esc_html__('none', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__icon' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_align',
			[
				'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'shopready-elementor-addon'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'shopready-elementor-addon'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'shopready-elementor-addon'),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__('Justify', 'shopready-elementor-addon'),
						'icon' => 'eicon-text-align-justify',
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
				'label' => esc_html__('Position', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => 'initial',

				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'absolute' => esc_html__('Absulute', 'shopready-elementor-addon'),
					'relative' => esc_html__('Relative', 'shopready-elementor-addon'),
					'static' => esc_html__('Static', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__icon' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_position_from_left',
			[
				'label' => esc_html__('From Left', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('From Right', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('From Top', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('From Bottom', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Transition', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 3,
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
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
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
				'label' => esc_html__('Padding', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'icon_hover_tab',
			[
				'label' => esc_html__('Hover', 'shopready-elementor-addon'),
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'hover_icon_image_filters',
				'selector' => '{{WRAPPER}} :hover .area__icon img',
				'condition' => [
					'icon_type' => ['image_icon']
				],
			]
		);
		$this->add_control(
			'hover_icon_color',
			[
				'label' => esc_html__('Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .area__icon, {{WRAPPER}} :focus .area__icon' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'hover_icon_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
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
				'name' => 'hover_icon_border',
				'label' => esc_html__('Border', 'shopready-elementor-addon'),
				'selector' => '{{WRAPPER}} :hover .area__icon,{{WRAPPER}} :hover .area__icon',
			]
		);
		$this->add_control(
			'hover_icon_radius',
			[
				'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} :hover .area__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'hover_icon_shadow',
				'selector' => '{{WRAPPER}} .area__icon:hover',
			]
		);
		$this->add_control(
			'icon_hover_animation',
			[
				'label' => esc_html__('Hover Animation', 'shopready-elementor-addon'),
				'type' => Controls_Manager::HOVER_ANIMATION,
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
				'label' => esc_html__('Background Icon', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_bg_icon' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bgicon_typography',
				'selector' => '{{WRAPPER}} .area__content .title__bg__icon',
			]
		);
		$this->add_control(
			'bgicon_text_color',
			[
				'label' => esc_html__('Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .area__content .title__bg__icon' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'bgicon_text_width',
			[
				'label' => esc_html__('Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__content .title__bg__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'bgicon_padding',
			[
				'label' => esc_html__('Padding', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__content .title__bg__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'bgicon_opacity',
			[
				'label' => esc_html__('Opacity', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
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
				'label' => esc_html__('Background Text', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_bg_text' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bgtext_typography',
				'selector' => '{{WRAPPER}} .area__content .title__bg__text',
			]
		);
		$this->add_control(
			'bgtext_text_color',
			[
				'label' => esc_html__('Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .area__content .title__bg__text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_bg_text_shadow',
				'label' => esc_html__('Text Shadow', 'shopready-elementor-addon'),
				'selector' => '{{WRAPPER}} .area__content .title__bg__text',
				'condition' => [
					'title_bg_text!' => ''
				]
			]
		);

		$this->add_responsive_control(
			'title_bg_text_custom_tab_area_css',
			[
				'label' => esc_html__('Custom CSS', 'shopready-elementor-addon'),
				'type' => Controls_Manager::CODE,
				'rows' => 20,
				'language' => 'css',
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
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__content .title__bg__text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'bgtext_padding',
			[
				'label' => esc_html__('Padding', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__content .title__bg__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'bgtext_opacity',
			[
				'label' => esc_html__('Opacity', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
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
				'label' => esc_html__('Title', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => '',
				]
			]
		);
		$this->start_controls_tabs('title_tab_style');
		$this->start_controls_tab(
			'title_normal_tab',
			[
				'label' => esc_html__('Normal', 'shopready-elementor-addon'),
			]
		);
		$this->add_control(
			'title_text_color',
			[
				'label' => esc_html__('Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .area__title, {{WRAPPER}} .area__title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .area__title',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'title_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__title',
			]
		);
		$this->add_responsive_control(
			'title_display',
			[
				'label' => esc_html__('Display', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'block' => esc_html__('Block', 'shopready-elementor-addon'),
					'inline-block' => esc_html__('Inline Block', 'shopready-elementor-addon'),
					'flex' => esc_html__('Flex', 'shopready-elementor-addon'),
					'inline-flex' => esc_html__('Inline Flex', 'shopready-elementor-addon'),
					'none' => esc_html__('none', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__title' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'title_border',
				'label' => esc_html__('Border', 'shopready-elementor-addon'),
				'selector' => '{{WRAPPER}} .area__title',
			]
		);
		$this->add_responsive_control(
			'title_border_radius',
			[
				'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__('Padding', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'title_hover_tab',
			[
				'label' => esc_html__('Hover', 'shopready-elementor-addon'),
			]
		);
		$this->add_control(
			'hover_title_color',
			[
				'label' => esc_html__('Link Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .area__title a:hover, {{WRAPPER}} .area__title a:focus' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'box_hover_title_color',
			[
				'label' => esc_html__('Box Hover Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
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
				'label' => esc_html__('Title Before / After', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => '',
				]
			]
		);
		$this->start_controls_tabs('title_before_after_tab_style');
		$this->start_controls_tab(
			'title_before_tab',
			[
				'label' => esc_html__('BEFORE', 'shopready-elementor-addon'),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'title_before_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__title:before',
			]
		);
		$this->add_responsive_control(
			'title_before_display',
			[
				'label' => esc_html__('Display', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'block' => esc_html__('Block', 'shopready-elementor-addon'),
					'inline-block' => esc_html__('Inline Block', 'shopready-elementor-addon'),
					'flex' => esc_html__('Flex', 'shopready-elementor-addon'),
					'inline-flex' => esc_html__('Inline Flex', 'shopready-elementor-addon'),
					'none' => esc_html__('none', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__title:before' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_before_position',
			[
				'label' => esc_html__('Position', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => 'relative',

				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'absolute' => esc_html__('Absulute', 'shopready-elementor-addon'),
					'relative' => esc_html__('Relative', 'shopready-elementor-addon'),
					'static' => esc_html__('Static', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__title:before' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_before_position_from_left',
			[
				'label' => esc_html__('From Left', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Right', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Top', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Bottom', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'text-align:left' => [
						'title' => esc_html__('Left', 'shopready-elementor-addon'),
						'icon' => 'eicon-text-align-left',
					],
					'margin: 0 auto' => [
						'title' => esc_html__('Center', 'shopready-elementor-addon'),
						'icon' => 'eicon-text-align-center',
					],
					'float:right' => [
						'title' => esc_html__('Right', 'shopready-elementor-addon'),
						'icon' => 'eicon-text-align-right',
					],
					'text-align:justify' => [
						'title' => esc_html__('Justify', 'shopready-elementor-addon'),
						'icon' => 'eicon-text-align-justify',
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
				'label' => esc_html__('Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Height', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Opacity', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
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
				'label' => esc_html__('Z-Index', 'shopready-elementor-addon'),
				'type' => Controls_Manager::NUMBER,
				'min' => -99,
				'max' => 99,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .area__title:before' => 'z-index: {{SIZE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_before_margin',
			[
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__title:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'title_after_tab',
			[
				'label' => esc_html__('AFTER', 'shopready-elementor-addon'),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'title_after_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__title:after',
			]
		);
		$this->add_responsive_control(
			'title_after_display',
			[
				'label' => esc_html__('Display', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'block' => esc_html__('Block', 'shopready-elementor-addon'),
					'inline-block' => esc_html__('Inline Block', 'shopready-elementor-addon'),
					'flex' => esc_html__('Flex', 'shopready-elementor-addon'),
					'inline-flex' => esc_html__('Inline Flex', 'shopready-elementor-addon'),
					'none' => esc_html__('none', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__title:after' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_after_position',
			[
				'label' => esc_html__('Position', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => 'relative',

				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'absolute' => esc_html__('Absulute', 'shopready-elementor-addon'),
					'relative' => esc_html__('Relative', 'shopready-elementor-addon'),
					'static' => esc_html__('Static', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__title:after' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_after_position_from_left',
			[
				'label' => esc_html__('From Left', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Right', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Top', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Bottom', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'text-align:left' => [
						'title' => esc_html__('Left', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-left',
					],
					'margin: 0 auto' => [
						'title' => esc_html__('Center', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-center',
					],
					'float:right' => [
						'title' => esc_html__('Right', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-right',
					],
					'text-align:justify' => [
						'title' => esc_html__('Justify', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-justify',
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
				'label' => esc_html__('Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Height', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Opacity', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
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
				'label' => esc_html__('Z-Index', 'shopready-elementor-addon'),
				'type' => Controls_Manager::NUMBER,
				'min' => -99,
				'max' => 99,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .area__title:after' => 'z-index: {{SIZE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_after_margin',
			[
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
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
				'label' => esc_html__('Subtitle', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'subtitle!' => '',
				]
			]
		);
		$this->add_control(
			'subtitle_color',
			[
				'label' => esc_html__('Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .area__subtitle' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .area__subtitle',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'subtitle_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__subtitle',
			]
		);
		$this->add_responsive_control(
			'subtitle_display',
			[
				'label' => esc_html__('Display', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => '',

				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'block' => esc_html__('Block', 'shopready-elementor-addon'),
					'inline-block' => esc_html__('Inline Block', 'shopready-elementor-addon'),
					'flex' => esc_html__('Flex', 'shopready-elementor-addon'),
					'inline-flex' => esc_html__('Inline Flex', 'shopready-elementor-addon'),
					'none' => esc_html__('none', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__subtitle' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'subtitle_border',
				'label' => esc_html__('Border', 'shopready-elementor-addon'),
				'selector' => '{{WRAPPER}} .area__subtitle',
			]
		);
		$this->add_responsive_control(
			'subtitle_border_radius',
			[
				'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'box_hover_subtitle_color',
			[
				'label' => esc_html__('Box Hover Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .area__subtitle' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_margin',
			[
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_padding',
			[
				'label' => esc_html__('Padding', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
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
				'label' => esc_html__('Subtitle Before / After', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'subtitle!' => '',
				]
			]
		);
		$this->start_controls_tabs('subtitle_before_after_tab_style');
		$this->start_controls_tab(
			'subtitle_before_tab',
			[
				'label' => esc_html__('BEFORE', 'shopready-elementor-addon'),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'subtitle_before_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__subtitle:before',
			]
		);
		$this->add_responsive_control(
			'subtitle_before_display',
			[
				'label' => esc_html__('Display', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'block' => esc_html__('Block', 'shopready-elementor-addon'),
					'inline-block' => esc_html__('Inline Block', 'shopready-elementor-addon'),
					'flex' => esc_html__('Flex', 'shopready-elementor-addon'),
					'inline-flex' => esc_html__('Inline Flex', 'shopready-elementor-addon'),
					'none' => esc_html__('none', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__subtitle:before' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_before_position',
			[
				'label' => esc_html__('Position', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => 'relative',

				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'absolute' => esc_html__('Absulute', 'shopready-elementor-addon'),
					'relative' => esc_html__('Relative', 'shopready-elementor-addon'),
					'static' => esc_html__('Static', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__subtitle:before' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_before_position_from_left',
			[
				'label' => esc_html__('From Left', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Right', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Top', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Bottom', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'text-align:left' => [
						'title' => esc_html__('Left', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-left',
					],
					'margin: 0 auto' => [
						'title' => esc_html__('Center', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-center',
					],
					'float:right' => [
						'title' => esc_html__('Right', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-right',
					],
					'text-align:justify' => [
						'title' => esc_html__('Justify', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-justify',
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
				'label' => esc_html__('Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Height', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Opacity', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
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
				'label' => esc_html__('Z-Index', 'shopready-elementor-addon'),
				'type' => Controls_Manager::NUMBER,
				'min' => -99,
				'max' => 99,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .area__subtitle:before' => 'z-index: {{SIZE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_before_margin',
			[
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__subtitle:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'subtitle_after_tab',
			[
				'label' => esc_html__('AFTER', 'shopready-elementor-addon'),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'subtitle_after_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__subtitle:after',
			]
		);
		$this->add_responsive_control(
			'subtitle_after_display',
			[
				'label' => esc_html__('Display', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'block' => esc_html__('Block', 'shopready-elementor-addon'),
					'inline-block' => esc_html__('Inline Block', 'shopready-elementor-addon'),
					'flex' => esc_html__('Flex', 'shopready-elementor-addon'),
					'inline-flex' => esc_html__('Inline Flex', 'shopready-elementor-addon'),
					'none' => esc_html__('none', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__subtitle:after' => 'display: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_after_position',
			[
				'label' => esc_html__('Position', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'default' => 'relative',

				'options' => [
					'initial' => esc_html__('Initial', 'shopready-elementor-addon'),
					'absolute' => esc_html__('Absulute', 'shopready-elementor-addon'),
					'relative' => esc_html__('Relative', 'shopready-elementor-addon'),
					'static' => esc_html__('Static', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .area__subtitle:after' => 'position: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_after_position_from_left',
			[
				'label' => esc_html__('From Left', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Right', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Top', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('From Bottom', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
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
				'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'text-align:left' => [
						'title' => esc_html__('Left', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-left',
					],
					'margin: 0 auto' => [
						'title' => esc_html__('Center', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-center',
					],
					'float:right' => [
						'title' => esc_html__('Right', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-right',
					],
					'text-align:justify' => [
						'title' => esc_html__('Justify', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-justify',
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
				'label' => esc_html__('Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Height', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Opacity', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
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
				'label' => esc_html__('Z-Index', 'shopready-elementor-addon'),
				'type' => Controls_Manager::NUMBER,
				'min' => -99,
				'max' => 99,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .area__subtitle:after' => 'z-index: {{SIZE}};',
				],
			]
		);
		$this->add_responsive_control(
			'subtitle_after_margin',
			[
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
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
				'label' => esc_html__('Button', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_button' => 'yes'
				],
			]
		);
		$this->start_controls_tabs('button_tab_style');
		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => esc_html__('Normal', 'shopready-elementor-addon'),
			]
		);
		$this->add_control(
			'button_color',
			[
				'label' => esc_html__('Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.area__button, {{WRAPPER}} .area__button' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .area__button',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__button',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'label' => esc_html__('Border', 'shopready-elementor-addon'),
				'selector' => '{{WRAPPER}} .area__button',
			]
		);
		$this->add_control(
			'button_radius',
			[
				'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_shadow',
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
				'label' => esc_html__('Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Height', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__('Padding', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => esc_html__('Hover', 'shopready-elementor-addon'),
			]
		);
		$this->add_control(
			'hover_button_color',
			[
				'label' => esc_html__('Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .area__button:hover, {{WRAPPER}} a.area__button:focus, {{WRAPPER}} .area__button:focus' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'hover_button_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .area__button:hover,{{WRAPPER}} .area__button:focus',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'hover_button_border',
				'label' => esc_html__('Border', 'shopready-elementor-addon'),
				'selector' => '{{WRAPPER}} .area__button:hover,{{WRAPPER}} .area__button:focus',
			]
		);
		$this->add_control(
			'hover_button_radius',
			[
				'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .area__button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'hover_button_shadow',
				'selector' => '{{WRAPPER}} .area__button:hover',
			]
		);
		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__('Hover Animation', 'shopready-elementor-addon'),
				'type' => Controls_Manager::HOVER_ANIMATION,
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
				'label' => esc_html__('Box', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .area__content',
			]
		);
		$this->add_control(
			'box_color',
			[
				'label' => esc_html__('Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .area__content' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'hover_box_color',
			[
				'label' => esc_html__('Box Hover Color', 'shopready-elementor-addon'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} :hover .area__content' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'box_align',
			[
				'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__('Justify', 'shopready-elementor-addon'),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .area__content' => 'text-align: {{VALUE}};',
				],
				'default' => 'center',
			]
		);
		$this->add_control(
			'box_transition',
			[
				'label' => esc_html__('Transition', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .area__content' => 'transition: {{SIZE}}s;',
				],
			]
		);
		$this->end_controls_section();
		/*----------------------------
				  BOX STYLE END
			  -----------------------------*/
	}

	protected function html()
	{

		$settings = $this->get_settings_for_display();
		$this->add_render_attribute(
			'wrapper_style',
			[
				'class' => ['woo-ready-product-title-layout'],
			]
		);

		echo wp_kses_post(sprintf("<div %s>", wp_kses_post($this->get_render_attribute_string('wrapper_style'))));
		/*Button Link Attr*/
		if (!empty($settings['button_link']['url'])) {
			$this->add_render_attribute('more_button', 'href', $settings['button_link']['url']);

			if ($settings['button_link']['is_external']) {
				$this->add_render_attribute('more_button', 'target', '_blank');
			}

			if ($settings['button_link']['nofollow']) {
				$this->add_render_attribute('more_button', 'rel', 'nofollow');
			}
		}

		/*Button animation*/
		if ($settings['button_hover_animation']) {
			$button_animation = 'elementor-animation-' . $settings['button_hover_animation'];
		} else {
			$button_animation = '';
		}

		/*Icon Animation*/
		if ($settings['icon_hover_animation']) {
			$icon_animation = 'elementor-animation-' . $settings['icon_hover_animation'];
		} else {
			$icon_animation = '';
		}

		/*Icon Condition*/
		if ('yes' == $settings['show_icon']) {
			if ('font_icon' == $settings['icon_type'] && !empty($settings['font_icon'])) {
				$icon = '<div class="area__icon ' . esc_attr($icon_animation) . '">' . shop_ready_render_icons($settings['font_icon']) . '</div>';
			} elseif ('image_icon' == $settings['icon_type'] && !empty($settings['image_icon'])) {
				$icon_array = $settings['image_icon'];
				$icon_link = wp_get_attachment_image_url($icon_array['id'], 'thumbnail');
				$sr_img = Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', 'image_icon');
				$icon = '<div class="area__icon ' . esc_attr($icon_animation) . '">' . $sr_img . '</div>';
			}
		} else {
			$icon = '';
		}

		/*Title Background Text*/
		if (!empty($settings['title_bg_text'])) {
			$title_bg_text = '<div class="title__bg__text">' . esc_html($settings['title_bg_text']) . '</div>';
		} else {
			$title_bg_text = '';
		}

		/*Title Tag*/
		if (!empty($settings['title_tag'])) {
			$title_tag = $settings['title_tag'];
		} else {
			$title_tag = 'h3';
		}

		/*Title*/
		if (!empty($settings['title'])) {
			$title = '<' . $title_tag . ' class="area__title">' . wpautop($settings['title']) . '</' . $title_tag . '>';
		} else {
			$title = '';
		}

		/*Subtitle*/
		if (!empty($settings['subtitle'])) {
			$subtitle = '<div class="area__subtitle">' . esc_html($settings['subtitle']) . '</div>';
		} else {
			$subtitle = '';
		}

		/*Description*/
		if (!empty($settings['description'])) {
			$description = '<div class="area__description">' . wpautop($settings['description']) . '</div>';
		} else {
			$description = '';
		}

		/*Button*/
		if ('yes' == $settings['show_button'] && (!empty($settings['button_text']) && !empty($settings['button_link']))) {
			$button = '<a class="area__button ' . esc_attr($button_animation) . '" ' . $this->get_render_attribute_string('more_button') . '>' . esc_html($settings['button_text']) . '</a>';
		}

		/*Button With Icon*/
		if (!empty($settings['button_icon'])) {
			if ('left' == $settings['button_icon_align']) {
				$button = '<a class="area__button ' . esc_attr($button_animation) . '" ' . $this->get_render_attribute_string('more_button') . '><i class="area__button_icon_left ' . esc_attr($settings['button_icon']) . '"></i>' . esc_html($settings['button_text']) . '</a>';
			} elseif ('right' == $settings['button_icon_align']) {
				$button = '<a class="area__button ' . esc_attr($button_animation) . '" ' . $this->get_render_attribute_string('more_button') . '>' . esc_html($settings['button_text']) . '<i class="area__button_icon_right ' . esc_attr($settings['button_icon']) . '"></i></a>';
			}
		}

		/*Title Condition*/
		if ('before_title' == $settings['subtitle_position']) {
			$title_subtitle = $subtitle . $title;
		} elseif ('after_title' == $settings['subtitle_position']) {
			$title_subtitle = $title . $subtitle;
		} elseif (empty($settings['subtitle'])) {
			$title_subtitle = $title . $subtitle;
		}

		echo wp_kses_post('<div class="area__content">'); ?>
		<?php if ('yes' == $settings['show_bg_icon']): ?>
			<?php if ('font_icon' == $settings['bg_icon_type'] && !empty($settings['bg_font_or_svg'])): ?>
				<div class="title__bg__icon">
					<?php echo wp_kses_post(shop_ready_render_icons($settings['bg_font_or_svg'])); ?>
				</div>
			<?php elseif ('image_icon' == $settings['bg_icon_type'] && !empty($settings['bg_image_icon'])): ?>
				<?php
				$icon_array = $settings['bg_image_icon'];
				$icon_link = wp_get_attachment_image_url($icon_array['id'], 'thumbnail');
				echo wp_kses_post('<div class="title__bg__icon"><img src="' . esc_url($icon_link) . '" alt="" /></div>');
			?>
			<?php endif; ?>
		<?php endif;
		echo wp_kses_post('' . (isset($title_bg_text) ? $title_bg_text : '') . '
				' . (isset($icon) ? $icon : '') . '
				' . (isset($title_subtitle) ? $title_subtitle : '') . '
				' . (isset($description) ? $description : '') . '
				' . (isset($button) ? $button : '') . '');
		echo wp_kses_post('</div>');

		echo wp_kses_post('</div>');
	}
	protected function content_template()
	{
	}
}