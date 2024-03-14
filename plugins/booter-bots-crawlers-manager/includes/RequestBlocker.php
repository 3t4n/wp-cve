<?php

namespace Upress\Booter;

class RequestBlocker {

	private static $instance;

	/**
	 * @return RequestBlocker
	 */
	public static function initialize() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'muplugins_loaded', [ $this, 'maybe_block_request' ] );
	}

	/**
	 * Do the blocking
	 */
	public function maybe_block_request() {
		$settings = get_option( 'booter_settings' );

		if ( ! $settings ) {
			return;
		}

		$ip = implode( ',', Utilities::get_client_ip());

		// do not block cli
		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || defined( 'WP_CLI' ) && WP_CLI ) {
			return;
		}

		// block bad robots
		if ( Utilities::bool_value( $settings['block']['block_bad_robots'] ) ) {
			$bad_bots = is_array( $settings['block']['badrobots'] ) ? $settings['block']['badrobots'] : json_decode( $settings['block']['badrobots'] );
			foreach ( $bad_bots as $robot ) {
				if ( false !== strpos( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ), sanitize_text_field( $robot ) ) ) {
					Logger::write( "{$ip}, '{$_SERVER['HTTP_USER_AGENT']}' blocked due to bad bot block of '{$robot}'" );

					header( 'HTTP/1.0 403 Forbidden' );
					die( '<div style="text-align: center;"><h1 style="margin: 40px 0;">403 Forbidden</h1><hr><small>Booter - Bots & Crawlers Manager</small></div>' );
				}
			}
		}

		if ( ! Utilities::bool_value( $settings['block']['enabled'] ) ) {
			return;
		}

		if ( isset( $settings['block']['block_empty_useragents'] ) && Utilities::bool_value( $settings['block']['block_empty_useragents'] ) && empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			Logger::write( "{$ip}, blocked for empty user agent" );

			header( 'HTTP/1.0 403 Forbidden' );
			die( '<div style="text-align: center;"><h1 style="margin: 40px 0;">403 Forbidden</h1><hr><small>Booter - Bots & Crawlers Manager</small></div>' );
		}

		// allow users to decide not to rate limit some useragents
		$fingerprint = Utilities::generate_user_fingerprint_string();
		if ( false === apply_filters( 'booter_should_block_useragent', $fingerprint ) ) {
			return;
		}

		if ( isset( $settings['block']['block_useragents'] ) && 'bots' == $settings['block']['block_useragents'] ) {
			if ( empty( $_SERVER['HTTP_USER_AGENT'] ) || Utilities::is_user_logged_in() ) {
				return;
			}

			$useragents = array_map( function ( $item ) {
				return preg_quote( trim( $item ), '/' );
			}, Utilities::get_known_bots() );

			if ( ! preg_match( '/' . implode( '|', $useragents ) . '/i', $_SERVER['HTTP_USER_AGENT'] ) ) {
				return;
			}
		}

		$block   = false;

		$strings = is_array( $settings['block']['strings'] ) ? $settings['block']['strings'] : json_decode( $settings['block']['strings'] );

		$strings = array_map( function ( $item ) {
			return preg_quote( trim( $item ), '/' );
		}, $strings );

		// woocommerce specific
		$enabled_woocommerce = isset( $settings['block']['enabled_woocommerce'] ) ? $settings['block']['enabled_woocommerce'] : '1';
		if ( $enabled_woocommerce && count( $_GET ) && preg_match( '/filtering=|add-to-cart=|filter|orderby=|(filter_.+?=)/i', $_SERVER['QUERY_STRING'] ) ) {
			$block = true;
			Logger::write( "{$ip}, '{$_SERVER['HTTP_USER_AGENT']}' blocked for WooCommerce blocks" );
		}

		$uri = $_SERVER['REQUEST_URI'] . ( ! empty( $_SERVER['QUERY_STRING'] ) ? $_SERVER['QUERY_STRING'] : '' );

		// string filtering
		if ( ! $block && count( $strings ) ) {
			$strings = implode( '|', $strings );
			if ( preg_match( '/' . $strings . '/i', $uri ) ) {
				$block = true;
				Logger::write( "{$ip}, '{$_SERVER['HTTP_USER_AGENT']}' blocked for rejected strings" );
			}
		}

		// regex filtering
		if ( ! $block && Utilities::bool_value( $settings['block']['regex_enabled'] ) ) {
			$regex = is_array( $settings['block']['regex'] ) ? $settings['block']['regex'] : json_decode( $settings['block']['regex'] );
			if ( count( $regex ) > 0 ) {
				foreach ( $regex as $r ) {
					if ( ! empty($r) && preg_match( '#' . str_replace( '#', '\#', trim( $r ) ) . '#', $uri ) ) {
						$block = true;
						Logger::write( "{$ip}, '{$_SERVER['HTTP_USER_AGENT']}' blocked for regex block" );
						break;
					}
				}
			}
		}

		if ( $block ) {
			$response = $settings['block']['http_response'];
			switch( $response ) {
				case '401':
					$response = '401 Unauthorized';
					break;
				case '403':
					$response = '403 Forbidden';
					break;
				case '404':
					$response = '404 Page Not Found';
					break;
				default:
				case '410':
					$response = '410 Gone';
					break;
			}
			header( 'HTTP/1.0 ' . $response );
			die( '<div style="text-align: center;"><h1 style="margin: 40px 0;">' . ( $response ) . '</h1><hr><small>Booter - Bots & Crawlers Manager</small></div>' );
		}
	}
}
