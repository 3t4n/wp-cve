<?php
function bc_customizer_testimonial( $wp_customize ){
		
		global $hotelone_options_default;

		$wp_customize->add_section( 'hotelone_testimonial_section' ,
			array(
				'title'       => esc_html__( 'Section: Testimonial', 'britetechs-companion' ),
				'panel'       => 'frontpage_panel',
				'priority'    => 3,
			)
		);
		
			$wp_customize->add_setting( 'hotelone_testimonial_hide',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => $hotelone_options_default['hotelone_testimonial_hide'],
					'priority'    => 1,
				)
			);
			$wp_customize->add_control( 'hotelone_testimonial_hide',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'britetechs-companion'),
					'section'     => 'hotelone_testimonial_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_testimonial_title',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => $hotelone_options_default['hotelone_testimonial_title'],
					'priority'    => 2,
				)
			);
			$wp_customize->add_control( 'hotelone_testimonial_title',
				array(
					'label'    		=> esc_html__('Section Title', 'britetechs-companion'),
					'section' 		=> 'hotelone_testimonial_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_testimonial_subtitle',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => $hotelone_options_default['hotelone_testimonial_subtitle'],
					'priority'    => 3,
				)
			);
			$wp_customize->add_control( 'hotelone_testimonial_subtitle',
				array(
					'label'     => esc_html__('Section Subtitle', 'britetechs-companion'),
					'section' 		=> 'hotelone_testimonial_section',
				)
			);

			// title color
			$wp_customize->add_setting( 'testimonial_title_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => $hotelone_options_default['testimonial_title_color'],
		        'transport' => 'postMessage',
		        'priority'    => 4,
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'testimonial_title_color',
		        array(
		            'label'       => esc_html__( 'Title Color', 'hotelone' ),
		            'section'     => 'hotelone_testimonial_section',
		        )
		    ));

		    // subtitle color
			$wp_customize->add_setting( 'testimonial_subtitle_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => $hotelone_options_default['testimonial_subtitle_color'],
		        'transport' => 'postMessage',
		        'priority'    => 5,
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'testimonial_subtitle_color',
		        array(
		            'label'       => esc_html__( 'Subtitle Color', 'hotelone' ),
		            'section'     => 'hotelone_testimonial_section',
		        )
		    ));
		
			$wp_customize->add_setting('hotelone_testimonial_items',
			array(
				'sanitize_callback' => 'hotelone_sanitize_repeatable_data_field',
				'transport' => 'refresh', // refresh or postMessage
				'default' => $hotelone_options_default['hotelone_testimonial_items'],
				'priority'    => 6,
			) );
			$wp_customize->add_control(
				new HotelOne_Customize_Repeatable_Control(
					$wp_customize,
					'hotelone_testimonial_items',
					array(
						'label'     => esc_html__('Testimonial', 'britetechs-companion'),
						'description'   => '',
						'section'       => 'hotelone_testimonial_section',
						'live_title_id' => 'name', // apply for unput text and textarea only
						'title_format'  => esc_html__( '[live_title]', 'britetechs-companion'), // [live_title]
						'max_item'      => 2,
						'limited_msg' 	=> wp_kses_post( __( 'Upgrade to <a target="_blank" href="https://www.britetechs.com/free-hotelone-wordpress-theme/">Hotelone Pro</a> to be able to add more items and unlock other premium features!', 'britetechs-companion' ) ),
						'fields'    => array(
							'photo' => array(
								'title' => esc_html__('Client Photo', 'britetechs-companion'),
								'type'  =>'media',
								'desc'  => '',
							),
							'name' => array(
								'title' => esc_html__('Client Name', 'britetechs-companion'),
								'type'  =>'text',
								'desc'  => '',
							),
							'review' => array(
								'title' => esc_html__('Review Content', 'britetechs-companion'),
								'type'  =>'textarea',
								'desc'  => '',
							),
							'designation' => array(
								'title' => esc_html__('Designation', 'britetechs-companion'),
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
		
			$wp_customize->add_setting( 'hotelone_testimonial_bgcolor', array(
                'sanitize_callback' => 'sanitize_text_field',
                'default' => $hotelone_options_default['hotelone_testimonial_bgcolor'],
                'transport' => 'postMessage',
                'priority'    => 7,
            ) );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'hotelone_testimonial_bgcolor',
                array(
                    'label'       => esc_html__( 'Background Color', 'britetechs-companion' ),
                    'section'     => 'hotelone_testimonial_section',
                )
            ));
            
			$wp_customize->add_setting( 'hotelone_testimonial_bgimage',
				array(
					'sanitize_callback' => 'esc_url_raw',
					'default'           => $hotelone_options_default['hotelone_testimonial_bgimage'],
					'priority'    => 8,
				)
			);
			$wp_customize->add_control( new WP_Customize_Image_Control(
				$wp_customize,
				'hotelone_testimonial_bgimage',
				array(
					'label' 		=> esc_html__('Background image', 'britetechs-companion'),
					'section' 		=> 'hotelone_testimonial_section',
				)
			));
}
add_action('customize_register','bc_customizer_testimonial' );