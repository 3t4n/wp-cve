<?php 
	
class IHSS_Parallax extends IHSS {
	
	public function __construct() {
	    add_action( 'customize_register', array($this,'ihss_customize_parallax') );
    }   
    
    public function ihss_customize_parallax( $wp_customize) {
	    
	    $wp_customize->add_panel( 'parallax_panel', array(
		    'priority'       => 55,
		    'capability'     => 'edit_theme_options',
		    'theme_supports' => '',
		    'title'          => __('IH HERO Parallax','ih-slider-showcase'),
		) );
		
		$wp_customize->add_section(
		    'sec_parallax_options',
		    array(
		        'title'     => __('Enable/Disable','ih-slider-showcase'),
		        'priority'  => 0,
		        'panel'     => 'parallax_panel'
		    )
		);
		
		
		$wp_customize->add_setting(
			'parallax_enable',
			array( 'sanitize_callback' => 'sanitize_checkbox' )
		);
		
		$wp_customize->add_control(
				'parallax_enable', array(
			    'settings' => 'parallax_enable',
			    'label'    => __( 'Enable parallax on Front Page.', 'ih-slider-showcase' ),
			    'section'  => 'sec_parallax_options',
			    'type'     => 'checkbox',
			)
		);
		
		
		for ( $i = 1 ; $i <= 1 ; $i++ ) :
			
			//Create the settings Once, and Loop through it.
			$wp_customize->add_section(
			    'parallax_sec'.$i,
			    array(
			        'title'     => __('Parallax Content','ih-slider-showcase'),
			        'priority'  => $i,
			        'panel'     => 'parallax_panel',
			        
			    )
			);	
			
			$wp_customize->add_setting(
				'parallax_img'.$i,
				array( 'sanitize_callback' => 'esc_url_raw' )
			);
			
			$wp_customize->add_control(
			    new WP_Customize_Image_Control(
			        $wp_customize,
			        'parallax_img'.$i,
			        array(
			            'label' => __('Background Image','ih-slider-showcase'),
			            'section' => 'parallax_sec'.$i,
			            'settings' => 'parallax_img'.$i,			       
			        )
				)
			);
			
			$wp_customize->add_setting(
				'parallax_title'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'parallax_title'.$i, array(
				    'settings' => 'parallax_title'.$i,
				    'label'    => __( 'Section Title','ih-slider-showcase' ),
				    'section'  => 'parallax_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			$wp_customize->add_setting(
				'parallax_desc'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'parallax_desc'.$i, array(
				    'settings' => 'parallax_desc'.$i,
				    'label'    => __( 'Section Description','ih-slider-showcase' ),
				    'section'  => 'parallax_sec'.$i,
				    'type'     => 'textarea',
				)
			);
			
			$wp_customize->add_setting(
				'parallax_btn1',
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'parallax_btn1', array(
				    'settings' => 'parallax_btn1',
				    'label'    => __( 'Button 1','ih-slider-showcase' ),
				    'section'  => 'parallax_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			$wp_customize->add_setting(
				'parallax_url1',
				array( 'sanitize_callback' => 'esc_url_raw' )
			);
			
			$wp_customize->add_control(
					'parallax_url1', array(
				    'settings' => 'parallax_url1',
				    'label'    => __( 'Button 1 URL','ih-slider-showcase' ),
				    'section'  => 'parallax_sec'.$i,
				    'type'     => 'url',
				)
			);
			
			$wp_customize->add_setting(
				'parallax_btn2',
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'parallax_btn2', array(
				    'settings' => 'parallax_btn2',
				    'label'    => __( 'Button 2','ih-slider-showcase' ),
				    'section'  => 'parallax_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			$wp_customize->add_setting(
				'parallax_url2',
				array( 'sanitize_callback' => 'esc_url_raw' )
			);
			
			$wp_customize->add_control(
					'parallax_url2', array(
				    'settings' => 'parallax_url2',
				    'label'    => __( 'Button 2 URL','ih-slider-showcase' ),
				    'section'  => 'parallax_sec'.$i,
				    'type'     => 'url',
				)
			);
			
		endfor;
	
    }
    
}