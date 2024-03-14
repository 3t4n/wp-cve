<?php

/**
 * Base class for Stock Sync with Google Sheet for WooCommerce.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */
// Namespace.
namespace StockSyncWithGoogleSheetForWooCommerce;

// Exit if accessed directly.
defined('ABSPATH') || exit();

if ( ! class_exists('\StockSyncWithGoogleSheetForWooCommerce\Base') ) {

	/**
	 * Base class for Stock Sync with Google Sheet for WooCommerce.
	 *
	 * @package StockSyncWithGoogleSheetForWooCommerce
	 * @since   1.0.0
	 */
	abstract class Base {


		// Utilities Trait to use in all classes globally.
		use Utilities;

		/**
		 * The single instance of the App class.
		 *
		 * @var App
		 */
		protected $app = null;

		/**
		 * The single instance of the class.
		 *
		 * @var Base
		 */
		public static $instance = null;


		/**
		 * Ajax constructor.
		 */
		public function __construct() {
			$this->app = new App();
		}
	}
}
