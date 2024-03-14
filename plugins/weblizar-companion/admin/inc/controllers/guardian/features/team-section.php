<?php

defined( 'ABSPATH' ) or die();

/**
 *  Team Section 
 */
class wl_team_customizer {
	
	public static function wl_guardian_team_customizer( $wp_customize ) {

		/* Team Section */
		$wp_customize->add_section(
	        'team_sec',
	        array(
	            'title' 		  => __('Team Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'guardian_theme_option',
	            'description' 	  => __('Here you can add your services',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'team_home',
		array(
			'type'    => 'theme_mod',
			'default'=>1,
			'sanitize_callback'=>'guardian_sanitize_checkbox',
			'capability' => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'team_home', array(
			'label'      => __( 'Enable team Section on Home', WL_COMPANION_DOMAIN ),
			'type'       =>'checkbox',
			'section'    => 'team_sec',
			'settings'   => 'team_home'
		) );

	    $wp_customize->add_setting(
			'guardian_team_title',
			array(
				'type'              => 'theme_mod',
				'default'           => 'Our Team',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'guardian_sanitize_text'
			)
		);
		$wp_customize->add_control( 'guardian_team_title', array(
			'label'    => 'Team section title',
			'type'     =>'text',
			'section'  => 'team_sec',
			'settings' => 'guardian_team_title'
		) );

		$wp_customize->selective_refresh->add_partial( 'guardian_team_title', array(
				'selector' => '.text-center h2',
			) );

		
		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/functions/team-functions.php' );
		if ( class_exists( 'guardian_Customizer_team_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'guardian_team',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'guardian_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

		$wp_customize->add_control( new guardian_Customizer_team_fields( $wp_customize, 'guardian_team', array(
			'type'        => 'text',
			'section'     => 'team_sec',
			'settings'    => 'guardian_team',
			'label'       => __( 'Team', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your team.', WL_COMPANION_DOMAIN ),
			)));
		}

		$wp_customize->add_setting(
			'guardian_team_data',
			array(
				'type'              => 'theme_mod',
				'default'           => serialize( array(
            /*Repeater's first item*/
            array(
				'team_name' => 'Maria Rosi',
				'team_desc'      => 'Business Plann Expert',
				'team_image'       => get_template_directory_uri().'/images/team1.png' ,
				),
            /*Repeater's second item*/
            array(
				'team_name' => 'Maria Rosi',
				'team_desc'      => 'Business Plann Expert',
				'team_image'       => get_template_directory_uri().'/images/team2.png' ,
				),
            /*Repeater's third item*/
            array(
				'team_name' => 'Maria Rosi',
				'team_desc'      => 'Business Plann Expert',
				'team_image'       => get_template_directory_uri().'/images/team3.png' ,
				),
            ) ),
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'guardian_sanitize_text'
			)
		);
		
		$wp_customize->add_control( 'guardian_team_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'team_sec',
			'settings' => 'guardian_team_data'
		) );

	}
}

?>