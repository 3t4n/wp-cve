<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Szamlazz_Compatibility {
	protected static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning this object is forbidden.', 'wc-szamlazz' ), '3.0.0' );
	}

	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'wc-szamlazz' ), '3.0.0' );
	}

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_modules' ), 100 );
	}

	public function load_modules() {
		$module_paths = array();

		//WooCommerce Advanced Quantity support
		if ( class_exists( 'Morningtrain\WooAdvancedQTY\PluginInit' ) ) {
			$module_paths['advanced_quantity'] = 'modules/class-wc-szamlazz-advanced-quantity.php';
		}

		//Hucommerce support
		if ( defined( 'SURBMA_HC_PLUGIN_VERSION_NUMBER' ) ) {
			$module_paths['hucommerce'] = 'modules/class-wc-szamlazz-hucommerce.php';
		}

		//WooCommerce EU Vat Assistant support
		if ( isset($GLOBALS['wc-aelia-eu-vat-assistant']) || defined( 'WC_VAT_COMPLIANCE_DIR' ) ) {
			$module_paths['eu_vat_assistant'] = 'modules/class-wc-szamlazz-eu-vat-assistant.php';
		}

		//WooCommerce EU Vat Number support
		if ( defined( 'WC_EU_VAT_VERSION' ) ) {
			$module_paths['wc_eu_vat_number'] = 'modules/class-wc-szamlazz-eu-vat-number.php';
		}

		//WooCommerce Product Bundles compatibility
		if ( isset($GLOBALS['woocommerce_bundles']) ) {
			$module_paths['woocommerce_bundles'] = 'modules/class-wc-szamlazz-product-bundles.php';
		}

		if ( isset($GLOBALS['TRP_LANGUAGE']) ) {
			$module_paths['translatepress'] = 'modules/class-wc-szamlazz-translatepress.php';
		}

		if ( defined( 'WOOCCM_PLUGIN_NAME' ) ) {
			$module_paths['checkout_manager'] = 'modules/class-wc-szamlazz-checkout-manager.php';
		}

		if ( defined( 'WCU_LANG_CODE' ) && WCU_LANG_CODE == 'woo-currency' ) {
			$module_paths['woo_currency'] = 'modules/class-wc-szamlazz-woo-currency.php';
		}

		if ( class_exists( 'WC_Subscriptions' ) ) {
			$module_paths['woocommerce_subscriptions'] = 'modules/class-wc-szamlazz-subscriptions.php';
		}

		if ( class_exists( 'Alg_WC_Custom_Order_Numbers' ) || defined('WT_SEQUENCIAL_ORDNUMBER_VERSION') ) {
			$module_paths['custom_order_numbers_for_woocommerce'] = 'modules/class-wc-szamlazz-custom-order-numbers.php';
		}

		if ( class_exists( 'WC_Booking_Data_Store' ) ) {
			$module_paths['woocommerce_bookings'] = 'modules/class-wc-szamlazz-bookings.php';
		}

		if ( defined( 'VP_WOO_PONT_PLUGIN_FILE' ) ) {
			$module_paths['vp_woo_pont'] = 'modules/class-wc-szamlazz-vp-woo-pont.php';
		}

		if ( defined( 'PLLWC_VERSION' ) ) {
			$module_paths['polylang'] = 'modules/class-wc-szamlazz-polylang.php';
		}

		$module_paths = apply_filters( 'wc_szamlazz_compatibility_modules', $module_paths );
		foreach ( $module_paths as $name => $path ) {
			require_once $path;
		}

	}

}
