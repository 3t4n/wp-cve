<?php
/**
 * This file contains functions related to login flow.
 *
 * @package miniorange-login-security/database.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
if ( ! class_exists( 'Momls_Wpns_Db' ) ) {
	/**
	 * Class used to perform DB operation on security functions.
	 */
	class Momls_Wpns_Db {

		/**
		 * This function should run on activation of plugin.
		 *
		 * @return void
		 */
		public function momls_plugin_activate() {
			global $wpdb;
			if ( ! get_site_option( 'momls_wpns_dbversion' ) || get_site_option( 'momls_wpns_dbversion' ) < Momls_Wpns_Constants::DB_VERSION ) {
				update_site_option( 'momls_wpns_dbversion', Momls_Wpns_Constants::DB_VERSION );
			} else {
				$current_db_version = get_site_option( 'momls_wpns_dbversion' );
				if ( $current_db_version < Momls_Wpns_Constants::DB_VERSION ) {
					update_site_option( 'momls_wpns_dbversion', Momls_Wpns_Constants::DB_VERSION );

				}
			}
		}

	}
}

