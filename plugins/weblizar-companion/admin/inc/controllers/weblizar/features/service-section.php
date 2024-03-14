<?php

defined( 'ABSPATH' ) or die();

/**
 *  Service Section 
 */
class wl_service_customizer {
	
	public static function wl_wl_service_customizer( $wp_customize ) {

		/* Service Section */
		$wp_customize->add_section(
	        'service_sec',
	        array(
	            'title' 		  => __('Service Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'weblizar_theme_option',
	            'description' 	  => __('Here you can add your services',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'service_home',
		array(
			'type'    		   => 'theme_mod',
			'default'		   =>1,
			'sanitize_callback'=>'weblizar_sanitize_checkbox',
			'capability'       => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'service_home', array(
			'label'      => __( 'Enable service Section on Home', WL_COMPANION_DOMAIN ),
			'type'       =>'checkbox',
			'section'    => 'service_sec',
			'settings'   => 'service_home'
		) );

	    $wp_customize->add_setting(
			'weblizar_service_title',
			array(
				'type'              => 'theme_mod',
				'default'           => 'Our Service',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'weblizar_sanitize_text'
			)
		);
		$wp_customize->add_control( 'weblizar_service_title', array(
			'label'    => 'Service section title',
			'type'     =>'text',
			'section'  => 'service_sec',
			'settings' => 'weblizar_service_title'
		) );

		$wp_customize->selective_refresh->add_partial( 'weblizar_service_title', array(
				'selector' => '.weblizar_site_intro_title',
			) );

		$wp_customize->add_setting(
		'site_intro_text',
			array(
			'default'		   =>__("Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur.",WL_COMPANION_DOMAIN ),
			'capability'	   =>'edit_theme_options',
			'sanitize_callback'=>'weblizar_sanitize_text',
			)
		);

		$wp_customize->selective_refresh->add_partial( 'site_intro_text', array(
		'selector' 		  => '.weblizar_site_intro_text',
		) );

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/weblizar/functions/one-page-editor.php' );
		$wp_customize->add_control(new One_Page_Editor($wp_customize, 'site_intro_text', array(
			'label'                      => __( 'Service Section Description', WL_COMPANION_DOMAIN),
			'section'     				 => 'service_sec',
			'include_admin_print_footer' => true,
			'settings'   				 => 'site_intro_text',
		)
		) );

		
		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/weblizar/functions/service-functions.php' );
		if ( class_exists( 'weblizar_Customizer_service_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'weblizar_services',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'weblizar_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

		$wp_customize->add_control( new weblizar_Customizer_service_fields( $wp_customize, 'service_arr', array(
			'type'        => 'text',
			'section'     => 'service_sec',
			'settings'    => 'weblizar_services',
			'label'       => __( 'Services', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your services.', WL_COMPANION_DOMAIN ),
			)));
		}

		$wp_customize->add_setting(
			'weblizar_service_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'weblizar_sanitize_text'
			)
		);
		$wp_customize->add_control( 'weblizar_service_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'service_sec',
			'settings' => 'weblizar_service_data'
		) );

	}
}
?>