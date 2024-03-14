<?php
/**
Plugin Name: AZ Video and Audio Player Addon for Elementor
Plugin URI: http://demo.azplugins.com/video-and-audio-player
Description: Video & Audio player addon for Elementor
Version: 2.0.1
Author: AZ Plugins
Author URI: https://azplugins.com
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: vapfem
Domain Path: /languages/
*/

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define path
 */
define( 'VAPFEM_URI', plugins_url('', __FILE__) );
define( 'VAPFEM_DIR', dirname( __FILE__ ) );

/**
 * Deactivate the pro plugin if active
 */
if ( ! function_exists('is_plugin_active') ){ 
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if( is_plugin_active('az-video-and-audio-player-for-elementor/plugin-main.php') ){
    add_action('update_option_active_plugins', function(){
        deactivate_plugins('az-video-and-audio-player-for-elementor/plugin-main.php');
    });
}

/**
 * Include all files
 */
include_once( VAPFEM_DIR. '/includes/init.php');