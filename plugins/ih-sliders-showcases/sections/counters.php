<?php 
	
class IHSS_Counter extends IHSS {
	
	public function __construct() {
	    add_action( 'customize_register', array($this,'ihss_customize_counter') );
    }   
    
    public function ihss_customize_counter( $wp_customize) {
	    
	    $wp_customize->add_panel( 'counter_panel', array(
		    'priority'       => 55,
		    'capability'     => 'edit_theme_options',
		    'theme_supports' => '',
		    'title'          => __('IH Counters','ih-slider-showcase'),
		) );
		
		$wp_customize->add_section(
		    'sec_counter_options',
		    array(
		        'title'     => __('Enable/Disable','ih-slider-showcase'),
		        'priority'  => 0,
		        'panel'     => 'counter_panel'
		    )
		);
		
		
		$wp_customize->add_setting(
			'counter_enable',
			array( 'sanitize_callback' => 'sanitize_checkbox' )
		);
		
		$wp_customize->add_control(
				'counter_enable', array(
			    'settings' => 'counter_enable',
			    'label'    => __( 'Enable Counter on Static Front Page.', 'ih-slider-showcase' ),
			    'section'  => 'sec_counter_options',
			    'type'     => 'checkbox',
			)
		);
		
		$wp_customize->add_setting(
			'counter_title',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		
		$wp_customize->add_control(
				'counter_title', array(
			    'settings' => 'counter_title',
			    'label'    => __( 'Title','ih-slider-showcase' ),
			    'section'  => 'sec_counter_options',
			    'type'     => 'text',
			)
		);
		
		for ( $i = 1 ; $i <= 4 ; $i++ ) :
			
			//Create the settings Once, and Loop through it.
			$wp_customize->add_section(
			    'counter_sec'.$i,
			    array(
			        'title'     => __('Counter ','ih-slider-showcase').$i,
			        'priority'  => $i,
			        'panel'     => 'counter_panel',
			        
			    )
			);	
			
			$wp_customize->add_setting(
				'counter_title'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'counter_title'.$i, array(
				    'settings' => 'counter_title'.$i,
				    'label'    => __( 'Name of Statistic','ih-slider-showcase' ),
				    'section'  => 'counter_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			$wp_customize->add_setting(
				'counter_desc'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'counter_desc'.$i, array(
				    'settings' => 'counter_desc'.$i,
				    'label'    => __( 'Number or Stat','ih-slider-showcase' ),
				    'section'  => 'counter_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			
			$wp_customize->add_setting(
				'counter_url'.$i,
				array( 'sanitize_callback' => 'esc_url_raw' )
			);
			
			$wp_customize->add_control(
					'counter_url'.$i, array(
				    'settings' => 'counter_url'.$i,
				    'label'    => __( 'Target URL','ih-slider-showcase' ),
				    'section'  => 'counter_sec'.$i,
				    'type'     => 'url',
				)
			);
			
		endfor;
	
    }
    
}