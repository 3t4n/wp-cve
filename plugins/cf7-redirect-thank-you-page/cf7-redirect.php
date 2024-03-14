<?php

/*
Plugin Name: Contact Form 7 - Redirect & Thank You Page
Plugin URI: https://wpplugin.org/paypal/
Description: Adds Contact Form 7 Redirect & Thank You Page features
Author: Scott Paterson
Author URI: https://wpplugin.org
License: GPL2
Version: 1.0.4
*/

/*  Copyright 2014-2023 Scott Paterson

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/



// plugin variable: cf7rl

// empty function used by pro version to check if free version is installed
function cf7rl_free() {
}

// check if pro version is attempting to be activated - if so, then deactive that plugin
if (function_exists('cf7rl_pro')) {

	deactivate_plugins('contact-form-7-redirect-thank-you-page-pro/cf7-redirect.php');
	
} else {

	//  plugin functions
	register_activation_hook( 	__FILE__, "cf7rl_activate" );
	register_deactivation_hook( __FILE__, "cf7rl_deactivate" );
	register_uninstall_hook( 	__FILE__, "cf7rl_uninstall" );

	function cf7rl_activate() {
		
		// default options
		$cf7rl_options = array(
			'currency'    		=> '25',
			'language'    		=> '3',
			'liveaccount'    	=> '',
			'sandboxaccount'    => '',
			'mode' 				=> '2',
			'cancel'    		=> '',
			'return'    		=> '',
			'redirect'			=> '2',
			'pub_key_live'		=> '',
			'sec_key_live'		=> '',
			'pub_key_test'		=> '',
			'sec_key_test'		=> '',
		);
		
		add_option("cf7rl_options", $cf7rl_options);
		
	}

	function cf7rl_deactivate() {
		
		delete_option("cf7rl_my_plugin_notice_shown");
		
	}

	function cf7rl_uninstall() {
	}

	// check to make sure contact form 7 is installed and active
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
		
		// public includes
		include_once('includes/functions.php');
		include_once('includes/redirect_methods.php');
		include_once('includes/enqueue.php');
		
		// admin includes
		if (is_admin()) {
			include_once('includes/admin/tabs_page.php');
			include_once('includes/admin/menu_links.php');
			include_once('includes/admin/settings_page.php');
			include_once('includes/admin/extensions.php');
		}
		
	} else {
			
		// give warning if contact form 7 is not active
		function cf7rl_my_admin_notice() {
			?>
			<div class="error">
				<p><?php _e( '<b>Contact Form 7 - Redirect Logic:</b> Contact Form 7 is not installed and / or active! Please install or activate: <a target="_blank" href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a>.', 'cf7rl' ); ?></p>
			</div>
			<?php
		}
		add_action( 'admin_notices', 'cf7rl_my_admin_notice' );
		
	}
}

?>