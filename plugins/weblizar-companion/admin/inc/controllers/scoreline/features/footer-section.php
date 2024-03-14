<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_footer_customizer {
	
	public static function wl_scoreline_footer_customizer( $wp_customize ) {
		
		/* Footer Options */
		$wp_customize->add_section(
			'footer_section',array(
				'title'=>__("Footer Options",WL_COMPANION_DOMAIN),
				'panel'=>'scoreline_theme_option',
				'capability'=>'edit_theme_options',
			    'priority' => 50,
			)
		);

		$wp_customize->add_setting(
			'scoreline_footer_customization',
			array(
				'default'			=>' © Copyright 2020. All Rights Reserved',
				'type'				=>'theme_mod',
				'sanitize_callback' =>'scoreline_sanitize_text',
				'capability'		=>'edit_theme_options'
			)
		);

		$wp_customize->add_control( 'scoreline_footer_customization', array(
			'label'      => __( 'Footer Customization Text', WL_COMPANION_DOMAIN ),
			'type'		 =>'text',
			'section'    => 'footer_section',
			'settings'   => 'scoreline_footer_customization'
		) );

		$wp_customize->selective_refresh->add_partial( 'scoreline_footer_customization', array(
			    'selector' => '.scoreline_footer_text',
		    ));

		$wp_customize->add_setting(
		'scoreline_develop_by',
			array(
			'default'			=>'',
			'type'				=>'theme_mod',
			'sanitize_callback' =>'scoreline_sanitize_text',
			'capability'		=>'edit_theme_options'
			)
		);

		$wp_customize->add_control( 
			'scoreline_develop_by', 
			array(
				'label'      => __( 'Footer developed by Text', WL_COMPANION_DOMAIN ),
				'type'		 =>'text',
				'section'    => 'footer_section',
				'settings'   => 'scoreline_develop_by'
			) 
		);

		$wp_customize->selective_refresh->add_partial( 'scoreline_develop_by', array(
			    'selector' => '.scoreline_footer_text a',
		    ));

		$wp_customize->add_setting(
		'scoreline_deve_link',
			array(
			'default'			=>'',
			'type'				=>'theme_mod',
			'capability'		=>'edit_theme_options',
			'sanitize_callback' =>'esc_url_raw'
			)
		);

		$wp_customize->add_control( 
			'scoreline_deve_linkk', 
			array(
				'label'      => __( 'Footer developed by link', WL_COMPANION_DOMAIN ),
				'type'		 =>'url',
				'section'    => 'footer_section',
				'settings'   => 'scoreline_deve_link'
			) 
		);
	}
}

?>