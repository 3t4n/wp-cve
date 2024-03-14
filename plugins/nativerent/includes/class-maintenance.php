<?php
/**
 * Nativerent Maintanance
 *
 * @package nativerent
 */

namespace NativeRent;

use function defined;
use function filter_input;
use function header;

use const INPUT_GET;

defined( 'ABSPATH' ) || exit;

/**
 * Class Maintenance
 */
class Maintenance {
	const API_PARAM = 'NativeRentAPIv1';

	/**
	 * Init
	 */
	public static function init() {
		$action = self::get_action_name();
		if ( is_string( $action ) ) {
			self::handle_v1_api( $action );
		}
	}

	/**
	 * Getting API method name.
	 *
	 * @return string|null
	 */
	private static function get_action_name() {
		// Getting API method by GET param value.
		if ( isset( $_GET[ self::API_PARAM ] ) ) {
			$action = filter_input( INPUT_GET, self::API_PARAM );
			if ( is_string( $action ) ) {
				return $action;
			}
		}

		// Trying to get a method from the HTTP header.
		$header_name = strtoupper( 'HTTP_X_' . self::API_PARAM );
		if ( isset( $_SERVER[ $header_name ] ) ) {
			$action = filter_input( INPUT_SERVER, $header_name );
			if ( is_string( $action ) ) {
				return $action;
			}
		}

		return null;
	}

	/**
	 * API handlers
	 *
	 * @param  string $method  API method name.
	 *
	 * @return void
	 */
	public static function handle_v1_api( $method ) {
		switch ( $method ) {
			case 'check':
				API::check();
				break;

			case 'updateAdvPatterns':
				API::update_adv_patterns();
				break;

			case 'updateMonetizations':
				API::update_monetizations();
				break;

			case 'updateAdUnitsConfig':
				API::update_ad_units_config();
				break;

			case 'vars':
				API::vars();
				break;

			case 'state':
				API::state();
				break;

			case 'articles':
				API::articles();
				break;

			default:
				header( 'HTTP/1.1 403 Forbidden' );
				exit;
		}
	}
}
