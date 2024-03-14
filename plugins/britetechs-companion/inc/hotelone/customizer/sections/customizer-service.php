<?php
function hotelone_customizer_service( $wp_customize ){
		global $hotelone_options_default;

		$wp_customize->add_section( 'hotelone_service_section' ,
			array(
				'priority'    => 2,
				'title'       => esc_html__( 'Section: Service', 'hotelone' ),
				'panel'       => 'frontpage_panel',
			)
		);
		
			$wp_customize->add_setting( 'hotelone_services_hide',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => $hotelone_options_default['hotelone_services_hide'],
					'priority'    => 1,
				)
			);
			$wp_customize->add_control( 'hotelone_services_hide',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'hotelone'),
					'section'     => 'hotelone_service_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_services_title',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $hotelone_options_default['hotelone_services_title'],
					'priority'    => 2,
				)
			);
			$wp_customize->add_control( 'hotelone_services_title',
				array(
					'label'     => esc_html__('Section Title', 'hotelone'),
					'section' 		=> 'hotelone_service_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_services_subtitle',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $hotelone_options_default['hotelone_services_subtitle'],
					'priority'    => 3,
				)
			);
			$wp_customize->add_control( 'hotelone_services_subtitle',
				array(
					'label'     => esc_html__('Section Subtitle', 'hotelone'),
					'section' 		=> 'hotelone_service_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_service_layout',
				array(
					'sanitize_callback' => 'hotelone_sanitize_select',
					'default'           => $hotelone_options_default['hotelone_service_layout'],
					'priority'    => 4,
				)
			);

			$wp_customize->add_control( 'hotelone_service_layout',
				array(
					'label' 		=> esc_html__('Services Layout Settings', 'hotelone'),
					'section' 		=> 'hotelone_service_section',
					'type'          => 'select',
					'choices'       => array(
						'3' => esc_html__( '4 Columns', 'hotelone' ),
						'4' => esc_html__( '3 Columns', 'hotelone' ),
						'6' => esc_html__( '2 Columns', 'hotelone' ),
						'12' => esc_html__( '1 Column', 'hotelone' ),
					),
				)
			);

			// title color
			$wp_customize->add_setting( 'service_title_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => $hotelone_options_default['service_title_color'],
		        'transport' => 'postMessage',
		        'priority'    => 5,
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'service_title_color',
		        array(
		            'label'       => esc_html__( 'Title Color', 'hotelone' ),
		            'section'     => 'hotelone_service_section',
		        )
		    ));

		    // subtitle color
			$wp_customize->add_setting( 'service_subtitle_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => $hotelone_options_default['service_subtitle_color'],
		        'transport' => 'postMessage',
		        'priority'    => 6,
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'service_subtitle_color',
		        array(
		            'label'       => esc_html__( 'Subtitle Color', 'hotelone' ),
		            'section'     => 'hotelone_service_section',
		        )
		    ));
		
			$wp_customize->add_setting('hotelone_services',
				array(
					'sanitize_callback' => 'hotelone_sanitize_repeatable_data_field',
					'transport' => 'refresh', // refresh or postMessage
					'default' => $hotelone_options_default['hotelone_services'],
					'priority'    => 7,
			) );
			$wp_customize->add_control(new HotelOne_Customize_Repeatable_Control($wp_customize,'hotelone_services',
					array(
						'label'     	=> esc_html__('Service content', 'hotelone'),
						'section'       => 'hotelone_service_section',
						'live_title_id' => 'content_page', // apply for unput text and textarea only
						'title_format'  => esc_html__('[live_title]', 'hotelone'), // [live_title]
						'max_item'      => 100,
						'limited_msg' 	=> wp_kses_post( __('Upgrade to <a target="_blank" href="https://www.britetechs.com/free-hotelone-wordpress-theme/">Hotelone Pro</a> to be able to add more items and unlock other premium features!', 'hotelone' ) ),
						'fields'    => array(
							'icon_type'  => array(
								'title' => esc_html__('Custom icon', 'hotelone'),
								'type'  =>'select',
								'options' => array(
									'icon' => esc_html__('Icon', 'hotelone'),
									'image' => esc_html__('image', 'hotelone'),
								),
							),
							'icon'  => array(
								'title' => esc_html__('Icon', 'hotelone'),
								'type'  =>'icon',
								'required' => array( 'icon_type', '=', 'icon' ),
							),
							'image'  => array(
								'title' => esc_html__('Image', 'hotelone'),
								'type'  =>'media',
								'required' => array( 'icon_type', '=', 'image' ),
							),
							'title'  => array(
								'title' => esc_html__('Title', 'hotelone'),
								'type'  =>'text',
							),
							'desc'  => array(
								'title' => esc_html__('Description', 'hotelone'),
								'type'  =>'textarea',
							),
							'button_text'  => array(
								'title' => esc_html__('Button Text', 'hotelone'),
								'type'  =>'text',
							),
							'button_url'  => array(
								'title' => esc_html__('Button URL', 'hotelone'),
								'type'  =>'text',
							),
							'target'  => array(
								'title' => esc_html__('Open in new tab', 'hotelone'),
								'type'  =>'checkbox',
							),

						),

					)
				)
			);
			
			$wp_customize->add_setting( 'hotelone_service_icon_size',
				array(
					'sanitize_callback' => 'hotelone_sanitize_select',
					'default'           => $hotelone_options_default['hotelone_service_icon_size'],
					'priority'    => 8,
				)
			);

			$wp_customize->add_control( 'hotelone_service_icon_size',
				array(
					'label' 		=> esc_html__('Icon Size', 'hotelone'),
					'section' 		=> 'hotelone_service_section',
					'type'          => 'select',
					'choices'       => array(
						'5x' => esc_html__( '5x', 'hotelone' ),
						'4x' => esc_html__( '4x', 'hotelone' ),
						'3x' => esc_html__( '3x', 'hotelone' ),
						'2x' => esc_html__( '2x', 'hotelone' ),
						'1x' => esc_html__( '1x', 'hotelone' ),
					),
				)
			);
			
			$wp_customize->add_setting( 'hotelone_services_mbtn_text',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => $hotelone_options_default['hotelone_services_mbtn_text'],
					'priority'    => 9,
				)
			);
			$wp_customize->add_control( 'hotelone_services_mbtn_text',
				array(
					'label'     => esc_html__('Services More Button Text', 'hotelone'),
					'section' 		=> 'hotelone_service_section',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_services_mbtn_url',
				array(
					'sanitize_callback' => 'esc_url_raw',
					'default'           => $hotelone_options_default['hotelone_services_mbtn_url'],
					'priority'    => 10,
				)
			);
			$wp_customize->add_control( 'hotelone_services_mbtn_url',
				array(
					'label'     => esc_html__('Services More Button URL', 'hotelone'),
					'section' 		=> 'hotelone_service_section',
				)
			);
}
add_action('customize_register','hotelone_customizer_service' );