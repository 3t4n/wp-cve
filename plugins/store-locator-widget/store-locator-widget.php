<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/*
Plugin Name: Store Locator Widget
Plugin URI: https://en-au.wordpress.org/plugins/store-locator-widget/
Description: Add a powerful Store Locator to your Wordpress site with a user friendly admin console, lots of features and a variety of layouts.
Version: 20200131
Author: StoreLocatorWidgets.com
Author URI: https://www.storelocatorwidgets.com/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
*/

// Shortcodes
add_shortcode("storelocatorwidget", "storelocatorwidget_handler");

// Add actions
add_action('admin_menu', 'storelocatorwidget_create_menu');
add_action( 'wp_storelocatorwidget_api', 'storelocatorwidget_get_storelocatorwidget_api' );
add_action( 'wp_google_gapi', 'storelocatorwidget_get_google_api' );
add_action( 'wp_set_google_gapi', 'storelocatorwidget_save_google_api' );
add_action( 'wp_set_storelocatorwidget_gapi', 'storelocatorwidget_save_storelocatorwidget_api' );

// Process the apps
add_action( 'admin_post_storelocatorwidget_api_keys', 'storelocatorwidget_process_storelocatorwidget_keys' );

// Activiation Hook
register_activation_hook( __FILE__, 'storelocatorwidget_install' );

// Install functions
define( 'STORELOCATORWIDGET_DB_VERSION', '1.0' );

// Create the table to hold the API keys
function storelocatorwidget_install () {
   global $wpdb;

   $installed_ver = get_option( "storelocatorwidget_db_version" );
   $table_name = storelocatorwidget_get_table_name();

  if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name || $installed_ver != STORELOCATORWIDGET_DB_VERSION ) {

    $sql = 'CREATE TABLE ' .$table_name. ' (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      storelocatorwidget_api VARCHAR(255) DEFAULT "" NOT NULL,
      google_api VARCHAR(255) DEFAULT "" NOT NULL,
      UNIQUE KEY id (id)
    );';

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    update_option( "storelocatorwidget_db_version", STORELOCATORWIDGET_DB_VERSION );
    storelocatorwidget_create_first_row();
  }
}

// Get the table prefix and return the name
function storelocatorwidget_get_table_name(){
  global $wpdb;
  return $wpdb->prefix . "storelocatorwidget";
}

// End of Install functions

// Uninstall
function storelocatorwidget_uninstall() {
  global $wpdb;
  $table_name = storelocatorwidget_get_table_name();
  $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}
register_uninstall_hook( __FILE__, 'storelocatorwidget_uninstall' );
// End of Uninstall

// The function that actually handles replacing the short code
function storelocatorwidget_handler($incomingfrompost) {

  $api = storelocatorwidget_get_storelocatorwidget_api();
  $gapi = storelocatorwidget_get_google_api();
  $script_text = "";

  if ($api == "" || $gapi == "") $script_text = "<p>First you need to save your Store Locator Widgets API key and Google API key on the settings page.";
  else $script_text = '<div id="storelocatorwidget" style="width:100%;"><p>Loading <a href="https://www.storelocatorwidgets.com">Store Locator Software</a>...</p></div>';

  wp_enqueue_script( 'storelocatorwidget_google_maps_js', '//maps.googleapis.com/maps/api/js?key=' . $gapi . '&libraries=places' );
  wp_enqueue_script( 'storelocatorwidget_locator_js', '//cdn.storelocatorwidgets.com/widget/widget.js' );

  add_filter( 'script_loader_tag', 'storelocatorwidget_script_loader_tag', 10 ,2 );

  $incomingfrompost = shortcode_atts(array("headingstart" => $script_text), $incomingfrompost);

  $demolph_output = storelocatorwidget_script_output($incomingfrompost);
  return $demolph_output;
}


function storelocatorwidget_script_loader_tag( $tag, $handle ) {
  $api = storelocatorwidget_get_storelocatorwidget_api();
  if ( $handle == 'storelocatorwidget_locator_js' ) {
    return str_replace( 'src=', 'id="storelocatorscript" data-uid="' . $api . '" src=', $tag );
  }
  return $tag;
}


// build the script to replace the short code
function storelocatorwidget_script_output($incomingfromhandler) {
  // avoids Notice: Undefined Index Error
  $headingstart = (isset($incomingfromhandler["headingstart"])) ? $incomingfromhandler["headingstart"] : "";
  $headingend = (isset($incomingfromhandler["headingend"])) ? $incomingfromhandler["headingend"] : "";
  $liststart = (isset($incomingfromhandler["liststart"])) ? $incomingfromhandler["liststart"] : "";
  $listend = (isset($incomingfromhandler["listend"])) ? $incomingfromhandler["listend"] : "";
  $categorylist = (isset($incomingfromhandler["categorylist"])) ? $incomingfromhandler["categorylist"] : "";
  $itemstart = (isset($incomingfromhandler["itemstart"])) ? $incomingfromhandler["itemstart"] : "";
  $itemend = (isset($incomingfromhandler["itemend"])) ? $incomingfromhandler["itemend"] : "";

  $demolp_output = wp_specialchars_decode($headingstart);
  $demolp_output .= wp_specialchars_decode($liststart);

  for ($demolp_count = 1; $demolp_count <= $categorylist; $demolp_count++) {
    $demolp_output .= wp_specialchars_decode($itemstart);
    $demolp_output .= $demolp_count;
    $demolp_output .= " of ";
    $demolp_output .= wp_specialchars($categorylist);
    $demolp_output .= wp_specialchars_decode($itemend);
  }

  $demolp_output .= wp_specialchars_decode($listend);
  $demolp_output .= wp_specialchars_decode($headingend);

  return $demolp_output;
}

// Create the admin menu
function storelocatorwidget_create_menu() {
  //create new top-level menu
  add_menu_page('StoreLocatorWidget Settings', 'Store Locator', 'administrator', __FILE__, 'storelocatorwidget_settings_page',plugins_url('/images/icon.png', __FILE__));
}

// Require the important files
require("functions.php");
require("admin_page.php");
