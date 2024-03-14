<?php

function featured_section_setting( $wp_customize ) {
	global $default_setting;
	$sections = array();
	$featured_sections = apply_filters('custom_section', $sections);	
	//Featured Section
		$wp_customize->add_section( 'featured_sections' , array(
			'title'  => 'Featured Section',
			'panel'  => 'theme_option_panel',
		) ); 
		// Featured Section tabing
			$wp_customize->add_setting( 'featured_section_tab', 
		        array(
		            'default'    => 'general', //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability'     => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitize_select',
		        ) 
		    ); 
	        $wp_customize->add_control( new Custom_Radio_Control( 
		        $wp_customize,'featured_section_tab',array(
		            'settings'   => 'featured_section_tab', 
		            'priority'   => 10,
		            'section'    => 'featured_sections',
		            'type'    => 'select',
		            'choices'    => array(
			        	'general' => 'General',
			        	'design' => 'Design',
		        	),
		        ) 
	        ) );
	    if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'featured_section_tab',
				array(
					'selector'        => '.featured-section_data',
					'render_callback' => 'custom_customize_featured_section',
				)
			);
		}
	    //Featured Section in number of slides
		    $wp_customize->add_setting( 'featured_section_number', array(
		    	'default'  => 4,
			    'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',
		        'sanitize_callback' => 'custom_sanitize_number_range',
			) );
			$wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'featured_section_number',
		    	array(
					'type' => 'number',
					'settings'   => 'featured_section_number', 
					'section' => 'featured_sections', // // Add a default or your own section
					'label' => 'No of Section',
					'description' => 'Save and refresh the page if No. of Sections is changed (Max no of slides is 20)',
					'input_attrs' => array(
								    'min' => 1,
								    'max' => 20,
								),
					'active_callback' => 'featured_section_callback',					   
				)
			) );
			$about_number = get_theme_mod( 'featured_section_number', 4 );
				for ( $i = 1; $i <= $about_number ; $i++ ) {
					//Featured section Heading
						$wp_customize->add_setting('featured_section'.$i, array(
					        'type'       => 'theme_mod',
					        'transport'   => 'refresh',
					        'capability'     => 'edit_theme_options',
					        'sanitize_callback' => 'sanitize_text_field',
					    ));
					    $wp_customize->add_control( new Custom_GeneratePress_Upsell_Section(
					    	$wp_customize,'featured_section'.$i,
					    	array(
						        'settings' => 'featured_section'.$i,
						        'label'   => 'Featured Section '.$i ,
						        'section' => 'featured_sections',
						        'type'     => 'ast-description',
						        'active_callback' => 'featured_section_callback',
					        )
					    ));
					if($i <= 4){
						//Featured Section icon
							$wp_customize->add_setting('features_one_icon'. $i,
						        array(
						        	'default'   => $featured_sections['featured_section']['icon'][$i-1], 
						            'transport' => 'refresh',
						            'type'       => 'theme_mod',
						            'capability' => 'edit_theme_options',
						            'sanitize_callback' => 'sanitize_text_field',
						        )
						    );
						    $wp_customize->add_control( new WP_Customize_Control(
						    	$wp_customize,'features_one_icon'.$i,
						    	array(
						            'type'        => 'text',
									'settings'    => 'features_one_icon'.$i,
									'label'       => 'Select Features Icon '.$i,
									'description' =>  'Select font awesome icons <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/">Click Here</a> for select icon',
									'section'     => 'featured_sections',
									'active_callback' => 'featured_section_callback',
						        )
						    ));	
					    //Featured Section Title 
							$wp_customize->add_setting( 'featured_section_title_' . $i , array(
								'default'   => $featured_sections['featured_section']['title'][$i-1],
							    'type'       => 'theme_mod',
						        'transport'   => 'refresh',
						        'capability'     => 'edit_theme_options',
						        'sanitize_callback' => 'sanitize_text_field',
							) );
							$wp_customize->add_control( new WP_Customize_Control(
						    	$wp_customize,'featured_section_title_' . $i,
						    	array(
									'type' => 'text',
									'settings'   => 'featured_section_title_' . $i, 
									'section' => 'featured_sections', // // Add a default or your own section
									'label' => 'Title ' . $i,
									'active_callback' => 'featured_section_callback',
								)
							) );
						//Featured Section Description 
							$wp_customize->add_setting( 'featured_section_description_' . $i , array(
								'default'   => $featured_sections['featured_section']['description'][$i-1],
							    'type'       => 'theme_mod',
						        'transport'   => 'refresh',
						        'capability'     => 'edit_theme_options',
						        'sanitize_callback' => 'sanitize_text_field',
							) );
							$wp_customize->add_control( new WP_Customize_Control(
						    	$wp_customize,'featured_section_description_' . $i,
						    	array(
									'type' => 'text',
									'settings'   => 'featured_section_description_' . $i, 
									'section' => 'featured_sections', // // Add a default or your own section
									'label' => 'Description ' . $i,
									'active_callback' => 'featured_section_callback',
								)
							) );
					}else{
						//Featured Section icon
							$wp_customize->add_setting('features_one_icon'. $i,
						        array(
						            'transport' => 'refresh',
						            'type'       => 'theme_mod',
						            'capability' => 'edit_theme_options',
						            'sanitize_callback' => 'sanitize_text_field',
						        )
						    );
						    $wp_customize->add_control( new WP_Customize_Control(
						    	$wp_customize,'features_one_icon'.$i,
						    	array(
						            'type'        => 'text',
									'settings'    => 'features_one_icon'.$i,
									'label'       => 'Select Features Icon '.$i,
									'description' =>  'Select font awesome icons <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/">Click Here</a> for select icon',
									'section'     => 'featured_sections',
									'active_callback' => 'featured_section_callback',
						        )
						    ));	
					    //Featured Section Title 
							$wp_customize->add_setting( 'featured_section_title_' . $i , array(
							    'type'       => 'theme_mod',
						        'transport'   => 'refresh',
						        'capability'     => 'edit_theme_options',
						        'sanitize_callback' => 'sanitize_text_field',
							) );
							$wp_customize->add_control( new WP_Customize_Control(
						    	$wp_customize,'featured_section_title_' . $i,
						    	array(
									'type' => 'text',
									'settings'   => 'featured_section_title_' . $i, 
									'section' => 'featured_sections', // // Add a default or your own section
									'label' => 'Title ' . $i,
									'active_callback' => 'featured_section_callback',
								)
							) );
						//Featured Section Description 
							$wp_customize->add_setting( 'featured_section_description_' . $i , array(
							    'type'       => 'theme_mod',
						        'transport'   => 'refresh',
						        'capability'     => 'edit_theme_options',
						        'sanitize_callback' => 'sanitize_text_field',
							) );
							$wp_customize->add_control( new WP_Customize_Control(
						    	$wp_customize,'featured_section_description_' . $i,
						    	array(
									'type' => 'text',
									'settings'   => 'featured_section_description_' . $i, 
									'section' => 'featured_sections', // // Add a default or your own section
									'label' => 'Description ' . $i,
									'active_callback' => 'featured_section_callback',
								)
							) );
					}
				}
				//Features Section in pro version
					/*$wp_customize->add_setting('featuredimage_section_pro', array(
				        'type'       => 'theme_mod',
				        'transport'   => 'refresh',
				        'capability'     => 'edit_theme_options',
				        'sanitize_callback' => 'sanitize_text_field',
				    ));
				    $wp_customize->add_control( new designhubs_pro_option_Control(
				    	$wp_customize,'featuredimage_section_pro',
				    	array(
					        'settings' => 'featuredimage_section_pro',
					        'section' => 'featured_sections',
					        'active_callback' => 'featured_section_callback',
				        )
				    ));	*/
				//Featured Section icon size 
					$wp_customize->add_setting( 'featured_section_icon_size', array(
						'default'    => '35',
					    'type'       => 'theme_mod',
				        'transport'   => 'refresh',
				        'capability'     => 'edit_theme_options',
				        'sanitize_callback' => 'sanitize_text_field',
					) );
					$wp_customize->add_control( new WP_Customize_Control(
				    	$wp_customize,'featured_section_icon_size',
				    	array(
							'type' => 'number',
							'settings'   => 'featured_section_icon_size',
							'section' => 'featured_sections', // // Add a default or your own section
							'label' => 'Icon Size',
							'description' =>'in px',
							'active_callback' => 'featured_section_designcallback',
						)
					) );
				//Featured Section Background color
				    $wp_customize->add_setting( 'featured_section_main_bg_color', 
				        array(
				            'default'    => $default_setting['featured_section_main_bg_color'], 
				            'type'       => 'theme_mod',
				            'transport'   => 'refresh',
				            'capability' => 'edit_theme_options',
				            'sanitize_callback' => 'custom_sanitization_callback',
				        ) 
				    ); 
			        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
				        $wp_customize,'featured_section_main_bg_color', 
				        array(
				            'label'      => 'Background Color', 
				            'settings'   => 'featured_section_main_bg_color', 
				            'priority'   => 10,
				            'section'    => 'featured_sections',
				            'active_callback' => 'featured_section_designcallback',
				        ) 
			        ) );
				//Featured Section Background color
				    $wp_customize->add_setting( 'featured_section_bg_color', 
				        array(
				            'default'    => $default_setting['featured_section_bg_color'], 
				            'type'       => 'theme_mod',
				            'transport'   => 'refresh',
				            'capability' => 'edit_theme_options',
				            'sanitize_callback' => 'custom_sanitization_callback',
				        ) 
				    ); 
			        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
				        $wp_customize,'featured_section_bg_color', 
				        array(
				            'label'      => 'Contain Background Color', 
				            'settings'   => 'featured_section_bg_color', 
				            'priority'   => 10,
				            'section'    => 'featured_sections',
				            'active_callback' => 'featured_section_designcallback',
				        ) 
			        ) );
			    //Featured Section Text color
				    $wp_customize->add_setting( 'featured_section_color', 
				        array(
				            'default'    =>  $default_setting['featured_section_color'], 
				            'type'       => 'theme_mod',
				            'transport'   => 'refresh',
				            'capability' => 'edit_theme_options',
				            'sanitize_callback' => 'custom_sanitization_callback',
				        ) 
				    ); 
			        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
				        $wp_customize,'featured_section_color', 
				        array(
				            'label'      => 'Contain Text Color', 
				            'settings'   => 'featured_section_color', 
				            'priority'   => 10,
				            'section'    => 'featured_sections',
				            'active_callback' => 'featured_section_designcallback',
				        ) 
			        ) ); 
			    //Featured Section Background hover color
				    $wp_customize->add_setting( 'featured_section_bg_hover_color', 
				        array(
				            'default'    => $default_setting['featured_section_bg_hover_color'], 
				            'type'       => 'theme_mod',
				            'transport'   => 'refresh',
				            'capability' => 'edit_theme_options',
				            'sanitize_callback' => 'custom_sanitization_callback',
				        ) 
				    ); 
			        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
				        $wp_customize,'featured_section_bg_hover_color', 
				        array(
				            'label'      => 'Contain Background Hover Color', 
				            'settings'   => 'featured_section_bg_hover_color', 
				            'priority'   => 10,
				            'section'    => 'featured_sections',
				            'active_callback' => 'featured_section_designcallback',
				        ) 
			        ) ); 
			    //Featured Section Text hover color
				    $wp_customize->add_setting( 'featured_section_text_hover_color', 
				        array(
				            'default'    => $default_setting['featured_section_text_hover_color'], 
				            'type'       => 'theme_mod',
				            'transport'   => 'refresh',
				            'capability' => 'edit_theme_options',
				            'sanitize_callback' => 'custom_sanitization_callback',
				        ) 
				    ); 
			        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
				        $wp_customize,'featured_section_text_hover_color', 
				        array(
				            'label'      => 'Contain Text Hover Color', 
				            'settings'   => 'featured_section_text_hover_color', 
				            'priority'   => 10,
				            'section'    => 'featured_sections',
				            'active_callback' => 'featured_section_designcallback',
				        ) 
			        ) ); 
			    //Featured Section Icon color
				    $wp_customize->add_setting( 'featured_section_icon_color', 
				        array(
				            'default'    => $default_setting['featured_section_icon_color'], 
				            'type'       => 'theme_mod',
				            'transport'   => 'refresh',
				            'capability' => 'edit_theme_options',
				            'sanitize_callback' => 'custom_sanitization_callback',
				        ) 
				    ); 
			        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
				        $wp_customize,'featured_section_icon_color', 
				        array(
				            'label'      => 'Icon Color', 
				            'settings'   => 'featured_section_icon_color', 
				            'priority'   => 10,
				            'section'    => 'featured_sections',
				            'active_callback' => 'featured_section_designcallback',
				        ) 
			        ) ); 
			    //Featured Section Icon Hover color
				    $wp_customize->add_setting( 'featured_section_icon_hover_color', 
				        array(
				            'default'    => $default_setting['featured_section_icon_hover_color'], 
				            'type'       => 'theme_mod',
				            'transport'   => 'refresh',
				            'capability' => 'edit_theme_options',
				            'sanitize_callback' => 'custom_sanitization_callback',
				        ) 
				    ); 
			        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
				        $wp_customize,'featured_section_icon_hover_color', 
				        array(
				            'label'      => 'Icon Hover Color', 
				            'settings'   => 'featured_section_icon_hover_color', 
				            'priority'   => 10,
				            'section'    => 'featured_sections',
				            'active_callback' => 'featured_section_designcallback',
				        ) 
			        ) ); 
			    //Featured Section Icon Backgroundcolor
				    $wp_customize->add_setting( 'featured_section_icon_bg_color', 
				        array(
				            'default'    => $default_setting['featured_section_icon_bg_color'], 
				            'type'       => 'theme_mod',
				            'transport'   => 'refresh',
				            'capability' => 'edit_theme_options',
				            'sanitize_callback' => 'custom_sanitization_callback',
				        ) 
				    ); 
			        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
				        $wp_customize,'featured_section_icon_bg_color', 
				        array(
				            'label'      => 'Icon Background Color', 
				            'settings'   => 'featured_section_icon_bg_color', 
				            'priority'   => 10,
				            'section'    => 'featured_sections',
				            'active_callback' => 'featured_section_designcallback',
				        ) 
			        ) ); 
			    //Featured Section Icon Background Hover color
				    $wp_customize->add_setting( 'featured_section_icon_bg_hover_color', 
				        array(
				            'default'    => $default_setting['featured_section_icon_bg_hover_color'], 
				            'type'       => 'theme_mod',
				            'transport'   => 'refresh',
				            'capability' => 'edit_theme_options',
				            'sanitize_callback' => 'custom_sanitization_callback',
				        ) 
				    ); 
			        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
				        $wp_customize,'featured_section_icon_bg_hover_color', 
				        array(
				            'label'      => 'Icon Background Hover Color', 
				            'settings'   => 'featured_section_icon_bg_hover_color', 
				            'priority'   => 10,
				            'section'    => 'featured_sections',
				            'active_callback' => 'featured_section_designcallback',
				        ) 
			        ) ); 
			    //Featured Section margin
			        $wp_customize->add_setting( 'featured_section_margin', 
				        array(
				            'default'    => '0px 0px 0px 0px', //Default setting/value to save
				            'type'       => 'theme_mod',
				            'transport'   => 'refresh',
				            'capability' => 'edit_theme_options',
				            'sanitize_callback' => 'sanitize_text_field',
				        ) 
				    ); 
			        $wp_customize->add_control( new WP_Customize_Control( 
				        $wp_customize,'featured_section_margin', 
				        array(
				            'label'      => 'Margin', 
				            'description'=> '0px 0px 0px 0px',
				            'settings'   => 'featured_section_margin', 
				            'priority'   => 10,
				            'section'    => 'featured_sections',
				            'active_callback' => 'featured_section_designcallback',
				        ) 
			        ) ); 	    
}
add_action( 'customize_register', 'featured_section_setting' );
function featured_section_callback(){
	$featured_section_tab = get_theme_mod( 'featured_section_tab','general');
	if ( 'general' === $featured_section_tab ) {
		return true;
	}
	return false;
}
function featured_section_designcallback(){
	$featured_section_tab = get_theme_mod( 'featured_section_tab','design');
	if ( 'design' === $featured_section_tab ) {
		return true;
	}
	return false;
}