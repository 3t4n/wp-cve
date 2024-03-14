<?php

/**************************************************
**** Section Layout Setting
***************************************************/

if(!class_exists('Businesswp_Customize_Frontpage_Layout_Setting')):

	class Businesswp_Customize_Frontpage_Layout_Setting extends Businesswp_Custom_Base_Customize_Settings{

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$option = businesswp_theme_default_data();

			return array(

				'businesswp_option[contact_layout]' => array(
				'setting' => array(
					'default'           => $option['contact_layout'],
					'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_select' ),
					'type' => 'option',
					),
				'control' => array(
					'label'    => sprintf(__( 'Contact Layout', 'britetechs-companion' )),
					'section'  => 'businesswp_contact_section',
					'type'     => 'select',
					'is_default_type' => true,
					'choices'       => array(
						'layout1' =>esc_html__( 'Layout 1', 'britetechs-companion' ),
					),
					'priority' => 6,
					),
				),

			'businesswp_option[blog_layout]' => array(
				'setting' => array(
					'default'           => $option['blog_layout'],
					'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_select' ),
					'type' => 'option',
					),
				'control' => array(
					'label'    => sprintf(__( 'Blog Layout', 'britetechs-companion' )),
					'section'  => 'businesswp_blog_section',
					'type'     => 'select',
					'is_default_type' => true,
					'choices'       => array(
						'layout1' =>esc_html__( 'Layout 1', 'britetechs-companion' ),
					),
					'priority' => 6,
					),
				),

			
			'businesswp_option[testimonial_layout]' => array(
				'setting' => array(
					'default'           => $option['testimonial_layout'],
					'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_select' ),
					'type' => 'option',
					),
				'control' => array(
					'label'    => sprintf(__( 'Testimonial Layout', 'britetechs-companion' )),
					'section'  => 'businesswp_testimonial_section',
					'type'     => 'select',
					'is_default_type' => true,
					'choices'       => array(
						'layout1' =>esc_html__( 'Layout 1', 'britetechs-companion' ),
					),
					'priority' => 6,
					),
				),

			'businesswp_option[team_layout]' => array(
				'setting' => array(
					'default'           => $option['team_layout'],
					'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_select' ),
					'type' => 'option',
					),
				'control' => array(
					'label'    => sprintf(__( 'Team Layout', 'britetechs-companion' )),
					'section'  => 'businesswp_team_section',
					'type'     => 'select',
					'is_default_type' => true,
					'choices'       => array(
						'layout1' =>esc_html__( 'Layout 1', 'britetechs-companion' ),
					),
					'priority' => 6,
					),
				),

			'businesswp_option[service_layout]' => array(
				'setting' => array(
					'default'           => $option['service_layout'],
					'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_select' ),
					'type' => 'option',
					),
				'control' => array(
					'label'    => sprintf(__( 'Service Layout', 'britetechs-companion' )),
					'section'  => 'businesswp_service_section',
					'type'     => 'select',
					'is_default_type' => true,
					'choices'       => array(
						'layout1' =>esc_html__( 'Layout 1', 'britetechs-companion' ),
					),
					'priority' => 6,
					),
				),
			);
		}

	}

	new Businesswp_Customize_Frontpage_Layout_Setting();

endif;