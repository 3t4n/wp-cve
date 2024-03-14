<?php


// Include Dynamic CSS PHP File
include( plugin_dir_path( __FILE__ ) . 'ucd-dynamic-css.php');


// Links UCD admin stylesheet
function ucd_admin_styling() {
    global $ucd_plugin_version;
    wp_enqueue_style( 'ultimate-client-dash-styling', plugins_url( 'ucd-backend.css', __FILE__ ), array(), "$ucd_plugin_version" );
}
add_action( 'admin_enqueue_scripts', 'ucd_admin_styling' );


// Links UCD modern theme stylesheet
function ucd_modern_theme_styling() {
    $dashboard_modern_theme = get_option('ucd_dashboard_modern_theme');
    if (!empty($dashboard_modern_theme)) {
        global $ucd_plugin_version;
        wp_enqueue_style( 'ultimate-client-dash-modern-theme-styling', plugins_url( 'ucd-modern-theme.css', __FILE__ ), array(), "$ucd_plugin_version" );
    }
}
add_action( 'admin_enqueue_scripts', 'ucd_modern_theme_styling' );


// Links UCD frontend stylesheet
function ucd_frontend_styling() {
    if ( is_user_logged_in() ) {
        global $ucd_plugin_version;
        wp_enqueue_style( 'ucd-frontend-styling', plugins_url( 'ucd-frontend.css', __FILE__ ), array(), "$ucd_plugin_version" );
    }
}
add_action( 'wp_enqueue_scripts', 'ucd_frontend_styling' );
