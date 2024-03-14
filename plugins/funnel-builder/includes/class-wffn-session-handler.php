<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Funnel Session handler class
 * Class WFFN_Session
 */
if ( ! class_exists( 'WFFN_Session_Handler' ) ) {
	class WFFN_Session_Handler {

		private static $ins = null;
		/**
		 * @var null $transient_key
		 */
		public $transient_key = null;
		public $transient_object = null;
		private $default_group = 'funnel';
		private $groups = array( 'funnel', 'orders' );
		private $_data = array();

		/**
		 * Constructor for the session class.
		 */
		public function __construct() {

			$this->transient_object = WooFunnels_Transient::get_instance();

			add_action( 'init', array( $this, 'load_transient_from_cookie' ), 2 );
			add_action( 'init', array( $this, 'load_funnel_from_session' ), 6 );
		}


		public static function get_instance() {
			if ( self::$ins === null ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function load_funnel_from_session() {
			/**
			 * If we do not have transient key, we should not setup the data
			 */
			if ( null === $this->get_transient_key() ) {

				return;
			}
			$get_key = $this->get_transient_key();

			$data = $this->transient_object->get_transient( $get_key, 'wffn' );

			if ( false === $data ) {
				return;
			}

			foreach ( $this->groups as $group ) {
				$cookie_value = isset( $data[ $group ] ) ? wp_unslash( $data[ $group ] ) : false;

				$cookie_value          = maybe_unserialize( $cookie_value );
				$this->_data[ $group ] = apply_filters( 'wffn_front_funnel_data', $cookie_value, $group );

			}

			do_action( 'wffn_session_loaded' );

		}

		public function get_transient_key() {
			return $this->transient_key;
		}

		public function set_transient_key() {

			if ( null === $this->transient_key ) {

				$get_hash = $this->generate_transient_key();

				$this->transient_key = $get_hash;
				/**
				 * Serve the transient from the wc_session if exists
				 */

				if ( function_exists( 'WC' ) && ! is_null( WC()->session ) && WC()->session->has_session() ) {
					WC()->session->set( '_wffn_session_id', $get_hash );
				}

			}
		}

		public function get_all() {
			return $this->_data;
		}

		/**
		 * Set a session variable.
		 *
		 * @param string $key Key to set.
		 * @param mixed $value Value to set.
		 * @param mixed $group Value to set.
		 *
		 * @return object
		 */
		public function set( $key, $value, $group = null ) {
			if ( null === $group ) {
				$group = $this->default_group;
			}
			if ( $value !== $this->get( $key, null, $group ) ) {

				if ( 0 === strpos( $key, '_' ) ) {
					$this->_data[ $group ][ sanitize_key( $key ) ] = $value;
				} else {
					$this->_data[ $group ][ sanitize_key( $key ) ] = maybe_serialize( $value );

				}
			}

			return $this;

		}

		/**
		 * Get a session variable.
		 *
		 * @param string $key Key to get.
		 * @param mixed $default used if the session variable isn't set.
		 *
		 * @return array|string|object|mixed value of session variable
		 */
		public function get( $key, $default = false, $group = null ) {

			if ( null === $group ) {
				$group = $this->default_group;
			}
			$key = sanitize_key( $key );
			if ( 0 === strpos( $key, '_' ) ) {
				return isset( $this->_data[ $group ][ $key ] ) ? $this->_data[ $group ][ $key ] : $default;

			} else {
				return isset( $this->_data[ $group ][ $key ] ) ? maybe_unserialize( $this->_data[ $group ][ $key ] ) : $default;

			}
		}

		/**
		 * Destroy all session data.
		 */
		public function destroy_session() {

			$get_key = $this->get_transient_key();

			/**
			 * destroying the session means delete the respective transient.
			 * reset the value of transient key in the class object
			 * Unset the cookie in the current environment
			 */
			$this->transient_object->delete_transient( $get_key, 'wffn' );

			$this->transient_key = null;

			if ( wffn_is_wc_active() && ! is_null( WC()->session ) && WC()->session->has_session() ) {
				WC()->session->set( '_wffn_session_id', '' );
			}

			$this->set_cookie( 'wffn_ay_'.$get_key, '', time() - DAY_IN_SECONDS );
			$this->set_cookie( 'wffn_si', '', time() - DAY_IN_SECONDS );
			WFFN_Core()->logger->log( "destroying the session" . print_r( $get_key, true ) ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r


		}

		public function save( $group = null ) {

			if ( null === $group ) {
				$group = $this->default_group;
			}
			$clean_data = array();
			foreach ( $this->_data[ $group ] as $key => $data ) {

				if ( 0 === strpos( $key, '_' ) ) {
					continue;
				}
				$clean_data[ $key ] = $data;
			}

			$this->set_transient_key();

			$existing = $this->transient_object->get_transient( $this->transient_key, 'wffn' );

			if ( false === is_array( $existing ) ) {
				$existing = array();
			}

			$existing[ $group ] = $clean_data;

			$this->transient_object->set_transient( $this->transient_key, $existing, HOUR_IN_SECONDS * 24, 'wffn' );

		}

		/**
		 * Set a cookie - wrapper for setcookie using WP constants.
		 *
		 * @param string $name Name of the cookie being set.
		 * @param string $value Value of the cookie.
		 * @param integer $expire Expiry of the cookie.
		 * @param bool $secure Whether the cookie should be served only over https.
		 * @param bool $httponly Whether the cookie is only accessible over HTTP, not scripting languages like JavaScript. @since 3.6.0.
		 */
		public function set_cookie( $name, $value, $expire = 0, $secure = false, $httponly = false ) {
			if ( self::is_cli() || self::is_cron() || self::is_rest() ) {
				return;
			}
			if ( headers_sent() ) {
				WFFN_Core()->logger->log( "unable to set up cookie, headers sent" );

				return;
			}
			setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, apply_filters( 'wffn_cookie_httponly', $httponly, $name, $value, $expire, $secure ) ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.cookies_setcookie
		}


		/**
		 * Checks whether the current request is a WP cron request
		 * @return bool
		 */
		public function is_cron() {
			if ( defined( 'DOING_CRON' ) && true === DOING_CRON ) {
				return true;
			}

			return false;
		}

		/**
		 * Checks whether the current request is a WP rest request
		 * @return bool
		 */
		public function is_rest() {
			if ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) {
				return true;
			}

			return false;
		}

		/**
		 * Checks whether the current request is a WP CLI request
		 * @return bool
		 */
		public function is_cli() {
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				return true;
			}

			return false;
		}

		public function load_transient_from_cookie() {
			$cookie_value = '';
			if ( isset( $_REQUEST['wffn-si'] ) ) {  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$cookie_value = wffn_clean( $_REQUEST['wffn-si'] );  // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			}
			/**
			 * Serve the transient from the wc_session if exists
			 */
			if ( function_exists( 'WC' ) && ! is_null( WC()->session ) && WC()->session->has_session() ) {

				$cookie_value = WC()->session->get( '_wffn_session_id', '' );
			}

			if ( empty( $cookie_value ) ) {
				$cookie_value = isset( $_COOKIE['wffn_si'] ) ? wffn_clean( $_COOKIE['wffn_si'] ) : false; //phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
			}
			if ( $cookie_value && $cookie_value !== false && '' !== $cookie_value ) {
				$this->transient_key = $cookie_value;
			}
		}

		public function generate_transient_key() {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$hasher = new PasswordHash( 8, false );

			return md5( $hasher->get_random_bytes( 32 ) );
		}

		/**
		 * detect whether we have a valid session running
		 * @return bool
		 * @since 2.0
		 */
		public function has_valid_session() {
			/**
			 * if called before init then we might not have any valid session
			 */
			if ( ( 0 < did_action( 'wffn_session_loaded' ) ) || ( 0 < did_action( 'wffn_before_setup_funnel' ) ) ) {
				return true;
			}

			return false;

		}

		/**
		 * Add funnel session param in url
		 * @param $url		 *
		 * @return mixed|string
		 */
		public function maybe_add_funnel_session_param( $url ) {
			$key = WFFN_Core()->data->get_transient_key();
			if ( empty( $key ) ) {
				return $url;
			}

			return add_query_arg( array( 'wffn-si' => $key ), $url );
		}


	}
}

