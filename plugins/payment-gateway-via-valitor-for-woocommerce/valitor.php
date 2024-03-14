<?php
/*
	Plugin Name: Payment Gateway via Valitor for WooCommerce
	Description: Extends WooCommerce with a <a href="https://specs.valitor.is/acquiring/PaymentsPage/en/" target="_blank">Valitor Web Payments Page</a> gateway.
	Version: 1.9.37
	Author: Tactica
	Author URI: http://tactica.is
	Text Domain: valitor_woocommerce
	Domain Path: /languages
	Requires at least: 4.4
	WC requires at least: 3.2.3
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

define( 'VALITOR_VERSION', '1.9.37' );
define( 'VALITOR_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'VALITOR_URL', WP_PLUGIN_URL . "/" . plugin_basename( dirname( __FILE__ ) ) . '/' );

function valitor_wc_active() {
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		return true;
	} else {
		return false;
	}
}

add_action( 'plugins_loaded', 'woocommerce_valitor_init', 0 );
function woocommerce_valitor_init() {
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	//Add the gateway to woocommerce
	require_once VALITOR_DIR . '/includes/class-wc-gateway-valitor.php';
	add_filter( 'woocommerce_payment_gateways', 'add_valitor_gateway' );
	function add_valitor_gateway( $methods ) {
		$methods[] = 'WC_Gateway_Valitor';

		return $methods;
	}

	add_action( 'woocommerce_cancelled_order', 'valitor_cancel_order' );
	function valitor_cancel_order( $order_id ){
		if ( function_exists( 'wc_get_order' ) ) {
			$order = wc_get_order( $order_id );
		} else {
			$order = new WC_Order( $order_id );
		}
		if( !empty($order) && $order->get_payment_method() == 'valitor' ){
			$valitor_settings = get_option('woocommerce_valitor_settings');
			if( !empty($valitor_settings['cancelurl']) ){
				wp_safe_redirect( $valitor_settings['cancelurl'] );
				exit;
			}
		}
	}
}

add_action( 'plugins_loaded', 'woocommerce_valitor_textdomain' );
function woocommerce_valitor_textdomain(){
	global $wp_version;

	// Default languages directory for Valitor.
	$lang_dir = VALITOR_DIR . 'languages/';
	$lang_dir = apply_filters( 'valitor_languages_directory', $lang_dir );

	$current_lang = apply_filters( 'wpml_current_language', NULL );
	if($current_lang){
		$languages = apply_filters( 'wpml_active_languages', NULL );
		$locale = ( isset($languages[$current_lang]) && isset($languages[$current_lang]['default_locale']) ) ? $languages[$current_lang]['default_locale'] : '' ;
	}else{
		$locale = get_locale();
		if ( $wp_version >= 4.7 ) {
			$locale = get_user_locale();
		}
	}

	$mofile = sprintf( '%1$s-%2$s.mo', 'valitor_woocommerce', $locale );

	// Setup paths to current locale file.
	$mofile_local  = $lang_dir . $mofile;
	$mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;

	if ( file_exists( $mofile_global ) ) {
		// Look in global /wp-content/languages/valitor/ folder.
		load_textdomain( 'valitor_woocommerce', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) {
		// Look in local /wp-content/plugins/valitor/languages/ folder.
		load_textdomain( 'valitor_woocommerce', $mofile_local );
	} else {
		// Load the default language files.
		load_plugin_textdomain( 'valitor_woocommerce', false, $lang_dir );
	}
}

add_action( 'woocommerce_blocks_loaded', 'valitor_woocommerce_blocks_support' );
function valitor_woocommerce_blocks_support() {
  if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {

	require_once VALITOR_DIR . 'includes/class-payment-method-valitor-registration.php';
	add_action(
		'woocommerce_blocks_payment_method_type_registration',
		function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
			$payment_method_registry->register( new PaymentMethodValitor );
		}
	);
  }
}

add_action( 'woocommerce_blocks_checkout_enqueue_data', 'valitor_blocks_checkout_enqueue_styles' );
function valitor_blocks_checkout_enqueue_styles(){
		$payment_gateways = WC()->payment_gateways->payment_gateways();
		$enabled_gateways = array_filter( $payment_gateways, 'valitor_payment_gateways_callback');

		if(isset($enabled_gateways['valitor'])){
			wp_enqueue_style('valitor-styles-frontend');
		}
}

function valitor_payment_gateways_callback($gateway){
	return filter_var( $gateway->enabled, FILTER_VALIDATE_BOOLEAN);
}