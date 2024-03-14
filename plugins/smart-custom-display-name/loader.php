<?php

/**
 * The smart Custom Display Name Plugin Loader
 *
 * @since 4
 *
 **/
 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Load Plugin Foundation
 * @since 5.0.0
 */
require_once( plugin_dir_path( __FILE__ ) . '/inc/ppf/loader.php' );


/**
 * Load file
 */
require_once( plugin_dir_path( __FILE__ ) . '/inc/class-smart-custom-display-name.php' );


/**
 * Main Function
 */
function pp_smart_custom_display_name() {

  return PP_Smart_Custom_Display_Name::getInstance( array(
    'file'      => dirname( __FILE__ ) . '/smart-custom-display-name.php',
    'slug'      => basename( pathinfo( __FILE__, PATHINFO_DIRNAME ) ),
    'name'      => 'Smart Custom Display Name',
	'shortname' => 'Smart Custom Display Name',
    'version'   => '5.0.1'
  ) );
    
}


/**
 * Run the plugin
 */
pp_smart_custom_display_name();


?>