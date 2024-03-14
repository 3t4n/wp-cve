<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_topheader_customizer {
	
	public static function wl_bitstream_topheader_customizer( $wp_customize ) {

		/* top header Option */
		$wp_customize->add_section(
			'topheader_section',
			array(
				'title'      => __("Top Header Sections",WL_COMPANION_DOMAIN),
				'panel'      => 'bitstream_theme_option',
				'capability' => 'edit_theme_options',
			    'priority'   => 36
			)
		);

		$wp_customize->add_setting(
			'bitstream_show_topheader',
			array(
				'type'              => 'theme_mod',
				'default'           => 1,
				'sanitize_callback' => 'bitstream_sanitize_checkbox',
				'capability'        => 'edit_theme_options'
			)
		);
		$wp_customize->add_control( 
			'bitstream_show_topheader', 
			array(
				'label'    => __( 'Enable Contact Bar', WL_COMPANION_DOMAIN ),
				'type'     =>'checkbox',
				'section'  => 'topheader_section',
				'settings' => 'bitstream_show_topheader'
			) 
		);

		$wp_customize->add_setting(
			'bitstream_topheader_timing',
			array(
				'default'           => '',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'bitstream_sanitize_text',
				'capability'        => 'edit_theme_options'
			)
		);

		$wp_customize->add_control( 
			'bitstream_topheader_timing', 
			array(
				'label'    =>  __( 'Timing', WL_COMPANION_DOMAIN ),
				'type'     => 'text',
				'section'  => 'topheader_section',
				'settings' => 'bitstream_topheader_timing'
			) 
		);

		$wp_customize->selective_refresh->add_partial( 'bitstream_topheader_timing', array(
			'selector' => '.open_time',
		) );


		$wp_customize->add_setting(
			'bitstream_topheader_ctitle',
			array(
				'default'           => 'Call Us',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'bitstream_sanitize_text',
				'capability'        => 'edit_theme_options'
			)
		);

		$wp_customize->add_control( 
			'bitstream_topheader_ctitle', 
			array(
				'label'    =>  __( 'Contact Title', WL_COMPANION_DOMAIN ),
				'type'     => 'text',
				'section'  => 'topheader_section',
				'settings' => 'bitstream_topheader_ctitle'
			) 
		);

		$wp_customize->selective_refresh->add_partial( 'bitstream_topheader_ctitle', array(
			'selector' => '.info_call .info_label',
		) );

		$wp_customize->add_setting(
		'bitstream_topheader_cnumber',
			array(
			'default'           => '',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'bitstream_sanitize_text',
			'capability'        => 'edit_theme_options'
			)
		);

		$wp_customize->add_control( 
			'bitstream_topheader_cnumber',
			 array(
				'label'    => __( 'Contact Number', WL_COMPANION_DOMAIN ),
				'type'     => 'text',
				'section'  => 'topheader_section',
				'settings' => 'bitstream_topheader_cnumber'
			) 
		);

		$wp_customize->selective_refresh->add_partial( 'bitstream_topheader_cnumber', array(
			'selector' => '.info_call span',
		) );

		$wp_customize->add_setting(
		'bitstream_topheader_emailtitle',
			array(
			'default'           => 'Email Us',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'bitstream_sanitize_text',
			'capability'        => 'edit_theme_options'
			)
		);

		$wp_customize->add_control( 
			'bitstream_topheader_emailtitle', 
			array(
				'label'    =>  __( 'Email Title', WL_COMPANION_DOMAIN ),
				'type'     => 'text',
				'section'  => 'topheader_section',
				'settings' => 'bitstream_topheader_emailtitle'
			) 
		);

		$wp_customize->selective_refresh->add_partial( 'bitstream_topheader_emailtitle', array(
			'selector' => '.info_email .info_label',
		) );


		$wp_customize->add_setting(
		'bitstream_topheader_email',
			array(
			'default'           => '',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'bitstream_sanitize_text',
			'capability'        => 'edit_theme_options'
			)
		);

		$wp_customize->add_control( 
			'bitstream_topheader_email', 
			array(
				'label'    =>  __( 'Email ID', WL_COMPANION_DOMAIN ),
				'type'     => 'text',
				'section'  => 'topheader_section',
				'settings' => 'bitstream_topheader_email'
			) 
		);

		$wp_customize->selective_refresh->add_partial( 'bitstream_topheader_email', array(
			'selector' => '.info_email span',
		) );
	}
}

?>