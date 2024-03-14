<?php

/**************************************************
**** Slider
***************************************************/

if ( ! class_exists( 'Businesswp_Customize_Frontpage_Slider_Settings' ) ) :

	class Businesswp_Customize_Frontpage_Slider_Settings extends Businesswp_Custom_Base_Customize_Settings {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$option = businesswp_theme_default_data();

			return array(
				'businesswp_option[slider_show]' => array(
					'setting' => array(
						'default'           => $option['slider_show'],
						'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_checkbox' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Slider Enable', 'britetechs-companion' ),
						'section'  => 'businesswp_slider_section',
						'type'     => 'toggle',
						'priority' => 1,
					),
				),
				'businesswp_option[slider_nav_show]' => array(
					'setting' => array(
						'default'           => $option['slider_nav_show'],
						'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_checkbox' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Navigation Icons Enable', 'britetechs-companion' ),
						'section'  => 'businesswp_slider_section',
						'type'     => 'checkbox',
						'priority' => 2,
						'is_default_type' => true,
					),
				),
				'businesswp_option[slider_pagination_show]' => array(
					'setting' => array(
						'default'           => $option['slider_pagination_show'],
						'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_checkbox' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Pagination Dots Enable', 'britetechs-companion' ),
						'section'  => 'businesswp_slider_section',
						'type'     => 'checkbox',
						'priority' => 3,
						'is_default_type' => true,
					),
				),
				'businesswp_option[slider_mouse_drag]' => array(
					'setting' => array(
						'default'           => $option['slider_mouse_drag'],
						'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_checkbox' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Mouse Drag Enable', 'britetechs-companion' ),
						'section'  => 'businesswp_slider_section',
						'type'     => 'checkbox',
						'priority' => 4,
						'is_default_type' => true,
					),
				),
				'businesswp_option[slider_smart_speed]' => array(
					'setting' => array(
						'default'           => $option['slider_smart_speed'],
						'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_select' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Slider Smart Speed', 'britetechs-companion' ),
						'section'  => 'businesswp_slider_section',
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
				'businesswp_option[slider_scroll_speed]' => array(
					'setting' => array(
						'default'           => $option['slider_scroll_speed'],
						'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_select' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Slider Scroll Speed', 'britetechs-companion' ),
						'section'  => 'businesswp_slider_section',
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
				'businesswp_option[slider_container_width]' => array(
					'setting' => array(
						'default'           => $option['slider_container_width'],
						'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_select' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'Container Width', 'britetechs-companion' ),
						'section'  => 'businesswp_slider_section',
						'type'     => 'select',
						'priority' => 7,
						'is_default_type' => true,
						'choices' => array(
							'container'=>'Container',
							'container-fluid'=>'Full',
						),
					),
				),
				'businesswp_option[businesswp_slider_upgrade]' => array(
					'setting' => array(
						'type' => 'option',
					),
					'control' => array(
						'label'    => esc_html__( 'slides', 'businesswp' ),
						'section'  => 'businesswp_slider_section',
						'type'     => 'upgrade',
						'priority' => 101,
						'is_default_type' => false,
					),
				),

			);
		}
	}

	new Businesswp_Customize_Frontpage_Slider_Settings();
	
endif;