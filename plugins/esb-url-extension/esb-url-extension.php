<?php
/*
Plugin Name: ESB URL Extension
Plugin URI: https://wordpress.org/plugins/esb-url-extension/
Description: Extend your url with .html, .htm, .php, .jsp, .asp, .xml etc.
Version: 1.0.0
Author: Henry
Author URI: http://esparkinfo.com/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if( !defined( 'ESB_EU_DIR' ) ) {
    define('ESB_EU_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'ESB_EU_URL' ) ) {
    define('ESB_EU_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'ESB_EU_META_PREFIX' ) ) {
    define( 'ESB_EU_META_PREFIX', '_esb_eu_' ); // meta box prefix
}
if( !defined('ESB_EU_BASEPATH') ){
    define('ESB_EU_BASEPATH', plugin_basename( __FILE__ ) );  // plugin base path
}
if( !defined('ESB_EU_BASENAME') ){
    define('ESB_EU_BASENAME', 'esb-url-extension');  // plugin base name
}

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 */

function esb_eu_load_textdomain() {

  load_plugin_textdomain( 'esbeu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

}
add_action( 'init', 'esb_eu_load_textdomain' ); 

/**
 * Activation Hook
 *
 * Register plugin activation hook.
 */
register_activation_hook( __FILE__, 'esb_eu_install' );

/**
 * Deactivation Hook
 *
 * Register plugin deactivation hook.
 */
register_deactivation_hook( __FILE__, 'esb_eu_uninstall');

/**
 * Plugin Setup (On Activation)
 *
 * Does the initial setup,
 * stest default values for the plugin options.
 */
function esb_eu_install() {
    
    global $wp_rewrite;
    
    //get option for when plugin is activating first time
    $esb_eu_set_option = get_option( 'esb_eu_set_option' );

    if( empty( $esb_eu_set_option ) ) { //check plugin version option

        $settings = array(
                                'extension' => '.html'
                            );
        
        //update settings for this plugin
        update_option( 'esb_eu_settings', $settings );
        
        //update plugin version to option 
        update_option( 'esb_eu_set_option', '1.0' );
        
        if( isset( $settings['extension'] ) ) {
            
            $permalink_structure = '/%postname%' . $settings['extension'];
            update_option( 'permalink_structure', $permalink_structure );
        
            $wp_rewrite->flush_rules();
        }
    }
}

/**
 * Plugin Setup (On Deactivation)
 *
 * Delete plugin options.
 */
function esb_eu_uninstall() {
    
}

global $esb_eu_settings;
$esb_eu_settings    = get_option( 'esb_eu_settings' );

//include model file
include ESB_EU_DIR . '/includes/esb-eu-model.php';

//include scripts file
include ESB_EU_DIR . '/includes/esb-eu-scripts.php';

//include admin file
include ESB_EU_DIR . '/includes/admin/esb-eu-admin.php';

//include public file
include ESB_EU_DIR . '/includes/esb-eu-public.php';
?>