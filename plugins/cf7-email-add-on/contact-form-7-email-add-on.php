<?php
/**
 * Plugin Name: Contact Form 7 Email Add On
 * Plugin URI: https://wordpress.org/plugins/cf7-email-add-on/
 * Description: Contact Form 7 Email Add on plugin provides the responsive Email templates to admin and users.
 * Version: 1.9
 * Author: KrishaWeb
 * Author URI: https://www.krishaweb.com
 * Text Domain: cf7-email-add-on
 * Domain Path: /languages
 */

// If check abspath exists or not.
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CF7_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once 'include/class-cf7-email.php';

/**
 * Plugin activate hook.
 */
function cf7_email_add_on_activate() {	
	// If check contact form 7 activate or not.
	if( ! has_action( 'wpcf7_init' ) ) {
		// Deactivate contact form 7 plguin.
		deactivate_plugins( plugin_basename( __FILE__ ) );
		// Display error message.
		wp_die( __( 'Please activate Contact Form 7.', 'cf7-email-add-on' ), 'Plugin dependency check',
			array(
				'back_link' => true
			)
		);
	}
}		
register_activation_hook( __FILE__, 'cf7_email_add_on_activate' );

/**
 * Plugin deactivate hook.
 */
function cf7_email_add_on_deactivate() {
	// Code here
	$cf7ea = cf7ea_init();
	$cf7ea->__clear_history();
}
register_deactivation_hook( __FILE__, 'cf7_email_add_on_deactivate' );

function cf7_email_add_on_uninstall() {
	// Code here
	$cf7ea = cf7ea_init();
	$cf7ea->__clear_history();
}
register_uninstall_hook( __FILE__, 'cf7_email_add_on_uninstall' );

/**
 * Loads a cf 7 email add on textdomain.
 */
function cf7ea_init() {
	load_plugin_textdomain( 'cf7-email-add-on', false, basename( dirname( __FILE__ ) ) . '/languages' );
	// Load core class
	$CF7EA = Cf7_Email_Add_on::_instance();
	return $CF7EA;
}
add_action( 'plugins_loaded', 'cf7ea_init' );
