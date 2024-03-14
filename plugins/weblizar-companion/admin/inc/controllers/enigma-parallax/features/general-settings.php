<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_general_customizer {
	
	public static function wl_enigma_parallax_general_customizer( $wp_customize ) {

		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.nav-brand .site-title',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description',
		) );
		$wp_customize->selective_refresh->add_partial( 'custom_logo', array(
			'selector' => '.navbar-brand img',
		) );

		$wp_customize->add_panel( 'enigma_parallax_theme_option', 
			array( 'title'      => esc_html__( 'Enigma Parallax Theme Options', WL_COMPANION_DOMAIN ), 
				   'priority'   => 1,
				   'capability' => 'edit_theme_options',
				)
		);

		// general Settings start
		$wp_customize->add_section('general_sec',	
			array( 'title'       => esc_html__( 'Theme General Options', WL_COMPANION_DOMAIN ),
				   'panel'       =>'enigma_parallax_theme_option',
		           'description' => esc_html__('Here you can manage General Options(Like:- Custom Css etc.)', WL_COMPANION_DOMAIN),
				   'capability'  =>'edit_theme_options',
		           'priority'    => 32,
	        ));

		require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/functions/general-functions.php' );
		if ( class_exists( 'enigma_Customizer_Range_Value_Control') ) {

			// logo height width //
			$wp_customize->add_setting(
				'logo_height',
				array(
					'type'              => 'theme_mod',
					'default'           => 55,
					'sanitize_callback' => 'enigma_parallax_sanitize_text',
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
					'sanitize_callback' => 'enigma_parallax_sanitize_text',
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

		/*navigation option*/
	$wp_customize->add_setting(
		'side_menu_option',
		array(
			'sanitize_callback' => 'enigma_parallax_sanitize_text',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => 'both_id',
		)
	);

	$wp_customize->add_control(
		'side_menu_option',
		array(
			'label'    => __( 'Display  sidemenu', WL_COMPANION_DOMAIN ),
			'type'     => 'select',
			'section'  => 'general_sec',
			'settings' => 'side_menu_option',
			'choices'  => array(
				'both'    => 'Main Menu With Side Menu',
				'both_id' => 'Main Menu + Side Menu(Default)',
				'side'    => 'Side',
				'side_id' => 'Side Menu(Internal Linked) ',
				'main'    => 'Main'
			),
		)
	);
		/*navigation option*/

		$wp_customize->add_setting(
			'sticky_header',
			array(
				'type'              => 'theme_mod',
				'default'           => '1',
				'sanitize_callback' => 'enigma_parallax_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'sticky_header', array(
			'label'    => __( 'Enable Sticky Header', WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'sticky_header',
		) );
		$wp_customize->add_setting(
			'title_position',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'sanitize_callback' => 'enigma_parallax_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'title_position', array(
			'label'    => __( 'Show Site Title in Center', WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'title_position',
		) );
		$wp_customize->add_setting(
			'search_box',
			array(
				'type'              => 'theme_mod',
				'default'           => '',
				'sanitize_callback' => 'enigma_parallax_sanitize_checkbox',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control( 'search_box', array(
			'label'    => __( 'Show Search Box', WL_COMPANION_DOMAIN ),
			'type'     => 'checkbox',
			'section'  => 'general_sec',
			'settings' => 'search_box',
		) );
		
		$wp_customize->add_setting(
			'page_header',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'enigma_parallax_sanitize_checkbox',
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
				'sanitize_callback' => 'enigma_parallax_sanitize_checkbox',
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

/*
		$wp_customize->add_setting(
		'color_title',
		array(
			//'type'    => 'option',
			'default'=>'default',
			'capability' => 'edit_theme_options',
			//'sanitize_callback'=>'enigma_sanitize_select',
		)
	);
   $wp_customize->add_control( 'color_title', array(
		'label'        => 'Color Scheme',
		'type'=>'select',
		'section'    => 'general_sec',
		'settings'   => 'color_title',
		'choices' => array(
			'default' => __( 'Default' ),
			'green' => __( 'Green' ),
			'orange' => __( 'Orange' ),
			'Blue' => __( 'Blue' ),   
			'Indigo' => __( 'Indigo' ),
			'Pink' => __( 'Pink' ),
			'Yellow' => __( 'Yellow' ),
			'Red' => __( 'Red' ),
 			),
	) ); */

       $wp_customize->add_setting('color_title',
        array(
            'default' => esc_html__('#666','enigma-parallax'),
           // 'sanitize_callback' => 'sanitize_hex_color'
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize,'color_title',array(
            'label' => esc_html__('Color Scheme','enigma-parallax'),           
            'description' => esc_html__('Change Theme Color','enigma-parallax'),
            'section' => 'general_sec',
            'settings' => 'color_title'
        ))
    ); 


  


		//sanitize callbacks
		function enigma_parallax_sanitize_text( $input ) {
	    	return wp_kses_post( force_balance_tags( $input ) );
		}
	 	function enigma_parallax_sanitize_checkbox( $input ) {
		   if ( $input == 1 ) {
				return 1 ;
			} else {
				return 0;
			}
		}
		function enigma_parallax_sanitize_integer( $input ) {
			return (int)($input);
		}
		function enigma_parallax_sanitize_js( $input ) {
			return base64_encode($input);
		}
		function enigma_parallax_sanitize_js_output( $input ) {
			return base64_decode($input);
		}
	}
}