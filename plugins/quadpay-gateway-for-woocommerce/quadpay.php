<?php
/*
Plugin Name: Zip Gateway for WooCommerce
Description: Use Zip as a payment gateway for WooCommerce.
Author: Zip Co Limited
Author URI: https://zip.co
Version: 1.7.3
WC requires at least: 3.1.0
WC tested up to: 7.5
*/

define( 'QUADPAY_WC_VERSION', '1.7.3' );
define( 'QUADPAY_WC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once __DIR__ . '/includes/class-quadpay-settings.php';
require_once __DIR__ . '/includes/class-quadpay-logger.php';
require_once __DIR__ . '/includes/class-quadpay-api.php';
require_once __DIR__ . '/includes/class-quadpay-widget.php';
require_once __DIR__ . '/includes/class-quadpay-mfpp.php';

function quadpay_wc_gateway() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	require_once __DIR__ . '/includes/class-quadpay-gateway.php';

	/**
	 * Add the QuadPay gateway to WooCommerce
	 *
	 * @param  array $methods Array of Payment Gateways
	 * @return  array Array of Payment Gateways
	 **/
	function quadpay_add_gateway( $methods ) {
		$methods[] = 'WC_Gateway_QuadPay';
		return $methods;
	}
	add_filter('woocommerce_payment_gateways', 'quadpay_add_gateway' );

	/**
	 * Check for the CANCELLED payment status
	 * We have to do this before the gateway initialises because WC clears the cart before initialising the gateway
	 */
	function quadpay_check_for_cancelled_payment() {
		// Check if the payment was cancelled
		if (isset($_GET['status'], $_GET['key'], $_GET['token']) && $_GET['status'] === 'cancelled') {

			$gateway = WC_Gateway_QuadPay::instance();
			$order_id = wc_get_order_id_by_order_key( $_GET['key'] );
			$order = wc_get_order( $order_id );

			if ( $order && $order->get_payment_method() === 'quadpay' ) {
				$gateway::log( 'Order ' . $order_id . ' payment cancelled by the customer while on the Zip checkout pages.' );
				$order->add_order_note( __( 'Payment cancelled by the customer while on the Zip checkout pages.', 'woo_quadpay' ) );

				if ( method_exists( $order, "get_cancel_order_url_raw" ) ) {
					wp_redirect( $order->get_cancel_order_url_raw() );
				} else {
					wp_redirect( $order->get_cancel_order_url() );
				}
				exit;
			}
		}
	}
	add_action( 'template_redirect', 'quadpay_check_for_cancelled_payment' );

	/**
	 * Call the cron task to check all pending orders status in the gateway
	 **/
	function quadpay_check_pending_orders_cron_job() {
		$gateway = WC_Gateway_QuadPay::instance();
		$gateway->check_pending_orders();
	}
	add_action( 'quadpay_thirty_minutes_cron_jobs', 'quadpay_check_pending_orders_cron_job' );

	/**
	 * Call the cron task to check the merchant payment limits on QuadPay
	 **/
	function quadpay_four_times_daily_cron_jobs() {
		$gateway = WC_Gateway_QuadPay::instance();
		$gateway->update_payment_limits();
	}
	add_action( 'quadpay_four_times_daily_cron_jobs', 'quadpay_four_times_daily_cron_jobs' );

	/**
	 * Custom WooCommerce Order Actions: Check payment status on QuadPay
	 *
	 * @param $order_actions
	 *
	 * @return mixed
	 */
	function quadpay_custom_woocommerce_order_action( $order_actions ) {

		global $post;
		global $theorder;

		// This is used by some callbacks attached to hooks such as woocommerce_order_actions which rely on the global to determine if actions should be displayed for certain orders.
		if ( ! is_object( $theorder ) ) {
			$theorder = wc_get_order( $post->ID );
		}

		$status         = $theorder->get_status();
		$payment_method = $theorder->get_payment_method();

		if ( 'quadpay' !== $payment_method ) {
			return $order_actions;
		}

		if ( in_array( $status, array( 'processing', 'completed', 'refunded' ) ) ) {
			return $order_actions;
		}

		$order_actions['check_quadpay_status'] = __( 'Check payment status on Zip' , 'woo_quadpay' );

		return $order_actions;

	}
	add_filter( 'woocommerce_order_actions', 'quadpay_custom_woocommerce_order_action' );

	/**
	 * Process custom WooCommerce Order Actions: Check payment status on QuadPay
	 *
	 * @param $order
	 */
	function quadpay_woocommerce_check_order_payment_status( $order ) {

		$order_id       = $order->get_id();
		$status         = $order->get_status();
		$payment_method = $order->get_payment_method();

		if ( 'quadpay' !== $payment_method ) {
			return;
		}

		if ( in_array( $status, array( 'processing', 'completed', 'refunded' ) ) ) {
			return;
		}

		$quadpay_order_token = get_post_meta( $order_id, '_quadpay_order_token', true );

		// Check if there's a stored order token. If not, it's not an QuadPay order.
		if ( ! $quadpay_order_token ) {
			return;
		}

		$gateway = WC_Gateway_QuadPay::instance();
		if ( !$gateway->sync_order_status( $order ) ) {
			$order->add_order_note( 'Can\'t check order payment status on Zip.' );
		}

	}
	add_action( 'woocommerce_order_action_check_quadpay_status', 'quadpay_woocommerce_check_order_payment_status' );


	// run additional features
	Quadpay_WC_Widget::instance()->init();
	(new Quadpay_WC_Mfpp())->init();
}
add_action( 'plugins_loaded', 'quadpay_wc_gateway', 0 );


