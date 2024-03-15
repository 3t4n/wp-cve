<?php
/**
 * File that holds all the author plugins functions
 *
 * @package WordPress_Plugins
 * @subpackage Goolytics
 */


/**
 * Writes CSS and JS to the plugin page's header for displaying my other plugins
 *
 * @since 1.0
 * @author scripts@schloebe.de
 */
function goolytics_authorplugins_head() {
	global $pagenow;
	
	wp_enqueue_script( 'os_authorplugins_script', trailingslashit(plugins_url( 'js/', dirname(__FILE__) )) . "os_authorplugins_script.js", array('jquery'), GOOLYTICSVERSION );
	wp_enqueue_style( 'os_authorplugins_style', trailingslashit(plugins_url( 'css/', dirname(__FILE__) )) . "os_authorplugins_style.css", array(), GOOLYTICSVERSION );
}

if( $pagenow == 'options-general.php' && isset( $_GET['page'] ) && $_GET['page'] == 'goolytics' ) {
	add_action( "admin_print_scripts", 'goolytics_authorplugins_head' );
}
?>