<?php

class Youzify_Membership_Limit {

	function __construct() {

		// Actions.
		add_action( 'wp_login_failed', array( $this, 'init' ) );
		add_action( 'wp_login', array( $this, 'update_retries' ), 10 );
		add_filter( 'wp_authenticate_user', array( $this, 'authenticate_user' ), 10, 2 );

	}

	/**
	 * Limit Login Attempts.
	 */
	function init( $username ) {

		// Get User IP Address.
		$user_ip = $this->get_user_address();

		// Get Current Lockouts.
		$lockouts = youzify_option( 'youzify_membership_login_lockouts' );

		// if currently locked-out, do not add to retries.
		if ( ! is_array( $lockouts ) ) {
			$lockouts = array();
		}

		// Check if IP is locked-out.
		if ( isset( $lockouts[ $user_ip ] ) && time() < $lockouts[ $user_ip ] ) {
			return;
		}

		// Get User IP Retries Data.
		$retries = youzify_option( 'youzify_membership_login_retries' );

		if ( ! is_array( $retries ) ) {
			$retries = array();
		}
		// Check User Retries number.
		if ( isset( $retries[ $user_ip ] ) && time() < $retries[ $user_ip ]['expired'] ) {
			$retries[ $user_ip ]['retries']++;
		} else {
			$retries[ $user_ip ]['retries'] = 1;
		}

		// Get Retry Expiration Date.
		$retries[ $user_ip ]['expired'] = time() + youzify_option( 'youzify_membership_retries_duration', 1200 );

		// Get Allowed Retries.
		$allowed_retries = youzify_option( 'youzify_membership_allowed_retries', 4 );

		// Save Retry Without lockout the login.
		if ( ( $retries[ $user_ip ]['retries'] % $allowed_retries ) != 0 ) {

			// Update Data
			$this->update_data( $retries );

			return false;

		}

		/**
		 * Lockout User Login
		 */

		// Get Maximum Retries Number.
		$max_retries = $allowed_retries * youzify_option( 'youzify_membership_allowed_lockouts', 2 );

		// Set Long Louckout
		if ( $retries[ $user_ip ]['retries'] >= $max_retries ) {
			// Get Long Duration Value.
			$lockouts[ $user_ip ] = time() + youzify_option( 'youzify_membership_long_lockout_duration', 86400 );
			unset( $retries[ $user_ip ] );
		} else {
			// Set Short Lockout
			$lockouts[ $user_ip ] = time() + youzify_option( 'youzify_membership_short_lockout_duration', 43200 );
		}

		// Update Retries & Lockouts.
		$this->update_data( $retries, $lockouts );

		// Update Statistics.
		$this->update_statistics();

	}

	/**
	 * Update Available Retries.
	 */
	public function update_retries() {

		// Get User IP.
		$user_ip = $this->get_user_address();

		// Get Retreis
		$retries = youzify_option( 'youzify_membership_login_retries' );

		// Update Retries
		if ( isset( $retries[ $user_ip ] ) ) {
			unset( $retries[ $user_ip ] );
			update_option( 'youzify_membership_login_retries', $retries );
		}

	}

	/**
	 * Update Available Lockouts
	 */
	public function update_data( $retries, $lockouts = null ) {

		// Get Current Time.
		$now = time();

		// Remove Expired Retries.
		$retries = ! empty( $retries ) ? $retries : youzify_option( 'youzify_membership_login_retries' );

		// Check if result is an array.
		if ( is_array( $retries ) ) {

			// Check Retries Date.
			foreach ( $retries as $user_ip => $retry ) {
				if ( $retry['expired'] < $now ) {
					unset( $retries[ $user_ip ] );
				}
			}

			update_option( 'youzify_membership_login_retries', $retries );

		}

		// Get Current Time.
		$now = time();

		// Get Available Louckouts
		$lockouts = ! empty( $lockouts ) ? $lockouts : youzify_option( 'youzify_membership_login_lockouts' );

		// Check if result is an array.
		if ( is_array( $lockouts ) ) {

			// Remove Expired Lockouts
			foreach ( $lockouts as $user_ip => $lockout ) {
				if ( $lockout < $now ) {
					unset( $lockouts[ $user_ip ] );
				}
			}

			// Update Date.
			update_option( 'youzify_membership_login_lockouts', $lockouts );

		}

	}

