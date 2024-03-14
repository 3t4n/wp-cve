<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Wp_Revisions_Limit
 * @subpackage Wp_Revisions_Limit/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Revisions_Limit
 * @subpackage Wp_Revisions_Limit/includes
 * @author     Roger Rodrigo
 */
class Wp_Revisions_Limit_Activator {

	/**
	 * @since    1.0.0
	 */
	public static function activate() {

		add_option( 'revisions_limit_activation_redirect', true );
		add_action( 'activated_plugin', array( __CLASS__, 'activation_redirect' ) );

	}

	/**
	 * @since    1.1
	 */
	public static function activation_redirect() {

		if ( get_option( 'revisions_limit_activation_redirect', false ) ) {
			delete_option( 'revisions_limit_activation_redirect' );
			if ( !isset( $_GET['activate-multi'] ) ) {
				exit( wp_redirect( "options-general.php?page=wp-revisions-limit" ) );
			}
		}

	}

}
