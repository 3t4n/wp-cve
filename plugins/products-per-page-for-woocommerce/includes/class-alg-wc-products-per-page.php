<?php
/**
 * Products per Page for WooCommerce - Main Class
 *
 * @version 2.2.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Products_Per_Page' ) ) :

final class Alg_WC_Products_Per_Page {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = ALG_WC_PRODUCTS_PER_PAGE_VERSION;

	/**
	 * core.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	public $core;

	/**
	 * @var   Alg_WC_Products_Per_Page The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Products_Per_Page Instance
	 *
	 * Ensures only one instance of Alg_WC_Products_Per_Page is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  Alg_WC_Products_Per_Page - Main instance
	 */
	static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Products_Per_Page Constructor.
	 *
	 * @version 2.2.0
	 * @since   1.0.0
	 */
	function __construct() {

		// Check for active WooCommerce plugin
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Declare compatibility with custom order tables for WooCommerce
		add_action( 'before_woocommerce_init', array( $this, 'wc_declare_compatibility' ) );

		// Pro
		if ( 'products-per-page-for-woocommerce-pro.php' === basename( ALG_WC_PRODUCTS_PER_PAGE_FILE ) ) {
			require_once( 'pro/class-alg-wc-products-per-page-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}

	}

	/**
	 * localize.
	 *
	 * @version 1.6.0
	 * @since   1.4.0
	 */
	function localize() {
		load_plugin_textdomain( 'products-per-page-for-woocommerce', false, dirname( plugin_basename( ALG_WC_PRODUCTS_PER_PAGE_FILE ) ) . '/langs/' );
	}

	/**
	 * wc_declare_compatibility.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 *
	 * @see     https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book#declaring-extension-incompatibility
	 */
	function wc_declare_compatibility() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			$files = ( defined( 'ALG_WC_PRODUCTS_PER_PAGE_FILE_FREE' ) ?
				array( ALG_WC_PRODUCTS_PER_PAGE_FILE, ALG_WC_PRODUCTS_PER_PAGE_FILE_FREE ) :
				array( ALG_WC_PRODUCTS_PER_PAGE_FILE ) );
			foreach ( $files as $file ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $file, true );
			}
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	function includes() {
		$this->core = require_once( 'class-alg-wc-products-per-page-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.6.0
	 * @since   1.2.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( ALG_WC_PRODUCTS_PER_PAGE_FILE ), array( $this, 'action_links' ) );
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		// Version update
		if ( get_option( 'alg_products_per_page_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_products_per_page' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'products-per-page-for-woocommerce.php' === basename( ALG_WC_PRODUCTS_PER_PAGE_FILE ) ) {
			$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/products-per-page-woocommerce/">' .
				__( 'Go Pro', 'products-per-page-for-woocommerce' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Add Products per Page settings tab to WooCommerce settings.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'settings/class-alg-wc-settings-products-per-page.php' );
		return $settings;
	}

	/**
	 * version_updated.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function version_updated() {
		update_option( 'alg_products_per_page_version', $this->version );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_WC_PRODUCTS_PER_PAGE_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_WC_PRODUCTS_PER_PAGE_FILE ) );
	}

}

endif;
