<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_header_footer_scripts_customizer {
	
	public static function wl_hfs_customizer( $wp_customize ) {
		/* Blog Option */
		$wp_customize->add_section('hfs_section',array(
		'title'=>__("Header and Footer scripts",WL_COMPANION_DOMAIN),
		'panel'=>'nineteen_theme_option',
		'capability'=>'edit_theme_options',
	    'priority' => 40
			)
		);

		$wp_customize->add_setting(
		'show_hs',
		array(
			'type'    => 'theme_mod',
			'default'=>0,
			'sanitize_callback'=>'nineteen_sanitize_checkbox',
			'capability' => 'edit_theme_options'
			)
		);

		$wp_customize->add_control( 'show_hs', array(
			'label'        => __( 'Enable Header Scripts', WL_COMPANION_DOMAIN ),
			'type'=>'checkbox',
			'section'    => 'hfs_section',
			'settings'   => 'show_hs'
			) 
		);

		$wp_customize->add_setting(
		'header_script',
			array(
			'default'=>'',
			'type'=>'theme_mod',
			'sanitize_callback'=>'nineteen_sanitize_js',
			'sanitize_js_callback'=>'nineteen_sanitize_js_output',
			'capability'=>'edit_theme_options'
			)
		);
		$wp_customize->add_control( 'header_script', array(
			'label'        => __( 'Header Script', WL_COMPANION_DOMAIN ),
			'type'=>'textarea',
			'section'    => 'hfs_section',
			'settings'   => 'header_script'
			) 
		);

		$wp_customize->add_setting(
		'show_fs',
		array(
			'type'    => 'theme_mod',
			'default'=>0,
			'sanitize_callback'=>'nineteen_sanitize_checkbox',
			'capability' => 'edit_theme_options'
			)
		);

		$wp_customize->add_control( 'show_fs', array(
			'label'        => __( 'Enable Footer Scripts', WL_COMPANION_DOMAIN ),
			'type'=>'checkbox',
			'section'    => 'hfs_section',
			'settings'   => 'show_fs'
			) 
		);
		$wp_customize->add_setting(
		'footer_script',
			array(
			'default'=>'',
			'type'=>'theme_mod',
			'sanitize_callback'=>'nineteen_sanitize_js',
			'sanitize_js_callback'=>'nineteen_sanitize_js_output',
			'capability'=>'edit_theme_options'
			)
		);
		$wp_customize->add_control( 'footer_script', array(
			'label'        => __( 'Footer Script', WL_COMPANION_DOMAIN ),
			'type'=>'textarea',
			'section'    => 'hfs_section',
			'settings'   => 'footer_script'
			) 
		);


	}
}

?>