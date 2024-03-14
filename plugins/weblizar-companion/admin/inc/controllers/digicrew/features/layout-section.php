<?php

defined( 'ABSPATH' ) or die();

/**
 *  General options
 */
class wl_layout_customizer {
	
	public static function wl_digicrew_layout_customizer( $wp_customize ) {
		
		// home layout //
	    $wp_customize->add_section( 'Home_Page_Layout', array(
		    'title'      => __( "Home Page Layout Option", 'digicrew' ),
		    'panel'      => 'digicrew_theme_option',
		    'capability' => 'edit_theme_options',
		    'priority'   => 60,
	    ) );
	    $wp_customize->add_setting( 'home_reorder',
		    array(
			    'type'              => 'theme_mod',
			    'sanitize_callback' => 'sanitize_json_string',
			    'capability'        => 'edit_theme_options',
		    )
	    );

	    require( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/functions/sortable.php' );
	    $theme_name = wl_companion_helper::wl_get_theme_name();
	    if (  $theme_name == 'Digicrew' ) {
	    $wp_customize->add_control( new digicrew_Custom_sortable_Control( $wp_customize, 'home_reorder', array(
		    'label'    => __( 'Front Page Layout Option', 'digicrew' ),
		    'section'  => 'Home_Page_Layout',
		    'type'     => 'home-sortable',
		    'choices'  => array(
			    'services'  => __( 'Home Services', WL_COMPANION_DOMAIN ),
			    'blog'      => __( 'Home Blog', WL_COMPANION_DOMAIN),
		    ),
		    'settings' => 'home_reorder',
	    ) ) );
	    }elseif($theme_name == 'Digitrails'){
	    	$wp_customize->add_control( new digicrew_Custom_sortable_Control( $wp_customize, 'home_reorder', array(
		    'label'    => __( 'Front Page Layout Option', 'digicrew' ),
		    'section'  => 'Home_Page_Layout',
		    'type'     => 'home-sortable',
		    'choices'  => array(
			    'services'  => __( 'Home Services', WL_COMPANION_DOMAIN ),
			    'team'      => __( 'Home Team', WL_COMPANION_DOMAIN),
			    'blog'      => __( 'Home Blog', WL_COMPANION_DOMAIN),
		    ),
		    'settings' => 'home_reorder',
	    ) ) );
	    }
	}
}
?>