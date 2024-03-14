<?php
// customizer homepage settings
add_action( 'customize_register', 'amigo_industri_customizer_homepage_settings');
function amigo_industri_customizer_homepage_settings( $wp_customize ) {

	$default = amigo_industri_default_settings();

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

    // theme option panel
	$wp_customize->add_panel( 
		'theme_homepage', 
		array(
			'priority'      => 24,
			'capability'    => 'edit_theme_options',
			'title'         => __('Homepage Sections', 'amigo-extensions'),
		) 
	);
}

// customizer custom controls
require_once ( AMIGO_PLUGIN_DIR_PATH . '/lib/customizer-controls/customizer-separator-control/separator-control.php');
require_once ( AMIGO_PLUGIN_DIR_PATH . '/lib/customizer-controls/customizer-iconpicker-control/iconpicker-control.php');
require_once ( AMIGO_PLUGIN_DIR_PATH . '/lib/customizer-controls/customizer-range-control/range-control.php'); 
require_once ( AMIGO_PLUGIN_DIR_PATH . '/lib/customizer-controls/customizer-repeater-control/repeater-control.php');

// customizer settings
require_once ( AMIGO_PLUGIN_DIR_PATH.'includes/industri/customizer/customizer-header.php' );
require_once( AMIGO_PLUGIN_DIR_PATH.'/includes/industri/customizer/customizer-slider.php' );
require_once( AMIGO_PLUGIN_DIR_PATH.'/includes/industri/customizer/customizer-info.php' );
require_once( AMIGO_PLUGIN_DIR_PATH.'/includes/industri/customizer/customizer-about.php' );
require_once( AMIGO_PLUGIN_DIR_PATH.'/includes/industri/customizer/customizer-service.php' );
require_once( AMIGO_PLUGIN_DIR_PATH.'/includes/industri/customizer/customizer-c2a.php' );
require_once( AMIGO_PLUGIN_DIR_PATH.'/includes/industri/customizer/customizer-blog.php' );
require_once( AMIGO_PLUGIN_DIR_PATH.'/includes/industri/customizer/customizer-footer.php' );