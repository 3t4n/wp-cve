<?php 
	
	class IHSS_Slider extends IHSS {
		
		public function __construct() {
		    add_action( 'customize_register', array($this,'ihss_customize_slider') );
	    }   
    
		
		public function ihss_customize_slider( $wp_customize ) {
		
			$wp_customize->add_panel( 'slider_panel', array(
			    'priority'       => 55,
			    'capability'     => 'edit_theme_options',
			    'theme_supports' => '',
			    'title'          => 'IH Slider',
			) );
			
			$wp_customize->add_section(
			    'sec_slider_options',
			    array(
			        'title'     => 'Enable/Disable',
			        'priority'  => 0,
			        'panel'     => 'slider_panel'
			    )
			);
			
			
			$wp_customize->add_setting(
				'main_slider_enable',
				array( 'sanitize_callback' => 'sanitize_checkbox' )
			);
			
			$wp_customize->add_control(
					'main_slider_enable', array(
				    'settings' => 'main_slider_enable',
				    'label'    => __( 'Enable Slider on Home/Blog.', 'ih-slider-showcase' ),
				    'section'  => 'sec_slider_options',
				    'type'     => 'checkbox',
				)
			);
		
			$wp_customize->add_setting(
				'main_slider_enable_front',
				array( 'sanitize_callback' => 'sanitize_checkbox' )
			);
			
			$wp_customize->add_control(
					'main_slider_enable_front', array(
				    'settings' => 'main_slider_enable_front',
				    'label'    => __( 'Enable Slider on front page.', 'ih-slider-showcase' ),
				    'section'  => 'sec_slider_options',
				    'type'     => 'checkbox',
				)
			);
			
			$wp_customize->add_setting(
				'main_slider_enable_posts',
				array( 'sanitize_callback' => 'sanitize_checkbox' )
			);
			
			$wp_customize->add_control(
					'main_slider_enable_posts', array(
				    'settings' => 'main_slider_enable_posts',
				    'label'    => __( 'Enable Slider on All Posts.', 'ih-slider-showcase' ),
				    'section'  => 'sec_slider_options',
				    'type'     => 'checkbox',
				)
			);
			
			$wp_customize->add_setting(
				'main_slider_enable_pages',
				array( 'sanitize_callback' => 'sanitize_checkbox' )
			);
			
			$wp_customize->add_control(
					'main_slider_enable_pages', array(
				    'settings' => 'main_slider_enable_pages',
				    'label'    => __( 'Enable Slider on All Pages.', 'ih-slider-showcase' ),
				    'section'  => 'sec_slider_options',
				    'type'     => 'checkbox',
				)
			);
			
			$wp_customize->add_setting(
				'main_slider_count',
					array(
						'default' => 0,
						'sanitize_callback' => 'sanitize_positive_number',
					)
			);
			
			$wp_customize->add_control(
					'main_slider_count', array(
				    'settings' => 'main_slider_count',
				    'label'    => __( 'No. of Slides(Min:0, Max: 30)' ,'ih-slider-showcase'),
				    'section'  => 'sec_slider_options',
				    'type'     => 'number',
				    'description' => __('Save the Settings, and Reload this page to Configure the Slides.','ih-slider-showcase'),
				    
				)
			);
			
			$wp_customize->add_setting(
				'main_slider_priority',
					array(
						'default' => 10,
						'sanitize_callback' => 'sanitize_positive_number',
					)
			);
			
			$wp_customize->add_control(
					'main_slider_priority', array(
				    'settings' => 'main_slider_priority',
				    'label'    => __( 'Priority' ,'ih-slider-showcase'),
				    'section'  => 'sec_slider_options',
				    'type'     => 'number',
				    'description' => __('Elements with Low Value of Priority will appear first.','ih-slider-showcase'),
				    
				)
			);
			
			function sanitize_positive_number( $input ) {
				if ( ($input >= 0) && is_numeric($input) )
					return $input;
				else
					return '';	
			}
			
			
			//Slider Config
			$wp_customize->add_section(
			    'slider_config',
			    array(
			        'title'     => __('Configure Slider','ih-slider-showcase'),
			        'priority'  => 0,
			        'panel'     => 'slider_panel'
			    )
			);
			
			$wp_customize->add_setting(
				'slider_pause',
					array(
						'default' => 5000,
						'sanitize_callback' => 'sanitize_positive_number'
					)
			);
			
			$wp_customize->add_control(
					'slider_pause', array(
				    'settings' => 'slider_pause',
				    'label'    => __( 'Time Between Each Slide.' ,'ih-slider-showcase'),
				    'section'  => 'slider_config',
				    'type'     => 'number',
				    'description' => __('Value in Milliseconds. Set to 0, to disable Autoplay. Default: 5000.','ih-slider-showcase'),
				    
				)
			);
			
			$wp_customize->add_setting(
				'slider_speed',
					array(
						'default' => 500,
						'sanitize_callback' => 'sanitize_positive_number'
					)
			);
			
			$wp_customize->add_control(
					'slider_speed', array(
				    'settings' => 'slider_speed',
				    'label'    => __( 'Animation Speed.' ,'ih-slider-showcase'),
				    'section'  => 'slider_config',
				    'type'     => 'number',
				    'description' => __('Value in Milliseconds. Default: 500.','ih-slider-showcase'),
				    
				)
			);
			
			
			$wp_customize->add_setting(
				'slider_pager',
					array(
						'default' => true,
						'sanitize_callback' => 'sanitize_checkbox'
					)
			);
			
			$wp_customize->add_control(
					'slider_pager', array(
				    'settings' => 'slider_pager',
				    'label'    => __( 'Enable Pager.' ,'ih-slider-showcase'),
				    'section'  => 'slider_config',
				    'type'     => 'checkbox',
				    'description' => __('Pager is the Circles at the bottom, which represent current slide.','ih-slider-showcase'),		    
				)
			);
			
			$wp_customize->add_setting(
				'slider_arrow',
					array(
						'default' => true,
						'sanitize_callback' => 'sanitize_checkbox'
					)
			);
			
			$wp_customize->add_control(
					'slider_arrow', array(
				    'settings' => 'slider_arrow',
				    'label'    => __( 'Enable Right/Left Navigation Arrows.' ,'ih-slider-showcase'),
				    'section'  => 'slider_config',
				    'type'     => 'checkbox',
				)
			);
			
			
			$wp_customize->add_setting(
				'slider_effect',
					array(
						'default' => 'fade',
						'sanitize_callback' => 'sanitize_text'
					)
			);
			
			$earray=array('fade','slide');
				$earray = array_combine($earray, $earray);
			
			$wp_customize->add_control(
					'slider_effect', array(
				    'settings' => 'slider_effect',
				    'label'    => __( 'Slider Animation Effect.' ,'ih-slider-showcase'),
				    'section'  => 'slider_config',
				    'type'     => 'select',
				    'choices' => $earray,
			) );
			
			// Select How Many Slides the User wants, and Reload the Page.
			
			for ( $i = 1 ; $i <= 30 ; $i++ ) :
				
				//Create the settings Once, and Loop through it.
				static $x = 0;
				$wp_customize->add_section(
				    'slide_sec'.$i,
				    array(
				        'title'     => 'Slide '.$i,
				        'priority'  => $i,
				        'panel'     => 'slider_panel',
				        'active_callback' => 'show_slide_sec'
				        
				    )
				);	
				
				$wp_customize->add_setting(
					'slide_img'.$i,
					array( 'sanitize_callback' => 'esc_url_raw' )
				);
				
				$wp_customize->add_control(
				    new WP_Customize_Image_Control(
				        $wp_customize,
				        'slide_img'.$i,
				        array(
				            'label' => '',
				            'section' => 'slide_sec'.$i,
				            'settings' => 'slide_img'.$i,			       
				        )
					)
				);
				
				$wp_customize->add_setting(
					'slide_title'.$i,
					array( 'sanitize_callback' => 'sanitize_text_field' )
				);
				
				$wp_customize->add_control(
						'slide_title'.$i, array(
					    'settings' => 'slide_title'.$i,
					    'label'    => __( 'Slide Title','ih-slider-showcase' ),
					    'section'  => 'slide_sec'.$i,
					    'type'     => 'text',
					)
				);
				
				$wp_customize->add_setting(
					'slide_desc'.$i,
					array( 'sanitize_callback' => 'sanitize_text_field' )
				);
				
				$wp_customize->add_control(
						'slide_desc'.$i, array(
					    'settings' => 'slide_desc'.$i,
					    'label'    => __( 'Slide Description','ih-slider-showcase' ),
					    'section'  => 'slide_sec'.$i,
					    'type'     => 'text',
					)
				);
				
				
				
				$wp_customize->add_setting(
					'slide_CTA_button'.$i,
					array( 'sanitize_callback' => 'sanitize_text_field' )
				);
				
				$wp_customize->add_control(
						'slide_CTA_button'.$i, array(
					    'settings' => 'slide_CTA_button'.$i,
					    'label'    => __( 'Custom Call to Action Button Text(Optional)','ih-slider-showcase' ),
					    'section'  => 'slide_sec'.$i,
					    'type'     => 'text',
					)
				);
				
				$wp_customize->add_setting(
					'slide_url'.$i,
					array( 'sanitize_callback' => 'esc_url_raw' )
				);
				
				$wp_customize->add_control(
						'slide_url'.$i, array(
					    'settings' => 'slide_url'.$i,
					    'label'    => __( 'Target URL','ih-slider-showcase' ),
					    'section'  => 'slide_sec'.$i,
					    'type'     => 'url',
					)
				);
				
			endfor;
		
			//active callback to see if the slide section is to be displayed or not
			function show_slide_sec( $control ) {
		        $option = $control->manager->get_setting('main_slider_count');
		        global $x;
		        if ( $x < $option->value() ){
		        	$x++;
		        	return true;
		        }
			}
		}	
		
	}