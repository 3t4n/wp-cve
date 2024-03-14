<?php
function bizcor_customizer_slider_section( $wp_customize ){

	global $bizcor_options;

	// Frontpage Sections Panel
	$wp_customize->add_panel( 'bizcor_frontpage',
		array(
			'priority'       => 33,
			'capability'     => 'edit_theme_options',
			'title'          => esc_html__('Bizcor Homepage Sections','bizcor'),
		)
	);

		// Homepage Slider
		$wp_customize->add_section( 'slider_section',
			array(
				'priority'    => 1,
				'title'       => esc_html__('Section Slider','bizcor'),
				'panel'       => 'bizcor_frontpage',
			)
		);

			// bizcor_slider_disable
			$wp_customize->add_setting('bizcor_slider_disable',
					array(
						'sanitize_callback' => 'bizcor_sanitize_checkbox',
						'default'           => $bizcor_options['bizcor_slider_disable'],
						'priority'          => 1,
					)
				);
			$wp_customize->add_control('bizcor_slider_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'bizcor'),
					'section'     => 'slider_section',
				)
			);

			// bizcor_slider_content
			$wp_customize->add_setting('bizcor_slider_content',array(
					'sanitize_callback' => 'bizcor_sanitize_repeatable_data_field',
					'transport'         => 'refresh', // refresh or postMessage
					'priority'          => 2,
					'default'           => $bizcor_options['bizcor_slider_content'],
				) );

			$wp_customize->add_control(new Bizcor_Repeatable_Control($wp_customize,'bizcor_slider_content',
					array(
						'label'         => esc_html__('Slider Content','bizcor'),
						'section'       => 'slider_section',
						'live_title_id' => 'text', // apply for unput text and textarea only
						'title_format'  => esc_html__( '[live_title]','bizcor'), // [live_title]
						'max_item'      => 3,
						'limited_msg' 	=> bizcor_upgrade_pro_msg(),
						'fields'    => array(
							'icon_type'  => array(
								'title' => esc_html__('Custom icon','bizcor'),
								'type'  =>'select',
								'options' => array(
									//'icon' => esc_html__('Icon', 'bizcor'),
									'image' => esc_html__('image','bizcor'),
								),
							),
							'icon'  => array(
								'title' => esc_html__('Icon','bizcor'),
								'type'  =>'icon',
								'required' => array('icon_type','=','icon'),
							),
							'image'  => array(
								'title' => esc_html__('Image','bizcor'),
								'type'  =>'media',
								'required' => array('icon_type','=','image'),
							),
							'title' => array(
								'title' => esc_html__('Title','bizcor'),
								'type'  =>'textarea',
								'desc'  => '',
							),
							'desc' => array(
								'title' => esc_html__('Description','bizcor'),
								'type'  =>'textarea',
								'desc'  => '',
							),
							'button1_label' => array(
								'title' => esc_html__('Button1 Label','bizcor'),
								'type'  =>'text',
								'desc'  => '',
							),
							'button1_link' => array(
								'title' => esc_html__('Button1 Link','bizcor'),
								'type'  =>'text',
								'desc'  => '',
							),
							'button1_target' => array(
								'title' => esc_html__('Button1 open in new tab?','bizcor'),
								'type'  =>'checkbox',
								'desc'  => '',
							),
							'button2_label' => array(
								'title' => esc_html__('Button2 Label','bizcor'),
								'type'  =>'text',
								'desc'  => '',
							),
							'button2_link' => array(
								'title' => esc_html__('Button2 Link','bizcor'),
								'type'  =>'text',
								'desc'  => '',
							),
							'button2_target' => array(
								'title' => esc_html__('Button2 open in new tab?','bizcor'),
								'type'  =>'checkbox',
								'desc'  => '',
							),
						),
					)
				)
			);

			// bizcor_slider_opacity
			$wp_customize->add_setting('bizcor_slider_opacity',
					array(
						'sanitize_callback' => 'bizcor_sanitize_range_value',
						'default'           => $bizcor_options['bizcor_slider_opacity'],
						'priority'          => 3,
						'transport'         => 'postMessage',
					)
				);
			$wp_customize->add_control(new Bizcor_Range_Control($wp_customize,'bizcor_slider_opacity',
				array(
					'label' 		=> esc_html__('Overlay Opacity', 'bizcor'),
					'section' 		=> 'slider_section',
					'type'          => 'range-value',
					'media_query'   => false,
                    'input_attr' => array(
                        'mobile' => array(
                            'min' => 0.1,
                            'max' => 1,
                            'step' => 0.1,
                            'default_value' => $bizcor_options['bizcor_slider_opacity'],
                        ),
                        'tablet' => array(
                            'min' => 0.1,
                            'max' => 1,
                            'step' => 0.1,
                            'default_value' => $bizcor_options['bizcor_slider_opacity'],
                        ),
                        'desktop' => array(
                            'min' => 0.1,
                            'max' => 1,
                            'step' => 0.1,
                            'default_value' => $bizcor_options['bizcor_slider_opacity'],
                        ),
                    ),
				)
			) );
}
add_action('customize_register','bizcor_customizer_slider_section');