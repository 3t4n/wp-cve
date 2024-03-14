<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH PayPal Express Checkout for WooCommerce
 * @since  1.0.0
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/*
 * @package YITH
 * @since  1.0.0
 */

if ( ! class_exists( 'YITH_PayPal_EC_Frontend' ) ) {
	/**
	 * Class YITH_PayPal_EC_Frontend
	 */
	class YITH_PayPal_EC_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_PayPal_EC_Frontend
		 */
		protected static $instance;


		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_PayPal_EC_Frontend
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

			if ( 'yes' !== yith_paypal_ec()->ec->enabled ) {
				return;
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 300 );

			if ( 'yes' === yith_paypal_ec()->ec->on_cart_page ) {
				// show button on cart.
				add_action( 'woocommerce_proceed_to_checkout', array( $this, 'show_button' ), 30 );
			}

			// show button PayPal Express Checkout on single product page.
			if ( 'yes' === yith_paypal_ec()->ec->on_single_product_page ) {
				add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'show_button' ) );
				add_action( 'wp_loaded', array( $this, 'clear_cart' ) );
				add_action( 'wc_ajax_yith_paypal_ec_add_to_cart', array( $this, 'ajax_ec_add_to_cart' ) );
				add_action( 'wc_ajax_yith_paypal_ec_cancelled_payment', array( $this, 'restore_cart' ) );
			}

			add_filter( 'the_title', array( $this, 'change_checkout_page_title' ) );

			add_filter( 'script_loader_tag', array( $this, 'add_bn_code_on_sdk' ), 10, 3 );

		}

		/**
		 * Add styles and scripts at frontend.
		 *
		 * @since 1.0
		 */
		public function enqueue_scripts() {

			// load the files only where is necessary.
			if ( ! ( ( 'yes' === yith_paypal_ec()->ec->on_single_product_page && is_single() ) || ( 'yes' === yith_paypal_ec()->ec->on_cart_page && is_cart() ) || is_checkout() && 'yes' === yith_paypal_ec()->ec->checkout_button ) && ! apply_filters( 'yith_ppec_show_button_everywhere', false )
			) {
				return;
			}

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script( 'yith-paypal', 'https://www.paypalobjects.com/api/checkout.js', array(), YITH_PAYPAL_EC_VERSION, true );
			wp_enqueue_script(
				'yith-paypal-ec-frontend',
				YITH_PAYPAL_EC_ASSETS_URL . '/js/yith-paypal-ec-frontend' . $suffix . '.js',
				array(
					'jquery',
					'yith-paypal',
				),
				YITH_PAYPAL_EC_VERSION,
				true
			);

			wp_localize_script(
				'yith-paypal-ec-frontend',
				'yith_paypal_ec_frontend',
				apply_filters(
					'yith_paypal_ec_localize_script',
					array(
						'color'                    => yith_paypal_ec()->ec->button_color,
						'style'                    => yith_paypal_ec()->ec->button_style,
						'size'                     => yith_paypal_ec()->ec->button_size,
						'label'                    => yith_paypal_ec()->ec->button_label,
						'fundingicons'             => yith_paypal_ec()->ec->button_cc_icons,
						'env'                      => yith_paypal_ec()->ec->env === 'live' ? 'production' : 'sandbox',
						'ajaxurl'                  => WC_AJAX::get_endpoint( '%%endpoint%%' ),
						'yith_add_to_cart_nonce'   => wp_create_nonce( 'yith_add_to_cart_nonce' ),
						'set_express_checkout_url' => add_query_arg( 'yith_paypal_set_express_checkout', 1, WC()->api_request_url( 'yith_paypal_ec' ) ),
						'locale'                   => get_locale(),
						'confirm_checkout'         => isset( WC()->session->yith_paypal_session ) ? 'yes' : 'no',
						'needs_shipping'           => isset( WC()->session->yith_paypal_session['shipping_info'] ) ? 'yes' : 'no',
					)
				)
			);
		}

		/**
		 * Clear the notices of WC after the product is added via ajax on cart.
		 *
		 * @since 1.0
		 */
		public function ajax_ec_add_to_cart() {
			wc_clear_notices();
			wp_send_json(
				array(
					'result' => 'success',
				)
			);
		}


		/**
		 * Clear cart before that the singular product is added to cart to pay it with the gateway.
		 *
		 * @since 1.0
		 */
		public function clear_cart() {
			if ( isset( $_REQUEST['action'] ) && 'yith_paypal_ec_add_to_cart' === sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) ) { //phpcs:ignore
				WC()->session->old_cart = WC()->session->get( 'cart' );
				WC()->cart->empty_cart( true );
			}
		}

		/**
		 * Restore old cart if a the customer had cancelled the transition from a singular product page.
		 *
		 * @since 1.0
		 */
		public function restore_cart() {
			if ( isset( WC()->session->old_cart ) ) {
				WC()->cart->empty_cart( true );
				WC()->session->set( 'cart', WC()->session->old_cart );
				WC()->cart->get_cart_from_session();
				WC()->cart->set_session();
				unset( WC()->session->old_cart );
			}
		}

		/**
		 * Change the checkout page title
		 *
		 * @param string $title Title.
		 * @return string
		 */
		public function change_checkout_page_title( $title ) {
			global $wp_query;
			if ( $wp_query && ! is_admin() && is_main_query() && in_the_loop() && is_page() && is_checkout() && isset( WC()->session->yith_paypal_session ) ) {
				$title = apply_filters( 'yith_paypal_ec_checkout_title', __( 'Confirm your PayPal Order', 'yith-paypal-express-checkout-for-woocommerce' ) );
				remove_filter( 'the_title', array( $this, 'change_checkout_page_title' ) );
			}
			return $title;
		}

		/**
		 * Show the PayPal button on cart page.
		 *
		 * @since 1.0
		 */
		public function show_button() {
			global $product;
			$sbs_on_cart = false;

			if ( defined( 'YITH_YWSBS_PREMIUM' ) ) {
				$sbs_on_cart    = is_callable( 'YWSBS_Subscription_Cart::cart_has_subscriptions' ) ? YWSBS_Subscription_Cart::cart_has_subscriptions() : YITH_WC_Subscription()->cart_has_subscriptions();
				$is_sbs_product = function_exists( 'ywsbs_is_subscription_product' ) ? ywsbs_is_subscription_product( $product ) : YITH_WC_Subscription()->is_subscription( $product );
				if ( ( doing_action( 'woocommerce_proceed_to_checkout' ) && $sbs_on_cart && 'no' === yith_paypal_ec()->ec->reference_transaction )
					|| ( 'no' === yith_paypal_ec()->ec->reference_transaction && $is_sbs_product ) ) {
					return;
				}
			}

			wc_get_template( 'paypal-ec-button.php', null, '', YITH_PAYPAL_EC_TEMPLATE_PATH . '/' );

		}

		/**
		 * Filters the HTML script tag of an enqueued script adding the BN code
		 *
		 * @param   string  $tag     The `<script>` tag for the enqueued script.
		 * @param   string  $handle  The script's registered handle.
		 * @param   string  $src     The script's source URL.
		 *
		 * @since 2.9.0
		 *
		 */
		public function add_bn_code_on_sdk( $tag, $handle, $src ) {
			$bn_code = 'Yith_PCP';
			if ( 'yith-paypal' === $handle ) {
				$tag = str_replace( ' id=', " data-partner-attribution-id='{$bn_code}' id=", $tag );
			}

			return $tag;
		}
	}

	/**
	 * Unique access to instance of YITH_PayPal_EC_Frontend class
	 *
	 * @return \YITH_PayPal_EC_Frontend
	 */
	function YITH_PayPal_EC_Frontend() { //phpcs:ignore
		return YITH_PayPal_EC_Frontend::get_instance();
	}
}