/* WP-Cron activation and schedule setup */

/**
 * Schedule QuadPay WP-Cron job
 **/
function quadpay_wc_create_wpcronjob() {

	if ( ! wp_next_scheduled( 'quadpay_thirty_minutes_cron_jobs' ) ) {
		wp_schedule_event( time(),'thirtyminutes', 'quadpay_thirty_minutes_cron_jobs' );
	}

	if ( ! wp_next_scheduled( 'quadpay_forty_five_minutes_cron_jobs' ) ) {
		wp_schedule_event( time(),'fortyfiveminutes', 'quadpay_forty_five_minutes_cron_jobs' );
	}

	if ( ! wp_next_scheduled( 'quadpay_four_times_daily_cron_jobs' ) ) {
		wp_schedule_event( time(),'fourtimesdaily', 'quadpay_four_times_daily_cron_jobs' );
	}

}
add_action( 'wp', 'quadpay_wc_create_wpcronjob' );

/**
 * Delete QuadPay WP-Cron job
 **/
function quadpay_delete_wpcronjob() {

	wp_clear_scheduled_hook( 'quadpay_do_cron_jobs' );
	wp_clear_scheduled_hook( 'quadpay_thirty_minutes_cron_jobs' );
	wp_clear_scheduled_hook( 'quadpay_forty_five_minutes_cron_jobs' );
	wp_clear_scheduled_hook( 'quadpay_four_times_daily_cron_jobs' );

}
register_deactivation_hook( __FILE__, 'quadpay_delete_wpcronjob' );

/**
 * Add custom WP-Cron job scheduling intervals
 *
 * @param  array $schedules
 * @return array Array of schedules
 **/
function quadpay_add_custom_cron_schedules( $schedules ) {

	$schedules['thirtyminutes'] = array(
		'interval' => 30 * 60,
		'display'  => __( 'Every 30 minutes', 'woo_quadpay' ),
	);

	$schedules['fortyfiveminutes'] = array(
		'interval' => 45 * 60,
		'display'  => __( 'Every 45 minutes', 'woo_quadpay' ),
	);

	$schedules['fourtimesdaily'] = array(
		'interval' => 6 * HOUR_IN_SECONDS,
		'display'  => __( '4 times a day', 'woo_quadpay' ),
	);

	return $schedules;
}
add_filter( 'cron_schedules', 'quadpay_add_custom_cron_schedules' );

/**
 * Add Settings link to the plugin entry in the plugins menu
 **/
function quadpay_woocommerce_plugin_action_links( $links ) {

	$settings_link = array(
		'settings' => sprintf( __( '<a href="%s" title="View WooCommerce Zip Gateway Settings">Settings</a>', 'woo_quadpay' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=quadpay' ) ),
		'support'  => sprintf( __( '<a href="%s" title="Plugin Support" target="_blank">Support</a>', 'woo_quadpay' ), 'https://wordpress.org/support/plugin/quadpay-gateway-for-woocommerce/' ),
		'signup'  => sprintf( __( '<a href="%s" title="Create your Zip merchant account" target="_blank">Sign Up</a>', 'woo_quadpay' ), 'https://www.quadpay.com/signup-merchant/' )
	);

	return array_merge( $settings_link, $links );

}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'quadpay_woocommerce_plugin_action_links' );
