<?php
/**
 * PeachPay Extension Trait.
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

require_once PEACHPAY_ABSPATH . 'core/traits/trait-peachpay-singleton.php';

trait PeachPay_Extension {

	use PeachPay_Singleton;

	/**
	 * Initializes the extension.
	 */
	private function __construct() {
		if ( ! self::should_load() ) {
			return;
		}

		$enabled = $this->enabled();

		$this->internal_hooks( $enabled );
		$this->includes( $enabled );
	}

	/**
	 * Initialize actions and filters. This should not be attempted to be overridden. Any custom hooks
	 * should be registered in hooks.php and defined in functions.php. These two files should be loaded
	 * in the includes method.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function internal_hooks( $enabled ) {
		$extension = $this;
		add_action(
			'init',
			function () use ( $extension, $enabled ) {
				$extension->init( $enabled );
			}
		);
		add_action(
			'woocommerce_init',
			function () use ( $extension, $enabled ) {
				$extension->woocommerce_init( $enabled );
			}
		);

		add_action(
			'plugins_loaded',
			function () use ( $extension, $enabled ) {
				$extension->plugins_loaded( $enabled );
			}
		);
		add_action(
			'wp_enqueue_scripts',
			function () use ( $extension, $enabled ) {
				$extension->enqueue_public_scripts( $enabled );
			},
			PHP_INT_MAX
		);
		add_action(
			'rest_api_init',
			function () use ( $extension, $enabled ) {
				$extension->rest_api_init( $enabled );
			}
		);
	}

	/**
	 * This is called immediately when the class is constructed. This is a good time to load files and utilities that do not depend on outside plugins.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	abstract protected function includes( $enabled );

	/**
	 * This is called with the init hook.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function init( $enabled ) {}

	/**
	 * Called after all plugins are loaded when the extension is enabled.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function plugins_loaded( $enabled ) {}

	/**
	 * Initialize extension specific woocommerce dependencies.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function woocommerce_init( $enabled ) {}

	/**
	 * Load extension specific public scripts here.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function enqueue_public_scripts( $enabled ) {}

	/**
	 * Init any rest API endpoints.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function rest_api_init( $enabled ) {}

	/**
	 * This should be where you check for required dependencies for this extension. Like required plugins, libraries, etc. If something is missing, return false.
	 *
	 * @return boolean If it should load.
	 */
	abstract public static function should_load();

	/**
	 * This should be where you check if the merchant has enabled/disabled this extension.
	 *
	 * @return boolean If it should be enabled.
	 */
	abstract public static function enabled();
}
