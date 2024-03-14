<?php

/**
 * Admin File
 * Handles to admin functionality & other functions
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Validate Settings
 */
function esb_eu_settings_validate( $input ) {
    
    global $wp_rewrite;
    
    if( isset( $input['extension'] ) ) {
        
        $input['extension'] = esb_eu_escape_slashes_deep( $input['extension'] );
    
        $permalink_structure = '/%postname%' . $input['extension'];
        update_option( 'permalink_structure', $permalink_structure );
        
        $wp_rewrite->flush_rules();
    }
    
    return $input;
}

/**
 * Add Register Settings
 */
function esb_eu_register_settings() {
    
    register_setting( 'esb-eu-settings-group', 'esb_eu_settings', 'esb_eu_settings_validate' );
}

/**
 * Adding Menu Pages
 */
function esb_eu_add_menu_pages() {

    add_options_page( __( 'Extension Settings', 'esbeu' ), __( 'Extension Settings', 'esbeu' ), 'manage_options', 'esb-eu-settings', 'esb_eu_settings_page');
}

/**
 * Settings Page.
 */
function esb_eu_settings_page() {
    
    include ESB_EU_DIR . '/includes/admin/views/settings-page.php';
}

/**
 * Add plugin settings page link
 */
function esb_eu_plugin_action_links( $links, $file ) {
    
    if ( $file != ESB_EU_BASEPATH )
        return $links;

    $settings_link = '<a href="' . menu_page_url( 'esb-eu-settings', false ) . '">' . __( 'Settings', 'esbeu' ) . '</a>';

    array_unshift( $links, $settings_link );

    return $links;
}

//add action to add settings page 
add_action( 'admin_menu', 'esb_eu_add_menu_pages' );

//add action to call register settings function
add_action( 'admin_init', 'esb_eu_register_settings' );

//add filter to add plugin settings page link
add_filter( 'plugin_action_links', 'esb_eu_plugin_action_links', 10, 2 );

?>