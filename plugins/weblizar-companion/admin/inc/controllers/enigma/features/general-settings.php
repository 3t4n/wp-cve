<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_general_customizer {
	
	public static function wl_enigma_general_customizer( $wp_customize ) {

		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.nav-brand .site-title',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'' => '.site-description',
		) );
		$wp_customize->selective_refresh->add_partial( 'custom_logo', array(
			'selector' => '.navbar-brand img',
		) );

		$wp_customize->add_panel( 'enigma_theme_option', 
			array( 'title'      => esc_html__( 'Enigma Theme Options', WL_COMPANION_DOMAIN ), 
				   'priority'   => 1,
				   'capability' => 'edit_theme_options',
				)
		);

		// general Settings start
		$wp_customize->add_section('general_sec',	
			array( 'title'       => esc_html__( 'Theme General Options', WL_COMPANION_DOMAIN ),
				   'panel'       =>'enigma_theme_option',
		           'description' => esc_html__('Here you can manage General Options(Like:- Custom Css etc.)', WL_COMPANION_DOMAIN),
				   'capability'  =>'edit_theme_options',
		           'priority'    => 32,
	        ));

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/functions/general-functions.php' );
		if ( class_exists( 'enigma_Customizer_Range_Value_Control') ) {

			// logo height width //
			$wp_customize->add_setting(
				'logo_height',
				array(
					'type'              => 'theme_mod',
					'default'           => isset( $logo_height ) ? $logo_height : '48',
					'sanitize_callback' => 'enigma_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);

			$wp_customize->add_control( new enigma_Customizer_Range_Value_Control( $wp_customize, 'logo_height', array(
					'type'        => 'range-value',
					'section'     => 'general_sec',
					'settings'    => 'logo_height',
					'label'       => __( 'Logo Height', WL_COMPANION_DOMAIN ),
					'input_attrs' => array(
						'min'     => 1,
						'max'     => 500,
						'step'    => 1,
						'suffix'  => 'px', //optional suffix
				  	),
				)
			));
			
			$wp_customize->add_setting(
				'logo_width',
				array(
					'type'              => 'theme_mod',
					'default'           => 150,
					'sanitize_callback' => 'enigma_sanitize_text',
					'capability'        => 'edit_theme_options',
				)
			);	

			$wp_customize->add_control( new enigma_Customizer_Range_Value_Control( $wp_customize, 'logo_width', array(
				'type'        => 'range-value',
				'section'     => 'general_sec',
				'settings'    => 'logo_width',
				'label'       => __('Logo Width', WL_COMPANION_DOMAIN ),
				'input_attrs' => array(
					'min'     => 1,
					'max'     => 310,
					'step'    => 1,
					'suffix'  => 'px', //optional suffix
			  	),
			)));

			// logo height width //
		}

		$wp_customize->add_setting(
			'title_position',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'sanitize_callback' => 'enigma_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'title_position', array(
			'label'    => __( 'Show Site Title in Center', WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'title_position',
		) );
		
		$title_position = get_option('theme_mods_enigma');
		$title_position = isset($title_position['breadcrumb']) ? ($title_position['breadcrumb']) : null ;
		if(!$title_position){
		    $title_position = 1;
		}

		$wp_customize->add_setting(
			'page_header',
			array(
				'type'              => 'theme_mod',
				'default'           => $title_position,
				'sanitize_callback' => 'enigma_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'page_header', array(
			'label'    => __( 'Enable Page Header',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'page_header',
		) );
		
		//scroll up button option
		$wp_customize->add_setting(
			'enigma_return_top',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'enigma_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'enigma_return_top', array(
			'label'    => __( 'Enable scroll up button for the site',  WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'enigma_return_top',
		) );

		$wp_customize->selective_refresh->add_partial( 'enigma_return_top', array(
			'selector' => 'a#btn-to-top',
		) );

        
 /*color Section */
 /*$wp_customize->add_panel( 'color_option', array(
	   'title' => 'Theme color options',
	   'description' =>'', 
   'priority' => 10, 
	) );	
	   $wp_customize->add_section(
         'color_section',
         array(
            'title' => 'Color Section',
             'description' => 'Here you can add about title,description and even about',
			// 'panel'=>'color_option',
			// 'capability'=>'edit_theme_options',
             'priority' => 5,
         )
    );	*/

    $wp_customize->add_setting('enigma_color_scheme',
        array(
            'default' => esc_html__('#31A3DD','enigma'),
            'sanitize_callback' => 'sanitize_hex_color'
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize,'enigma_color_scheme',array(
            'label' => esc_html__('Color Scheme','enigma'),           
            'description' => esc_html__('Change Theme Color','enigma'),
            'section' => 'general_sec',
            'settings' => 'enigma_color_scheme'
        ))
    );  


	/*$wp_customize->add_setting(
		'color_title',
		array(
			//'type'    => 'option',
			'default'=>'default',
			'capability' => 'edit_theme_options',
			//'sanitize_callback'=>'enigma_sanitize_select',
		)
	);
   $wp_customize->add_control( 'color_title', array(
		'label'        => 'color title',
		'type'=>'select',
		'section'    => 'general_sec',
		'settings'   => 'color_title',
		'choices' => array(
			'default' => __( 'default' ),
			'green' => __( 'green' ),
		  ),
	) );*/

		//sanitize callbacks
		function enigma_sanitize_text( $input ) {
	    	return wp_kses_post( force_balance_tags( $input ) );
		}
	 	function enigma_sanitize_checkbox( $input ) {
		   if ( $input == 1 ) {
				return 1 ;
			} else {
				return 0;
			}
		}
		function enigma_sanitize_integer( $input ) {
			return (int)($input);
		}
		function enigma_sanitize_js( $input ) {
			return base64_encode($input);
		}
		function enigma_sanitize_js_output( $input ) {
			return base64_decode($input);
		}
	}
}