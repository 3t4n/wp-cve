<?php

defined( 'ABSPATH' ) or die();

/**
 *  Typography options
 */
class wl_typography7_customizer {
	
	
	
	public static function wl_is_medify_typography7_customizer( $wp_customize ) {
		
	
	require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/is-medify/functions/typography-functions.php' );
	
	
	/* Font Family Section */
    $wp_customize->add_section( 'font_section', array(
	    'title'      => __( 'Typography Settings', 'is-medify' ),
	    'panel'      => 'is_medify_theme_option',
	    'capability' => 'edit_theme_options',
	    'priority'   => 35
    ) );

    $wp_customize->add_setting(
	    'main_heading_font',
	    array(
		    'default'           => 'Open Sans',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'is_medify_sanitize_text',
		    'capability'        => 'edit_theme_options',
	    ) );

    $wp_customize->add_control( new is_medify_Font_Control( $wp_customize, 'main_heading_font', array(
	    'label'    => __( 'Logo Font Style', 'is-medify' ),
	    'section'  => 'font_section',
	    'settings' => 'main_heading_font',
    ) ) );

    $wp_customize->add_setting(
	    'menu_font',
	    array(
		    'default'           => 'Open Sans',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'is_medify_sanitize_text',
		    'capability'        => 'edit_theme_options'
	    ) );

    $wp_customize->add_control( new is_medify_Font_Control( $wp_customize, 'menu_font', array(
	    'label'    => __( 'Header Menu Font Style', 'is-medify' ),
	    'section'  => 'font_section',
	    'settings' => 'menu_font'
    ) ) );

    $wp_customize->add_setting(
	    'theme_title',
	    array(
		    'default'           => 'Open Sans',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'is_medify_sanitize_text',
		    'capability'        => 'edit_theme_options'
	    ) );

    $wp_customize->add_control( new is_medify_Font_Control( $wp_customize, 'theme_title', array(
	    'label'    => __( 'Theme Title Font Style', 'is-medify' ),
	    'section'  => 'font_section',
	    'settings' => 'theme_title'
    ) ) );

    $wp_customize->add_setting(
	    'desc_font_all',
	    array(
		    'default'           => 'Open Sans',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'is_medify_sanitize_text',
		    'capability'        => 'edit_theme_options'
	    ) );

    $wp_customize->add_control( new is_medify_Font_Control( $wp_customize, 'desc_font_all', array(
	    'label'    => __( 'Theme Description Font Style', 'is-medify' ),
	    'section'  => 'font_section',
	    'settings' => 'desc_font_all'
    ) ) );
	
}
}

?>