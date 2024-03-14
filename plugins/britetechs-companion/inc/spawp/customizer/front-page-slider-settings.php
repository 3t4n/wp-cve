<?php
if ( ! class_exists( 'SPAWP_Customize_Frontpage_Slider_Settings' ) ) :
	class SPAWP_Customize_Frontpage_Slider_Settings extends SPAWP_Custom_Base_Customize_Settings {
		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$option = spawp_theme_default_data();

			return array(
				'spawp_option[slider_show]' => array(
					'setting' => array(
						'default'           => $option['slider_show'],
						'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_checkbox' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Slider Enable', 'spawp' ),
						'section'  => 'spawp_slider_section',
						'type'     => 'toggle',
						'priority' => 1,
					),
				),
				'spawp_option[slider_nav_show]' => array(
					'setting' => array(
						'default'           => $option['slider_nav_show'],
						'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_checkbox' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Navigation Icons Enable', 'spawp' ),
						'section'  => 'spawp_slider_section',
						'type'     => 'checkbox',
						'priority' => 2,
						'is_default_type' => true,
					),
				),
				'spawp_option[slider_pagination_show]' => array(
					'setting' => array(
						'default'           => $option['slider_pagination_show'],
						'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_checkbox' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Pagination Dots Enable', 'spawp' ),
						'section'  => 'spawp_slider_section',
						'type'     => 'checkbox',
						'priority' => 3,
						'is_default_type' => true,
					),
				),
				'spawp_option[slider_mouse_drag]' => array(
					'setting' => array(
						'default'           => $option['slider_mouse_drag'],
						'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_checkbox' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Mouse Drag Enable', 'spawp' ),
						'section'  => 'spawp_slider_section',
						'type'     => 'checkbox',
						'priority' => 4,
						'is_default_type' => true,
					),
				),
				'spawp_option[slider_smart_speed]' => array(
					'setting' => array(
						'default'           => $option['slider_smart_speed'],
						'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_select' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Slider Smart Speed', 'spawp' ),
						'section'  => 'spawp_slider_section',
						'type'     => 'select',
						'priority' => 5,
						'is_default_type' => true,
						'choices' => array(
							500=>500,
							1000=>1000,
							1500=>1500,
							2000=>2000,
							2500=>2500,
							3000=>3000,
							3500=>3500,
							4000=>4000,
							4500=>4500,
							5000=>5000,
						),
					),
				),
				'spawp_option[slider_scroll_speed]' => array(
					'setting' => array(
						'default'           => $option['slider_scroll_speed'],
						'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_select' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Slider Scroll Speed', 'spawp' ),
						'section'  => 'spawp_slider_section',
						'type'     => 'select',
						'priority' => 6,
						'is_default_type' => true,
						'choices' => array(
							500=>500,
							1000=>1000,
							1500=>1500,
							2000=>2000,
							2500=>2500,
							3000=>3000,
							3500=>3500,
							4000=>4000,
							4500=>4500,
							5000=>5000,
						),
					),
				),
				'spawp_option[slider_container_width]' => array(
					'setting' => array(
						'default'           => $option['slider_container_width'],
						'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_select' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Container Width', 'spawp' ),
						'section'  => 'spawp_slider_section',
						'type'     => 'select',
						'priority' => 7,
						'is_default_type' => true,
						'choices' => array(
							'container'=>'Container',
							'container-fluid'=>'Full',
						),
					),
				),
				'spawp_option[spawp_slider_upgrade]' => array(
					'setting' => array(
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'slides', 'spawp' ),
						'section'  => 'spawp_slider_section',
						'type'     => 'upgrade',
						'priority' => 101,
						'is_default_type' => false,
					),
				),
			);
		}
	}
	new SPAWP_Customize_Frontpage_Slider_Settings();
endif;