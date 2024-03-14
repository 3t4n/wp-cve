<?php
function bizcor_customizer_testimonial_section( $wp_customize ){

	global $bizcor_options;

		// Homepage Testimonial
		$wp_customize->add_section( 'testimonial_section',
			array(
				'priority'    => 8,
				'title'       => esc_html__('Section Testimonial','bizcor'),
				'panel'       => 'bizcor_frontpage',
			)
		);

			// bizcor_testimonial_disable
			$wp_customize->add_setting('bizcor_testimonial_disable',
					array(
						'sanitize_callback' => 'bizcor_sanitize_checkbox',
						'default'           => $bizcor_options['bizcor_testimonial_disable'],
						'priority'          => 1,
					)
				);
			$wp_customize->add_control('bizcor_testimonial_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'bizcor'),
					'section'     => 'testimonial_section',
				)
			);

			// bizcor_testimonial_subtitle
			$wp_customize->add_setting('bizcor_testimonial_subtitle',
					array(
						'sanitize_callback' => 'wp_kses_post',
						'default'           => $bizcor_options['bizcor_testimonial_subtitle'],
						'priority'          => 2,
					)
				);
			$wp_customize->add_control('bizcor_testimonial_subtitle',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Subtitle', 'bizcor'),
					'section'     => 'testimonial_section',
				)
			);

			// bizcor_testimonial_title
			$wp_customize->add_setting('bizcor_testimonial_title',
					array(
						'sanitize_callback' => 'wp_kses_post',
						'default'           => $bizcor_options['bizcor_testimonial_title'],
						'priority'          => 3,
					)
				);
			$wp_customize->add_control('bizcor_testimonial_title',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Title', 'bizcor'),
					'section'     => 'testimonial_section',
				)
			);

			// bizcor_testimonial_desc
			$wp_customize->add_setting('bizcor_testimonial_desc',
					array(
						'sanitize_callback' => 'wp_kses_post',
						'default'           => $bizcor_options['bizcor_testimonial_desc'],
						'priority'          => 4,
					)
				);
			$wp_customize->add_control('bizcor_testimonial_desc',
				array(
					'type'        => 'textarea',
					'label'       => esc_html__('Description', 'bizcor'),
					'section'     => 'testimonial_section',
				)
			);

			// bizcor_testimonial_content
			$wp_customize->add_setting('bizcor_testimonial_content',array(
					'sanitize_callback' => 'bizcor_sanitize_repeatable_data_field',
					'transport'         => 'refresh', // refresh or postMessage
					'priority'          => 5,
					'default'           => $bizcor_options['bizcor_testimonial_content'],
				) );

			$wp_customize->add_control(new Bizcor_Repeatable_Control($wp_customize,'bizcor_testimonial_content',
					array(
						'label'         => esc_html__('Testimonial Content','bizcor'),
						'section'       => 'testimonial_section',
						'live_title_id' => 'title', // apply for unput text and textarea only
						'title_format'  => esc_html__( '[live_title]','bizcor'), // [live_title]
						'max_item'      => 2,
						'limited_msg' 	=> bizcor_upgrade_pro_msg(),
						'fields'    => array(
							'image'  => array(
								'title' => esc_html__('Reviewer Photo','bizcor'),
								'type'  =>'media',
								//'required' => array('icon_type','=','image'),
							),
							'title' => array(
								'title' => esc_html__('Reviewer Name','bizcor'),
								'type'  =>'text',
								'desc'  => '',
							),
							'designation' => array(
								'title' => esc_html__('Reviewer Designation','bizcor'),
								'type'  =>'text',
								'desc'  => '',
							),
							'desc' => array(
								'title' => esc_html__('Review','bizcor'),
								'type'  =>'textarea',
								'desc'  => '',
							),
							'rating' => array(
								'title' => esc_html__('Rating','bizcor'),
								'type'  =>'select',
								'options'  => array(
									1 => 1,
									2 => 2,
									3 => 3,
									4 => 4,
									5 => 5
								),
								'desc'  => '',
							),							
						),
					)
				)
			);

}
add_action('customize_register','bizcor_customizer_testimonial_section');