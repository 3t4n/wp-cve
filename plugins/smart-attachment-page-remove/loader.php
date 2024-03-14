<?php

/**
 * Smart Attachment Page Remove Plugin Loader
 *
 * @since 3
 *
 **/
 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Load Plugin Foundation
 * @since 4.0.0
 */
require_once( plugin_dir_path( __FILE__ ) . '/inc/ppf/loader.php' );



/**
 * Load Plugin Main File
 */
require_once( plugin_dir_path( __FILE__ ) . '/inc/class-smart-attachment-page-remove.php' );


/**
 * Main Function
 */
function pp_smart_attachment_page_remove() {

  return PP_Smart_Attachment_Page_Remove::getInstance( array(
  
    'file'      => dirname( __FILE__ ) . '/smart-attachment-page-remove.php',
    'slug'      => pathinfo( dirname( __FILE__ ) . '/smart-attachment-page-remove.php', PATHINFO_FILENAME ),
    'name'      => 'Smart Attachment Page Remove',
    'shortname' => 'Smart Attachment Page Remove',
    'version'   => '4.0.3'
    
  ) );
    
}


/**
 * Run the plugin
 */
pp_smart_attachment_page_remove();

?>