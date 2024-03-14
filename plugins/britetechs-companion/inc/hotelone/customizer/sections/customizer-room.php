<?php
function hotelone_customizer_room( $wp_customize ){
	
		global $hotelone_options_default;
	
		$wp_customize->add_section( 'hotelone_room_section' ,
			array(
				'priority'    => 3,
				'title'       => esc_html__( 'Section: Room', 'hotelone' ),
				'panel'       => 'frontpage_panel',
			)
		);
		
			$wp_customize->add_setting( 'hotelone_room_hide',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => $hotelone_options_default['hotelone_room_hide'],
					'priority'    => 1,
				)
			);
			$wp_customize->add_control( 'hotelone_room_hide',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'hotelone'),
					'section'     => 'hotelone_room_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_room_title',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $hotelone_options_default['hotelone_room_title'],
					'priority'    => 2,
				)
			);
			$wp_customize->add_control( 'hotelone_room_title',
				array(
					'label'     => esc_html__('Section Title', 'hotelone'),
					'section' 		=> 'hotelone_room_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_room_subtitle',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $hotelone_options_default['hotelone_room_subtitle'],
					'priority'    => 3,
				)
			);
			$wp_customize->add_control( 'hotelone_room_subtitle',
				array(
					'label'     => esc_html__('Section Subtitle', 'hotelone'),
					'section' 		=> 'hotelone_room_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_room_layout',
				array(
					'sanitize_callback' => 'hotelone_sanitize_select',
					'default'           => $hotelone_options_default['hotelone_room_layout'],
					'priority'    => 4,
				)
			);

			$wp_customize->add_control( 'hotelone_room_layout',
				array(
					'label' 		=> esc_html__('Room Layout Settings', 'hotelone'),
					'section' 		=> 'hotelone_room_section',
					'type'          => 'select',
					'choices'       => array(
						'4' => esc_html__( '3 Columns', 'hotelone' ),
						'6' => esc_html__( '2 Columns', 'hotelone' ),
						'12' => esc_html__( '1 Column', 'hotelone' ),
					),
				)
			);

			// title color
			$wp_customize->add_setting( 'room_title_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => $hotelone_options_default['room_title_color'],
		        'transport' => 'postMessage',
		        'priority'    => 5,
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'room_title_color',
		        array(
		            'label'       => esc_html__( 'Title Color', 'hotelone' ),
		            'section'     => 'hotelone_room_section',
		        )
		    ));

		    // subtitle color
			$wp_customize->add_setting( 'room_subtitle_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => $hotelone_options_default['room_subtitle_color'],
		        'transport' => 'postMessage',
		        'priority'    => 6,
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'room_subtitle_color',
		        array(
		            'label'       => esc_html__( 'Subtitle Color', 'hotelone' ),
		            'section'     => 'hotelone_room_section',
		        )
		    ));
		
			$wp_customize->add_setting('hotelone_room',
			array(
				'sanitize_callback' => 'hotelone_sanitize_repeatable_data_field',
				'transport' => 'refresh', // refresh or postMessage
				'default' => $hotelone_options_default['hotelone_room'],
				'priority'    => 7,
			) );
			$wp_customize->add_control(new HotelOne_Customize_Repeatable_Control($wp_customize,'hotelone_room',
					array(
						'label'     	=> esc_html__('Hotel Rooms', 'hotelone'),
						'section'       => 'hotelone_room_section',
						'live_title_id' => 'content_page', // apply for unput text and textarea only
						'title_format'  => esc_html__('[live_title]', 'hotelone'), // [live_title]
						'max_item'      => 3,
						'limited_msg' 	=> wp_kses_post( __('Upgrade to <a target="_blank" href="https://www.britetechs.com/free-hotelone-wordpress-theme/">Hotelone Pro</a> to be able to add more items and unlock other premium features!', 'hotelone' ) ),
						'fields'    => array(
							'icon_type'  => array(
								'title' => esc_html__('Custom icon', 'hotelone'),
								'type'  =>'select',
								'options' => array(
									'image' => esc_html__('image', 'hotelone'),
								),
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
							'rating'  => array(
								'title' => esc_html__('Rating', 'hotelone'),
								'type'  =>'select',
								'options' => array(
									'' => __('Rating','hotelone'),
									1 => 1,
									2 => 2,
									3 => 3,
									4 => 4,
									5 => 5,
								)
							),
							'person'  => array(
								'title' => esc_html__('Persons', 'hotelone'),
								'type'  =>'select',
								'options' => array(
									'' => __('Person','hotelone'),
									1 => 1,
									2 => 2,
									3 => 3,
									4 => 4,
								)
							),
							'price'  => array(
								'title' => esc_html__('Price', 'hotelone'),
								'type'  =>'text',
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
		
}
add_action('customize_register','hotelone_customizer_room' );