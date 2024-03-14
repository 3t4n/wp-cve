<?php 
	
class IHSS_Testimonials extends IHSS {
	
	public function __construct() {
	    add_action( 'customize_register', array($this,'ihss_customize_testimonial') );
    }   
    
    public function ihss_customize_testimonial( $wp_customize) {
	    
	    $wp_customize->add_panel( 'testimonial_panel', array(
		    'priority'       => 55,
		    'capability'     => 'edit_theme_options',
		    'theme_supports' => '',
		    'title'          => __('IH Testimonials','ih-slider-showcase'),
		) );
		
		$wp_customize->add_section(
		    'sec_testimonial_options',
		    array(
		        'title'     => __('Enable/Disable','ih-slider-showcase'),
		        'priority'  => 0,
		        'panel'     => 'testimonial_panel'
		    )
		);
		
		
		$wp_customize->add_setting(
			'testimonial_enable',
			array( 'sanitize_callback' => 'sanitize_checkbox' )
		);
		
		$wp_customize->add_control(
				'testimonial_enable', array(
			    'settings' => 'testimonial_enable',
			    'label'    => __( 'Enable testimonial on Static Front Page.', 'ih-slider-showcase' ),
			    'section'  => 'sec_testimonial_options',
			    'type'     => 'checkbox'
			)
		);
		
		function sanitize_checkbox( $input ) {
		    if ( $input == 1 ) {
		        return 1;
		    } else {
		        return '';
		    }
		}
		
		$wp_customize->add_setting(
			'testimonial_title',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		
		$wp_customize->add_control(
				'testimonial_title', array(
			    'settings' => 'testimonial_title',
			    'label'    => __( 'Title','ih-slider-showcase' ),
			    'section'  => 'sec_testimonial_options',
			    'type'     => 'text',
			)
		);
		
		for ( $i = 1 ; $i <= 4 ; $i++ ) :
			
			//Create the settings Once, and Loop through it.
			$wp_customize->add_section(
			    'testimonial_sec'.$i,
			    array(
			        'title'     => __('Testimonial ','ih-slider-showcase').$i,
			        'priority'  => $i,
			        'panel'     => 'testimonial_panel',
			        
			    )
			);	
			
			$wp_customize->add_setting(
				'testimonial_img'.$i,
				array( 'sanitize_callback' => 'esc_url_raw' )
			);
			
			$wp_customize->add_control(
			    new WP_Customize_Image_Control(
			        $wp_customize,
			        'testimonial_img'.$i,
			        array(
			            'label' => '',
			            'section' => 'testimonial_sec'.$i,
			            'settings' => 'testimonial_img'.$i,			       
			        )
				)
			);
			
			$wp_customize->add_setting(
				'testimonial_title'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'testimonial_title'.$i, array(
				    'settings' => 'testimonial_title'.$i,
				    'label'    => __( 'Author','ih-slider-showcase' ),
				    'section'  => 'testimonial_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			$wp_customize->add_setting(
				'testimonial_desc'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'testimonial_desc'.$i, array(
				    'settings' => 'testimonial_desc'.$i,
				    'label'    => __( 'Testimonial Content','ih-slider-showcase' ),
				    'section'  => 'testimonial_sec'.$i,
				    'type'     => 'text',
				)
			);
			
		endfor;
	
    }
    
}