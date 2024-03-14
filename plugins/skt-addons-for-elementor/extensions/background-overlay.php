<?php
namespace Skt_Addons_Elementor\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

defined( 'ABSPATH' ) || die();

class Background_Overlay {

	public static function init() {
		add_action( 'elementor/element/common/_section_background/after_section_end', [__CLASS__, 'add_section'] );
		add_action( 'elementor/element/after_add_attributes', [__CLASS__, 'add_attributes'] );
	}

	public static function add_attributes( Element_Base $element ) {
		if ( in_array( $element->get_name(), [ 'column', 'section' ] ) ) {
			return;
		}

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			return;
		}

		$settings = $element->get_settings_for_display();

		$overlay_bg = isset( $settings['_skt_addons_elementor_background_overlay_background'] ) ? $settings['_skt_addons_elementor_background_overlay_background'] : '';
		$overlay_hover_bg = isset( $settings['_skt_addons_elementor_background_overlay_hover_background'] ) ? $settings['_skt_addons_elementor_background_overlay_hover_background'] : '';

		$has_background_overlay = ( in_array( $overlay_bg, [ 'classic', 'gradient' ], true ) ||
			in_array( $overlay_hover_bg, [ 'classic', 'gradient' ], true ) );

		if ( $has_background_overlay ) {
			$element->add_render_attribute( '_wrapper', 'class', 'skt-has-bg-overlay' );
		}
	}

	public static function add_section( Element_Base $element ) {
		$element->start_controls_section(
			'_skt_addons_elementor_section_background_overlay',
			[
				'label' => __( 'Background Overlay', 'skt-addons-elementor' ) . skt_addons_elementor_get_section_icon(),
				'tab' => Controls_Manager::TAB_ADVANCED,
				'condition' => [
					'_background_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$element->start_controls_tabs( '_skt_addons_elementor_tabs_background_overlay' );

		$element->start_controls_tab(
			'_skt_addons_elementor_tab_background_overlay_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => '_skt_addons_elementor_background_overlay',
				'selector' => '{{WRAPPER}}.skt-has-bg-overlay > .elementor-widget-container:before',
			]
		);

		$element->add_control(
			'_skt_addons_elementor_background_overlay_opacity',
			[
				'label' => __( 'Opacity', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.skt-has-bg-overlay > .elementor-widget-container:before' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'_skt_addons_elementor_background_overlay_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$element->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => '_skt_addons_elementor_css_filters',
				'selector' => '{{WRAPPER}}.skt-has-bg-overlay > .elementor-widget-container:before',
			]
		);

		$element->add_control(
			'_skt_addons_elementor_overlay_blend_mode',
			[
				'label' => __( 'Blend Mode', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Normal', 'skt-addons-elementor' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}}.skt-has-bg-overlay > .elementor-widget-container:before' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'_skt_addons_elementor_tab_background_overlay_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => '_skt_addons_elementor_background_overlay_hover',
				'selector' => '{{WRAPPER}}.skt-has-bg-overlay:hover > .elementor-widget-container:before',
			]
		);

		$element->add_control(
			'_skt_addons_elementor_background_overlay_hover_opacity',
			[
				'label' => __( 'Opacity', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.skt-has-bg-overlay:hover > .elementor-widget-container:before' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'_skt_addons_elementor_background_overlay_hover_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$element->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => '_skt_addons_elementor_css_filters_hover',
				'selector' => '{{WRAPPER}}.skt-has-bg-overlay:hover > .elementor-widget-container:before',
			]
		);

		$element->add_control(
			'_skt_addons_elementor_background_overlay_hover_transition',
			[
				'label' => __( 'Transition Duration', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}}.skt-has-bg-overlay > .elementor-widget-container:before' => 'transition: background {{SIZE}}s;',
				]
			]
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}
}

Background_Overlay::init();