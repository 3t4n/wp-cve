<?php
/*
Plugin Name: AMS Post And Page Duplicator
Description: For creating copy of post and page.
Author: Manoj Sathyavrathan
Version: 1.1
Text Domain: amspd-post-duplicator
*/

// Exit if accessed directly.
defined('ABSPATH') or die();

/**
 * Plugin directory
 * @param const, AMSPAPDMS_DIR
 */
define( 'AMSPAPDMS_DIR', plugin_dir_path( __FILE__ ) );

if (is_admin()){
	
	if (file_exists ( AMSPAPDMS_DIR .'/includes/amspapd-post-and-page-duplictor-setup.php' ) )
		include_once ( AMSPAPDMS_DIR .'/includes/amspapd-post-and-page-duplictor-setup.php' ); 
		
	if (file_exists ( AMSPAPDMS_DIR .'/includes/amspapd-post-and-page-duplictor-menu-and-post-links.php' ) )
		include_once ( AMSPAPDMS_DIR .'/includes/amspapd-post-and-page-duplictor-menu-and-post-links.php' );
}
?>