<?php
	/**
	 * Plugin Name: Duplicate Variations for WooCommerce
	 * Plugin URI: https://wordpress.org/plugins/variation-duplicator-for-woocommerce/
	 * Description: Duplicate WooCommerce variable product variations with its all available properties including Variation Price, Variation Image, and SKU in just a single click.
	 * Author: Emran Ahmed
	 * Domain Path: /languages
	 * Version: 2.0.8
	 * Requires PHP: 7.4
	 * Requires at least: 5.6
	 * Tested up to: 6.3
	 * WC requires at least: 5.6
	 * WC tested up to: 8.0
	 * Text Domain: variation-duplicator-for-woocommerce
	 * Author URI: https://getwooplugins.com/
	 */
	
	defined( 'ABSPATH' ) or die( 'Keep Silent' );
	
	if ( ! defined( 'VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_VERSION' ) ) {
		define( 'VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_VERSION', '2.0.8' );
	}
	
	if ( ! defined( 'VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_FILE' ) ) {
		define( 'VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_FILE', __FILE__ );
	}
	
	// Include the main class.
	if ( ! class_exists( 'Variation_Duplicator_For_Woocommerce', false ) ) {
		require_once dirname( __FILE__ ) . '/includes/class-variation-duplicator-for-woocommerce.php';
	}
	
	// Require woocommerce admin message
	function variation_duplicator_for_woocommerce_wc_requirement_notice() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			$text    = esc_html__( 'WooCommerce', 'variation-duplicator-for-woocommerce' );
			$info    = array(
				'tab'       => 'plugin-information',
				'plugin'    => 'woocommerce',
				'TB_iframe' => 'true',
				'width'     => '640',
				'height'    => '500',
			);
			$link    = esc_url( add_query_arg( $info, admin_url( 'plugin-install.php' ) ) );
			$message = wp_kses( __( "<strong>Duplicate Variations for WooCommerce</strong> is an add-on of ", 'variation-duplicator-for-woocommerce' ), array( 'strong' => array() ) );
			
			printf( '<div class="%1$s"><p>%2$s <a class="thickbox open-plugin-details-modal" href="%3$s"><strong>%4$s</strong></a></p></div>', 'notice notice-error', $message, $link, $text );
		}
	}
	
	add_action( 'admin_notices', 'variation_duplicator_for_woocommerce_wc_requirement_notice' );
	
	/**
	 * Returns the main instance.
	 */
	
	function variation_duplicator_for_woocommerce() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		if ( ! class_exists( 'WooCommerce', false ) ) {
			return false;
		}
		
		return Variation_Duplicator_For_Woocommerce::instance();
	}
	
	add_action( 'plugins_loaded', 'variation_duplicator_for_woocommerce' );
	
	// Supporting WooCommerce High-Performance Order Storage
	function variation_duplicator_for_woocommerce_hpos_compatibility() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
	
	add_action( 'before_woocommerce_init', 'variation_duplicator_for_woocommerce_hpos_compatibility' );