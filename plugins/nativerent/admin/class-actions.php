<?php

namespace NativeRent\Admin;

use NativeRent\API;
use NativeRent\Install;
use NativeRent\Options;

use function add_action;
use function array_map;
use function defined;
use function do_action;
use function get_site_url;
use function getenv;
use function is_array;
use function is_null;
use function nativerent_clear_cache;
use function nativerent_clear_cache_possible;
use function reset;
use function sanitize_text_field;
use function wp_parse_url;
use function wp_unslash;
use function wp_verify_nonce;

use const NATIVERENT_PARAM_AUTH;
use const PHP_URL_HOST;

defined( 'ABSPATH' ) || exit;

/**
 * Admin handlers
 */
class Actions {
	const ACTIONS_PARAM_NAME = 'nativerent_admin_action';
	const ACTION_INIT = 'initialization';
	const ACTION_AD_UNITS_CONFIG = 'ad_units_config';
	const ACTION_PURGE = 'purge';

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Nonce status
	 *
	 * @var bool|null
	 */
	private $nonce_status = null;

	/**
	 * Main Instance.
	 *
	 * @return self
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * A dummy magic method to prevent class from being cloned
	 */
	public function __clone() {
	}

	/**
	 * A dummy magic method to prevent class from being un-serialized
	 */
	public function __wakeup() {
	}

	/**
	 * Constructor is private to prevent creating new instances
	 */
	private function __construct() {
		Cache_Actions::init();

		// Process admin page actions.
		add_action( 'admin_init', array( $this, 'clear_cache_check' ) );
		add_action( 'admin_init', array( $this, 'do_admin_action' ), 5 );
		add_action( 'admin_init', array( $this, 'check_authentication' ), 15 );
	}

	/**
	 * Nativerent do admin action
	 */
	public function do_admin_action() {
		$action = $this->get_request_param( self::ACTIONS_PARAM_NAME );
		if ( ! $action ) {
			return;
		}

		// Processing.
		switch ( $action ) {
			case self::ACTION_INIT:
				$this->add_initialization_action();
				break;

			case self::ACTION_AD_UNITS_CONFIG:
				$this->add_units_config_action();
				break;

			case self::ACTION_PURGE:
				$this->add_purge_action();
				break;
		}
	}

	/**
	 * Check nonce
	 */
	private function check_nonce() {
		if ( null !== $this->nonce_status ) {
			return $this->nonce_status;

		} else {
			// CSRF protection.
			if (
				! isset( $_REQUEST[ Settings::NONCE_NAME ] ) ||
				! wp_verify_nonce(
					sanitize_text_field( wp_unslash( $_REQUEST[ Settings::NONCE_NAME ] ) ),
					Settings::NONCE_ACTION
				)
			) {
				$this->add_notice( 'nonce_expired' );
				$this->nonce_status = false;

			} else {
				$this->nonce_status = true;
			}
		}

		return $this->nonce_status;
	}

	/**
	 * Get admin action
	 *
	 * @param string $action Action to get.
	 */
	private function get_request_param( $action ) {
		if ( ! isset( $_REQUEST[ $action ] ) || ! $this->check_nonce() ) {
			return false;
		}

		if ( is_array( $_REQUEST[ $action ] ) ) {
			$action_arr = wp_unslash( $_REQUEST[ $action ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

			return array_map(
				function ( $val ) {
					return sanitize_text_field( $val );
				},
				$action_arr
			);
		}

		return sanitize_text_field( wp_unslash( $_REQUEST[ $action ] ) );
	}

	/**
	 * Initialization action
	 */
	private function add_initialization_action() {
		$auth_domain = getenv( 'NATIVERENT_AUTH_DOMAIN' );
		if ( ! empty( $auth_domain ) ) {
			$domain = sanitize_text_field( ( wp_unslash( $auth_domain ) ) );
		} else {
			$domain = wp_parse_url( get_site_url(), PHP_URL_HOST );
		}
		Options::set( 'domain', $domain );

		$nr_login = $this->get_request_param( Settings::LOGIN_FIELD );
		$nr_pass  = $this->get_request_param( Settings::PASS_FIELD );
		if ( ! $nr_login || ! $nr_pass ) {
			return;
		}

		$auth = API::auth( $nr_login, $nr_pass, $domain );
		if ( is_array( $auth ) && ! empty( $auth['success'] ) ) {
			Install::activate_plugin();
			if (
				! isset( $_GET[ NATIVERENT_PARAM_AUTH ] )
				&& empty( Options::get_adunits_config() )
			) {
				$ad_units_config = Options::create_adunits_config_map();
				Options::update_adunits_config( $ad_units_config );
			}
			Cache_Actions::need_to_clear_cache();
			API::settings( array( 'adUnits' => Options::get_adunits_config() ) );
		} elseif ( ! empty( $auth['errors'] ) ) {
			$_SESSION['NativeRentAuthError'] = is_array( $auth['errors'] )
				? reset( $auth['errors'] )
				: (string) $auth['errors'];
		}
	}

	/**
	 * Add notice
	 *
	 * @param string $notice Notice name to add.
	 */
	private function add_notice( $notice = '' ) {
		Notices::add_notice( $notice );
	}

	/**
	 * Units config
	 */
	private function add_units_config_action() {
		//phpcs:disable
		$ad_units_config = Options::create_adunits_config_map(
			isset( $_REQUEST['NativeRentAdmin_adUnitsConfig'] )
				? $_REQUEST['NativeRentAdmin_adUnitsConfig']
				: array()
		);
		//phpcs:enable

		if ( Options::update_adunits_config( $ad_units_config ) ) {
			Cache_Actions::need_to_clear_cache( '2' ); // Need to clear cache after settings update.
		}

		$this->add_notice( 'success' );
		API::send_state();
	}

	/**
	 * Logout handler
	 *
	 * @return void
	 */
	private function add_purge_action() {
		Install::purge_plugin();
	}

	/**
	 * Clear cache check
	 *
	 * @return void
	 */
	public function clear_cache_check() {
		$action = $this->get_request_param( 'NativeRentAdmin_dropSiteCache' );

		if ( $action && nativerent_clear_cache_possible() ) {
			nativerent_clear_cache();
		} elseif ( $action ) {
			do_action( 'nativerent_cache_is_cleared' ); // To remove notice if clearing cannot be done.
		}
	}

	/**
	 * Check auth problems.
	 *
	 * @return void
	 */
	public function check_authentication() {
		// not authenticated.
		if ( ! Options::authenticated() && ! Settings::instance()->is_settings_page() ) {
			// Only show this notice if user isn't already on our settings page.
			$this->add_notice( 'authentication_needed' );
		}

		if ( Options::authenticated() && ! isset( $_GET[ NATIVERENT_PARAM_AUTH ] ) && Options::invalid_token() ) {
			$this->add_notice( 'refresh_authentication' );
		}
	}
}
