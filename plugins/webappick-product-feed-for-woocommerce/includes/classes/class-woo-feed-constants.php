<?php
/**
 * Class: Woo Feed Constants
 *
 * @since 4.4.41
 */

if( ! class_exists("Woo_Feed_Constants") ) {
	class Woo_Feed_Constants {
		public $version;
		function __construct() {
			$this->version = "free";
		}

		static function defined_constants() {
			if ( defined( 'WOO_FEED_FREE_VERSION' ) )
				return;

			if ( ! defined( 'WOO_FEED_FREE_VERSION' ) ) {
				/**
				 * Plugin Version
				 *
				 * @var string
				 * @since 3.1.6
				 */

				define( 'WOO_FEED_FREE_VERSION', '6.4.16' );

			}

			if ( ! defined( 'WOO_FEED_FREE_PATH' ) ) {
				/**
				 * Plugin Path with trailing slash
				 *
				 * @var string dirname( __FILE__ )
				 * * @since 3.1.6
				 */
				/** @define "WOO_FEED_FREE_PATH" "./" */ // phpcs:ignore
				define( 'WOO_FEED_FREE_PATH', plugin_dir_path( WOO_FEED_FREE_FILE ) );
			}
			if ( ! defined( 'WOO_FEED_FREE_ADMIN_PATH' ) ) {
				/**
				 * Admin File Path with trailing slash
				 *
				 * @var string
				 * @since 3.1.6
				 */
				define( 'WOO_FEED_FREE_ADMIN_PATH', WOO_FEED_FREE_PATH . 'admin/' );
			}



			if ( ! defined( 'WOO_FEED_FREE_LIBS_PATH' ) ) {
				/**
				 * Admin File Path with trailing slash
				 *
				 * @var string
				 */
				define( 'WOO_FEED_FREE_LIBS_PATH', WOO_FEED_FREE_PATH . 'libs/' );
			}
			if ( ! defined( 'WOO_FEED_PLUGIN_URL' ) ) {
				/**
				 * Plugin Directory URL
				 *
				 * @var string
				 * @since 3.1.37
				 */
				define( 'WOO_FEED_PLUGIN_URL', trailingslashit( plugin_dir_url( WOO_FEED_FREE_FILE ) ) );
			}
			if ( ! defined( 'WOO_FEED_MIN_PHP_VERSION' ) ) {
				/**
				 * Minimum PHP Version Supported
				 *
				 * @var string
				 * @since 3.1.41
				 */
				define( 'WOO_FEED_MIN_PHP_VERSION', '5.6' );
			}
			if ( ! defined( 'WOO_FEED_MIN_WC_VERSION' ) ) {
				/**
				 * Minimum WooCommerce Version Supported
				 *
				 * @var string
				 * @since 3.1.45
				 */
				define( 'WOO_FEED_MIN_WC_VERSION', '3.2' );
			}
			if ( ! defined( 'WOO_FEED_PLUGIN_BASE_NAME' ) ) {
				/**
				 * Plugin Base name..
				 *
				 * @var string
				 * @since 3.1.41
				 */
				define( 'WOO_FEED_PLUGIN_BASE_NAME', plugin_basename( WOO_FEED_FREE_FILE ) );
			}

			if ( ! defined( 'WOO_FEED_LOG_DIR' ) ) {
				$upload_dir = wp_get_upload_dir();
				/**
				 * Log Directory
				 *
				 * @var string
				 * @since 3.2.1
				 */
				/** @define "WOO_FEED_LOG_DIR" "./../../uploads/woo-feed/logs" */ // phpcs:ignore
				define( 'WOO_FEED_LOG_DIR', $upload_dir['basedir'] . '/woo-feed/logs/' );
			}

			if ( ! defined( 'WOO_FEED_CACHE_TTL' ) ) {
				$_cache_ttl = get_option( 'woo_feed_settings', array( 'cache_ttl' => 6 * HOUR_IN_SECONDS ) );
				/**
				 * Cache TTL
				 *
				 * @var int
				 * @since 3.3.11
				 */
				define( 'WOO_FEED_CACHE_TTL', $_cache_ttl['cache_ttl'] );
			}
		}
	}
}
