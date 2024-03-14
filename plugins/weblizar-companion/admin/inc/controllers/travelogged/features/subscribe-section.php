<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_subscribe_customizer {
	
	public static function wl_travelogged_subscribe_customizer( $wp_customize ) {
		/* Subscribe Option */
		$wp_customize->add_section('subscribe_section',array(
		'title'      => __("Home Subscribe Options",WL_COMPANION_DOMAIN),
		'panel'      => 'theme_options',
		'capability' => 'edit_theme_options',
	    'priority'   => 36
		));
		$wp_customize->add_setting(
		'subscribe_home',
		array(
			'type'              => 'theme_mod',
			'default'           => 1,
			'sanitize_callback' => 'travelogged_sanitize_checkbox',
			'capability'        => 'edit_theme_options'
		)
		);
		$wp_customize->add_control( 'travelogged_show_subscribe', array(
			'label'    => __( 'Enable Subscribe on Home', WL_COMPANION_DOMAIN ),
			'type'     =>'checkbox',
			'section'  => 'subscribe_section',
			'settings' => 'subscribe_home'
		) );

		$wp_customize->add_setting(
		'travelogged_subscribe_title',
			array(
			'default'           => 'Subscribe',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'travelogged_sanitize_text',
			'capability'        => 'edit_theme_options'
			)
		);

		$wp_customize->add_control( 'travelogged_subscribe_title', array(
			'label'    =>  __( 'Home Subscribe Title', WL_COMPANION_DOMAIN ),
			'type'     => 'text',
			'section'  => 'subscribe_section',
			'settings' => 'travelogged_subscribe_title'
		) );

		$wp_customize->add_setting(
		'travelogged_subscribe_title_1',
			array(
			'default'           => 'FOR NEWSLETTER',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'travelogged_sanitize_text',
			'capability'        => 'edit_theme_options'
			)
		);

		$wp_customize->add_control( 'travelogged_subscribe_title_1', array(
			'label'    =>  __( 'Home Subscribe Subtitle', WL_COMPANION_DOMAIN ),
			'type'     => 'text',
			'section'  => 'subscribe_section',
			'settings' => 'travelogged_subscribe_title_1'
		) );

		$wp_customize->add_setting(
		'travelogged_subscribe_desc',
			array(
			'default'           => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'travelogged_sanitize_text',
			'capability'        => 'edit_theme_options'
			)
		);
		$wp_customize->add_control( 'travelogged_subscribe_desc', array(
			'label'    => __( 'Home Subscribe Description', WL_COMPANION_DOMAIN ),
			'type'     => 'textarea',
			'section'  => 'subscribe_section',
			'settings' => 'travelogged_subscribe_desc'
		) );

		$wp_customize->add_setting(
		'travelogged_subscribe_btntext',
			array(
			'default'           => 'Subscribe',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'travelogged_sanitize_text',
			'capability'        => 'edit_theme_options'
			)
		);
		$wp_customize->add_control( 'travelogged_subscribe_btntext', array(
			'label'    =>  __( 'Subscribe Button text', WL_COMPANION_DOMAIN ),
			'type'     => 'text',
			'section'  => 'subscribe_section',
			'settings' => 'travelogged_subscribe_btntext'
		) );
	}
}

?>