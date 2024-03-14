<?php
/*
Plugin Name: Timeline Event History 
Plugin URI:  https://blogwpthemes.com/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: wpdiscover
Version: 3.1
Author URI: https://profiles.wordpress.org/wpdiscover

* @package Timeline Event History
*/

/** Configuration **/

if ( !defined( 'TIMELINE_WP_CURRENT_VERSION' ) ) {
    define( 'TIMELINE_WP_CURRENT_VERSION', '3.1' );
}

if(!defined( 'TIMELINE_WP_PLUGIN_UPGRADE' ) ) {
    define('TIMELINE_WP_PLUGIN_UPGRADE','https://blogwpthemes.com/downloads/timeline-event-history-pro-wordpress-plugin'); // Plugin Check link
}

define( 'TIMELINE_WP_NAME'                 , 'timeline_wp' );
define( 'TIMELINE_WP_DIR'              , plugin_dir_path(__FILE__) );
define( 'TIMELINE_WP_URL'              , plugin_dir_url(__FILE__) );

define( 'TIMELINE_WP_INCLUDES'         , TIMELINE_WP_DIR        . 'includes'            . DIRECTORY_SEPARATOR );
define( 'TIMELINE_WP_LANGUAGES'        , TIMELINE_WP_DIR        . 'languages'           . DIRECTORY_SEPARATOR );
define( 'TIMELINE_WP_ADMIN'            , TIMELINE_WP_INCLUDES   . 'admin'               . DIRECTORY_SEPARATOR );
define( 'TIMELINE_WP_LIBRARIES'        , TIMELINE_WP_INCLUDES   . 'libraries'           . DIRECTORY_SEPARATOR );
define( 'TIMELINE_WP_TEMPLATES'        , TIMELINE_WP_INCLUDES   . 'public/templates'    . DIRECTORY_SEPARATOR );

define( 'TIMELINE_WP_ASSETS'           , TIMELINE_WP_URL . 'assets/' );
define( 'TIMELINE_WP_JS'               , TIMELINE_WP_URL . 'assets/js/' );
define( 'TIMELINE_WP_IMAGES'           , TIMELINE_WP_URL . 'assets/images/' );
define( 'TIMELINE_WP_RESOURCES'        , TIMELINE_WP_URL . 'assets/resources/' );
define( 'timeline_wp'                  , 'timeline_wp' );

add_image_size( 'twp-icon', 256,256, true );
/**
* Activating plugin and adding some info
*/

if (class_exists( 'Timeline_WP_Pro' ) ) {           
            include_once( ABSPATH . "wp-admin/includes/plugin.php" );           
            deactivate_plugins( 'timeline-event-history/timeline-wp.php' );
            return;
}

function activate() {
    update_option( "timelne-wp-v", TIMELINE_WP_CURRENT_VERSION );
    update_option("timelne-wp-type","FREE");
    update_option("timelne-wp-installDate",date('Y-m-d h:i:s') );
}

/**
 * Deactivate the plugin
 */
function deactivate() {
    // Do nothing
} 

// Installation and uninstallation hooks
register_activation_hook(__FILE__, 'activate' );
register_deactivation_hook(__FILE__, 'deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require TIMELINE_WP_INCLUDES . 'class-timeline-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
 
function timeline_wp_run() {
	// instantiate the plugin class
    $timelineWp = new Timeline_WP();
} timeline_wp_run();

// php Temp Ajax Method To Get Element

// Set One Column Layout
function so_screen_layout_columns( $columns ) {
    $columns['timeline_wp'] = 2;
    return $columns;
}
add_filter( 'screen_layout_columns', 'so_screen_layout_columns' );

function so_screen_layout_timeline_wp() {
    return 2;
}
add_filter( 'get_user_option_screen_layout_timeline_wp', 'so_screen_layout_timeline_wp' );

// Installation file
require_once( 'includes/install/installation.php' );