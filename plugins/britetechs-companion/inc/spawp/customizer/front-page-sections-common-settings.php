<?php
if ( ! class_exists( 'SPAWP_Customize_Frontpage_Section_Common_Settings' ) ) :
	class SPAWP_Customize_Frontpage_Section_Common_Settings extends SPAWP_Custom_Base_Customize_Settings {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$option = spawp_theme_default_data();

			$elements = array();

			$section_names = array(
				'service',
				'feature',
				'team',
				'testimonial',
			);
			foreach ($section_names as $key => $name) {
				$title = ucwords($name);

				$elements['spawp_option['.$name.'_show]'] = array(
					'setting' => array(
						'default'           => $option[$name.'_show'],
						'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_checkbox' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => sprintf(__( '%s Enable', 'spawp' ),$title),
						'section'  => 'spawp_'.$name.'_section',
						'type'     => 'toggle',
						'priority' => 5,
					),
				);

				if($name=='portfolio'){
					$elements['spawp_option['.$name.'_no_to_show]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_no_to_show'],
							'sanitize_callback' => 'absint',
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'No. of items to show', 'spawp' )),
							'section'  => 'spawp_'.$name.'_section',
							'type'     => 'number',
							'is_default_type' => true,
							'priority' => 6,
						),
					);
				}

				if($name!='callout'){
					$elements['spawp_option['.$name.'_subtitle]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_subtitle'],
							'sanitize_callback' => 'sanitize_text_field',
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Subtitle', 'spawp' )),
							'section'  => 'spawp_'.$name.'_section',
							'type'     => 'text',
							'is_default_type' => true,
							'priority' => 10,
						),
					);
				}

				$elements['spawp_option['.$name.'_title]'] = array(
					'setting' => array(
						'default'           => $option[$name.'_title'],
						'sanitize_callback' => 'wp_kses_post',
						'type' => 'option',
					),
					'control' => array(
						'label'    => sprintf(__( 'Title', 'spawp' )),
						'section'  => 'spawp_'.$name.'_section',
						'type'     => 'text',
						'is_default_type' => true,
						'priority' => 15,
					),
				);

				$elements['spawp_option['.$name.'_desc]'] = array(
					'setting' => array(
						'default'           => $option[$name.'_desc'],
						'sanitize_callback' => 'wp_kses_post',
						'type' => 'option',
					),
					'control' => array(
						'label'    => sprintf(__( 'Description', 'spawp' )),
						'section'  => 'spawp_'.$name.'_section',
						'type'     => 'textarea',
						'is_default_type' => true,
						'priority' => 20,
					),
				);

				if($name!='callout'){
					$elements['spawp_option['.$name.'_divider_show]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_divider_show'],
							'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_checkbox' ),
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Divider Show', 'spawp' ),$title),
							'section'  => 'spawp_'.$name.'_section',
							'type'     => 'checkbox',
							'is_default_type' => true,
							'priority' => 25,
						),
					);

					$elements['spawp_option['.$name.'_divider_type]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_divider_type'],
							'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_select' ),
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Divider Type', 'spawp' )),
							'section'  => 'spawp_'.$name.'_section',
							'type'     => 'select',
							'is_default_type' => true,
							'choices' => array(
								''         => 'Line',
								'div-arrow-down'=>'Arrow Down',
								'div-tab-down'=>'Tab Down',
								'div-stopper'=>'Stopper',
								'div-dot'=>'Dot',
							),
							'priority' => 30,
						),
					);

					$elements['spawp_option['.$name.'_divider_width]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_divider_width'],
							'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_select' ),
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Divider Width', 'spawp' )),
							'section'  => 'spawp_'.$name.'_section',
							'type'     => 'select',
							'is_default_type' => true,
							'choices' => array(
								'w-10'         => 'w-10',
								'w-20'         => 'w-20',
								'w-30'         => 'w-30',
								'w-40'         => 'w-40',
								'w-50'         => 'w-50',
								'w-60'         => 'w-60',
								'w-70'         => 'w-70',
								'w-80'         => 'w-80',
								'w-90'         => 'w-90',
								'w-100'        => 'w-100',
							),
							'priority' => 31,
						),
					);
				}

				$elements['spawp_option['.$name.'_container_width]'] = array(
					'setting' => array(
						'default'           => $option[$name.'_container_width'],
						'sanitize_callback' => array( 'SPAWP_Customizer_Sanitize', 'sanitize_select' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => sprintf(__( 'Container Width', 'spawp' )),
						'section'  => 'spawp_'.$name.'_section',
						'type'     => 'select',
						'is_default_type' => true,
						'choices' => array(
							'container'=>'Container',
							'container-fluid'=>'Full',
						),
						'priority' => 45,
					),
				);

				$elements['spawp_option[spawp_'.$name.'_upgrade]'] = array(
					'setting' => array(
						'type' => 'option',
					),
					'control' => array(
						'label'    => sprintf(esc_html__( '%s', 'spawp' ), $name ),
						'section'  => 'spawp_'.$name.'_section',
						'type'     => 'upgrade',
						'priority' => 101,
						'is_default_type' => false,
					),
				);

			}

			return $elements;
		}
	}
	new SPAWP_Customize_Frontpage_Section_Common_Settings();
endif;