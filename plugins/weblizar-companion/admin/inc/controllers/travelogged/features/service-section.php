<?php

defined( 'ABSPATH' ) or die();

/**
 *  Service Section 
 */
class wl_service_customizer {
	
	public static function wl_travelogged_service_customizer( $wp_customize ) {

		/* Service Section */
		$wp_customize->add_section(
	        'service_sec',
	        array(
	            'title' 		  => __('Service Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'theme_options',
	            'description' 	  => __('Here you can add your services',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'service_home',
		array(
			'type'    => 'theme_mod',
			'default'=>1,
			'sanitize_callback'=>'travelogged_sanitize_checkbox',
			'capability' => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'travelogged_show_service', array(
			'label'        => __( 'Enable service Section on Home', WL_COMPANION_DOMAIN ),
			'type'=>'checkbox',
			'section'    => 'service_sec',
			'settings'   => 'service_home'
		) );

	    $wp_customize->add_setting(
			'travelogged_service_title',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'travelogged_sanitize_text'
			)
		);
		$wp_customize->add_control( 'travelogged_service_title', array(
			'label'    => 'Service section title',
			'type'     =>'text',
			'section'  => 'service_sec',
			'settings' => 'travelogged_service_title'
		) );

		$wp_customize->selective_refresh->add_partial( 'travelogged_service_title', array(
				'selector' => '.our-services .section-title',
			) );

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/functions/one-page-editor.php' );
		if ( class_exists( 'One_Page_Editor') ) {

			$wp_customize->add_setting(
				'travelogged_service_desc',
				array(
					'type'              => 'theme_mod',
					'default'           => '',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'travelogged_sanitize_text'
				)
			);

			$wp_customize->add_control(new One_Page_Editor($wp_customize, 'travelogged_service_desc', array(
				'label'                      => __( 'Service Section Description', WL_COMPANION_DOMAIN ),
				'active_callback'            => 'show_on_front',
				'include_admin_print_footer' => true,
				'section'                    => 'service_sec',
				'settings'                   => 'travelogged_service_desc'
			) ));
			
			$wp_customize->selective_refresh->add_partial( 'travelogged_service_desc', array(
				'selector' => '.our-services .section-description',
			) );	
		}

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/travelogged/functions/service-functions.php' );
		if ( class_exists( 'travelogged_Customizer_service_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'travelogged_services',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'travelogged_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new travelogged_Customizer_service_fields( $wp_customize, 'service_arr', array(
			'type'        => 'text',
			'section'     => 'service_sec',
			'settings'    => 'travelogged_services',
			'label'       => __( 'Services', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your services.', WL_COMPANION_DOMAIN ),
			)));
		}

		$wp_customize->add_setting(
			'travelogged_service_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'travelogged_sanitize_text'
			)
		);
		$wp_customize->add_control( 'travelogged_service_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'service_sec',
			'settings' => 'travelogged_service_data'
		) );

	}
}

?>