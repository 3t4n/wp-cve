<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_footer_customizer {
	
	public static function wl_digicrew_footer_customizer( $wp_customize ) {
		
		/* Footer Options */
		$wp_customize->add_section(
			'footer_section',array(
				'title'=>__("Footer Options",WL_COMPANION_DOMAIN),
				'panel'=>'digicrew_theme_option',
				'capability'=>'edit_theme_options',
			    'priority' => 50,
			)
		);

		$wp_customize->add_setting(
			'footer_customizations',
			array(
				'default'			=>__(' © Copyright 2020. All Rights Reserved',WL_COMPANION_DOMAIN ),
				'type'				=>'theme_mod',
				'sanitize_callback' =>'digicrew_sanitize_text',
				'capability'		=>'edit_theme_options'
			)
		);

		$wp_customize->add_control( 'footer_customizations', array(
			'label'      => __( 'Footer Customization Text', WL_COMPANION_DOMAIN ),
			'type'		 =>'text',
			'section'    => 'footer_section',
			'settings'   => 'footer_customizations'
		) );

		$wp_customize->selective_refresh->add_partial( 'footer_customizations', array(
			    'selector' => '.digicrew_footer_area p',
		    ));

		$wp_customize->add_setting(
	    'developed_by_text',
	    array(
		    'default'           => __( 'Developed By', WL_COMPANION_DOMAIN ),
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'digicrew_sanitize_text',
		    'capability'        => 'edit_theme_options'
	    )
    );
    $wp_customize->add_control( 'developed_by_text', array(
	    'label'    => __( 'Developed By Text', WL_COMPANION_DOMAIN ),
	    'type'     => 'text',
	    'section'  => 'footer_section',
	    'settings' => 'developed_by_text'
    ) );

		$wp_customize->add_setting(
		'developed_by_weblizar_text',
			array(
			'default'			=>'',
			'type'				=>'theme_mod',
			'sanitize_callback' =>'digicrew_sanitize_text',
			'capability'		=>'edit_theme_options'
			)
		);

		$wp_customize->add_control( 
			'developed_by_weblizar_text', 
			array(
				'label'      => __( 'Footer developed by Link Text', WL_COMPANION_DOMAIN ),
				'type'		 =>'text',
				'section'    => 'footer_section',
				'settings'   => 'developed_by_weblizar_text'
			) 
		);

		$wp_customize->add_setting(
		'developed_by_link',
			array(
			'default'			=>'',
			'type'				=>'theme_mod',
			'capability'		=>'edit_theme_options',
			'sanitize_callback' =>'esc_url_raw'
			)
		);

		$wp_customize->add_control( 
			'developed_by_link', 
			array(
				'label'      => __( 'Footer developed by link', WL_COMPANION_DOMAIN ),
				'type'		 =>'url',
				'section'    => 'footer_section',
				'settings'   => 'developed_by_link'
			) 
		);
	}
}

?>