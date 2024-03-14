<?php

namespace StaxWoocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Plugin
 *
 * @package StaxWoocommerce
 */
class Plugin {

	/**
	 * @var null
	 */
	public static $instance;

	/**
	 * @return Plugin|null
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		require_once STAX_WOO_CORE_PATH . '/Utils.php';
		require_once STAX_WOO_CORE_PATH . '/StaxWidgets.php';

		// Admin pages
		require_once STAX_WOO_CORE_PATH . '/admin/pages/Base.php';
		require_once STAX_WOO_CORE_PATH . '/admin/pages/Widgets.php';

		require_once STAX_WOO_CORE_PATH . '/admin/Settings.php';

	}

	/**
	 * Get plugin slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return STAX_WOO_SLUG_PREFIX . 'woocommerce';
	}

	/**
	 * Check if current page is
	 *
	 * @param $page
	 *
	 * @return bool
	 */
	public function is_current_page( $page ) {
		$page = STAX_WOO_SLUG_PREFIX . $page;

		return isset( $_GET['page'] ) && $_GET['page'] === $page;
	}

}

Plugin::instance();
