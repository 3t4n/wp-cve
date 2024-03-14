<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Plugin Name: Ultimate Member + WP User Frontend - Addon
 * Plugin URI: http://devwp.pl/ultimate-member-wp-user-frontend-addon/
 * Description: Integration of "Ultimate Member" + "WP User Fronted" in user profiles
 * Version: 2.1
 * Author: UDX
 * Author URI: https://www.udx.pl
 * Text Domain: ufa
 */
if ( !class_exists( 'WeDevs_Settings_API' ) ) { 
  require_once dirname( __FILE__ ) . '/lib/class.settings-api.php';
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(is_plugin_active('ultimate-member/ultimate-member.php')){
  require_once 'admin/options.php';
  require_once 'user-dashboard.php';
  require_once 'actions.php';
}

function ufa_scripts(){
        wp_enqueue_style( 'style-ufa', plugins_url('css/style.css', __FILE__), array('wpuf-css') );
}
add_action('wp_enqueue_scripts','ufa_scripts');

add_action( 'plugins_loaded', 'ufa_load_language' );
function ufa_load_language() {
  load_plugin_textdomain( 'ufa', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}

    
?>
