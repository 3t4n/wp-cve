<?php
/**
* Plugin Name: Rapyd Payments
* Plugin URI: https://wordpress.org/plugins/rapyd-payments/
* Description: Accept cards and local payment methods using Rapyd.
* Author: Rapyd
* Author URI: https://rapyd.net/
* Version: 1.1.8
* Requires at least: 4.4
* Tested up to: 6.0.1
* WC requires at least: 3.0
* WC tested up to: 6.8.1
* Text Domain: rapyd-payments-plugin-for-woocommerce
* Domain Path: /languages
* Woo: 6815184:6e156d6744c61678d912f62f75bd180e
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const RAPYD_NO_KEYS_ERROR = 'No access_key or secret_key provided. Please provide access_key and secret_key for using Rapyd Payment Gateway.';
//minimums and constants
define( 'WC_RAPYD_VERSION', '1.1.8' );
define( 'WC_RAPYD_MIN_PHP_VER', '5.6.0' );
define( 'WC_RAPYD_MIN_WC_VER', '3.0' );
define( 'WC_RAPYD_FUTURE_MIN_WC_VER', '3.0' );
define( 'WC_RAPYD_MAIN_FILE', __FILE__ );
define( 'WC_RAPYD_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'WC_RAPYD_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
//end minimum


function woocommerce_rapyd_missing_wc_notice() {
	/* translators: 1. URL link. */
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Rapyd requires WooCommerce to be installed and active. You can download %s here.', 'rapyd-payments-plugin-for-woocommerce' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

function woocommerce_rapyd_wc_not_supported() {
	/* translators: $1. Minimum WooCommerce version. $2. Current WooCommerce version. */
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Rapyd requires WooCommerce %1$s or greater to be installed and active. WooCommerce %2$s is no longer supported.', 'rapyd-payments-plugin-for-woocommerce' ), esc_html(WC_RAPYD_MIN_WC_VER ), esc_html(WC_VERSION ) ) . '</strong></p></div>';
}

add_action( 'plugins_loaded', 'woocommerce_gateway_rapyd_init' );

function rapyd_activate() {
	require_once dirname(__FILE__) . '/includes/abstracts/abstract-wc-rapyd-payment-gateway.php';

	require_once dirname(__FILE__) . '/includes/payment-methods/class-wc-gateway-rapyd-card.php';

	//enable categories
	$rapyd     = new WC_Gateway_Rapyd_Card();
	$rapyd->enableCategories();
}

register_activation_hook( __FILE__, 'rapyd_activate' );

function woocommerce_gateway_rapyd_init() {

	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'woocommerce_rapyd_missing_wc_notice' );
		return;
	}

	if ( version_compare( WC_VERSION, WC_RAPYD_MIN_WC_VER, '<' ) ) {
		add_action( 'admin_notices', 'woocommerce_rapyd_wc_not_supported' );
		return;
	}

	if ( ! class_exists( 'WC_Rapyd' ) ) :

		class WC_Rapyd {

			private static $instance;

			public static function get_instance() {
				if ( null === self::$instance ) {
					self::$instance = new self();
				}
				return self::$instance;
			}

			private function __clone() {}

			public function __wakeup() {}

			private function __construct() {
				$this->init();
			}

			public function init() {

				require_once dirname(__FILE__) . '/includes/abstracts/abstract-wc-rapyd-payment-gateway.php';

				require_once dirname(__FILE__) . '/includes/payment-methods/class-wc-gateway-rapyd-card.php';
				require_once dirname(__FILE__) . '/includes/payment-methods/class-wc-gateway-rapyd-cash.php';
				require_once dirname(__FILE__) . '/includes/payment-methods/class-wc-gateway-rapyd-ewallet.php';
				require_once dirname(__FILE__) . '/includes/payment-methods/class-wc-gateway-rapyd-bank.php';
				require_once dirname(__FILE__) . '/includes/payment-methods/class-wc-gateway-rapyd-common.php';



				add_action('admin_notices', array( $this, 'rapyd_wc_keys_checking') );
				add_action('wp_ajax_generate_token', array( $this, 'generate_token') );
				add_action('wp_ajax_nopriv_generate_token', array( $this, 'generate_token') );

				add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateways' ) );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

				add_action( 'wp_enqueue_scripts', function() {
					$common     = new WC_Gateway_Rapyd_Common();
					wp_enqueue_script( 'rapyd_public', plugin_dir_url( __FILE__ ) . 'public/js/rapyd-public.js', array( 'jquery' ), '1.1.8', false );
					wp_enqueue_script('rapyd_toolkit', $common->rapyd_get_toolkit_url(), array( 'jquery' ), '1.1.8', false);
					wp_localize_script( 'rapyd_public', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
				});
				load_plugin_textdomain( 'rapyd-payments-plugin-for-woocommerce', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
			}

			public function generate_token() {
				if ( !empty( $_REQUEST['order_id'] ) && !empty( $_REQUEST['class_name'] ) ) {
					$order_id = sanitize_text_field($_REQUEST['order_id']);
					$class_name = sanitize_text_field($_REQUEST['class_name']);
					$obj = new $class_name();
					header('Content-Type: application/json');
					echo json_encode($obj->rapyd_generate_token($order_id));
				}

				exit;
			}

			public function rapyd_wc_is_keys_provided() {

				$common     = new WC_Gateway_Rapyd_Common();
				$access_key = $common->rapyd_get_access_key();
				$secret_key = $common->rapyd_get_secret_key();

				if ( !empty( $access_key ) && !empty($secret_key) ) {
					return true;
				}
				return false;

			}

			public function rapyd_wc_keys_checking() {
				if ( !$this->rapyd_wc_is_keys_provided() ) {
					$class = 'notice notice-error';
					$message = __( RAPYD_NO_KEYS_ERROR );

					printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
				}
			}

			public function plugin_action_links( $links ) {
				$plugin_links = array(
					'<a href="admin.php?page=wc-settings&tab=checkout&section=rapyd_card">' . esc_html__( 'Settings', 'rapyd-payments-plugin-for-woocommerce' ) . '</a>',
				);
				return array_merge( $plugin_links, $links );
			}

			public function add_gateways( $methods ) {
				$methods[] = 'WC_Gateway_Rapyd_Card';
				$methods[] = 'WC_Gateway_Rapyd_Cash';
				$methods[] = 'WC_Gateway_Rapyd_Ewallet';
				$methods[] = 'WC_Gateway_Rapyd_Bank';

				return $methods;
			}

		}

		WC_Rapyd::get_instance();
	endif;

}
