<?php

/**
 * Fired during plugin deactivation
 *
 * @since      1.0.0
 *
 * @package    Wp_Revisions_Limit
 * @subpackage Wp_Revisions_Limit/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Revisions_Limit
 * @subpackage Wp_Revisions_Limit/includes
 * @author     Roger Rodrigo
 */
class Wp_Revisions_Limit_Deactivator {

	/**
	 * @since    1.0.0
	 */
	public static function deactivate() {

	}

	/**
	 * @since    1.1
	 */
	public static function uninstall_plugin() {

		delete_option( 'revisions_limit_activation_redirect' );
		delete_option( 'revisions_limit_option' );
		
	}

}
