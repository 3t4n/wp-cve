<?php

/**************************************************
**** Front Page Common Settings
***************************************************/

if ( ! class_exists( 'Businesswp_Customize_Frontpage_Section_Common_Settings' ) ) :

	class Businesswp_Customize_Frontpage_Section_Common_Settings extends Businesswp_Custom_Base_Customize_Settings {

		/**
		 * Arguments for options.
		 *
		 * @return array
		 */
		public function elements() {

			$option = businesswp_theme_default_data();

			$elements = array();

			$section_names = array(
				'service',
				'portfolio',
				'testimonial',
				'team',
				'blog',
				'contact',
			);

			foreach ($section_names as $key => $name) {

				$title = ucwords($name);


				$elements['businesswp_option['.$name.'_show]'] = array(
					'setting' => array(
						'default'           => $option[$name.'_show'],
						'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_checkbox' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => sprintf(__( '%s Enable', 'britetechs-companion' ),$title),
						'section'  => 'businesswp_'.$name.'_section',
						'type'     => 'toggle',
						'priority' => 5,
					),
				);

				if($name!='callout'){
					$elements['businesswp_option['.$name.'_subtitle]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_subtitle'],
							'sanitize_callback' => 'sanitize_text_field',
							'type' => 'option',
							'transport' => 'postMessage',
						),
						'control' => array(
							'label'    => sprintf(__( 'Subtitle', 'britetechs-companion' )),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'text',
							'is_default_type' => true,
							'priority' => 10,
						),
					);
				}

				$elements['businesswp_option['.$name.'_title]'] = array(
					'setting' => array(
						'default'           => $option[$name.'_title'],
						'sanitize_callback' => 'wp_kses_post',
						'type' => 'option',
						'transport' => 'postMessage',
					),
					'control' => array(
						'label'    => sprintf(__( 'Title', 'britetechs-companion' )),
						'section'  => 'businesswp_'.$name.'_section',
						'type'     => 'text',
						'is_default_type' => true,
						'priority' => 15,
					),
				);

				$elements['businesswp_option['.$name.'_desc]'] = array(
					'setting' => array(
						'default'           => $option[$name.'_desc'],
						'sanitize_callback' => 'wp_kses_post',
						'type' => 'option',
						'transport' => 'postMessage',
					),
					'control' => array(
						'label'    => sprintf(__( 'Description', 'britetechs-companion' )),
						'section'  => 'businesswp_'.$name.'_section',
						'type'     => 'textarea',
						'is_default_type' => true,
						'priority' => 20,
					),
				);

				if($name!='callout'){

					$elements['businesswp_option['.$name.'_divider_show]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_divider_show'],
							'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_checkbox' ),
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Divider Show', 'britetechs-companion' ),$title),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'checkbox',
							'is_default_type' => true,
							'priority' => 25,
						),
					);

					$elements['businesswp_option['.$name.'_divider_type]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_divider_type'],
							'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_select' ),
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Divider Type', 'britetechs-companion' )),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'select',
							'is_default_type' => true,
							'choices' => array(
								'center-ball'         => 'center-ball',
								'center-diamond'=>'center-diamond',
								'center-square'=>'center-square',
							),
							'priority' => 30,
						),
					);
				}

				if($name=='callout'){

					$elements['businesswp_option['.$name.'_button_text]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_button_text'],
							'sanitize_callback' => 'sanitize_text_field',
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Button Text', 'britetechs-companion' )),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'text',
							'is_default_type' => true,
							'priority' => 41,
						),
					);
					$elements['businesswp_option['.$name.'_button_url]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_button_url'],
							'sanitize_callback' => 'sanitize_text_field',
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Button URL', 'britetechs-companion' )),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'text',
							'is_default_type' => true,
							'priority' => 42,
						),
					);
				}

				if($name=='newsletter'){

					$elements['businesswp_option['.$name.'_button_text]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_button_text'],
							'sanitize_callback' => 'sanitize_text_field',
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Button Text', 'britetechs-companion' )),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'text',
							'is_default_type' => true,
							'priority' => 41,
						),
					);
					$elements['businesswp_option['.$name.'_button_url]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_button_url'],
							'sanitize_callback' => 'sanitize_text_field',
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Button URL', 'britetechs-companion' )),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'text',
							'is_default_type' => true,
							'priority' => 42,
						),
					);

				$elements['businesswp_option['.$name.'_form_shortcode]'] = array(
				'setting' => array(
					'default'           => $option[$name.'_form_shortcode'],
					'sanitize_callback' => 'wp_kses_post',
					'type' => 'option',
					),
					'control' => array(
						'label'    => sprintf(__( 'Newsletter Form Shortcode', 'britetechs-companion' )),
						'section'  => 'businesswp_'.$name.'_section',
						'type'     => 'textarea',
						'is_default_type' => true,
						'priority' => 20,
					),
				);
				}
				if($name=='about'){
					$elements['businesswp_option['.$name.'_button_text]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_button_text'],
							'sanitize_callback' => 'sanitize_text_field',
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Button Text', 'britetechs-companion' )),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'text',
							'is_default_type' => true,
							'priority' => 41,
						),
					);
					$elements['businesswp_option['.$name.'_button_url]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_button_url'],
							'sanitize_callback' => 'sanitize_text_field',
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Button URL', 'britetechs-companion' )),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'text',
							'is_default_type' => true,
							'priority' => 42,
						),
					);
					$elements['businesswp_option['.$name.'_content]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_content'],
							'sanitize_callback' => 'wp_kses_post',
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'About Content', 'britetechs-companion' )),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'textarea',
							'is_default_type' => true,
							'priority' => 40,
						),
					);
				}
				if($name=='contact'){
					$elements['businesswp_option['.$name.'_button_text]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_button_text'],
							'sanitize_callback' => 'sanitize_text_field',
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Button Text', 'britetechs-companion' )),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'text',
							'is_default_type' => true,
							'priority' => 41,
						),
					);
					$elements['businesswp_option['.$name.'_button_url]'] = array(
						'setting' => array(
							'default'           => $option[$name.'_button_url'],
							'sanitize_callback' => 'sanitize_text_field',
							'type' => 'option',
						),
						'control' => array(
							'label'    => sprintf(__( 'Button URL', 'britetechs-companion' )),
							'section'  => 'businesswp_'.$name.'_section',
							'type'     => 'text',
							'is_default_type' => true,
							'priority' => 42,
						),
					);
				}

				$elements['businesswp_option['.$name.'_container_width]'] = array(
					'setting' => array(
						'default'           => $option[$name.'_container_width'],
						'sanitize_callback' => array( 'Businesswp_Customizer_Sanitize', 'sanitize_select' ),
						'type' => 'option',
					),
					'control' => array(
						'label'    => sprintf(__( 'Container Width', 'britetechs-companion' )),
						'section'  => 'businesswp_'.$name.'_section',
						'type'     => 'select',
						'is_default_type' => true,
						'choices' => array(
							'container'=>'Container',
							'container-fluid'=>'Full',
						),
						'priority' => 45,
					),
				);

				$elements['businesswp_option[businesswp_'.$name.'_upgrade]'] = array(
					'setting' => array(
						'type' => 'option',
					),
					'control' => array(
						'label'    => sprintf(esc_html__( '%s', 'businesswp' ), $name ),
						'section'  => 'businesswp_'.$name.'_section',
						'type'     => 'upgrade',
						'priority' => 101,
						'is_default_type' => false,
					),
				);
			}

			return $elements;

		}

	}

	new Businesswp_Customize_Frontpage_Section_Common_Settings();
	
endif;