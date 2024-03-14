<?php
/**
 * Payment Gateways by Customer Location for WooCommerce - Main Class
 *
 * @version 1.4.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Payment_Gateways_by_Customer_Location' ) ) :

final class Alg_WC_Payment_Gateways_by_Customer_Location {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = ALG_WC_PGBCL_VERSION;

	/**
	 * @var   Alg_WC_Payment_Gateways_by_Customer_Location The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Payment_Gateways_by_Customer_Location Instance
	 *
	 * Ensures only one instance of Alg_WC_Payment_Gateways_by_Customer_Location is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  Alg_WC_Payment_Gateways_by_Customer_Location - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Payment_Gateways_by_Customer_Location Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Check for active WooCommerce plugin
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Pro
		if ( 'payment-gateways-by-customer-location-for-woocommerce-pro.php' === basename( ALG_WC_PGBCL_FILE ) ) {
			require_once( 'pro/class-alg-wc-pgbcl-pro.php' );
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
	 * @version 1.4.0
	 * @since   1.3.0
	 */
	function localize() {
		load_plugin_textdomain( 'payment-gateways-by-customer-location-for-woocommerce', false, dirname( plugin_basename( ALG_WC_PGBCL_FILE ) ) . '/langs/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function includes() {
		// Frontend functions
		require_once( 'functions/alg-wc-pgbcl-functions-frontend.php' );
		// Core
		$this->core = require_once( 'class-alg-wc-pgbcl-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.4.0
	 * @since   1.1.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( ALG_WC_PGBCL_FILE ), array( $this, 'action_links' ) );
		// Admin functions
		require_once( 'functions/alg-wc-pgbcl-functions-admin.php' );
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		// Version update
		if ( get_option( 'alg_wc_gateways_by_location_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_gateways_by_location' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'payment-gateways-by-customer-location-for-woocommerce.php' === basename( ALG_WC_PGBCL_FILE ) ) {
			$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/payment-gateways-by-customer-location-for-woocommerce/">' .
				__( 'Go Pro', 'payment-gateways-by-customer-location-for-woocommerce' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Add Payment Gateways by Customer Location settings tab to WooCommerce settings.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'settings/class-alg-wc-settings-pgbcl.php' );
		return $settings;
	}

	/**
	 * version_updated.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function version_updated() {
		// Handle deprecated options
		if ( version_compare( get_option( 'alg_wc_gateways_by_location_version', '' ), '1.1.0', '<' ) && function_exists( 'WC' ) && ( $gateways = WC()->payment_gateways->payment_gateways() ) ) {
			foreach ( $gateways as $key => $gateway ) {
				foreach ( array( 'country', 'state', 'postcode' ) as $type ) {
					foreach ( array( 'include', 'exclude' ) as $incl_or_excl ) {
						if ( false !== ( $old_value = get_option( 'alg_wc_gateways_by_location_' . $type . '_' . $incl_or_excl . '_' . $key, false ) ) ) {
							delete_option( 'alg_wc_gateways_by_location_' . $type . '_' . $incl_or_excl . '_' . $key );
							$new_value = get_option( 'alg_wc_gateways_by_location_' . $type . '_' . $incl_or_excl, array() );
							$new_value[ $key ] = $old_value;
							update_option( 'alg_wc_gateways_by_location_' . $type . '_' . $incl_or_excl, $new_value );
						}
					}
				}
			}
		}
		// Update version
		update_option( 'alg_wc_gateways_by_location_version', $this->version );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_WC_PGBCL_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_WC_PGBCL_FILE ) );
	}

}

endif;
