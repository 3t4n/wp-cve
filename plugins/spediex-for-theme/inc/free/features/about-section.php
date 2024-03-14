<?php
function about_section_setting( $wp_customize ) {
	global $default_setting;
	$sections = array();
	$about_sections = apply_filters('custom_section', $sections);
	//About Section	
		$wp_customize->add_section( 'about_section' , array(
			'title'  => 'About Section',
			'panel'  => 'theme_option_panel',
		) );
		//About Section title
		    $wp_customize->add_setting('about_main_title', array(
		        'default'        => 'About',
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',		
		        'sanitize_callback' => 'sanitize_text_field',
		    ));
		    $wp_customize->add_control( new WP_Customize_Control($wp_customize,'about_main_title',
		    	array(
			        'settings' => 'about_main_title',
			        'label'   => 'About Title',
			        'section' => 'about_section',
			        'type'  => 'text',
		        )
		    ));
		    if ( isset( $wp_customize->selective_refresh ) ) {
				$wp_customize->selective_refresh->add_partial(
					'about_main_title',
					array(
						'selector'        => '.about_section_info',
						'render_callback' => 'custom_customize_about_name',
					)
				);
			}
		//About Section Description
		    $wp_customize->add_setting('about_description', array(
		    	'default'    => $about_sections['about']['description'],
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',		
		        'sanitize_callback' => 'sanitize_textarea_field',
		    ));
		    $wp_customize->add_control( new WP_Customize_Control($wp_customize,'about_description',
		    	array(
			        'settings' => 'about_description',
			        'label'   => 'About Description',
			        'section' => 'about_section',
			        'type'  => 'textarea',
		        )
		    ));
		//About Section image 
			$wp_customize->add_setting('about_section_image', array(
	        	'type'       => 'theme_mod',
		        'transport'     => 'refresh',
		        'height'        => 180,
		        'width'        => 160,
		        'capability' => 'edit_theme_options',
		        'sanitize_callback' => 'esc_url_raw'
		    ));
		    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'about_section_image' , array(
		        'label' =>  'Image',
		        'section' => 'about_section',
		        'settings' => 'about_section_image',
		        'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
		    )));
		//About Section layouts
			$wp_customize->add_setting('about_section_layouts', array(
		        'default'        => 'layout1',
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',		
		        'sanitize_callback' => 'custom_sanitize_select',
		    ));
		    $wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'about_section_layouts',
		    	array(
			        'settings' => 'about_section_layouts',
			        'label'   => 'About Layouts',
			        'section' => 'about_section',
			        'type'  => 'select',
			        'choices'    => array(
			        	'layout1' => 'Layout 1',
			        	'layout2' => 'Layout 2',
		        	),
		        )
		    ));
		//Layout1
		    //Layout1 title
			    $wp_customize->add_setting('about_layout1_title', array(
			        'default'        => 'Hi, I Am Samantha!',
			        'type'       => 'theme_mod',
			        'transport'   => 'refresh',
			        'capability'     => 'edit_theme_options',		
			        'sanitize_callback' => 'sanitize_text_field',
			    ));
			    $wp_customize->add_control( new WP_Customize_Control(
			    	$wp_customize,'about_layout1_title',
			    	array(
				        'settings' => 'about_layout1_title',
				        'label'   => 'About Title',
				        'section' => 'about_section',
				        'type'  => 'text',
				        'active_callback' => 'about_layout1_callback',
			        )
			    ));
			//Layout1 subheading
			    $wp_customize->add_setting('about_layout1_subheading', array(
			        'default'        => 'Owner/Founder, Executive Coach',
			        'type'       => 'theme_mod',
			        'transport'   => 'refresh',
			        'capability'     => 'edit_theme_options',		
			        'sanitize_callback' => 'sanitize_text_field',
			    ));
			    $wp_customize->add_control( new WP_Customize_Control(
			    	$wp_customize,'about_layout1_subheading',
			    	array(
				        'settings' => 'about_layout1_subheading',
				        'label'   => 'Sub Heading',
				        'section' => 'about_section',
				        'type'  => 'text',
				        'active_callback' => 'about_layout1_callback',
			        )
			    ));
			//Layout1 description
			    $wp_customize->add_setting('about_layout1_description', array(
			    	'default'    => $about_sections['about']['description1'],
			        'type'       => 'theme_mod',
			        'transport'   => 'refresh',
			        'capability'     => 'edit_theme_options',		
			        'sanitize_callback' => 'sanitize_textarea_field',
			    ));
			    $wp_customize->add_control( new WP_Customize_Control(
			    	$wp_customize,'about_layout1_description',
			    	array(
				        'settings' => 'about_layout1_description',
				        'label'   => 'About Description',
				        'section' => 'about_section',
				        'type'  => 'textarea',
				        'active_callback' => 'about_layout1_callback',
			        )
			    ));
			//Layout1 button
			    $wp_customize->add_setting('about_layout1_button', array(
			        'default'        => 'Read More',
			        'type'       => 'theme_mod',
			        'transport'   => 'refresh',
			        'capability'     => 'edit_theme_options',		
			        'sanitize_callback' => 'sanitize_text_field',
			    ));
			    $wp_customize->add_control( new WP_Customize_Control(
			    	$wp_customize,'about_layout1_button',
			    	array(
				        'settings' => 'about_layout1_button',
				        'label'   => 'Button',
				        'section' => 'about_section',
				        'type'  => 'text',
				        'active_callback' => 'about_layout1_callback',
			        )
			    ));
			//Layout1 button Link
			    $wp_customize->add_setting('about_layout1_button_link', array(
			        'default'        => '#',
			        'type'       => 'theme_mod',
			        'transport'   => 'refresh',
			        'capability'     => 'edit_theme_options',		
			        'sanitize_callback' => 'sanitize_text_field',
			    ));
			    $wp_customize->add_control( new WP_Customize_Control(
			    	$wp_customize,'about_layout1_button_link',
			    	array(
				        'settings' => 'about_layout1_button_link',
				        'label'   => 'Button Link',
				        'section' => 'about_section',
				        'type'  => 'text',
				        'active_callback' => 'about_layout1_callback',
			        )
			    ));
		//Layout2
			//About Section in number of section
		    $wp_customize->add_setting( 'about_section_number', array(
		    	'default'  => 4,
			    'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',
		        'sanitize_callback' => 'custom_sanitize_number_range',
			) );
			$wp_customize->add_control( new WP_Customize_Control(
		    	$wp_customize,'about_section_number',
		    	array(
					'type' => 'number',
					'settings'   => 'about_section_number', 
					'section' => 'about_section', // // Add a default or your own section
					'label' => 'No of Section',
					'description' => 'Save and refresh the page if No. of Sections is changed (Max no of section is 4)',
					'input_attrs' => array(
								    'min' => 1,
								    'max' => 4,
								),
					'active_callback' => 'about_layout2_callback',					   
				)
			) );
			$about_section_number = get_theme_mod( 'about_section_number', 4 );
			for ( $i = 1; $i <= $about_section_number ; $i++ ) {
					//About section Heading
						$wp_customize->add_setting('about_section_'.$i, array(
					        'type'       => 'theme_mod',
					        'transport'   => 'refresh',
					        'capability'     => 'edit_theme_options',
					        'sanitize_callback' => 'sanitize_text_field',
					    ));
					    $wp_customize->add_control( new Custom_GeneratePress_Upsell_Section(
					    	$wp_customize,'about_section_'.$i,
					    	array(
						        'settings' => 'about_section_'.$i,
						        'label'   => 'About Section '.$i ,
						        'section' => 'about_section',
						        'type'     => 'ast-description',
						        'active_callback' => 'about_layout2_callback',
					        )
					    ));
					//About Section icon
						$wp_customize->add_setting('about_one_icon'. $i,
					        array(
					        	'default'   => $about_sections['about']['icon'][$i-1], 
					            'transport' => 'refresh',
					            'type'       => 'theme_mod',
					            'capability' => 'edit_theme_options',
					            'sanitize_callback' => 'sanitize_text_field',
					        )
					    );
					    $wp_customize->add_control( new WP_Customize_Control(
					    	$wp_customize,'about_one_icon'.$i,
					    	array(
					            'type'        => 'text',
								'settings'    => 'about_one_icon'.$i,
								'label'       => 'Select Features Icon '.$i,
								'description' =>  'Select font awesome icons <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/">Click Here</a> for select icon',
								'section'     => 'about_section',
								'active_callback' => 'about_layout2_callback',
					        )
					    ));	
					//About Section Title
					    $wp_customize->add_setting('about_section_title_'.$i, array(
					    	'default'   => $about_sections['about']['title'][$i-1], 
					        'type'       => 'theme_mod',
					        'transport'   => 'refresh',
					        'capability'     => 'edit_theme_options',
					        'sanitize_callback' => 'sanitize_text_field',
					    ));
					    $wp_customize->add_control( new WP_Customize_Control(
					    	$wp_customize,'about_section_title_'.$i,
					    	array(
						        'settings' => 'about_section_title_'.$i,
						        'label'   => 'Title '.$i ,
						        'section' => 'about_section',
						        'type'     => 'text',
						        'active_callback' => 'about_layout2_callback',
					        )
					    ));
					//About Section Link Title Url
					    $wp_customize->add_setting('about_section_title_url_'.$i, array(
					    	'default'   => '#',
					        'type'       => 'theme_mod',
					        'transport'   => 'refresh',
					        'capability'     => 'edit_theme_options',
					        'sanitize_callback' => 'sanitize_text_field',
					    ));
					    $wp_customize->add_control( new WP_Customize_Control(
					    	$wp_customize,'about_section_title_url_'.$i,
					    	array(
						        'settings' => 'about_section_title_url_'.$i,
						        'label'   => 'Link Title '.$i ,
						        'section' => 'about_section',
						        'type'     => 'text',
						        'active_callback' => 'about_layout2_callback',
					        )
					    ));
					//About Section Description
					    $wp_customize->add_setting('about_section_description_'.$i, array(
					    	'default'   => $about_sections['about']['description3'][$i-1], 
					        'type'       => 'theme_mod',
					        'transport'   => 'refresh',
					        'capability'     => 'edit_theme_options',
					        'sanitize_callback' => 'sanitize_text_field',
					    ));
					    $wp_customize->add_control( new WP_Customize_Control(
					    	$wp_customize,'about_section_description_'.$i,
					    	array(
						        'settings' => 'about_section_description_'.$i,
						        'label'   => 'Description '.$i ,
						        'section' => 'about_section',
						        'type'     => 'text',
						        'active_callback' => 'about_layout2_callback',
					        )
					    ));					
		    }
		//About in pro version
			$wp_customize->add_setting('about_section_pro', array(
		        'type'       => 'theme_mod',
		        'transport'   => 'refresh',
		        'capability'     => 'edit_theme_options',
		        'sanitize_callback' => 'sanitize_text_field',
		    ));
		    $wp_customize->add_control( new pro_option_custom_control(
		    	$wp_customize,'about_section_pro',
		    	array(
			        'settings' => 'about_section_pro',
			        'section' => 'about_section',
			        'active_callback' => 'about_layout2_callback',
		        )
		    ));	
		//About Background Color
		    $wp_customize->add_setting( 'about_bg_color', 
		        array(
		            'default'    => $default_setting['about_bg_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'about_bg_color', 
		        array(
		            'label'      => 'Background Color', 
		            'settings'   => 'about_bg_color', 
		            'priority'   => 10,
		            'section'    => 'about_section',
		        ) 
	        ) ); 
	    //About title text color
	        $wp_customize->add_setting( 'about_title_text_color', 
		        array(
		            'default'    => $default_setting['about_title_text_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'about_title_text_color', 
		        array(
		            'label'      => 'Title Text Color', 
		            'settings'   => 'about_title_text_color', 
		            'priority'   => 10,
		            'section'    => 'about_section',
		        ) 
	        ) ); 
	    //About text color
	        $wp_customize->add_setting( 'about_text_color', 
		        array(
		            'default'    => $default_setting['about_text_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'about_text_color', 
		        array(
		            'label'      => 'Text Color', 
		            'settings'   => 'about_text_color', 
		            'priority'   => 10,
		            'section'    => 'about_section',
		        ) 
	        ) ); 
	    //About Link color
	        $wp_customize->add_setting( 'about_link_color', 
		        array(
		            'default'    => $default_setting['about_link_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'about_link_color', 
		        array(
		            'label'      => 'Link Color', 
		            'settings'   => 'about_link_color', 
		            'priority'   => 10,
		            'section'    => 'about_section',
		        ) 
	        ) ); 
	    //About text color
	        $wp_customize->add_setting( 'about_link_hover_color', 
		        array(
		            'default'    => $default_setting['about_link_hover_color'], //Default setting/value to save
		            'type'       => 'theme_mod',
		            'transport'   => 'refresh',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'custom_sanitization_callback',
		        ) 
		    ); 
	        $wp_customize->add_control( new Customize_Transparent_Color_Control( 
		        $wp_customize,'about_link_hover_color', 
		        array(
		            'label'      => 'Link Hover Color', 
		            'settings'   => 'about_link_hover_color', 
		            'priority'   => 10,
		            'section'    => 'about_section',
		        ) 
	        ) ); 
}
add_action( 'customize_register', 'about_section_setting' );

function about_layout1_callback(){
	$about_section_layouts = get_theme_mod( 'about_section_layouts','layout1');
	if ( 'layout1' === $about_section_layouts ) {
		return true;
	}
	return false;
}
function about_layout2_callback(){
	$about_section_layouts = get_theme_mod( 'about_section_layouts','layout1');
	if ( 'layout2' === $about_section_layouts ) {
		return true;
	}
	return false;
}
?>