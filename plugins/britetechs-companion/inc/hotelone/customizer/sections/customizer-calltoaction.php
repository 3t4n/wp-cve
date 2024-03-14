<?php
function hotelone_customizer_calltoaction( $wp_customize ){
		global $hotelone_options_default;
		
		$wp_customize->add_section( 'hotelone_calltoaction_section' ,
			array(
				'priority'    => 3,
				'title'       => esc_html__( 'Section: Call To Action', 'hotelone' ),
				'panel'       => 'frontpage_panel',
			)
		);
		
			$wp_customize->add_setting( 'hotelone_calltoaction_hide',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => $hotelone_options_default['hotelone_calltoaction_hide'],
				)
			);
			$wp_customize->add_control( 'hotelone_calltoaction_hide',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'hotelone'),
					'section'     => 'hotelone_calltoaction_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_calltoaction_title',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $hotelone_options_default['hotelone_calltoaction_title'],
				)
			);
			$wp_customize->add_control( 'hotelone_calltoaction_title',
				array(
					'label'    		=> esc_html__('Section Title', 'hotelone'),
					'section' 		=> 'hotelone_calltoaction_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_calltoaction_subtitle',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $hotelone_options_default['hotelone_calltoaction_subtitle'],
				)
			);
			$wp_customize->add_control( 'hotelone_calltoaction_subtitle',
				array(
					'label'     => esc_html__('Section Subtitle', 'hotelone'),
					'section' 		=> 'hotelone_calltoaction_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_calltoaction_btn_text',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => $hotelone_options_default['hotelone_calltoaction_btn_text'],
				)
			);
			$wp_customize->add_control( 'hotelone_calltoaction_btn_text',
				array(
					'label'     => esc_html__('Call To Action Button Text', 'hotelone'),
					'section' 		=> 'hotelone_calltoaction_section',
				)
			);
			
			$wp_customize->add_setting( 'hotelone_calltoaction_btn_URL',
				array(
					'sanitize_callback' => 'esc_url',
					'default'           => $hotelone_options_default['hotelone_calltoaction_btn_URL'],
				)
			);
			$wp_customize->add_control( 'hotelone_calltoaction_btn_URL',
				array(
					'label'     => esc_html__('Call To Action Button URL', 'hotelone'),
					'section' 		=> 'hotelone_calltoaction_section',
				)
			);

			// title color
			$wp_customize->add_setting( 'calltoaction_title_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => $hotelone_options_default['calltoaction_title_color'],
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'calltoaction_title_color',
		        array(
		            'label'       => esc_html__( 'Title Color', 'hotelone' ),
		            'section'     => 'hotelone_calltoaction_section',
		        )
		    ));

		    // subtitle color
			$wp_customize->add_setting( 'calltoaction_subtitle_color', array(
		        'sanitize_callback' => 'sanitize_hex_color',
		        'default' => $hotelone_options_default['calltoaction_subtitle_color'],
		        'transport' => 'postMessage',
		    ) );
		    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'calltoaction_subtitle_color',
		        array(
		            'label'       => esc_html__( 'Subtitle Color', 'hotelone' ),
		            'section'     => 'hotelone_calltoaction_section',
		        )
		    ));
			
			$wp_customize->add_setting( 'hotelone_calltoaction_bgcolor', array(
                'sanitize_callback' => 'sanitize_hex_color',
                'default' => $hotelone_options_default['hotelone_calltoaction_bgcolor'],
                'transport' => 'postMessage',
            ) );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
			'hotelone_calltoaction_bgcolor',
                array(
                    'label'       => esc_html__( 'Background Color', 'hotelone' ),
                    'section'     => 'hotelone_calltoaction_section',
                )
            ));

			$wp_customize->add_setting( 'hotelone_calltoaction_bgimage',
				array(
					'sanitize_callback' => 'esc_url_raw',
					'default'           => $hotelone_options_default['hotelone_calltoaction_bgimage'],
				)
			);
			$wp_customize->add_control( new WP_Customize_Image_Control(
				$wp_customize,
				'hotelone_calltoaction_bgimage',
				array(
					'label' 		=> esc_html__('Background image', 'hotelone'),
					'section' 		=> 'hotelone_calltoaction_section',
				)
			));
}
add_action('customize_register','hotelone_customizer_calltoaction' );