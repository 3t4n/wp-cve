<?php

defined( 'ABSPATH' ) or die();

/**
 *  Client Section 
 */
class wl_client_customizer {
	
	public static function wl_nineteen_client_customizer( $wp_customize ) {

		$wp_customize->add_section(
	        'client_sec',
	        array(
	            'title' 		  => __('Client Options', WL_COMPANION_DOMAIN),
				'panel'			  => 'nineteen_theme_option',
	            'description' 	  => __('Here you can add your Clients', WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'client_home',
		array(
			'type'    => 'theme_mod',
			'default'=>1,
			'sanitize_callback'=>'nineteen_sanitize_checkbox',
			'capability' => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'nineteen_show_client', array(
			'label'        => __( 'Enable Client Section on Home', WL_COMPANION_DOMAIN ),
			'type'=>'checkbox',
			'section'    => 'client_sec',
			'settings'   => 'client_home'
		) );

	    $wp_customize->add_setting(
			'nineteen_client_title',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_client_title', array(
			'label'    => 'Client section title',
			'type'     =>'text',
			'section'  => 'client_sec',
			'settings' => 'nineteen_client_title'
		) );
		$wp_customize->selective_refresh->add_partial( 'nineteen_client_title', array(
				'selector' => '.our-clientsss .section-title',
			) );
		
		if ( class_exists( 'One_Page_Editor') ) {

			$wp_customize->add_setting(
				'nineteen_client_desc',
				array(
					'type'              => 'theme_mod',
					'default'           => '',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'nineteen_sanitize_text'
				)
			);

			$wp_customize->add_control(new One_Page_Editor($wp_customize, 'nineteen_client_desc', array(
				'label'                      => __( 'Client Section Description', WL_COMPANION_DOMAIN ),
				'active_callback'            => 'show_on_front',
				'include_admin_print_footer' => true,
				'section'                    => 'client_sec',
				'settings'                   => 'nineteen_client_desc'
			) ));
			
			$wp_customize->selective_refresh->add_partial( 'nineteen_client_desc', array(
				'selector' => '.our-clientsss .section-description',
			) );	
		}

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/functions/client-functions.php' );
		if ( class_exists( 'nineteen_Customizer_client_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'nineteen_clients',
				array(
					'type'              => 'theme_mod',
					'default'           => '',
					'sanitize_callback' => 'nineteen_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new nineteen_Customizer_client_fields( $wp_customize, 'client_arr', array(
			'type'        => 'text',
			'section'     => 'client_sec',
			'settings'    => 'nineteen_clients',
			'label'       => __( 'Clients', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Clients.', WL_COMPANION_DOMAIN ),
			)));
		}

		$wp_customize->add_setting(
			'nineteen_client_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_client_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'client_sec',
			'settings' => 'nineteen_client_data'
		) );

	}
}

?>