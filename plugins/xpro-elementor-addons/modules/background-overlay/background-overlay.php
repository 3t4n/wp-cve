<?php

namespace XproElementorAddons\Module;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

defined( 'ABSPATH' ) || die();

class Background_Overlay {

	public static function init() {

		add_action( 'elementor/element/common/_section_background/after_section_end', array( __CLASS__, 'register_controls' ) );
		add_action( 'elementor/element/after_add_attributes', array( __CLASS__, 'add_attributes' ) );

	}

	public static function register_controls( Element_Base $element ) {

		$element->start_controls_section(
			'section_xpro_elementor_widget_bg_overlay',
			array(
				'label' => __( 'Background Overlay', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->start_controls_tabs( 'xpro_elementor_tabs_background_overlay' );

		$element->start_controls_tab(
			'xpro_elementor_tab_background_overlay_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'xpro_elementor_background_overlay',
				'selector' => '{{WRAPPER}}.xpro-widget-bg-overlay > .elementor-widget-container:before',
			)
		);

		$element->add_control(
			'xpro_elementor_background_overlay_opacity',
			array(
				'label'     => __( 'Opacity', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => .5,
				),
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}.xpro-widget-bg-overlay > .elementor-widget-container' => 'position: relative; z-index: 1;',
					'{{WRAPPER}}.xpro-widget-bg-overlay > .elementor-widget-container:before' => 'opacity: {{SIZE}};',
				),
				'condition' => array(
					'xpro_elementor_background_overlay_background' => array( 'classic', 'gradient' ),
				),
			)
		);

		$element->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'xpro_elementor_css_filters',
				'selector' => '{{WRAPPER}}.xpro-widget-bg-overlay > .elementor-widget-container:before',
			)
		);

		$element->add_control(
			'xpro_elementor_overlay_blend_mode',
			array(
				'label'     => __( 'Blend Mode', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''            => __( 'Normal', 'xpro-elementor-addons' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'luminosity'  => 'Luminosity',
				),
				'selectors' => array(
					'{{WRAPPER}}.xpro-widget-bg-overlay > .elementor-widget-container:before' => 'mix-blend-mode: {{VALUE}}',
				),
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'xpro_elementor_tab_background_overlay_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'xpro_elementor_background_overlay_hover',
				'selector' => '{{WRAPPER}}.xpro-widget-bg-overlay:hover > .elementor-widget-container:before',
			)
		);

		$element->add_control(
			'xpro_elementor_background_overlay_hover_opacity',
			array(
				'label'     => __( 'Opacity', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => .5,
				),
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}.xpro-widget-bg-overlay:hover > .elementor-widget-container:before' => 'opacity: {{SIZE}};',
				),
				'condition' => array(
					'xpro_elementor_background_overlay_hover_background' => array( 'classic', 'gradient' ),
				),
			)
		);

		$element->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'xpro_elementor_css_filters_hover',
				'selector' => '{{WRAPPER}}.xpro-widget-bg-overlay:hover > .elementor-widget-container:before',
			)
		);

		$element->add_control(
			'xpro_elementor_background_overlay_hover_transition',
			array(
				'label'     => __( 'Transition Duration', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0.3,
				),
				'range'     => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}}.xpro-widget-bg-overlay > .elementor-widget-container:before' => 'transition: background {{SIZE}}s;',
				),
			)
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}

	public static function add_attributes( $element ) {

		if ( in_array( $element->get_name(), array( 'column', 'section' ), true ) ) {
			return;
		}

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			return;
		}

		$settings = $element->get_settings_for_display();

		$overlay_bg       = isset( $settings['xpro_elementor_background_overlay_background'] ) ? $settings['xpro_elementor_background_overlay_background'] : '';
		$overlay_hover_bg = isset( $settings['xpro_elementor_background_overlay_hover_background'] ) ? $settings['xpro_elementor_background_overlay_hover_background'] : '';

		$has_background_overlay = ( in_array( $overlay_bg, array( 'classic', 'gradient' ), true ) ||
									in_array( $overlay_hover_bg, array( 'classic', 'gradient' ), true ) );

		if ( $has_background_overlay ) {
			$element->add_render_attribute( '_wrapper', 'class', 'xpro-widget-bg-overlay' );
		}
	}

}

Background_Overlay::init();
