<?php
namespace SG_Email_Marketing\Traits;

trait Ip_Trait {
	/**
	 * Get the current user's IP address.
	 *
	 * @since  1.0.0
	 *
	 * @return string The users's IP.
	 */
	public function get_current_user_ip() {
		if ( ! isset( $_SERVER[ 'REMOTE_ADDR' ] ) ) { // phpcs:ignore
			return '127.0.0.1';
		}

		if ( ! filter_var( $_SERVER[ 'REMOTE_ADDR' ], FILTER_VALIDATE_IP ) ) { // phpcs:ignore
			return '127.0.0.1';
		}

		// Return the users's IP Address.
		return preg_replace( '/^::1$/', '127.0.0.1', sanitize_text_field( $_SERVER[ 'REMOTE_ADDR' ] ) ); //phpcs:ignore
	}
}