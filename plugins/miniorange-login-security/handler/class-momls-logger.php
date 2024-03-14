<?php
/**
 * This file is handler for views/twofa/two-fa.php.
 *
 * @package miniorange-login-security/handler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Momls_Logger' ) ) {
	/**
	 * Class for log login transactions
	 */
	class Momls_Logger {
		/**
		 * Constructor for Mo2f_Logger
		 */
		public function __construct() {
			add_action( 'momls_log_403', array( $this, 'momls_log_403' ) );
			add_action( 'template_redirect', array( $this, 'momls_log_404' ) );
		}
		/**
		 * Log 403.
		 *
		 * @return void
		 */
		public function momls_log_403() {
			global $momls_wpns_utility;
			$user      = wp_get_current_user();
			$user_name = is_user_logged_in() ? $user->user_login : 'GUEST';
		}
		/**
		 * Log 404.
		 *
		 * @return void
		 */
		public function momls_log_404() {
			global $momls_wpns_utility;

			if ( ! is_404() ) {
				return;
			}
			$user      = wp_get_current_user();
			$user_name = is_user_logged_in() ? $user->user_login : 'GUEST';
		}
	}
	new Momls_Logger();
}
