<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WPKoi_Elements_Lite_Effects_Extension' ) ) {

	/**
	 * Define WPKoi_Elements_Lite_Effects_Extension class
	 */
	class WPKoi_Elements_Lite_Effects_Extension {

		/**
		 * Sections Data
		 */
		public $sections_data = array();

		/**
		 * Columns Data
		 */
		public $columns_data = array();

		/**
		 * Widgets Data
		 */
		public $widgets_data = array();

		public $view_more_sections = array();


		public $default_widget_settings = array(
			
		);

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance = null;

		/**
		 * Init Handler
		 */
		public function init() {
			add_action( 'elementor/element/common/_section_responsive/after_section_end', array( $this, 'after_common_section_responsive' ), 10, 2 );

			add_action( 'elementor/frontend/widget/before_render', array( $this, 'widget_before_render' ), 10, 1 );

			add_action( 'elementor/widget/before_render_content', array( $this, 'widget_before_render_content' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		}


		/**
		 * After section_layout callback
		 */
		public function after_common_section_responsive( $obj, $args ) {
			$obj->start_controls_section(
				'widget_wpkoi_tricks',
				array(
					'label' => esc_html__( 'Effects (WPKoi)', 'wpkoi-elements' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);

			$obj->add_control(
				'rotate_heading',
				array(
					'label' => esc_html__( 'Rotate', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
				)
			);
			
			$obj->add_control(
				'wpkoi_widget_rotate',
				[
					'label'        => esc_html__( 'Use Rotate?', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'prefix_class' => 'wpkoi-rotate-effect-',
				]
			);
			
			$obj->start_controls_tabs( 'wpkoi_widget_motion_effect_tabs' );

			$obj->start_controls_tab(
				'wpkoi_widget_motion_effect_tab_normal',
				[
					'label' => esc_html__( 'Normal', 'wpkoi-elements' ),
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
					],
				]
			);
		
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatex_normal',
				[
					'label'      => esc_html__( 'Rotate X', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range'      => [
						'px' => [
							'min'  => -180,
							'max'  => 180,
						],
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
					],
				]
			);
	
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatey_normal',
				[
					'label'      => esc_html__( 'Rotate Y', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range'      => [
						'px' => [
							'min'  => -180,
							'max'  => 180,
						],
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
					],
				]
			);
	
	
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatez_normal',
				[
					'label'   => __( 'Rotate Z', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min'  => -180,
							'max'  => 180,
						],
					],
					'selectors' => [
						'(desktop){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget' => 'transform: translate( {{wpkoi_widget_effect_transx_normal.SIZE || 0}}px, {{wpkoi_widget_effect_transy_normal.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_normal.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_normal.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_normal.SIZE || 0}}deg);',
						'(tablet){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget' => 'transform: translate( {{wpkoi_widget_effect_transx_normal_tablet.SIZE || 0}}px, {{wpkoi_widget_effect_transy_normal_tablet.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_normal.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_normal.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_normal.SIZE || 0}}deg);',
						'(mobile){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget' => 'transform: translate( {{wpkoi_widget_effect_transx_normal_mobile.SIZE || 0}}px, {{wpkoi_widget_effect_transy_normal_mobile.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_normal.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_normal.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_normal.SIZE || 0}}deg);',
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
					],
				]
			);
	
			$obj->end_controls_tab();
	
			$obj->start_controls_tab(
				'wpkoi_widget_motion_effect_tab_hover',
				[
					'label' => esc_html__( 'Hover', 'wpkoi-elements' ),
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
					],
				]
			);
	
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatex_hover',
				[
					'label'      => esc_html__( 'Rotate X', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range'      => [
						'px' => [
							'min'  => -180,
							'max'  => 180,
						],
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
					],
				]
			);
	
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatey_hover',
				[
					'label'      => esc_html__( 'Rotate Y', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range'      => [
						'px' => [
							'min'  => -180,
							'max'  => 180,
						],
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
					],
				]
			);
	
	
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatez_hover',
				[
					'label'   => __( 'Rotate Z', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min'  => -180,
							'max'  => 180,

						],
					],
					'selectors' => [
						'(desktop){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget:hover' => 'transform: translate( {{wpkoi_widget_effect_transx_hover.SIZE || 0}}px, {{wpkoi_widget_effect_transy_hover.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_hover.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_hover.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_hover.SIZE || 0}}deg);',
						'(tablet){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget:hover' => 'transform: translate( {{wpkoi_widget_effect_transx_hover_tablet.SIZE || 0}}px, {{wpkoi_widget_effect_transy_hover_tablet.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_hover.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_hover.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_hover.SIZE || 0}}deg);',
						'(mobile){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget:hover' => 'transform: translate( {{wpkoi_widget_effect_transx_hover_mobile.SIZE || 0}}px, {{wpkoi_widget_effect_transy_hover_mobile.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_hover.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_hover.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_hover.SIZE || 0}}deg);',
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
					],
				]
			);
	
	
			$obj->end_controls_tab();
	
			$obj->end_controls_tabs();
			
			$obj->add_control(
				'adv_parallax_heading',
				array(
					'label' => esc_html__( 'Parallax', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);
			
			$obj->add_control(
				'adv_parallax_effects_show',
				[
					'label'        => esc_html__( 'Use Parallax?', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'default'      => '',
					'return_value' => 'yes',
				]
			);
			
			$obj->add_control(
				'adv_parallax_subheading',
				array(
					'label' => esc_html__( 'The result of the effects are not visible in the editor, only on the live page.', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
				)
			);
		
			$obj->add_control(
				'adv_parallax_effects_y',
				[
					'label' => __( 'Vertical Parallax', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_y_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -500,
							'max'   => 500,
							'step' => 10,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 50,
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_y_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -500,
							'max'   => 500,
							'step' => 10,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
	
	
			$obj->end_popover();
	
	
			$obj->add_control(
				'adv_parallax_effects_x',
				[
					'label' => __( 'Horizontal Parallax', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_x_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -500,
							'max'   => 500,
							'step' => 10,
						],
					],
	
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_x_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -500,
							'max'   => 500,
							'step' => 10,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->end_popover();
			
			$obj->add_control(
				'adv_parallax_effects_opacity',
				[
					'label' => __( 'Opacity', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_opacity_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => 1,
							'max'   => 100,
							'step' => 1,
						],
					],
	
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_opacity_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => 1,
							'max'   => 100,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->end_popover();
		
			$obj->add_control(
				'adv_parallax_effects_rotate',
				[
					'label' => __( 'Rotate', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_rotate_value_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'  => -360,
							'max'  => 360,
							'step' => 5,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_rotate_value_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'  => -360,
							'max'  => 360,
							'step' => 5,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->end_popover();
	
			$obj->add_control(
				'adv_parallax_effects_scale',
				[
					'label' => __( 'Scale', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_scale_value',
				[
					'label'       => esc_html__( 'Value', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'  => -10,
							'max'  => 10,
							'step' => 0.1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->end_popover();
	
			$obj->add_control(
				'adv_parallax_effects_blur',
				[
					'label' => __( 'Blur', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_blur_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => 0,
							'max'   => 20,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_blur_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => 0,
							'max'   => 20,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->end_popover();
	
			$obj->add_control(
				'adv_parallax_effects_hue',
				[
					'label' => __( 'Hue', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_hue_value',
				[
					'label'       => esc_html__( 'Value', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'  => 0,
							'max'  => 360,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->end_popover();
	
	
			$obj->add_control(
				'adv_parallax_effects_grayscale',
				[
					'label' => __( 'Grayscale', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_grayscale_value',
				[
					'label'       => esc_html__( 'Value', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
	
			$obj->end_popover();
	
	
			$obj->add_control(
				'adv_parallax_effects_saturate',
				[
					'label' => __( 'Saturate', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_saturate_value',
				[
					'label'       => esc_html__( 'Value', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
	
			$obj->end_popover();
	
	
			$obj->add_control(
				'adv_parallax_effects_sepia',
				[
					'label' => __( 'Sepia', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_sepia_value',
				[
					'label'       => esc_html__( 'Value', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
	
			$obj->end_popover();
	
			$obj->end_controls_section();
		}

		public function widget_before_render( $widget ) {
			$data     = $widget->get_data();
			$settings = $data['settings'];

			$settings = wp_parse_args( $settings, $this->default_widget_settings );

			$widget_settings = array();
			
			$parallax_settings = $widget->get_settings_for_display();

			if( $parallax_settings['adv_parallax_effects_show'] == 'yes' ) {

				$parallax_y_start    = ($parallax_settings['adv_parallax_effects_y_start']['size']) ? $parallax_settings['adv_parallax_effects_y_start']['size'] : 0;
				$parallax_y_end      = ($parallax_settings['adv_parallax_effects_y_end']['size']) ? $parallax_settings['adv_parallax_effects_y_end']['size'] : 0;
	
				$parallax_x_start    = $parallax_settings['adv_parallax_effects_x_start']['size'];
				$parallax_x_end      = $parallax_settings['adv_parallax_effects_x_end']['size'];
	
				$parallax_opacity_start    = ($parallax_settings['adv_parallax_effects_opacity_start']['size']) ? $parallax_settings['adv_parallax_effects_opacity_start']['size'] : 100;
				$parallax_opacity_end      = ($parallax_settings['adv_parallax_effects_opacity_end']['size']) ? $parallax_settings['adv_parallax_effects_opacity_end']['size'] : 100;
	
				$parallax_blur_start = ($parallax_settings['adv_parallax_effects_blur_start']['size']) ? $parallax_settings['adv_parallax_effects_blur_start']['size'] : 0;
				$parallax_blur_end   = ($parallax_settings['adv_parallax_effects_blur_end']['size']) ? $parallax_settings['adv_parallax_effects_blur_end']['size'] : 0;
	
				$parallax_rotate_start     = ($parallax_settings['adv_parallax_effects_rotate_value_start']['size']) ? $parallax_settings['adv_parallax_effects_rotate_value_start']['size'] : 0;
				$parallax_rotate_end     = ($parallax_settings['adv_parallax_effects_rotate_value_end']['size']) ? $parallax_settings['adv_parallax_effects_rotate_value_end']['size'] : 0;
	
				$parallax_scale      = $parallax_settings['adv_parallax_effects_scale_value']['size'];
	
				$parallax_hue        = $parallax_settings['adv_parallax_effects_hue_value']['size'];
	
				$parallax_grayscale  = $parallax_settings['adv_parallax_effects_grayscale_value']['size'];
	
				$parallax_saturate   = $parallax_settings['adv_parallax_effects_saturate_value']['size'];
	
				$parallax_sepia      = $parallax_settings['adv_parallax_effects_sepia_value']['size'];
	
				if ( $parallax_settings['adv_parallax_effects_y'] ) {
					$widget->add_render_attribute( "_wrapper", "data-uk-parallax", "y: '" . $parallax_y_start . "," . $parallax_y_end . "'," );
				}
	
				if ( $parallax_settings['adv_parallax_effects_x'] ) {
					$widget->add_render_attribute( "_wrapper", "data-uk-parallax", "x: '" . $parallax_x_start . "," . $parallax_x_end . "'," );
				}
	
				if ( $parallax_settings['adv_parallax_effects_opacity'] ) {
					$parallax_opacity_start_full = '0.' . $parallax_opacity_start;
					if ($parallax_opacity_start < 10){ $parallax_opacity_start_full = '0.0' . $parallax_opacity_start;}
					if ($parallax_opacity_start == 100){ $parallax_opacity_start_full = '1'; }
					$parallax_opacity_end_full = '0.' . $parallax_opacity_end;
					if ($parallax_opacity_end < 10){ $parallax_opacity_end_full = '0.0' . $parallax_opacity_end;}
					if ($parallax_opacity_end == 100){ $parallax_opacity_end_full = '1'; }
					
					$widget->add_render_attribute( "_wrapper", "data-uk-parallax", "opacity: '" . $parallax_opacity_start_full . "," . $parallax_opacity_end_full . "'," );
				}
	
				if ( $parallax_settings['adv_parallax_effects_blur'] ) {
					$widget->add_render_attribute( "_wrapper", "data-uk-parallax", "blur: '" . $parallax_blur_start . "," . $parallax_blur_end . "'," );
				}
	
				if ( $parallax_settings['adv_parallax_effects_rotate'] ) {
					$widget->add_render_attribute( "_wrapper", "data-uk-parallax", "rotate: '" . $parallax_rotate_start . "," . $parallax_rotate_end . "'," );
				}
	
				if ( $parallax_settings['adv_parallax_effects_scale'] ) {
					$widget->add_render_attribute( '_wrapper', 'data-uk-parallax', 'scale: ' . $parallax_scale . ',' );
				}
	
				if ( $parallax_settings['adv_parallax_effects_hue'] ) {
					$widget->add_render_attribute( '_wrapper', 'data-uk-parallax', 'hue: ' . $parallax_hue . ',' );
				}
	
				if ( $parallax_settings['adv_parallax_effects_grayscale'] ) {
					$widget->add_render_attribute( '_wrapper', 'data-uk-parallax', 'grayscale: ' . $parallax_grayscale . ',' );
				}
	
				if ( $parallax_settings['adv_parallax_effects_saturate'] ) {
					$widget->add_render_attribute( '_wrapper', 'data-uk-parallax', 'saturate: ' . $parallax_saturate . ',' );
				}
	
				if ( $parallax_settings['adv_parallax_effects_sepia'] ) {
					$widget->add_render_attribute( '_wrapper', 'data-uk-parallax', 'sepia: ' . $parallax_sepia . ',' );
				}
	
			}
		
			$widget_settings = apply_filters(
				'wpkoi-tricks/frontend/widget/settings',
				$widget_settings,
				$widget,
				$this
			);

			if ( ! empty( $widget_settings ) ) {
				$widget->add_render_attribute( '_wrapper', array(
					'data-wpkoi-tricks-settings' => json_encode( $widget_settings ),
				) );
			}

			$this->widgets_data[ $data['id'] ] = $widget_settings;
		}

		public function widget_before_render_content( $widget ) {

			$data     = $widget->get_data();
			$settings = $data['settings'];

			$settings = wp_parse_args( $settings, $this->default_widget_settings );
			$settings = apply_filters( 'wpkoi-tricks/frontend/widget-content/settings', $settings, $widget, $this );

			
		}

		public function enqueue_scripts() {

			wp_enqueue_script('uikit',WPKOI_ELEMENTS_LITE_URL.'elements/effects/assets/uikit.js', array('jquery'),WPKOI_ELEMENTS_LITE_VERSION, true);
			wp_enqueue_script('uikit-parallax',WPKOI_ELEMENTS_LITE_URL.'elements/effects/assets/parallax.js', array('jquery'),WPKOI_ELEMENTS_LITE_VERSION, true);

			wpkoi_elements_lite_integration()->elements_data['sections'] = $this->sections_data;
			wpkoi_elements_lite_integration()->elements_data['columns'] = $this->columns_data;
			wpkoi_elements_lite_integration()->elements_data['widgets'] = $this->widgets_data;
		}

		/**
		 * Returns the instance.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

/**
 * Returns instance of WPKoi_Elements_Lite_Effects_Extension
 */
function wpkoi_elements_lite_effect_extension() {
	return WPKoi_Elements_Lite_Effects_Extension::get_instance();
}
wpkoi_elements_lite_effect_extension()->init();