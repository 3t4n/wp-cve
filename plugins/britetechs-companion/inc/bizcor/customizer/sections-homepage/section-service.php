<?php
function bizcor_customizer_service_section( $wp_customize ){

	global $bizcor_options;

		// Homepage Service
		$wp_customize->add_section( 'service_section',
			array(
				'priority'    => 3,
				'title'       => esc_html__('Section Service','bizcor'),
				'panel'       => 'bizcor_frontpage',
			)
		);

			// bizcor_service_disable
			$wp_customize->add_setting('bizcor_service_disable',
					array(
						'sanitize_callback' => 'bizcor_sanitize_checkbox',
						'default'           => $bizcor_options['bizcor_service_disable'],
						'priority'          => 1,
					)
				);
			$wp_customize->add_control('bizcor_service_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'bizcor'),
					'section'     => 'service_section',
				)
			);

			// bizcor_service_subtitle
			$wp_customize->add_setting('bizcor_service_subtitle',
					array(
						'sanitize_callback' => 'wp_kses_post',
						'default'           => $bizcor_options['bizcor_service_subtitle'],
						'priority'          => 2,
					)
				);
			$wp_customize->add_control('bizcor_service_subtitle',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Subtitle', 'bizcor'),
					'section'     => 'service_section',
				)
			);

			// bizcor_service_title
			$wp_customize->add_setting('bizcor_service_title',
					array(
						'sanitize_callback' => 'wp_kses_post',
						'default'           => $bizcor_options['bizcor_service_title'],
						'priority'          => 3,
					)
				);
			$wp_customize->add_control('bizcor_service_title',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Title', 'bizcor'),
					'section'     => 'service_section',
				)
			);

			// bizcor_service_desc
			$wp_customize->add_setting('bizcor_service_desc',
					array(
						'sanitize_callback' => 'wp_kses_post',
						'default'           => $bizcor_options['bizcor_service_desc'],
						'priority'          => 4,
					)
				);
			$wp_customize->add_control('bizcor_service_desc',
				array(
					'type'        => 'textarea',
					'label'       => esc_html__('Description', 'bizcor'),
					'section'     => 'service_section',
				)
			);

			// bizcor_service_content
			$wp_customize->add_setting('bizcor_service_content',array(
					'sanitize_callback' => 'bizcor_sanitize_repeatable_data_field',
					'transport'         => 'refresh', // refresh or postMessage
					'priority'          => 5,
					'default'           => $bizcor_options['bizcor_service_content'],
				) );

			$wp_customize->add_control(new Bizcor_Repeatable_Control($wp_customize,'bizcor_service_content',
					array(
						'label'         => esc_html__('Service Content','bizcor'),
						'section'       => 'service_section',
						'live_title_id' => 'title', // apply for unput text and textarea only
						'title_format'  => esc_html__( '[live_title]','bizcor'), // [live_title]
						'max_item'      => 3,
						'limited_msg' 	=> bizcor_upgrade_pro_msg(),
						'fields'    => array(
							// 'icon_type'  => array(
							// 	'title' => esc_html__('Custom icon','bizcor'),
							// 	'type'  =>'select',
							// 	'options' => array(
							// 		'icon' => esc_html__('Icon', 'bizcor'),
							// 		'image' => esc_html__('image','bizcor'),
							// 	),
							// ),
							'icon'  => array(
								'title' => esc_html__('Icon','bizcor'),
								'type'  =>'icon',
								//'required' => array('icon_type','=','icon'),
							),
							'image'  => array(
								'title' => esc_html__('Image','bizcor'),
								'type'  =>'media',
								//'required' => array('icon_type','=','image'),
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
						),
					)
				)
			);
}
add_action('customize_register','bizcor_customizer_service_section');