	/**
	 * Prevent User Login on Lockout.
	 */
	function authenticate_user( $user, $password ) {

		// Don't do anything if user have no lockouts.

		// Show Lockout Error Message.
		if ( $this->prevent_login() ) {
			$error = new WP_Error();
			$error->add( 'too_many_retries', $this->get_lockout_msg() );
			return $error;
		}

		if ( is_wp_error( $user ) ) {

			// Get Remaining Retries.
			$remaining_retries = $this->get_remaining_retries( $user );

			// Show Retries Error Message.
			if ( $remaining_retries ) {
				$user->add( 'remaining_retries', $remaining_retries );
			}

		}

		return $user;

	}

	/**
	 * Get Remaining Retries Error Message.
	 */
	function get_remaining_retries( $errors ) {

		// Get User IP
		$user_ip = $this->get_user_address();

		// Get All Retries.
		$retries = youzify_option( 'youzify_membership_login_retries' );

		// No Retries Found
		if ( ! is_array( $retries ) || ! isset( $retries[ $user_ip ] ) || time() > $retries[ $user_ip ]['expired'] ) {
			return false;
		}

		// Get Allowed Retries Number.
		$allowed_retries = youzify_option( 'youzify_membership_allowed_retries', 4 );

		// Get Allowed Retries number
		if ( $allowed_retries == 0 || ( $retries[ $user_ip ]['retries'] % $allowed_retries ) == 0 ) {
			return false;
		}

		// Get Remaining Tries.
		$remaining = max( ( $allowed_retries - ( $retries[ $user_ip ]['retries'] % $allowed_retries ) ), 0 );

		// Add Error
		return sprintf( _n( '%d attempt remaining.', '%d attempts remaining.', $remaining, 'youzify' ), $remaining );

	}

	/**
	 * Get Lockout Error Message.
	 */
	function get_lockout_msg() {

		// Get User IP.
		$ip = $this->get_user_address();

		// Get Available Lockouts.
		$lockouts = youzify_option( 'youzify_membership_login_lockouts' );

		// Get Main Message.
		$msg[] = __( 'Too many failed login attempts.', 'youzify' );

		// Check
		if ( ! is_array( $lockouts ) || ! isset( $lockouts[ $ip ] ) || time() >= $lockouts[ $ip ] ) {
			$msg[] =  __( 'Please try again later.', 'youzify' );
			return implode( ' ', $msg );
		}

		// Get Lockout Time.
		$lockout_time = ceil( ( $lockouts[ $ip ] - time() ) / 60 );

		if ( $lockout_time > 60 ) {
			$lockout_time = ceil( $lockout_time / 60 );
			$msg[] = sprintf(
				_n(
					'Please try again in %d hour.',
					'Please try again in %d hours.', $lockout_time, 'youzify'
				),
				$lockout_time
			);
		} else {
			$msg[] = sprintf(
				_n(
					'Please try again in %d minute.',
					'Please try again in %d minutes.', $lockout_time, 'youzify'
					),
				$lockout_time
			);
		}

		return implode( ' ', $msg );
	}

	/**
	 * Prevent User Login on Lockout existence.
	 */
	public function prevent_login() {

		// Get Lockouts.
		$lockouts = youzify_option( 'youzify_membership_login_lockouts' );

		if ( empty( $lockouts ) ) {
			return false;
		}

		// Get User IP.
		$ip = $this->get_user_address();

		// Get User IP.
		$user_ip = isset( $lockouts[ $ip ] ) ? $lockouts[ $ip ] : false;

		// Check ?
		if ( ! $user_ip || time() >= $user_ip ) {
			return false;
		}

		return true;
	}

	/**
	 * Update Lockouts Statistics.
	 * this will be available in the coming updates.
	 */
	public function update_statistics() {

		// Get Total Lockouts Number.
		$total_lockouts = youzify_option( 'youzify_membership_total_lockouts' );

		// Update Statistics
		if ( ! $total_lockouts ) {
			update_option( 'youzify_membership_total_lockouts', 1 );
		} else {
			update_option( 'youzify_membership_total_lockouts', $total_lockouts++ );
		}

	}

	/**
	 * Get User Address IP.
	 */
	function get_user_address() {
		$address = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR']: false;
		return $address;
	}

}

new Youzify_Membership_Limit();