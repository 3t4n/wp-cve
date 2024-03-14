<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_footer_customizer {
	
	public static function wl_bitstream_footer_customizer( $wp_customize ) {
		
		/* Footer Options */
		$wp_customize->add_section(
			'footer_section',array(
				'title'=>__("Footer Options","bitstream"),
				'panel'=>'bitstream_theme_option',
				'capability'=>'edit_theme_options',
			    'priority' => 50,
			)
		);

		$wp_customize->add_setting(
			'bitstream_footer_customization',
			array(
				'default'=>' © Copyright 2019. All Rights Reserved',
				'type'=>'theme_mod',
				'sanitize_callback'=>'bitstream_sanitize_text',
				'capability'=>'edit_theme_options'
			)
		);

		$wp_customize->add_control( 'bitstream_footer_customizationn', array(
			'label'        => __( 'Footer Customization Text', 'bitstream' ),
			'type'=>'text',
			'section'    => 'footer_section',
			'settings'   => 'bitstream_footer_customization'
		) );

		$wp_customize->add_setting(
		'bitstream_develop_by',
			array(
			'default'=>'',
			'type'=>'theme_mod',
			'sanitize_callback'=>'bitstream_sanitize_text',
			'capability'=>'edit_theme_options'
			)
		);

		$wp_customize->add_control( 
			'bitstream_develop_byy', 
			array(
				'label'        => __( 'Footer developed by Text', 'bitstream' ),
				'type'=>'text',
				'section'    => 'footer_section',
				'settings'   => 'bitstream_develop_by'
			) 
		);

		$wp_customize->add_setting(
		'bitstream_deve_link',
			array(
			'default'=>'',
			'type'=>'theme_mod',
			'capability'=>'edit_theme_options',
			'sanitize_callback'=>'esc_url_raw'
			)
		);

		$wp_customize->add_control( 
			'bitstream_deve_linkk', 
			array(
				'label'        => __( 'Footer developed by link', 'bitstream' ),
				'type'=>'url',
				'section'    => 'footer_section',
				'settings'   => 'bitstream_deve_link'
			) 
		);
	}
}

?>