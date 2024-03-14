<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
*/
class wl_extra_customizer {
	
	public static function wl_enigma_extra_customizer( $wp_customize ) {
		  
    /* Extra Section Option */
    $wp_customize->add_section( 'extra_section', array(
	    'title'      => __( 'Home Extra Section Options', WL_COMPANION_DOMAIN ),
	    'panel'      => 'enigma_theme_option',
	    'capability' => 'edit_theme_options',
	    'priority'   => 41
    ) );

    $wp_customize->add_setting(
	    'editor_home',
	    array(
		    'default'           => '',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'enigma_sanitize_checkbox',
		    'capability'        => 'edit_theme_options'
	    )
    );

    $wp_customize->add_control( 'editor_home', array(
	    'label'    => __( 'Enable extra section on homepage.', WL_COMPANION_DOMAIN ),
	    'type'     => 'checkbox',
	    'section'  => 'extra_section',
	    'settings' => 'editor_home'
    ) );

    $wp_customize->add_setting(
	    'extra_sec_desc',
	    array(
		    'default'           => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
		    'type'              => 'theme_mod',
		    'sanitize_callback' => 'enigma_sanitize_text',
		    'capability'        => 'edit_theme_options'
	    )
    );
	require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/functions/one-page-editor.php' );
    $wp_customize->add_control( new One_Page_Editor( $wp_customize, 'extra_sec_desc', array(
	    'label'                      => __( 'Extra section content', WL_COMPANION_DOMAIN ),
	    'active_callback'            => 'show_on_front',
	    'include_admin_print_footer' => true,
	    'section'                    => 'extra_section',
	    'settings'                   => 'extra_sec_desc'
    ) ) );
		
	}
}
?>