<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_footer_customizer {
	
	public static function wl_wl_footer_customizer( $wp_customize ) {
		
		/* Footer Options */
		$wp_customize->add_section(
			'footer_section',array(
				'title'=>__("Footer Options",WL_COMPANION_DOMAIN),
				'panel'=>'weblizar_theme_option',
				'capability'=>'edit_theme_options',
			    'priority' => 50,
			)
		);

		$wp_customize->add_setting(
			'weblizar_footer_customization',
			array(
				'default'			=>' © Copyright 2020. All Rights Reserved',
				'type'				=>'theme_mod',
				'sanitize_callback' =>'weblizar_sanitize_text',
				'capability'		=>'edit_theme_options'
			)
		);

		$wp_customize->add_control( 'weblizar_footer_customization', array(
			'label'      => __( 'Footer Customization Text', WL_COMPANION_DOMAIN ),
			'type'		 =>'text',
			'section'    => 'footer_section',
			'settings'   => 'weblizar_footer_customization'
		) );

		$wp_customize->selective_refresh->add_partial( 'weblizar_footer_customizationn', array(
			'selector' => '.weblizar_footer_customizations',
		) );

		$wp_customize->add_setting(
		'weblizar_develop_by',
			array(
			'default'			=>'',
			'type'				=>'theme_mod',
			'sanitize_callback' =>'weblizar_sanitize_text',
			'capability'		=>'edit_theme_options'
			)
		);

		$wp_customize->add_control( 
			'weblizar_develop_byy', 
			array(
				'label'      => __( 'Footer developed by Text', WL_COMPANION_DOMAIN ),
				'type'		 =>'text',
				'section'    => 'footer_section',
				'settings'   => 'weblizar_develop_by'
			) 
		);

		$wp_customize->add_setting(
		'weblizar_deve_link',
			array(
			'default'			=>'',
			'type'				=>'theme_mod',
			'capability'		=>'edit_theme_options',
			'sanitize_callback' =>'esc_url_raw'
			)
		);

		$wp_customize->add_control( 
			'weblizar_deve_linkk', 
			array(
				'label'      => __( 'Footer developed by link', WL_COMPANION_DOMAIN ),
				'type'		 =>'url',
				'section'    => 'footer_section',
				'settings'   => 'weblizar_deve_link'
			) 
		);
	}
}

?>