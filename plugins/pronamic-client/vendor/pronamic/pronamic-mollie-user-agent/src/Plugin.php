<?php
/**
 * Pronamic Mollie User Agent Plugin
 *
 * @package   PronamicMollieUserAgent
 * @author    Pronamic
 * @copyright 2023 Pronamic
 */

namespace Pronamic\MollieUserAgent;

/**
 * Pronamic Mollie User Agent Plugin class
 */
class Plugin {
	/**
	 * Instance of this class.
	 *
	 * @since 4.7.1
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @return self A single instance of this class.
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Setup.
	 * 
	 * @return void
	 */
	public function setup() {
		if ( \has_filter( 'http_request_args', [ $this, 'http_request_args' ] ) ) {
			return;
		}

		// phpcs:ignore WordPressVIPMinimum.Hooks.RestrictedHooks.http_request_args -- Timeout is not adjusted.
		\add_filter( 'http_request_args', [ $this, 'http_request_args' ], 10, 2 );
	}

	/**
	 * Get user agent value for requests to Mollie.
	 *
	 * @link https://github.com/pronamic/wp-pronamic-pay-mollie/issues/13
	 * @return string
	 */
	private function get_user_agent() {
		return \implode(
			' ',
			[
				/**
				 * Pronamic - Mollie user agent token.
				 *
				 * @link https://github.com/pronamic/pronamic-pay/issues/12
				 */
				'uap/FyuVeDDqnKdzdry7',
				/**
				 * WordPress version.
				 *
				 * @link https://github.com/WordPress/WordPress/blob/f9db66d504fc72942515f6c0ed2b63aee7cef876/wp-includes/class-wp-http.php#L183-L192
				 */
				'WordPress/' . \get_bloginfo( 'version' ) . '; ' . \get_bloginfo( 'url' ),
			]
		);
	}

	/**
	 * Filters the arguments used in an HTTP request.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/http_request_args/
	 * @link https://github.com/pronamic/wp-pronamic-pay-mollie/issues/13
	 * @param array<string, string> $args Arguments.
	 * @param string                $url  URL.
	 * @return array<string, string>
	 */
	public function http_request_args( $args, $url ) {
		if ( ! \str_starts_with( $url, 'https://api.mollie.com/' ) ) {
			return $args;
		}

		$args['user-agent'] = $this->get_user_agent();

		return $args;
	}
}
