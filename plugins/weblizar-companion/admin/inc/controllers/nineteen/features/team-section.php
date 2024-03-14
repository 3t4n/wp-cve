<?php

defined( 'ABSPATH' ) or die();

/**
 *  Team Section 
 */
class wl_team_customizer {
	
	public static function wl_nineteen_team_customizer( $wp_customize ) {

		$wp_customize->add_section(
	        'team_sec',
	        array(
	            'title' 		  => __('Team Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'nineteen_theme_option',
	            'description' 	  => __('Here you can add you Team',WL_COMPANION_DOMAIN),
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
			'sanitize_callback'=>'nineteen_sanitize_checkbox',
			'capability' => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'nineteen_show_team', array(
			'label'        => __( 'Enable Team Section on Home', WL_COMPANION_DOMAIN ),
			'type'=>'checkbox',
			'section'    => 'team_sec',
			'settings'   => 'team_home'
		) );

	    $wp_customize->add_setting(
			'nineteen_team_title',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_team_title', array(
			'label'    => 'Team section title',
			'type'     =>'text',
			'section'  => 'team_sec',
			'settings' => 'nineteen_team_title'
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_team_title', array(
				'selector' => '.our-team .section-title',
			) );

		if ( class_exists( 'One_Page_Editor') ) {

			$wp_customize->add_setting(
				'nineteen_team_desc',
				array(
					'type'              => 'theme_mod',
					'default'           => '',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'nineteen_sanitize_text'
				)
			);

			$wp_customize->add_control(new One_Page_Editor($wp_customize, 'nineteen_team_desc', array(
				'label'                      => __( 'Team Section Description', WL_COMPANION_DOMAIN ),
				'active_callback'            => 'show_on_front',
				'include_admin_print_footer' => true,
				'section'                    => 'team_sec',
				'settings'                   => 'nineteen_team_desc'
			) ));
			
			$wp_customize->selective_refresh->add_partial( 'nineteen_team_desc', array(
				'selector' => '.our-team .section-description',
			) );	
		}

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/functions/team-functions.php' );
		if ( class_exists( 'nineteen_Customizer_team_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'nineteen_teams',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'nineteen_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new nineteen_Customizer_team_fields( $wp_customize, 'team_arr', array(
			'type'        => 'text',
			'section'     => 'team_sec',
			'settings'    => 'nineteen_teams',
			'label'       => __( 'Team', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Team members.', WL_COMPANION_DOMAIN ),
			)));
		}
		$wp_customize->add_setting(
			'nineteen_team_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_team_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'team_sec',
			'settings' => 'nineteen_team_data'
		) );


	}
}

?>