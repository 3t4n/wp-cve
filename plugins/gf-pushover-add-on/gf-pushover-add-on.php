<?php
/*
Plugin Name: Gravity Forms Pushover Add-On
Plugin URI: https://wp2pgpmail.com/gravity-pushover/
Description: Get Gravity Forms submissions as instant push notifications on your phone or tablet with Pushover.
Version: 1.06
Author: wp2pgpmail
Author URI: https://wp2pgpmail.com
License: GPLv2
*/

define( 'GF_PUSHOVER_VERSION', '1.06' );

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( is_plugin_active('gravityforms/gravityforms.php') ) {
	add_action('gform_loaded', array('GF_Pushover_Bootstrap', 'load'), 5);

	class GF_Pushover_Bootstrap {

		public static function load(){
			require_once('class-gf-pushover.php');
			GFAddOn::register('GFPushover');
		}

	}

	function gf_pushover() {
		return GFPushover::get_instance();
	}
	
} else {
	add_action('admin_notices', 'showAdminMessages');
	function showAdminMessages() {
		$plugin_messages = array();
		if ( !is_plugin_active('gravityforms/gravityforms.php') ) {
			$plugin_messages[] = __( 'Gravity Forms Pushover requires you to install <a href="http://bit.ly/GravityFormsWordPress" target="_blank">Gravity Forms</a>.', 'gform_pushover' );
		}
		if(count($plugin_messages) > 0) {
			echo '<div id="message" class="error">';
			foreach($plugin_messages as $message) {
				echo '<p><strong>'.$message.'</strong></p>';
			}
			echo '</div>';
		}
	}
}