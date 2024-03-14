<?php 
	
class IHSS_Showcase extends IHSS {
	
	public function __construct() {
	    add_action( 'customize_register', array($this,'ihss_customize_showcase') );
    }   
    
    public function ihss_customize_showcase( $wp_customize) {
	    
	    $wp_customize->add_panel( 'showcase_panel', array(
		    'priority'       => 55,
		    'capability'     => 'edit_theme_options',
		    'theme_supports' => '',
		    'title'          => __('IH Custom Showcase','ih-slider-showcase'),
		) );
		
		$wp_customize->add_section(
		    'sec_showcase_options',
		    array(
		        'title'     => __('Enable/Disable','ih-slider-showcase'),
		        'priority'  => 0,
		        'panel'     => 'showcase_panel'
		    )
		);
		
		
		$wp_customize->add_setting(
			'showcase_enable',
			array( 'sanitize_callback' => 'sanitize_checkbox' )
		);
		
		$wp_customize->add_control(
				'showcase_enable', array(
			    'settings' => 'showcase_enable',
			    'label'    => __( 'Enable Showcase on Front Page.', 'ih-slider-showcase' ),
			    'section'  => 'sec_showcase_options',
			    'type'     => 'checkbox',
			)
		);
		
		
		$wp_customize->add_setting(
			'showcase_title',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		
		$wp_customize->add_control(
				'showcase_title', array(
			    'settings' => 'showcase_title',
			    'label'    => __( 'Title','ih-slider-showcase' ),
			    'section'  => 'sec_showcase_options',
			    'type'     => 'text',
			)
		);
		
		for ( $i = 1 ; $i <= 3 ; $i++ ) :
			
			//Create the settings Once, and Loop through it.
			$wp_customize->add_section(
			    'showcase_sec'.$i,
			    array(
			        'title'     => __('ShowCase ','ih-slider-showcase').$i,
			        'priority'  => $i,
			        'panel'     => 'showcase_panel',
			        
			    )
			);	
			
			$wp_customize->add_setting(
				'showcase_img'.$i,
				array( 'sanitize_callback' => 'esc_url_raw' )
			);
			
			$wp_customize->add_control(
			    new WP_Customize_Image_Control(
			        $wp_customize,
			        'showcase_img'.$i,
			        array(
			            'label' => '',
			            'section' => 'showcase_sec'.$i,
			            'settings' => 'showcase_img'.$i,			       
			        )
				)
			);
			
			$wp_customize->add_setting(
				'showcase_title'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'showcase_title'.$i, array(
				    'settings' => 'showcase_title'.$i,
				    'label'    => __( 'Showcase Title','ih-slider-showcase' ),
				    'section'  => 'showcase_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			$wp_customize->add_setting(
				'showcase_desc'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'showcase_desc'.$i, array(
				    'settings' => 'showcase_desc'.$i,
				    'label'    => __( 'Showcase Description','ih-slider-showcase' ),
				    'section'  => 'showcase_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			
			$wp_customize->add_setting(
				'showcase_url'.$i,
				array( 'sanitize_callback' => 'esc_url_raw' )
			);
			
			$wp_customize->add_control(
					'showcase_url'.$i, array(
				    'settings' => 'showcase_url'.$i,
				    'label'    => __( 'Target URL','ih-slider-showcase' ),
				    'section'  => 'showcase_sec'.$i,
				    'type'     => 'url',
				)
			);
			
		endfor;
	
    }
    
}