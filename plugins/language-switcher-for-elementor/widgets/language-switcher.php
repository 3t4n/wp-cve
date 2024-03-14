<?php
namespace LanguageSwitcherForElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Language Switcher
 *
 * Elementor widget for Language Switcher.
 *
 * @since 1.0.0
 */
class Language_Switcher extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'language-switcher';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Language Switcher', 'language-switcher-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'lsfe-icon';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Retrieve the list of styles the widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		return [ 'lsfe-frontend' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'language-switcher-for-elementor' ),
			]
		);

		$this->add_responsive_control(
			'layout',
			[
				'label' => __( 'Layout', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal'  => __( 'Horizontal', 'language-switcher-for-elementor' ),
					'vertical' => __( 'Vertical', 'language-switcher-for-elementor' ),
				],
				'label_block' => true,
				'prefix_class' => 'lsfe%s-layout-',
			]
		);

		$this->add_responsive_control(
			'align_items',
			[
				'label' => __( 'Align', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'language-switcher-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'language-switcher-for-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'language-switcher-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
					'justify' => [
						'title' => __( 'Stretch', 'language-switcher-for-elementor' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'label_block' => true,
				'prefix_class' => 'lsfe%s-align-',
			]
		);

		$this->add_control(
			'skip_missing',
			[
				'label' => __( 'Skip missing', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 1,
				'default' => 0,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_country_flag',
			[
				'label' => __( 'Show Country Flag', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'show_native_name',
			[
				'label' => __( 'Show Native Name', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_translated_name',
			[
				'label' => __( 'Show Translated Name', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'show_language_code',
			[
				'label' => __( 'Show Language Code', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'main_section',
			[
				'label' => __( 'Main Menu', 'language-switcher-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => __( 'Normal', 'language-switcher-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_menu_item',
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .lsfe-menu .lsfe-item',
			]
		);

		$this->add_control(
			'color_menu_item',
			[
				'label' => __( 'Text Color', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_3,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .lsfe-menu .lsfe-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover',
			[
				'label' => __( 'Hover', 'language-switcher-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_menu_item_hover',
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .lsfe-menu .lsfe-item:hover,
					{{WRAPPER}} .lsfe-menu .lsfe-item.lsfe-item__active,
					{{WRAPPER}} .lsfe-menu .lsfe-item.highlighted,
					{{WRAPPER}} .lsfe-menu .lsfe-item:focus',
			]
		);

		$this->add_control(
			'color_menu_item_hover',
			[
				'label' => __( 'Text Color', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .lsfe-menu .lsfe-item:hover,
					{{WRAPPER}} .lsfe-menu .lsfe-item.lsfe-item__active,
					{{WRAPPER}} .lsfe-menu .lsfe-item.highlighted,
					{{WRAPPER}} .lsfe-menu .lsfe-item:focus' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active',
			[
				'label' => __( 'Active', 'language-switcher-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_menu_item_active',
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .lsfe-menu .lsfe-item.lsfe-item__active',
			]
		);

		$this->add_control(
			'color_menu_item_active',
			[
				'label' => __( 'Text Color', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .lsfe-menu .lsfe-item.lsfe-item__active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'padding_horizontal_menu_item',
			[
				'label' => __( 'Horizontal Padding', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .lsfe-switcher .lsfe-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'padding_vertical_menu_item',
			[
				'label' => __( 'Vertical Padding', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .lsfe-switcher .lsfe-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'menu_space_between',
			[
				'label' => __( 'Space Between', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .lsfe-layout-horizontal .lsfe-menu > li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .lsfe-layout-horizontal .lsfe-menu > li:not(:last-child)' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .lsfe-switcher:not(.lsfe-layout-horizontal) .lsfe-menu > li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_item_border',
				'selector' => '{{WRAPPER}} .lsfe-menu > li',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'country_flag_section',
			[
				'label' => __( 'Country Flag', 'language-switcher-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_country_flag' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'margin_country_flag',
			[
				'label' => __( 'margin', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .lsfe-switcher .lsfe-country-flag' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'native_name_section',
			[
				'label' => __( 'Native Name', 'language-switcher-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_native_name' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'margin_native_name',
			[
				'label' => __( 'margin', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .lsfe-switcher .lsfe-native-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'translated_name_section',
			[
				'label' => __( 'Translated Name', 'language-switcher-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_translated_name' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'margin_translated_name',
			[
				'label' => __( 'margin', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .lsfe-switcher .lsfe-translated-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'language_code_section',
			[
				'label' => __( 'Language Code', 'language-switcher-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_language_code' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'margin_language_code',
			[
				'label' => __( 'margin', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .lsfe-switcher .lsfe-language-code' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'before_language_code',
			[
				'label' => __( 'Before', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'after_language_code',
			[
				'label' => __( 'After', 'language-switcher-for-elementor' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_active_settings();

		$this->add_render_attribute( 'main-menu', 'class', [
			'lsfe-switcher',
		] );

		$languages = apply_filters( 'wpml_active_languages', NULL, array(
			'skip_missing' => $settings['skip_missing'],
		) );
		
		if( !empty( $languages ) ) {
			echo '<nav ' . $this->get_render_attribute_string( 'main-menu' ) . '><ul class="lsfe-menu">';
			foreach( $languages as $language ){
				echo '<li class="lsfe-menu-item">';
		
					echo ( $language['active'] ) ? '<a href="' . $language['url'] . '" class="lsfe-item lsfe-item__active">' : '<a href="' . $language['url'] . '" class="lsfe-item">';

						echo $settings['show_country_flag'] ? '<span class="lsfe-country-flag"><img src="' . $language['country_flag_url'] . '" alt="' . $language['language_code'] . '" width="18" height="12" /></span>' : '';
						
						echo $settings['show_native_name'] ? '<span class="lsfe-native-name">' . $language['native_name'] . '</span>' : '';
						
						echo $settings['show_translated_name'] ? '<span class="lsfe-translated-name">' . $language['translated_name'] . '</span>' : '';

						echo $settings['before_language_code'] ?: '';
						echo $settings['show_language_code'] ? '<span class="lsfe-language-code">' . $language['language_code'] . '</span>' : '';
						echo $settings['after_language_code'] ?: '';

					echo '</a>';

				echo '</li>';
			}
			echo '</ul></nav>';
		}

	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {}
}
