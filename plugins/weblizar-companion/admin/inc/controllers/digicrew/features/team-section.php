<?php

defined( 'ABSPATH' ) or die();

/**
 *  Team Section 
 */
class wl_team_customizer_new {
	
	public static function wl_digicrew_team_customizer_new( $wp_customize ) {

		$wp_customize->add_section(
	        'team_sec',
	        array(
	            'title' 		  => __('Team Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'digicrew_theme_option',
	            'description' 	  => __('Here you can add you Team',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 38,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'team_home',
		array(
			'type'              => 'theme_mod',
			'default'           => 1,
			'sanitize_callback' =>'digicrew_sanitize_checkbox',
			'capability'        => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'team_home', array(
			'label'        => __( 'Enable Team Section on Home', WL_COMPANION_DOMAIN ),
			'type'=>'checkbox',
			'section'    => 'team_sec',
			'settings'   => 'team_home'
		) );

	    $wp_customize->add_setting(
			'travel_team_title',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'digicrew_sanitize_text'
			)
		);
		$wp_customize->add_control( 'travel_team_title', array(
			'label'    => 'Team section title',
			'type'     => 'text',
			'section'  => 'team_sec',
			'settings' => 'travel_team_title'
		) );

		$wp_customize->selective_refresh->add_partial( 'digicrew_team_title', array(
				'selector' => '.main-title-two h2',
			) );

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/functions/team-functions.php' );
		if ( class_exists( 'digicrew_Customizer_team_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'digicrew_teams',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'digicrew_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new digicrew_Customizer_team_fields( $wp_customize, 'team_arr', array(
			'type'        => 'text',
			'section'     => 'team_sec',
			'settings'    => 'digicrew_teams',
			'label'       => __( 'Team', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Team members.', WL_COMPANION_DOMAIN ),
			)));
		}

		$wp_customize->add_setting(
			'digicrew_team_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'digicrew_sanitize_text'
			)
		);
		$wp_customize->add_control( 'digicrew_team_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'team_sec',
			'settings' => 'digicrew_team_data'
		) );


	}
}

?>