<?php
/*
Plugin Name: Contact Form 7 Shortcode Enabler 
Plugin URI: #
Description: This plugin enables the usage of external shortcuts inside Contact Form 7 Forms.
Version: 1.1
Author: Tobias Zimpel (TZ Media)
Author URI: http://www.tobias-zimpel.de
License: GPLv2 or later.
*/

function wpcf7_shortcode_enabler_activate() {
	if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) and current_user_can( 'activate_plugins' ) ) {
		// Stop activation redirect and show error
		wp_die('Sorry, but this plugin requires the <a href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a> Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
	}
}
register_activation_hook( __FILE__, 'wpcf7_shortcode_enabler_activate' );

// Activate Shortcode Execution for Contact Form 7

add_filter( 'wpcf7_form_elements', 'do_shortcode' );

?>