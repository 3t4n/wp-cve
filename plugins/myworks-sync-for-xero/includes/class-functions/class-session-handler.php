<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class MyWorks_WC_Xero_Sync_Lib_Session_Handler extends WC_Session {
	protected $_cookie;
	protected $_session_expiring;
	protected $_session_expiration;
	protected $_has_cookie = false;
	protected $_table;
	
	protected $_scg_name;
	protected $_scg_p;
	
	public function __construct($s_tbl='') {
		global $wpdb;
		#$this->_cookie = apply_filters( 'mw_wc_xero_sync_cookie', 'wp_mw_wc_xero_sync_session_' . COOKIEHASH );
		$this->_table  = (!empty($s_tbl))?$s_tbl:$wpdb->prefix . 'mw_wc_xero_sync_sessions';
		$this->_scg_name  = 'mwxeros_session_id';#wc_session_id
		$this->_scg_p  = 'mw_wc_xero_sync_';#wc_
	}
	
	public function init() {
		$this->init_session_cookie();
		
		add_action( 'shutdown', array( $this, 'save_data' ), 20 );
		add_action( 'wp_logout', array( $this, 'destroy_session' ) );

		if ( ! is_user_logged_in() ) {
			#add_filter( 'nonce_user_logged_out', array( $this, 'nonce_user_logged_out' ) );
			add_filter( 'nonce_user_logged_out', array( $this, 'maybe_update_nonce_user_logged_out' ), 10, 2 );
		}
		#$this->set_customer_session_cookie( true );
	}
	
	public function init_session_cookie() {
		$cookie = $this->get_session_cookie();

		if ( $cookie ) {
			$this->_customer_id        = $cookie[0];
			$this->_session_expiration = $cookie[1];
			$this->_session_expiring   = $cookie[2];
			$this->_has_cookie         = true;
			$this->_data               = $this->get_session_data();
			
			// If the user logs in, update session.
			if ( is_user_logged_in() && strval( get_current_user_id() ) !== $this->_customer_id ) {
				$guest_session_id   = $this->_customer_id;
				$this->_customer_id = strval( get_current_user_id() );
				$this->_dirty       = true;
				$this->save_data( $guest_session_id );
				#$this->set_customer_session_cookie( true );
			}

			// Update session if its close to expiring.
			if ( time() > $this->_session_expiring ) {
				$this->set_session_expiration();
				$this->update_session_timestamp( $this->_customer_id, $this->_session_expiration );
			}
		} else {
			$this->set_session_expiration();
			$this->_customer_id = $this->generate_customer_id();
			$this->_data        = $this->get_session_data();
		}
	}
	
	public function set_customer_session_cookie( $set ) {
		if ( $set ) {
			$to_hash           = $this->_customer_id . '|' . $this->_session_expiration;
			$cookie_hash       = hash_hmac( 'md5', $to_hash, wp_hash( $to_hash ) );
			$cookie_value      = $this->_customer_id . '||' . $this->_session_expiration . '||' . $this->_session_expiring . '||' . $cookie_hash;
			$this->_has_cookie = true;

			if ( ! isset( $_COOKIE[ $this->_cookie ] ) || $_COOKIE[ $this->_cookie ] !== $cookie_value ) {
				wc_setcookie( $this->_cookie, $cookie_value, $this->_session_expiration, $this->use_secure_cookie(), true );
			}
		}
	}
	
	protected function use_secure_cookie() {
		return apply_filters( 'wc_session_use_secure_cookie', wc_site_is_https() && is_ssl() );
	}
	
	public function has_session() {
		return isset( $_COOKIE[ $this->_cookie ] ) || $this->_has_cookie || is_user_logged_in();
	}
	
	public function set_session_expiration() {
		$this->_session_expiring   = time() + intval( apply_filters( 'mw_wc_xero_sync_expiring', 60 * 60 * 47 ) );
		$this->_session_expiration = time() + intval( apply_filters( 'mw_wc_xero_sync_session_expiration', 60 * 60 * 48 ) );
	}
	
	public function generate_customer_id() {
		$customer_id = '';

		if ( is_user_logged_in() ) {
			$customer_id = strval( get_current_user_id() );
		}

		if ( empty( $customer_id ) ) {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$hasher      = new PasswordHash( 8, false );
			$customer_id = md5( $hasher->get_random_bytes( 32 ) );
		}

		return $customer_id;
	}
	
	public function get_session_cookie() {
		$cookie_value = isset( $_COOKIE[ $this->_cookie ] ) ? wp_unslash( sanitize_text_field($_COOKIE[ $this->_cookie ]) ) : false;
		
		if ( empty( $cookie_value ) || ! is_string( $cookie_value ) ) {
			return false;
		}

		list( $customer_id, $session_expiration, $session_expiring, $cookie_hash ) = explode( '||', $cookie_value );

		if ( empty( $customer_id ) ) {
			return false;
		}

		// Validate hash.
		$to_hash = $customer_id . '|' . $session_expiration;
		$hash    = hash_hmac( 'md5', $to_hash, wp_hash( $to_hash ) );

		if ( empty( $cookie_hash ) || ! hash_equals( $hash, $cookie_hash ) ) {
			return false;
		}

		return array( $customer_id, $session_expiration, $session_expiring, $cookie_hash );
	}
	
	public function get_session_data() {
		return $this->has_session() ? (array) $this->get_session( $this->_customer_id, array() ) : array();
	}
	
	private function get_cache_prefix() {		
		$prefix = wp_cache_get( $this->_scg_p . $this->_scg_name . '_cache_prefix', $this->_scg_name );

		if ( false === $prefix ) {
			$prefix = microtime();
			wp_cache_set( $this->_scg_p . $this->_scg_name . '_cache_prefix', $prefix, $this->_scg_name );
		}

		return $this->_scg_p.'cache_' . $prefix . '_';
	}
	
	public function save_data( $old_session_key = 0 ) {
		// Dirty if something changed - prevents saving nothing new.
		if ( $this->_dirty && $this->has_session() ) {
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
					"INSERT INTO {$this->_table} (`session_key`, `session_value`, `session_expiry`) VALUES (%s, %s, %d)
 					ON DUPLICATE KEY UPDATE `session_value` = VALUES(`session_value`), `session_expiry` = VALUES(`session_expiry`)",
					$this->_customer_id,
					maybe_serialize( $this->_data ),
					$this->_session_expiration
				)
			);

			wp_cache_set( $this->get_cache_prefix() . $this->_customer_id, $this->_data, $this->_scg_name, $this->_session_expiration - time() );
			$this->_dirty = false;
			if ( get_current_user_id() != $old_session_key && ! is_object( get_user_by( 'id', $old_session_key ) ) ) {
				$this->delete_session( $old_session_key );
			}
		}
	}
	
	public function destroy_session() {
		$this->delete_session( $this->_customer_id );
		$this->forget_session();
	}
	
	public function forget_session() {
		#wc_setcookie( $this->_cookie, '', time() - YEAR_IN_SECONDS, $this->use_secure_cookie(), true );

		wc_empty_cart();

		$this->_data        = array();
		$this->_dirty       = false;
		$this->_customer_id = $this->generate_customer_id();
	}
	
	public function nonce_user_logged_out( $uid ) {
		return $this->has_session() && $this->_customer_id ? $this->_customer_id : $uid;
	}
	
	public function maybe_update_nonce_user_logged_out( $uid, $action ) {
		if ( Automattic\WooCommerce\Utilities\StringUtil::starts_with( $action, 'woocommerce' ) ) {
			return $this->has_session() && $this->_customer_id ? $this->_customer_id : $uid;
		}

		return $uid;
	}
	
	public function cleanup_sessions() {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$this->_table} WHERE session_expiry < %d", time() ) );

		if ( class_exists( 'WC_Cache_Helper' ) ) {
			wp_cache_set( $this->_scg_p . $this->_scg_name . '_cache_prefix', microtime(), $this->_scg_name );
		}
	}
	
	public function get_session( $customer_id, $default = false ) {
		global $wpdb;

		if ( defined( 'WP_SETUP_CONFIG' ) ) {
			return false;
		}
		
		// Try to get it from the cache, it will return false if not present or if object cache not in use.
		$value = wp_cache_get( $this->get_cache_prefix() . $customer_id, $this->_scg_name );

		if ( false === $value ) {
			$value = $wpdb->get_var( $wpdb->prepare( "SELECT session_value FROM {$this->_table} WHERE session_key = %s", $customer_id ) );

			if ( is_null( $value ) ) {
				$value = $default;
			}

			$cache_duration = $this->_session_expiration - time();
			if ( 0 < $cache_duration ) {
				wp_cache_add( $this->get_cache_prefix() . $customer_id, $value, $this->_scg_name, $cache_duration );
			}
		}

		return maybe_unserialize( $value );
	}
	
	public function delete_session( $customer_id ) {
		global $wpdb;

		wp_cache_delete( $this->get_cache_prefix() . $customer_id, $this->_scg_name );

		$wpdb->delete(
			$this->_table,
			array(
				'session_key' => $customer_id,
			)
		);
	}
	
	public function update_session_timestamp( $customer_id, $timestamp ) {
		global $wpdb;

		$wpdb->update(
			$this->_table,
			array(
				'session_expiry' => $timestamp,
			),
			array(
				'session_key' => $customer_id,
			),
			array(
				'%d',
			)
		);
	}
}