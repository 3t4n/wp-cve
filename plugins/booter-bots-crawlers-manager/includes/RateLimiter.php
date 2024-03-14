<?php

namespace Upress\Booter;

class RateLimiter {
	private static $instance;

	/**
	 * @return RateLimiter
	 */
	public static function initialize() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'muplugins_loaded', [ $this, 'maybe_rate_limit' ] );
	}

	/**
	 *  Do the rate filtering
	 */
	function maybe_rate_limit() {
		$settings = get_option( 'booter_settings' );

		if ( ! Utilities::bool_value( $settings['rate_limit']['enabled'] ) ) {
			return;
		}

		if ( Utilities::is_running_in_cli() || Utilities::is_request_coming_from_server_ip() ) {
			return;
		}

		$fingerprint = Utilities::generate_user_fingerprint_string();

		if ( ! empty( $settings['rate_limit']['exclude'] ) ) {
			$excluded_useragents = is_array( $settings['rate_limit']['exclude'] ) ? $settings['rate_limit']['exclude'] : json_decode( $settings['rate_limit']['exclude'] );
			$excluded_useragents = array_map( function ( $item ) {
				return preg_quote( trim( $item ), '/' );
			}, $excluded_useragents );

			if ( count( $excluded_useragents ) > 0 && ! empty( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/' . implode( '|', $excluded_useragents ) . '/i', $_SERVER['HTTP_USER_AGENT'] ) ) {
				Logger::write( "'{$_SERVER['HTTP_USER_AGENT']}' is excluded via useragent whitelist" );
				return;
			}
		}

		$MAX_ATTEMPTS = min( max( intval( $settings['rate_limit']['requests_limit'] ), 10 ), 60 );
		$LOCKOUT_TIME = min( max( intval( $settings['rate_limit']['block_for'] ), 300 ), 3600 );
		$INTERVAL     = 60;

		// allow users to decide not to rate limit some useragents
		if ( false === apply_filters( 'booter_should_rate_limit_useragent', $fingerprint ) ) {
			Logger::write( "'{$_SERVER['HTTP_USER_AGENT']}' is excluded via fingerprint filter" );
			return;
		}

		// allow user to block logged in users, by default we won't block them
		if ( ! Utilities::bool_value( $settings['rate_limit']['enabled_logged_in'] ) && Utilities::is_user_logged_in() ) {
			return;
		}

		$id            = hash( 'sha256', $fingerprint );
		$transient_key = "rate_limit_{$id}";
		$last_access   = get_transient( $transient_key );

		$attempts          = 1;
		$last_access_time  = time();
		$should_be_blocked = false;
		$first_block       = true;

		if ( false !== $last_access ) {
			$attempts          = $last_access['attempts'] + 1;
			$last_access_time  = $last_access['last_access_time'];
			$should_be_blocked = $last_access['should_be_blocked'];

			if ( $should_be_blocked ) {
				$first_block = false;
			}

			if ( ( time() - intval( $last_access_time ) ) <= $INTERVAL ) {
				if ( $attempts > $MAX_ATTEMPTS ) {
					$should_be_blocked = true;
				}
			} else {
				$attempts = 1;
			}

			$last_access_time = time();
		}

		if ( ! $should_be_blocked || $first_block ) {
			set_transient( $transient_key, compact( 'last_access_time', 'attempts', 'should_be_blocked' ), $LOCKOUT_TIME );
		}

		if ( $should_be_blocked ) {
			if ( $first_block ) {
				Logger::write( "'{$_SERVER['HTTP_USER_AGENT']}' blocked by rate limit" );
			}

			header( 'HTTP/1.0 429 Too Many Requests', true, 429 );
			header( 'Retry-After: ' . date( 'r', time() + $LOCKOUT_TIME ) );
			die( '<div style="text-align: center;"><h1 style="margin: 40px 0;">429 Too Many Requests</h1><hr><small>Booter - Bots & Crawlers Manager</small></div>' );
		}
	}
}
