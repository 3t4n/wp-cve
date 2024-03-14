<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_footer_customizer {
	
	public static function wl_nineteen_footer_customizer( $wp_customize ) {
		
		/* Footer Options */
		$wp_customize->add_section('footer_section',array(
		'title'=>__("Footer Options","nineteen"),
		'panel'=>'nineteen_theme_option',
		'capability'=>'edit_theme_options',
	    'priority' => 50,
		));

		$wp_customize->add_setting(
		'nineteen_footer_customization',
			array(
			'default'=>'Powered by WordPress',
			'type'=>'theme_mod',
			'sanitize_callback'=>'nineteen_sanitize_text',
			'capability'=>'edit_theme_options'
			)
		);
		$wp_customize->selective_refresh->add_partial( 'nineteen_footer_customization', array(
			'selector' => '.copy_nineteen',
		) );
		$wp_customize->add_control( 'nineteen_footer_customizationn', array(
			'label'        => __( 'Footer Customization Text', 'nineteen' ),
			'type'=>'text',
			'section'    => 'footer_section',
			'settings'   => 'nineteen_footer_customization'
		) );

		$wp_customize->add_setting(
		'nineteen_develop_by',
			array(
			'default'=>'',
			'type'=>'theme_mod',
			'sanitize_callback'=>'nineteen_sanitize_text',
			'capability'=>'edit_theme_options'
			)
		);
		$wp_customize->add_control( 'nineteen_develop_byy', array(
			'label'        => __( 'Footer developed by Text', 'nineteen' ),
			'type'=>'text',
			'section'    => 'footer_section',
			'settings'   => 'nineteen_develop_by'
		) );
		$wp_customize->add_setting(
		'nineteen_deve_link',
			array(
			'default'=>'',
			'type'=>'theme_mod',
			'capability'=>'edit_theme_options',
			'sanitize_callback'=>'esc_url_raw'
			)
		);
		$wp_customize->add_control( 'nineteen_deve_linkk', array(
			'label'        => __( 'Footer developed by link', 'nineteen' ),
			'type'=>'url',
			'section'    => 'footer_section',
			'settings'   => 'nineteen_deve_link'
		) );
	}
}

?>