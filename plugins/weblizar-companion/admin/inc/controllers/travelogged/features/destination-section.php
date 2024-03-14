<?php

defined( 'ABSPATH' ) or die();

/**
 *  Destination Section 
 */
class wl_destination_customizer {
	
	public static function wl_travelogged_destination_customizer( $wp_customize ) {

		$wp_customize->add_section(
	        'destination_sec',
	        array(
	            'title' 		  => __('Destination Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'theme_options',
	            'description' 	  => __('Here you can add you Destination',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'destination_home',
		array(
			'type'             => 'theme_mod',
			'default'          => 1,
			'sanitize_callback'=>'travelogged_sanitize_checkbox',
			'capability'       => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'travelogged_show_destination', array(
			'label'    => __( 'Enable Destination Section on Home', WL_COMPANION_DOMAIN ),
			'type'     =>'checkbox',
			'section'  => 'destination_sec',
			'settings' => 'destination_home'
		) );

	    $wp_customize->add_setting(
			'travelogged_destination_title',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'travelogged_sanitize_text'
			)
		);
		$wp_customize->add_control( 'travelogged_destination_title', array(
			'label'    => 'Destination section title',
			'type'     => 'text',
			'section'  => 'destination_sec',
			'settings' => 'travelogged_destination_title'
		) );

		$wp_customize->selective_refresh->add_partial( 'travelogged_destination_title', array(
				'selector' => '.our-destination .section-title',
			) );

		if ( class_exists( 'One_Page_Editor') ) {

			$wp_customize->add_setting(
				'travelogged_destination_desc',
				array(
					'type'              => 'theme_mod',
					'default'           => '',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'travelogged_sanitize_text'
				)
			);

			$wp_customize->add_control(new One_Page_Editor($wp_customize, 'travelogged_destination_desc', array(
				'label'                      => __( 'Destination Section Description', WL_COMPANION_DOMAIN ),
				'active_callback'            => 'show_on_front',
				'include_admin_print_footer' => true,
				'section'                    => 'destination_sec',
				'settings'                   => 'travelogged_destination_desc'
			) ));
			
			$wp_customize->selective_refresh->add_partial( 'travelogged_destination_desc', array(
				'selector' => '.our-destination .section-description',
			) );	
		}

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/travelogged/functions/destination-functions.php' );
		if ( class_exists( 'travelogged_Customizer_destination_fields_new') ) {

			// logo height width //
			$wp_customize->add_setting(
				'travelogged_destinations',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'travelogged_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new travelogged_Customizer_destination_fields_new( $wp_customize, 'destination_arr', array(
			'type'        => 'text',
			'section'     => 'destination_sec',
			'settings'    => 'travelogged_destinations',
			'label'       => __( 'Destination', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Destination members.', WL_COMPANION_DOMAIN ),
			)));
		}
		$wp_customize->add_setting(
			'travelogged_destination_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'travelogged_sanitize_text'
			)
		);
		$wp_customize->add_control( 'travelogged_destination_data', array(
			'label'    => '',
			'type'     => 'hidden',
			'section'  => 'destination_sec',
			'settings' => 'travelogged_destination_data'
		) );


	}
}

?>