<?php
function bc_customizer_team( $wp_customize ){
		global $hotelone_options_default;

		$wp_customize->add_section( 'hotelone_team_section' ,
			array(
				'title'       => esc_html__( 'Section: Team', 'britetechs-companion' ),
				'description' => '',
				'panel'       => 'frontpage_panel',
				'priority'    => 4,
			)
		);
		
			$wp_customize->add_setting( 'hotelone_team_hide',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => $hotelone_options_default['hotelone_team_hide'],
					'priority'    => 1,
				)
			);
			$wp_customize->add_control( 'hotelone_team_hide',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'britetechs-companion'),
					'section'     => 'hotelone_team_section',
					'description' => esc_html__('Check this box to hide this section.', 'britetechs-companion'),
				)
			);
			
			$wp_customize->add_setting( 'hotelone_team_title',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => $hotelone_options_default['hotelone_team_title'],
					'priority'    => 2,
				)
			);
			$wp_customize->add_control( 'hotelone_team_title',
				array(
					'label'    		=> esc_html__('Section Title', 'britetechs-companion'),
					'section' 		=> 'hotelone_team_section',
					'description'   => '',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_team_subtitle',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => $hotelone_options_default['hotelone_team_subtitle'],
					'priority'    => 3,
				)
			);
			$wp_customize->add_control( 'hotelone_team_subtitle',
				array(
					'label'     => esc_html__('Section Subtitle', 'britetechs-companion'),
					'section' 		=> 'hotelone_team_section',
					'description'   => '',
				)
			);

			// title color
			$wp_customize->add_setting( 'team_title_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => $hotelone_options_default['team_title_color'],
		        'transport' => 'postMessage',
		        'priority'    => 4,
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'team_title_color',
		        array(
		            'label'       => esc_html__( 'Title Color', 'hotelone' ),
		            'section'     => 'hotelone_team_section',
		        )
		    ));

		    // subtitle color
			$wp_customize->add_setting( 'team_subtitle_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => $hotelone_options_default['team_subtitle_color'],
		        'transport' => 'postMessage',
		        'priority'    => 5,
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'team_subtitle_color',
		        array(
		            'label'       => esc_html__( 'Subtitle Color', 'hotelone' ),
		            'section'     => 'hotelone_team_section',
		        )
		    ));
			
			$wp_customize->add_setting( 'hotelone_team_layout',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => $hotelone_options_default['hotelone_team_layout'],
					'priority'    => 6,
				)
			);
			$wp_customize->add_control( 'hotelone_team_layout',
				array(
					'label' 		=> esc_html__('Team Layout Settings', 'britetechs-companion'),
					'section' 		=> 'hotelone_team_section',
					'description'   => '',
					'type'          => 'select',
					'choices'       => array(
						'3' => esc_html__( '4 Columns', 'britetechs-companion' ),
						'4' => esc_html__( '3 Columns', 'britetechs-companion' ),
						'6' => esc_html__( '2 Columns', 'britetechs-companion' ),
					),
				)
			);
		
			$wp_customize->add_setting('hotelone_team_members',
			array(
				'sanitize_callback' => 'hotelone_sanitize_repeatable_data_field',
				'transport' => 'refresh', // refresh or postMessage
				'default'           => $hotelone_options_default['hotelone_team_members'],
				'priority'    => 7,
			) );
			$wp_customize->add_control(new HotelOne_Customize_Repeatable_Control($wp_customize,'hotelone_team_members',
					array(
						'label'     => esc_html__('Team members', 'britetechs-companion'),
						'description'   => '',
						'section'       => 'hotelone_team_section',
						'live_title_id' => 'name', // apply for unput text and textarea only
						'title_format'  => esc_html__( '[live_title]', 'britetechs-companion'), // [live_title]
						'max_item'      => 3,
						'limited_msg' 	=> wp_kses_post( __( 'Upgrade to <a target="_blank" href="https://www.britetechs.com/free-hotelone-wordpress-theme/">Hotelone Pro</a> to be able to add more items and unlock other premium features!', 'britetechs-companion' ) ),
						'fields'    => array(
							'image' => array(
								'title' => esc_html__('User media', 'britetechs-companion'),
								'type'  =>'media',
								'desc'  => '',
							),
							'name' => array(
								'title' => esc_html__('Name', 'britetechs-companion'),
								'type'  =>'text',
								'desc'  => '',
							),
							'designation' => array(
								'title' => esc_html__('Designation', 'britetechs-companion'),
								'type'  =>'text',
								'desc'  => '',
							),
							'facebook_hide' => array(
								'title' => esc_html__('Hide Facebook Icon', 'hotelone'),
								'type'  =>'checkbox',
								'desc'  => '',
							),
							'facebook' => array(
								'title' => esc_html__('Facebook URL', 'hotelone'),
								'type'  =>'text',
								'desc'  => '',
							),
							'twitter_hide' => array(
								'title' => esc_html__('Hide Twitter Icon', 'hotelone'),
								'type'  =>'checkbox',
								'desc'  => '',
							),
							'twitter' => array(
								'title' => esc_html__('Twitter URL', 'hotelone'),
								'type'  =>'text',
								'desc'  => '',
							),
							'link' => array(
								'title' => esc_html__('Custom Link', 'britetechs-companion'),
								'type'  =>'text',
								'desc'  => '',
							),
						),

					)
				)
			);
}
add_action('customize_register','bc_customizer_team' );