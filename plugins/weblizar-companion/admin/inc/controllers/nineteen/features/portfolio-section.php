<?php

defined( 'ABSPATH' ) or die();

/**
 *  Portfolio Section 
 */
class wl_portfolio_customizer {
	
	public static function wl_nineteen_portfolio_customizer( $wp_customize ) {

		$wp_customize->add_section(
	        'portfolio_sec',
	        array(
	            'title' 		  => __('Portfolio Options',WL_COMPANION_DOMAIN),
				'panel'			  => 'nineteen_theme_option',
	            'description' 	  => __('Here you can add your Portfolio',WL_COMPANION_DOMAIN),
				'capability'	  => 'edit_theme_options',
	            'priority' 		  => 36,
				'active_callback' => 'is_front_page',
	        )
	    );

	    $wp_customize->add_setting(
		'portfolio_home',
		array(
			'type'    => 'theme_mod',
			'default'=>1,
			'sanitize_callback'=>'nineteen_sanitize_checkbox',
			'capability' => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'nineteen_show_portfolio', array(
			'label'        => __( 'Enable Portfolio Section on Home', WL_COMPANION_DOMAIN ),
			'type'=>'checkbox',
			'section'    => 'portfolio_sec',
			'settings'   => 'portfolio_home'
		) );

	    $wp_customize->add_setting(
			'nineteen_portfolio_title',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_portfolio_title', array(
			'label'    => 'Portfolio section title',
			'type'     =>'text',
			'section'  => 'portfolio_sec',
			'settings' => 'nineteen_portfolio_title'
		) );

		$wp_customize->selective_refresh->add_partial( 'nineteen_portfolio_title', array(
				'selector' => '.our_portfolio .section-title',
			) );

		if ( class_exists( 'One_Page_Editor') ) {

			$wp_customize->add_setting(
				'nineteen_portfolio_desc',
				array(
					'type'              => 'theme_mod',
					'default'           => '',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'nineteen_sanitize_text'
				)
			);

			$wp_customize->add_control(new One_Page_Editor($wp_customize, 'nineteen_portfolio_desc', array(
				'label'                      => __( 'Portfolio Section Description', WL_COMPANION_DOMAIN ),
				'active_callback'            => 'show_on_front',
				'include_admin_print_footer' => true,
				'section'                    => 'portfolio_sec',
				'settings'                   => 'nineteen_portfolio_desc'
			) ));
			
			$wp_customize->selective_refresh->add_partial( 'nineteen_portfolio_desc', array(
				'selector' => '.our_portfolio .section-description',
			) );	
		}

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/functions/portfolio-functions.php' );
		if ( class_exists( 'nineteen_Customizer_portfolio_fields') ) {

			// logo height width //
			$wp_customize->add_setting(
				'nineteen_portfolio',
				array(
					'type'              => 'theme_mod',
					'default'           => 90,
					'sanitize_callback' => 'nineteen_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new nineteen_Customizer_portfolio_fields( $wp_customize, 'portfolio_arr', array(
			'type'        => 'text',
			'section'     => 'portfolio_sec',
			'settings'    => 'nineteen_portfolio',
			'label'       => __( 'Portfolio', WL_COMPANION_DOMAIN ),
			'description' => __( 'Here you can add all your Portfolio.', WL_COMPANION_DOMAIN ),
			)));
		}

		$wp_customize->add_setting(
			'nineteen_portfolio_data',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'nineteen_sanitize_text'
			)
		);
		$wp_customize->add_control( 'nineteen_portfolio_data', array(
			'label'    => '',
			'type'     =>'hidden',
			'section'  => 'portfolio_sec',
			'settings' => 'nineteen_portfolio_data'
		) );

	}
}

?>