<?php 
	
class IHSS_Team extends IHSS {
	
	public function __construct() {
	    add_action( 'customize_register', array($this,'ihss_customize_team') );
    }   
    
    public function ihss_customize_team( $wp_customize) {
	    
	    $wp_customize->add_panel( 'team_panel', array(
		    'priority'       => 55,
		    'capability'     => 'edit_theme_options',
		    'theme_supports' => '',
		    'title'          => __('IH Team','ih-slider-showcase'),
		) );
		
		$wp_customize->add_section(
		    'sec_team_options',
		    array(
		        'title'     => __('Enable/Disable','ih-slider-showcase'),
		        'priority'  => 0,
		        'panel'     => 'team_panel'
		    )
		);
		
		
		$wp_customize->add_setting(
			'team_enable',
			array( 'sanitize_callback' => 'sanitize_checkbox' )
		);
		
		$wp_customize->add_control(
				'team_enable', array(
			    'settings' => 'team_enable',
			    'label'    => __( 'Enable team on Static Front Page.', 'ih-slider-showcase' ),
			    'section'  => 'sec_team_options',
			    'type'     => 'checkbox',
			)
		);
		
		$wp_customize->add_setting(
			'team_title',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		
		$wp_customize->add_control(
				'team_title', array(
			    'settings' => 'team_title',
			    'label'    => __( 'Title','ih-slider-showcase' ),
			    'section'  => 'sec_team_options',
			    'type'     => 'text',
			)
		);
		
		for ( $i = 1 ; $i <= 4 ; $i++ ) :
			
			//Create the settings Once, and Loop through it.
			$wp_customize->add_section(
			    'team_sec'.$i,
			    array(
			        'title'     => __('Team Member ','ih-slider-showcase').$i,
			        'priority'  => $i,
			        'panel'     => 'team_panel',
			        
			    )
			);	
			
			$wp_customize->add_setting(
				'team_img'.$i,
				array( 'sanitize_callback' => 'esc_url_raw' )
			);
			
			$wp_customize->add_control(
			    new WP_Customize_Image_Control(
			        $wp_customize,
			        'team_img'.$i,
			        array(
			            'label' => '',
			            'section' => 'team_sec'.$i,
			            'settings' => 'team_img'.$i,			       
			        )
				)
			);
			
			$wp_customize->add_setting(
				'team_title'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'team_title'.$i, array(
				    'settings' => 'team_title'.$i,
				    'label'    => __( 'Author','ih-slider-showcase' ),
				    'section'  => 'team_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			$wp_customize->add_setting(
				'team_desc'.$i,
				array( 'sanitize_callback' => 'sanitize_text_field' )
			);
			
			$wp_customize->add_control(
					'team_desc'.$i, array(
				    'settings' => 'team_desc'.$i,
				    'label'    => __( 'team Content','ih-slider-showcase' ),
				    'section'  => 'team_sec'.$i,
				    'type'     => 'text',
				)
			);
			
			
			$wp_customize->add_setting(
				'team_url'.$i,
				array( 'sanitize_callback' => 'esc_url_raw' )
			);
			
			$wp_customize->add_control(
					'team_url'.$i, array(
				    'settings' => 'team_url'.$i,
				    'label'    => __( 'Target URL','ih-slider-showcase' ),
				    'section'  => 'team_sec'.$i,
				    'type'     => 'url',
				)
			);
			
		endfor;
	
    }
    
}