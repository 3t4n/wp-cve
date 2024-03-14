<?php
namespace BetterLinks;

use BetterLinks\Link\Utils;
use DeviceDetector\DeviceDetector;

class Link extends Utils {
	private static $link_options;

	public function __construct() {
		if ( ! is_admin() && isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'] ) {
			add_action( 'init', array( $this, 'run_redirect' ), 0 );
		}
	}

	/**
	 * Redirects short links to the destination url
	 */
	public function run_redirect() {
		// Note: Using sanitize_text_field for $_SERVER['REQUEST_URI'] may not handle redirects properly when short URLs contain non-ASCII characters (e.g., Chinese).
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; // phpcs:ignore
		$request_uri = stripslashes( rawurldecode( $request_uri ) );
		$request_uri = substr( $request_uri, strlen( wp_parse_url( site_url( '/' ), PHP_URL_PATH ) ) );
		$param       = explode( '?', $request_uri, 2 );
		$data        = $this->get_slug_raw( rtrim( current( $param ), '/' ) );

		$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : ''; // phpcs:ignore
		$dd         = new DeviceDetector( $user_agent );
		$dd->parse();

		$data['is_bot'] = $dd->isBot();
		if ( empty( $data['target_url'] ) || ! apply_filters( 'betterlinks/pre_before_redirect', $data ) ) {
			if ( apply_filters( 'betterlinks/is_password_protected_redirect_compatible', false ) ) { // phpcs:ignore.
				// fetch settings to check password protected redirect is enable or not.
				$settings = get_option( 'betterlinks_links', true );
				if ( is_string( $settings ) ) {
					$settings = json_decode( $settings, true );
				}
				self::$link_options = $settings;

				if ( ! empty( self::$link_options['enable_password_protection'] ) ) {
					$referer           = isset( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : null;
					$request_uri       = site_url( '/' ) . $request_uri;
					$short_url         = $this->get_protected_self_url_short_link( $request_uri );
					$referer_short_url = $this->referer_short_url( $referer );

					// if request are coming from password protected form and password is okk.
					if ( $referer_short_url === $short_url ) {
						return false;
					}
					$data = $this->get_slug_raw( $short_url );

					$id = isset( $data['ID'] ) ? $data['ID'] : null;
					if ( empty( $id ) ) {
						return false;
					}
					$password_protection_status = \BetterLinksPro\Link::get_password_protection_status();
					$is_active_cookie           = $this->passsword_cookie_enabled( $password_protection_status['remember_password_cookies'], $id );
					if ( $is_active_cookie ) {
						return false;
					}
				}
			}

			if ( empty( $data['target_url'] ) || ! apply_filters( 'betterlinks/pre_before_redirect', $data ) ) { // phpcs:ignore
				return false;
			}
		}
		$data = apply_filters( 'betterlinks/link/before_dispatch_redirect', $data ); // phpcs:ignore.
		if ( empty( $data ) ) {
			return false;
		}

		do_action( 'betterlinks/before_redirect', $data ); // phpcs:ignore.
		$this->dispatch_redirect( $data, next( $param ) );
	}
}
