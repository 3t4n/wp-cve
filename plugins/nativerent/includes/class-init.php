<?php
/**
 * Init
 *
 * @package nativerent
 */

namespace NativeRent;

use function defined;
use function esc_url_raw;
use function is_admin;
use function is_null;
use function strpos;
use function wp_doing_ajax;
use function wp_unslash;

defined( 'ABSPATH' ) || exit;

/**
 * Main init
 */
class Init {
	/**
	 * The single instance of the class.
	 *
	 * @var self|null
	 */
	private static $instance = null;

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
		Install::init();

		// Include admin section.
		if ( is_admin() || wp_doing_ajax() ) {
			Admin\Settings::instance();
			Admin\Actions::instance();
		} elseif (
			( ! isset( $_SERVER['REQUEST_URI'] ) || // For WP CLI (possibly).
			  false === strpos( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'wp-login' ) )
			&&
			Options::authenticated()
		) {
			Maintenance::init();
			Handler::instance()->add_content_actions();
		}
	}
}
