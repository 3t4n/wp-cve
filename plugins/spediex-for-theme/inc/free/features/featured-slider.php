<?php

function featured_slider_setting( $wp_customize ) {
	global $default_setting;
	$sections = array();
	$sliders = apply_filters('custom_section', $sections);
	//Featured Slider Section
		$wp_customize->add_section( 'featured_slider_section' , array(
			'title'  => 'Featured Slider',
			'panel'  => 'theme_option_panel',
		) ); 
		//Featured Slider in tabing
			$wp_customize->add_setting( 'featuredimage_slider_tab', 
		        array(
		            'default'    => 'general', //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability'     => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitize_select',
		        ) 
		    ); 
	        $wp_customize->add_control( new Custom_Radio_Control( 
		        $wp_customize,'featuredimage_slider_tab',array(
		            'settings'   => 'featuredimage_slider_tab', 
		            'priority'   => 10,
		            'section'    => 'featured_slider_section',
		            'type'    => 'select',
		            'choices'    => array(
			        	'general' => 'General',
			        	'design' => 'Design',
		        	),
		        ) 
	        ) );
	    if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'featuredimage_slider_tab',
				array(
					'selector'        => '.featured_slider_image',
					'render_callback' => 'custom_customize_featuredimage_slider',
				)
			);
		}
		//Featured Slider in number of slides
		    $wp_customize->add_setting( 'featuredimage_slider_number', array(
		    	'default'  => 1,
			    'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',
		        'sanitize_callback' => 'custom_sanitize_number_range',
			) );
			$wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'featuredimage_slider_number',
		    	array(
					'type' => 'number',
					'settings'   => 'featuredimage_slider_number', 
					'section' => 'featured_slider_section', // // Add a default or your own section
					'label' => 'No of Slides',
					'description' => 'Save and refresh the page if No. of Slides is changed (Max no of slides is 1)',
					'input_attrs' => array(
								    'min' => 1,
								    'max' => 1,
								),
					'active_callback' => 'featured_generalcallback',					   
				)
			) );				
			$slider_number = get_theme_mod( 'featuredimage_slider_number', 1 );
				for ( $i = 1; $i <= $slider_number ; $i++ ) {	
					//Featured slider Heading
						$wp_customize->add_setting('featuredimage_slider'.$i, array(
					        'type'       => 'theme_mod',
					        'transport'   => 'refresh',
					        'capability'     => 'edit_theme_options',
					        'sanitize_callback' => 'sanitize_text_field',
					    ));
					    $wp_customize->add_control( new Custom_GeneratePress_Upsell_Section(
					    	$wp_customize,'featuredimage_slider'.$i,
					    	array(
						        'settings' => 'featuredimage_slider'.$i,
						        'label'   => 'Slider '.$i ,
						        'section' => 'featured_slider_section',
						        'type'     => 'ast-description',
						        'active_callback' => 'featured_generalcallback',
					        )
					    ));
					//Featured slider title 
						$wp_customize->add_setting( 'featuredimage_slider_title_' . $i , array(
							'default'    => $sliders['slider']['title'][$i-1],
						    'type'       => 'theme_mod',
					        'transport'   => 'refresh',
					        'capability'     => 'edit_theme_options',
					        'sanitize_callback' => 'sanitize_text_field',
						) );
						$wp_customize->add_control( new WP_Customize_Control(
					    	$wp_customize,'featuredimage_slider_title_' . $i,
					    	array(
								'type' => 'text',
								'settings'   => 'featuredimage_slider_title_' . $i, 
								'section' => 'featured_slider_section', // // Add a default or your own section
								'label' => 'Title ' . $i,	
								'active_callback' => 'featured_generalcallback',											   
							)
						) );
					//Featured slider description 
						$wp_customize->add_setting( 'featuredimage_slider_description_' . $i , array(
							'default'    => $sliders['slider']['description'][$i-1],
						    'type'       => 'theme_mod',
					        'transport'   => 'refresh',
					        'capability'     => 'edit_theme_options',
					        'sanitize_callback' => 'sanitize_textarea_field',
						) );
						$wp_customize->add_control( new WP_Customize_Control(
					    	$wp_customize,'featuredimage_slider_description_' . $i,
					    	array(
								'type' => 'textarea',
								'settings'   => 'featuredimage_slider_description_' . $i, 
								'section' => 'featured_slider_section', // // Add a default or your own section
								'label' => 'Description ' . $i, 
								'active_callback' => 'featured_generalcallback', 
							)
						) );
					//Featured slider image 
						$wp_customize->add_setting('featured_image_sliders_' . $i, array(
				        	'type'       => 'theme_mod',
					        'transport'     => 'refresh',
					        'height'        => 180,
					        'width'        => 160,
					        'capability' => 'edit_theme_options',
					        'sanitize_callback' => 'esc_url_raw'
					    ));
					    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'featured_image_sliders_' . $i, array(
					        'label' =>  'Image '. $i,
					        'section' => 'featured_slider_section',
					        'settings' => 'featured_image_sliders_' . $i,
					        'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
					        'active_callback' => 'featured_generalcallback',
					    )));
					//Featured slider add button
					    $wp_customize->add_setting( 'featuredimage_slider_button_' . $i , array(
					    	'default'    => $sliders['slider']['button'][$i-1],
						    'type'       => 'theme_mod',
					        'transport'   => 'refresh',
					        'capability'     => 'edit_theme_options',
					        'sanitize_callback' => 'sanitize_text_field',
						) );
						$wp_customize->add_control( new WP_Customize_Control(
					    	$wp_customize,'featuredimage_slider_button_' . $i,
					    	array(
								'type' => 'text',
								'settings'   => 'featuredimage_slider_button_' . $i, 
								'section' => 'featured_slider_section', // // Add a default or your own section
								'label' => 'Button Text ' . $i,	
								'active_callback' => 'featured_generalcallback',  
							)
						) );
					//Featured slider add button link
					    $wp_customize->add_setting( 'featuredimage_slider_button_link_' . $i , array(
					    	'default'    => $sliders['slider']['button_link'][$i-1],
						    'type'       => 'theme_mod',
					        'transport'   => 'refresh',
					        'capability'     => 'edit_theme_options',
					        'sanitize_callback' => 'sanitize_text_field',
						) );
						$wp_customize->add_control( new WP_Customize_Control(
					    	$wp_customize,'featuredimage_slider_button_link_' . $i,
					    	array(
								'type' => 'text',
								'settings'   => 'featuredimage_slider_button_link_' . $i, 
								'section' => 'featured_slider_section', // // Add a default or your own section
								'label' => 'Button Link ' . $i,	
								'active_callback' => 'featured_generalcallback',			   
							)
						) );
				}
			//Features slider in pro version
				$wp_customize->add_setting('featuredimage_slider_pro', array(
			        'type'       => 'theme_mod',
			        'transport'   => 'refresh',
			        'capability'     => 'edit_theme_options',
			        'sanitize_callback' => 'sanitize_text_field',
			    ));
			    $wp_customize->add_control( new pro_option_custom_control(
			    	$wp_customize,'featuredimage_slider_pro',
			    	array(
				        'settings' => 'featuredimage_slider_pro',
				        'section' => 'featured_slider_section',
				        'active_callback' => 'featured_generalcallback',
			        )
			    ));	
		//Featured Slider in add text color
		    $wp_customize->add_setting( 'featured_slider_text_color', 
		        array(
		            'default'    => $default_setting['featured_slider_text_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'featured_slider_text_color', 
		        array(
		            'label'      => 'Text Color' ,
		            'settings'   => 'featured_slider_text_color', 
		            'priority'   => 10,
		            'section'    => 'featured_slider_section',
		            'active_callback' => 'featured_designcallback',
		        ) 
	        ) );
	   	//Featured Slider arrow in add Text color
		    $wp_customize->add_setting( 'featured_slider_arrow_text_color', 
		        array(
		            'default'    => $default_setting['featured_slider_arrow_text_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'featured_slider_arrow_text_color', 
		        array(
		            'label'      => 'Arrow Text Color', 
		            'settings'   => 'featured_slider_arrow_text_color', 
		            'priority'   => 10,
		            'section'    => 'featured_slider_section',
		            'active_callback' => 'featured_designcallback',
		        ) 
	        ) );  	
	    //Featured Slider arrow in add background color
		    $wp_customize->add_setting( 'featured_slider_arrow_bg_color', 
		        array(
		            'default'    => $default_setting['featured_slider_arrow_bg_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'featured_slider_arrow_bg_color', 
		        array(
		            'label'      => 'Arrow Background Color', 
		            'settings'   => 'featured_slider_arrow_bg_color', 
		            'priority'   => 10,
		            'section'    => 'featured_slider_section',
		            'active_callback' => 'featured_designcallback',
		        ) 
	        ) );
	    //Featured Slider in arrow Text hover color
		    $wp_customize->add_setting( 'featured_slider_arrow_texthover_color', 
		        array(
		            'default'    => $default_setting['featured_slider_arrow_texthover_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'featured_slider_arrow_texthover_color', 
		        array(
		            'label'      => 'Arrow Text Hover Color', 
		            'settings'   => 'featured_slider_arrow_texthover_color', 
		            'priority'   => 10,
		            'section'    => 'featured_slider_section',
		            'active_callback' => 'featured_designcallback',
		        ) 
	        ) );
	    //Featured Slider in add background hover color
		    $wp_customize->add_setting( 'featured_slider_arrow_bghover_color', 
		        array(
		            'default'    => $default_setting['featured_slider_arrow_bghover_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'featured_slider_arrow_bghover_color', 
		        array(
		            'label'      => 'Arrow Background Hover Color', 
		            'settings'   => 'featured_slider_arrow_bghover_color', 
		            'priority'   => 10,
		            'section'    => 'featured_slider_section',
		            'active_callback' => 'featured_designcallback',
		        ) 
	        ) );
	    //Featured Slider in Autoplay True
		    $wp_customize->add_setting('featured_slider_autoplay', array(
		        'default'        => 'true',
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',		
		        'sanitize_callback' => 'custom_sanitize_select',
		    ));
		    $wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'featured_slider_autoplay',
		    	array(
			        'settings' => 'featured_slider_autoplay',
			        'label'   => 'Autoplay',
			        'section' => 'featured_slider_section',
			        'type'  => 'select',
			        'choices'    => array(
			        	'true' => 'True',
			        	'false' => 'False',
		        	),
		        	'active_callback' => 'featured_designcallback',
		        )
		    )); 
		//Featured Slider in autoplay speed
		    $wp_customize->add_setting('featured_slider_autoplay_speed', array(
		    	'default'        => '1000',
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',		
		        'sanitize_callback' => 'sanitize_text_field',
		    ));
		    $wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'featured_slider_autoplay_speed',
		    	array(
			        'settings' => 'featured_slider_autoplay_speed',
			        'label'   => 'AutoplaySpeed',
			        'section' => 'featured_slider_section',
			        'type'  => 'text',
			        'active_callback' => 'featured_designcallback',
		        )
		    ));  
		//Featured Slider in autoplay TimeOut
		    $wp_customize->add_setting('featured_slider_autoplay_timeout', array(
		    	'default'        => '5000',
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',		
		        'sanitize_callback' => 'sanitize_text_field',
		    ));
		    $wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'featured_slider_autoplay_timeout',
		    	array(
			        'settings' => 'featured_slider_autoplay_timeout',
			        'label'   => 'AutoplayTimeout',
			        'section' => 'featured_slider_section',
			        'type'  => 'text',
			        'active_callback' => 'featured_designcallback',
		        )
		    ));  
}
add_action( 'customize_register', 'featured_slider_setting' );

function featured_generalcallback(){
	$featuredimage_slider_tab = get_theme_mod( 'featuredimage_slider_tab','general');
	if ( 'general' === $featuredimage_slider_tab ) {
		return true;
	}
	return false;
}
function featured_designcallback(){
	$featuredimage_slider_tab = get_theme_mod( 'featuredimage_slider_tab','design');
	if ( 'design' === $featuredimage_slider_tab ) {
		return true;
	}
	return false;
}
