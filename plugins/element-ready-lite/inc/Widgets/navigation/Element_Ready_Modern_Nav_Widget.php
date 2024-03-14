<?php

namespace Element_Ready\Widgets\navigation;

if (!defined('ABSPATH'))
	exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Element_Ready\Base\Modern_Nav_Walker;

class Element_Ready_Modern_Nav_Widget extends Widget_Base
{
	public function get_name()
	{
		return 'Element_Ready_Modern_Nav_Widget';
	}

	public function get_title()
	{
		return esc_html__('ER Modern Navigation', 'element-ready-lite');
	}

	public function get_icon()
	{
		return 'eicon-nav-menu';
	}

	public function get_categories()
	{
		return ['element-ready-addons'];
	}

	public function get_keywords()
	{
		return ['Nav Menu', 'Menu', 'Navigation'];
	}

	public function get_style_depends()
	{

		wp_register_style('eready-modern-navigation-css', ELEMENT_READY_ROOT_CSS . 'widgets/modern-nav.css');
		return ['eready-modern-navigation-css'];
	}

	public function get_script_depends()
	{
		wp_register_script('eready-modern-navigation-js', ELEMENT_READY_ROOT_JS . 'widgets/modern-nav.js');
		return ['eready-modern-navigation-js'];
	}



	public function get_available_menus()
	{

		$menus = wp_get_nav_menus();
		$menulists = [];
		foreach ($menus as $menu) {
			$menulists[$menu->slug] = $menu->name;
		}
		return $menulists;
	}

	protected function register_controls()
	{
		$this->start_controls_section(
			'modern_nav_Content_section',
			[
				'label' => esc_html__('Modern Nav Content', 'element-ready-lite'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'inline_menu_id',
			[
				'label' => esc_html__('Menu', 'element-ready-lite'),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_available_menus(),
				'default' => '',
				'save_default' => true,
				'description' => sprintf(esc_html__('Go to the <a href="%s" target="_blank">Menus Option</a> to manage your menus.', 'element-ready-lite'), admin_url('nav-menus.php')),
				'separator' => 'before',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'modern_nav_style_section',
			[
				'label' => esc_html__('Modern Nav Style', 'element-ready-lite'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'menu_background',
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .element__ready__modern_menu',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => esc_html__('Menu Alignment', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'element-ready-lite'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'element-ready-lite'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'element-ready-lite'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .element__ready__modern_menu' => 'text-align: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();



		$this->start_controls_section(
			'title_style_section',
			[
				'label' => esc_html__('Menu Title', 'element-ready-lite'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'title_tabs'
		);

		$this->start_controls_tab(
			'title_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'selector' => '{{WRAPPER}} .element__ready__modern_menu li a',
			]
		);

		$this->add_control(
			'title_normal_color',
			[
				'label' => esc_html__('Text Color', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .element__ready__modern_menu li a' => 'color: {{VALUE}}',
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
			'title_hover_color',
			[
				'label' => esc_html__('Text Color', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .element__ready__modern_menu li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_hover_color_shadow',
			[
				'label' => esc_html__('Text Shadow', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::TEXT_SHADOW,
				'selectors' => [
					'{{WRAPPER}} .menu-text' => 'text-shadow:  {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};',
				],
			]
		);

		$this->end_controls_tab();



		$this->end_controls_tabs();

		$this->end_controls_section();


		$this->start_controls_section(
			'serial_style_section',
			[
				'label' => esc_html__('Menu Count', 'element-ready-lite'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'menu_numbering_tabs'
		);

		$this->start_controls_tab(
			'nubering_normal_tab',
			[
				'label' => esc_html__('Menu Count', 'element-ready-lite'),
			]
		);

		$this->add_control(
			'header_serial_switcher',
			[
				'label' => esc_html__('SERIAL DISPLAY', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [

					'none' => esc_html__('None', 'element-ready-lite'),
					'block' => esc_html__('Block', 'element-ready-lite'),

				],
				'selectors' => [
					'{{WRAPPER}} .menu-number' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'serial_typo',
				'selector' => '{{WRAPPER}} .menu-number',
			]
		);

		$this->add_control(
			'serial_normal_color',
			[
				'label' => esc_html__('Serial Color', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-number' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();


		$this->start_controls_tab(
			'serial_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);


		$this->add_control(
			'serial_hover_color',
			[
				'label' => esc_html__('Count Hover Color', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-number:hover' => 'color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_tab();



		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'megamenu_style_section',
			[
				'label' => esc_html__('Mega Menu Section', 'element-ready-lite'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'submenu_style_section',
			[
				'label' => esc_html__('Sub Menu Section', 'element-ready-lite'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'sub_menu_tabs'
		);

		$this->start_controls_tab(
			'submenu_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'submenu_typography',
				'selector' => '{{WRAPPER}} .element__ready__modern_menu li.menu-item-has-children>ul li a',
			]
		);

		$this->add_control(
			'submenu_text_color',
			[
				'label' => esc_html__('Sub Menu Text Color', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .element__ready__modern_menu li.menu-item-has-children>ul li a' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'submenu_background',
				'types' => ['classic', 'gradient', 'video'],
				'selector' => '{{WRAPPER}} .element__ready__modern_menu li.menu-item-has-children>ul',
			]
		);

		$this->add_responsive_control(
			'submenu_padding',
			[
				'label' => esc_html__('Sub Menu Padding', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .element__ready__modern_menu li.menu-item-has-children>ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'submneu_margin',
			[
				'label' => esc_html__('Sub Menu Margin', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .element__ready__modern_menu li.menu-item-has-children>ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_tab();


		$this->start_controls_tab(
			'submenu_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);

		$this->add_control(
			'submenu_hover_text_color',
			[
				'label' => esc_html__('Sub Menu Text Hover Color', 'element-ready-lite'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .element__ready__modern_menu li.menu-item-has-children>ul li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render($instance = [])
	{
		$count = count($this->get_available_menus());

		if ($count <= 0) {
			return;
		}



		$settings = $this->get_settings_for_display();
		$id = $this->get_id();


		$menuargs = [
			'echo' => false,
			'menu' => isset($settings['inline_menu_id']) ? $settings['inline_menu_id'] : 0,
			'menu_class' => 'element__ready__modern_menu',
			'menu_id' => 'menu-' . esc_attr($id),
			'fallback_cb' => '__return_empty_string',
			'container' => '',
			'depth' => 0,
			'walker' => new Modern_Nav_Walker()
		];

		echo wp_nav_menu($menuargs);
	}
}
