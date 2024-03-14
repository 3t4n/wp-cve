<?php 
function carpress_section_slider( $wp_customize ){
	$option = carpress_default_data();

	$wp_customize->add_panel( 'slider_panel', array(
		'priority'       => 35,
		'capability'     => 'edit_theme_options',
		'title'      => __('Section : Slider', 'carpress'),
	) );	

		$wp_customize->add_section( 'slider_setting' , array(
			'title'      => __('Slider Settings', 'carpress'),
			'panel'  => 'slider_panel',
		) );	

			$wp_customize->add_setting( 'carpress_options[slider_enable]' , array(
			'default'    => $option['slider_enable'],
			'sanitize_callback' => 'carpress_sanitize_checkbox',
			'type'=>'option'
			));
			$wp_customize->add_control('carpress_options[slider_enable]' , array(
			'label' => __('Slider Section Enable','carpress' ),
			'description' => __('Check this setting to enable slider section on the FrontPage.','carpress' ),
			'section' => 'slider_setting',
			'type'=>'checkbox',
			) );
			
			$wp_customize->add_setting( 'carpress_options[slider_effect]' , array(
			'default'    => $option['slider_effect'],
			'sanitize_callback' => 'sanitize_text_field',
			'type'=>'option'
			));
			$wp_customize->add_control('carpress_options[slider_effect]' , array(
			'label' => __('Slider Effect','carpress' ),
			'description' => __('Select choose slide effect from this setting.','carpress' ),
			'section' => 'slider_setting',
			'type'=>'select',
			'choices' => array(
				'slide' => __('Slide','carpress'),
				'fade' => __('Fade','carpress'),
			)
			) );
			
			$wp_customize->add_setting( 'carpress_options[slider_speed]' , array(
			'default'    => $option['slider_speed'],
			'sanitize_callback' => 'sanitize_text_field',
			'type'=>'option'
			));
			$wp_customize->add_control('carpress_options[slider_speed]' , array(
			'label' => __('Slider animation speed','carpress' ),
			'description' => __('This animation speed refers to which is image fade in. Integers in milliseconds are accepted.','carpress' ),
			'section' => 'slider_setting',
			'type'=>'text', 
			) );
			
		$wp_customize->add_section( 'slider_media' , array(
			'title'      => __('Slider media images', 'carpress'),
			'panel'  => 'slider_panel',
		) );		
			$wp_customize->add_setting('carpress_options[slider_media]',
				array(
					'sanitize_callback' => 'carpress_sanitize_repeatable_data_field',
					'transport' => 'refresh',
					'type'=>'option',
					'default' => json_encode( array(
						array(
							'image'=> array(
								'url' => get_template_directory_uri().'/images/slide1.jpg',
								'id' => ''
							)
						)
					) )
				) );
			$wp_customize->add_control(
				new carpress_Customize_Repeatable_Control(
					$wp_customize,
					'carpress_options[slider_media]',
					array(
						'label'     => esc_html__('Background Images', 'carpress'),
						'description'   => '',
						'section'       => 'slider_media',
						'title_format'  => esc_html__( 'Background', 'carpress'),
						'max_item'      => 2,
						'fields'    => array(
							'image' => array(
								'title' => esc_html__('Background Image', 'carpress'),
								'type'  =>'media',
								'default' => array(
									'url' => get_template_directory_uri().'/images/slide1.jpg',
									'id' => ''
								)
							),
						),
					)
				)
			);			
		$wp_customize->add_section( 'slider_content' , array(
			'title'      => __('Slider content', 'carpress'),
			'panel'  => 'slider_panel',
		) );
			$wp_customize->add_setting( 'carpress_options[slider_largetext]',array(
					'sanitize_callback' => 'carpress_sanitize_text',
					'mod' 				=> 'html',
					'default'           => $option['slider_largetext'],
					'type'=>'option',
				)
			);
			$wp_customize->add_control( new carpress_Editor_Custom_Control(	$wp_customize,'carpress_options[slider_largetext]',
				array(
					'label' 		=> esc_html__('Large Text', 'carpress'),
					'section' 		=> 'slider_content',
					'description'   => esc_html__('Put your big text for slider section.', 'carpress'),
				)
			));
			
			$wp_customize->add_setting( 'carpress_options[slider_smalltext]',array(
					'sanitize_callback' => 'carpress_sanitize_text',
					'mod' 				=> 'html',
					'default'           => $option['slider_smalltext'],
					'type'=>'option',
				)
			);
			$wp_customize->add_control( new carpress_Editor_Custom_Control(	$wp_customize,'carpress_options[slider_smalltext]',
				array(
					'label' 		=> esc_html__('Small Text', 'carpress'),
					'section' 		=> 'slider_content',
					'description'   => esc_html__('Small text for slider section.', 'carpress'),
				)
			));
			
			$wp_customize->add_setting( 'carpress_options[slider_button_text]' , array(
			'default'    => $option['slider_button_text'],
			'sanitize_callback' => 'sanitize_text_field',
			'type'=>'option'
			));
			$wp_customize->add_control('carpress_options[slider_button_text]' , array(
			'label' => __('Button Text','carpress' ),
			'description' => __('This setting for change button text in front page slider section','carpress' ),
			'section' => 'slider_content',
			'type'=>'text', 
			) );

			$wp_customize->add_setting( 'carpress_options[slider_button_link]' , array(
			'default'    => $option['slider_button_link'],
			'sanitize_callback' => 'esc_url_raw',
			'type'=>'option'
			));
			$wp_customize->add_control('carpress_options[slider_button_link]' , array(
			'label' => __('Button Link','carpress' ),
			'description' => __('This setting for change button link in front page slider section','carpress' ),
			'section' => 'slider_content',
			'type'=>'text', 
			) );

			$wp_customize->add_setting( 'carpress_options[slider_button_target]' , array(
			'default'    => $option['slider_button_target'],
			'sanitize_callback' => 'carpress_sanitize_checkbox',
			'type'=>'option'
			));
			$wp_customize->add_control('carpress_options[slider_button_target]' , array(
			'label' => __('Open button in new window','carpress' ),
			'description' => __('This setting to open button link in new window.','carpress' ),
			'section' => 'slider_content',
			'type'=>'checkbox', 
			) );		
}
add_action( 'customize_register', 'carpress_section_slider' );