<?php

function our_services_setting( $wp_customize ) {	
	global $default_setting;
	$sections = array();
	$services_sections = apply_filters('custom_section', $sections);			
	//OUR SERVICES
		$wp_customize->add_section( 'our_services_section' , array(
			'title'  => 'Our Services',
			'panel'  => 'theme_option_panel',
		) );
		//OUR SERVICES Tabing
			$wp_customize->add_setting( 'our_services_tab', 
		        array(
		            'default'    => 'general', //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability'     => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitize_select',
		        ) 
		    ); 
	        $wp_customize->add_control( new Custom_Radio_Control( 
		        $wp_customize,'our_services_tab',array(
		            'settings'   => 'our_services_tab', 
		            'priority'   => 10,
		            'section'    => 'our_services_section',
		            'type'    => 'select',
		            'choices'    => array(
			        	'general' => 'General',
			        	'design' => 'Design',
		        	),
		        ) 
	        ) );
		//Our services in Title
			$wp_customize->add_setting( 'our_services_main_title', array(
				'default'    => 'Our Services',
			    'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',
		        'sanitize_callback' => 'sanitize_text_field',
			) );
			$wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'our_services_main_title',
		    	array(
					'type' => 'text',
					'settings' => 'our_services_main_title',
					'section' => 'our_services_section', // // Add a default or your own section
					'label' => 'Our Services Title',
					'active_callback' => 'our_services_general_callback',  
				)
			) );
			if ( isset( $wp_customize->selective_refresh ) ) {
				$wp_customize->selective_refresh->add_partial(
					'our_services_main_title',
					array(
						'selector'        => '.our_services_section',
						'render_callback' => 'custom_customize_partial_services',
					)
				);
			}
		//Our services in Discription
			$wp_customize->add_setting( 'our_services_main_discription', array(
				'default'   => $services_sections['service']['description'], 
			    'type'      => 'theme_mod',
		        'transport' => 'refresh',
		        'capability'     => 'edit_theme_options',
		        'sanitize_callback' => 'sanitize_textarea_field',
			) );
			$wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'our_services_main_discription',
		    	array(
					'type' => 'textarea',
					'settings' => 'our_services_main_discription',
					'section' => 'our_services_section', // // Add a default or your own section
					'label' => 'Our Services Discription',   
					'active_callback' => 'our_services_general_callback',  
				)
			) );
		//Our services in number of section
		    $wp_customize->add_setting( 'our_services_number', array(
		    	'default'  => 3,
			    'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',
		        'sanitize_callback' => 'custom_sanitize_number_range',
			) );
			$wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'our_services_number',
		    	array(
					'type' => 'number',
					'settings'   => 'our_services_number', 
					'section' => 'our_services_section', // // Add a default or your own section
					'label' => 'No of Section',
					'description' => 'Save and refresh the page if No. of Section is changed (Max no of section is 3)',
					'input_attrs' => array(
								    'min' => 1,
								    'max' => 3,
								),	
					'active_callback' => 'our_services_general_callback',  				   
				)
			) );
			$our_services_number = get_theme_mod( 'our_services_number', 3 );
			for ( $i = 1; $i <= $our_services_number ; $i++ ) {
				//Our services in headline
					$wp_customize->add_setting( 'our_services_heading_'.$i, array(
					    'type'       => 'theme_mod',
				        'transport'   => 'refresh',
				        'capability'     => 'edit_theme_options',
				        'sanitize_callback' => 'sanitize_text_field',
					) );
					$wp_customize->add_control( new Custom_GeneratePress_Upsell_Section(
				    	$wp_customize,'our_services_heading_'.$i,
				    	array(
							'type' => 'text',
							'settings'   => 'our_services_heading_'.$i, 
							'section' => 'our_services_section', 
							'label' => 'Our Services ' .$i,   
							'active_callback' => 'our_services_general_callback',  
						)
					) );
				//Featured Section icon
					$wp_customize->add_setting('our_services_icon_'. $i,
				        array(
				        	'default'   => $services_sections['service']['icon'][$i-1], 
				            'transport' => 'refresh',
				            'type'       => 'theme_mod',
				            'capability' => 'edit_theme_options',
				            'sanitize_callback' => 'sanitize_text_field',
				        )
				    );
				    $wp_customize->add_control( new WP_Customize_Control(
				    	$wp_customize,'our_services_icon_'.$i,
				    	array(
				            'type'        => 'text',
							'settings'    => 'our_services_icon_'.$i,
							'label'       => 'Select Features Icon '.$i,
							'description' => 'Select font awesome icons <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/">Click Here</a> for select icon',
							'section'     => 'our_services_section', 
							'active_callback' => 'our_services_general_callback',    
				        )
				    ));	
				//Our services in Title
					$wp_customize->add_setting( 'our_services_title_'.$i, array(
						'default'   => $services_sections['service']['title'][$i-1],
					    'type'       => 'theme_mod',
				        'transport'   => 'refresh',
				        'capability'     => 'edit_theme_options',
				        'sanitize_callback' => 'sanitize_text_field',
					) );
					$wp_customize->add_control( new WP_Customize_Control(
				    	$wp_customize,'our_services_title_'.$i,
				    	array(
							'type' => 'text',
							'settings'   => 'our_services_title_'.$i, 
							'section' => 'our_services_section', 
							'label' => 'Title ' .$i, 
							'active_callback' => 'our_services_general_callback',  
						)
					) );
				//Our services in Link
					$wp_customize->add_setting( 'our_services_title_link_'.$i, array(
						'default'   => '#',
					    'type'      => 'theme_mod',
				        'transport'  => 'refresh',
				        'capability' => 'edit_theme_options',
				        'sanitize_callback' => 'sanitize_text_field',
					) );
					$wp_customize->add_control( new WP_Customize_Control(
				    	$wp_customize,'our_services_title_link_'.$i,
				    	array(
							'type' => 'text',
							'settings'   => 'our_services_title_link_'.$i, 
							'section' => 'our_services_section', 
							'label' => 'Title Link ' .$i,
							'active_callback' => 'our_services_general_callback',  
						)
					) );
				//Our services in Description
					$wp_customize->add_setting( 'our_services_description_'.$i, array(
						'default'   => $services_sections['service']['subheading'][$i-1],
					    'type'       => 'theme_mod',
				        'transport'   => 'refresh',
				        'capability'     => 'edit_theme_options',
				        'sanitize_callback' => 'sanitize_textarea_field',
					) );
					$wp_customize->add_control( new WP_Customize_Control(
				    	$wp_customize,'our_services_description_'.$i,
				    	array(
							'type' => 'textarea',
							'settings'   => 'our_services_description_'.$i, 
							'section' => 'our_services_section', 
							'label' => 'Description ' .$i,	 
							'active_callback' => 'our_services_general_callback',     
						)
					) );	
			}
	    //Our Portfolio in pro version
			$wp_customize->add_setting('our_services_section_pro', array(
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',
		        'sanitize_callback' => 'sanitize_text_field',
		    ));
		    $wp_customize->add_control( new pro_option_custom_control(
		    	$wp_customize,'our_services_section_pro',
		    	array(
			        'settings' => 'our_services_section_pro',
			        'section' => 'our_services_section',
			        'active_callback' => 'our_services_general_callback',
		        )
		    ));	
		//Our services Section in Background Title
	    	$wp_customize->add_setting('our_services_background_section', array(
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',
		        'sanitize_callback' => 'sanitize_text_field',
		    ));
		    $wp_customize->add_control( new Custom_GeneratePress_Upsell_Section(
		    	$wp_customize,'our_services_background_section',
		    	array(
			        'settings' => 'our_services_background_section',
			        'label'   => 'Background Color Or Image',
			        'section' => 'our_services_section',
			        'type'     => 'ast-description',
			        'active_callback' => 'our_services_design_callback',
		        )
		    ));
		//Our services Section in Background Image
		    	$wp_customize->add_setting('our_services_bg_image', array(
		    		'default'  => '',
		        	'type'       => 'theme_mod',
			        'transport'  => 'refresh',
			        'height'     => 180,
			        'width'      => 160,
			        'capability' => 'edit_theme_options',
			        'sanitize_callback' => 'esc_url_raw'
			    ));
			    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'our_services_bg_image', array(
			        'label' => 'Backgroung Image',
			        'section' => 'our_services_section',
			        'settings' => 'our_services_bg_image',
			        'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
			        'active_callback' => 'our_services_design_callback',
			    )));
		//Our services Section in Background Position
		    $wp_customize->add_setting('our_services_bg_position', array(
		        'default'        => 'center center',
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',		
		        'sanitize_callback' => 'sanitize_text_field',
		    ));
		    $wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'our_services_bg_position',
		    	array(
			        'settings' => 'our_services_bg_position',
			        'label'   => 'Background Position',
			        'section' => 'our_services_section',
			        'type'  => 'select',
			        'active_callback' => 'our_services_design_callback',
			        'choices'    => array(
			        	'left top' => 'Left Top',
			        	'left center' => 'Left Center',
			        	'left bottom' => 'Left Bottom',
			            'right top' => 'Right Top',
			            'right center' => 'Right Center',
			            'right bottom' => 'Right Bottom',
			            'center top' => 'Center Top',
			            'center center' => 'Center Center',
			            'center bottom' => 'Center Bottom',
		        	),
		        )
		    ));
		//Our services Section in Background Attachment
			$wp_customize->add_setting('our_services_bg_attachment', array(
		        'default'        => 'scroll',
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',		
		        'sanitize_callback' => 'sanitize_text_field',
		    ));
		    $wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'our_services_bg_attachment',
		    	array(
			        'settings' => 'our_services_bg_attachment',
			        'label'   => 'Background Attachment',
			        'section' => 'our_services_section',
			        'type'  => 'select',
			        'choices'    => array(
			        	'scroll' => 'Scroll',
			        	'fixed' => 'Fixed',
		        	),
		        	'active_callback' => 'our_services_design_callback',
		        )
		    ));
		//Our services Section in image background Size
		    $wp_customize->add_setting('our_services_bg_size', array(
		        'default'        => 'cover',
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',		
		        'sanitize_callback' => 'sanitize_text_field',
		    ));
		    $wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'our_services_bg_size',
		    	array(
			        'settings' => 'our_services_bg_size',
			        'label'   => 'Background Size',
			        'section' => 'our_services_section',
			        'type'  => 'select',
			        'active_callback' => 'our_services_design_callback',
			        'choices'    => array(
			        	'auto' => 'Auto',
			        	'cover' => 'Cover',
			            'contain' => 'Contain'
		        	),
		        )
		    ));   
		//Our services in Background color
			$wp_customize->add_setting( 'our_services_bg_color', 
		        array(
		            'default'    => $default_setting['our_services_bg_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'our_services_bg_color', 
		        array(
		            'label'      => 'Background Color', 
		            'settings'   => 'our_services_bg_color', 
		            'priority'   => 10,
		            'section'    => 'our_services_section',
		            'active_callback' => 'our_services_design_callback',       
		        ) 
	        ) ); 
	    //Our services in Text color
			$wp_customize->add_setting( 'our_services_text_color', 
		        array(
		            'default'    => $default_setting['our_services_text_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'our_services_text_color', 
		        array(
		            'label'      => 'Text Color', 
		            'settings'   => 'our_services_text_color', 
		            'priority'   => 10,
		            'section'    => 'our_services_section', 
		            'active_callback' => 'our_services_design_callback',   
		        ) 
	        ) ); 
	    //Our services in Contain Link color
			$wp_customize->add_setting( 'our_services_link_color', 
		        array(
		            'default'    => $default_setting['our_services_link_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'our_services_link_color', 
		        array(
		            'label'      => 'Link Color', 
		            'settings'   => 'our_services_link_color', 
		            'priority'   => 10,
		            'section'    => 'our_services_section',  
		            'active_callback' => 'our_services_design_callback', 
		        ) 
	        ) ); 
	    //Our services in Contain Link Hover color
			$wp_customize->add_setting( 'our_services_link_hover_color', 
		        array(
		            'default'    => $default_setting['our_services_link_hover_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'our_services_link_hover_color', 
		        array(
		            'label'      => 'Link Hover Color', 
		            'settings'   => 'our_services_link_hover_color', 
		            'priority'   => 10,
		            'section'    => 'our_services_section',  
		            'active_callback' => 'our_services_design_callback', 
		        ) 
	        ) ); 
	    //Our services in Contain Background color
			$wp_customize->add_setting( 'our_services_contain_bg_color', 
		        array(
		            'default'    => $default_setting['our_services_contain_bg_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'our_services_contain_bg_color', 
		        array(
		            'label'      => 'Contain Background Color', 
		            'settings'   => 'our_services_contain_bg_color', 
		            'priority'   => 10,
		            'section'    => 'our_services_section', 
		            'active_callback' => 'our_services_design_callback',   
		        ) 
	        ) );  
	    //Our services in Contain text color
			$wp_customize->add_setting( 'our_services_contain_text_color', 
		        array(
		            'default'    => $default_setting['our_services_contain_text_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'our_services_contain_text_color', 
		        array(
		            'label'      => 'Contain Text Color', 
		            'settings'   => 'our_services_contain_text_color', 
		            'priority'   => 10,
		            'section'    => 'our_services_section',   
		            'active_callback' => 'our_services_design_callback', 
		        ) 
	        ) ); 
	    //Our services in Contain Background hover color
			$wp_customize->add_setting( 'our_services_contain_bg_hover_color', 
		        array(
		            'default'    => $default_setting['our_services_contain_bg_hover_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'our_services_contain_bg_hover_color', 
		        array(
		            'label'      => 'Contain Background Hover Color', 
		            'settings'   => 'our_services_contain_bg_hover_color', 
		            'priority'   => 10,
		            'section'    => 'our_services_section',  
		            'active_callback' => 'our_services_design_callback', 
		        ) 
	        ) ); 	    
	    //Our services in icon color
			$wp_customize->add_setting( 'our_services_icon_color', 
		        array(
		            'default'    => $default_setting['our_services_icon_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'our_services_icon_color', 
		        array(
		            'label'      => 'Icon Color', 
		            'settings'   => 'our_services_icon_color', 
		            'priority'   => 10,
		            'section'    => 'our_services_section', 
		            'active_callback' => 'our_services_design_callback',   
		        ) 
	        ) );
	    //Our services in icon hover color
			$wp_customize->add_setting( 'our_services_icon_hover_color', 
		        array(
		            'default'    => $default_setting['our_services_icon_hover_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'our_services_icon_hover_color', 
		        array(
		            'label'      => 'Hover Icon Color', 
		            'settings'   => 'our_services_icon_hover_color', 
		            'priority'   => 10,
		            'section'    => 'our_services_section', 
		            'active_callback' => 'our_services_design_callback',   
		        ) 
	        ) );
}
add_action( 'customize_register', 'our_services_setting' );

function our_services_general_callback(){
	$our_services_tab = get_theme_mod( 'our_services_tab','general');
	if ( 'general' === $our_services_tab ) {
		return true;
	}
	return false;
}
function our_services_design_callback(){
	$our_services_tab = get_theme_mod( 'our_services_tab','design');
	if ( 'design' === $our_services_tab ) {
		return true;
	}
	return false;
}