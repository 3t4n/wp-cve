<?php
/**
 * Plugin Name: Skrill - WooCommerce
 * Plugin URI:  http://www.skrill.com/
 * Description: WooCommerce with Skrill payment gateway
 * Author:      Skrill
 * Author URI:  hhttp://www.skrill.com/
 * Version:     1.0.58
 *
 * @package Skrill
 */

/**
 * Copyright (c) Skrill
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once dirname( __FILE__ ) . '/skrill-install.php';
register_activation_hook( __FILE__, 'skrill_activate_plugin' );
register_uninstall_hook( __FILE__, 'skrill_uninstall_plugin' );
add_action( 'plugins_loaded', 'skrill_init_payment_gateway', 0 );

define( 'SKRILL_PLUGIN_VERSION', '1.0.58' );
define( 'SKRILL_PAYMENT_HOST', 'pay.skrill.com' );

/**
 * Skrill get notice when woocommerce not active.
 */
function skrill_get_notice_woocommerce_activation() {
	echo '<div id="notice" class="error"><p>';
	echo '<a href="http://www.woothemes.com/woocommerce/" style="text-decoration:none" target="_new">WooCommerce </a>';
	echo esc_attr( __( 'must be active for the plugin', 'wc-skrill' ) ) . '<b> Skrill Payment Gateway for WooCommerce</b>';
	echo '</p></div>';
}

/**
 * Add Skrill configuration link at plugin installation
 *
 * @param array $links - links.
 */
function skrill_add_configuration_links( $links ) {
	$configuration_links = array(
		'
        <a href="' . admin_url( 'admin.php?page=wc-settings&tab=skrill_settings' ) . '">' .
						__( 'Skrill Settings', 'wc-skrill' ) . '</a>
    ',
	);
	return array_merge( $configuration_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'skrill_add_configuration_links' );

/**
 * Init Skrill payment gateway
 */
function skrill_init_payment_gateway() {
	/* load plugin language */
	load_plugin_textdomain( 'wc-skrill', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages/' );

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		add_action( 'admin_notices', 'skrill_get_notice_woocommerce_activation' );
		return;
	}

	include_once dirname( __FILE__ ) . '/includes/core/class-skrill-configuration.php';
	include_once dirname( __FILE__ ) . '/includes/core/class-skrill-payment.php';
	include_once dirname( __FILE__ ) . '/includes/admin/class-skrill-payment-configuration.php';
	include_once dirname( __FILE__ ) . '/includes/admin/class-skrill-settings.php';
	include_once dirname( __FILE__ ) . '/models/class-skrill-transactions-model.php';

	if ( ! class_exists( 'Skrill_Payment_Gateway' ) ) {
		include_once dirname( __FILE__ ) . '/class-skrill-payment-gateway.php';
	}

	/**
	 * Add Skrill Payment Methods to WooCommerce
	 *
	 * @param  array $payment_methods - payment methods.
	 * @return array
	 */
	function skrill_add_payment_methods( $payment_methods ) {
		$payment_methods[] = 'Gateway_Skrill_Flexible';
		$payment_methods[] = 'Gateway_Skrill_WLT';
		$payment_methods[] = 'Gateway_Skrill_PSC';
		$payment_methods[] = 'Gateway_Skrill_ACC';
		$payment_methods[] = 'Gateway_Skrill_VSA';
		$payment_methods[] = 'Gateway_Skrill_MSC';
		$payment_methods[] = 'Gateway_Skrill_MAE';
		$payment_methods[] = 'Gateway_Skrill_OBT';
		$payment_methods[] = 'Gateway_Skrill_GIR';
		$payment_methods[] = 'Gateway_Skrill_SFT';
		$payment_methods[] = 'Gateway_Skrill_IDL';
		$payment_methods[] = 'Gateway_Skrill_PLI';
		$payment_methods[] = 'Gateway_Skrill_PWY';
		$payment_methods[] = 'Gateway_Skrill_BLK';
		$payment_methods[] = 'Gateway_Skrill_EPY';
		$payment_methods[] = 'Gateway_Skrill_ALI';
		$payment_methods[] = 'Gateway_Skrill_NTL';
		$payment_methods[] = 'Gateway_Skrill_ADB';
		$payment_methods[] = 'Gateway_Skrill_AOB';
		$payment_methods[] = 'Gateway_Skrill_ACI';
		$payment_methods[] = 'Gateway_Skrill_PCH';
		$payment_methods[] = 'Gateway_Skrill_SPX';
		$payment_methods[] = 'Gateway_Skrill_PGF';
		$payment_methods[] = 'Gateway_Skrill_EWLTID';
		$payment_methods[] = 'Gateway_Skrill_EWLTPH';
		$payment_methods[] = 'Gateway_Skrill_EWLTKR';
		$payment_methods[] = 'Gateway_Skrill_MUB';
		$payment_methods[] = 'Gateway_Skrill_MBW';

		return $payment_methods;
	}

	add_filter( 'woocommerce_payment_gateways', 'skrill_add_payment_methods' );

	/**
	 * Add Skrill Redirect Host to WordPress
	 *
	 * @param  array $content - content.
	 * @return array
	 */
	function skrill_add_redirect_hosts( $content ) {
		$content[] = SKRILL_PAYMENT_HOST;

		return $content;
	}

	add_filter( 'allowed_redirect_hosts', 'skrill_add_redirect_hosts' );

	foreach ( glob( dirname( __FILE__ ) . '/includes/gateways/*.php' ) as $filename ) {
		include_once $filename;
	}

	add_action( 'woocommerce_subscription_status_cancelled', 'cancel_sub' );

	/**
	 * Method to handle reccurring cancellation
	 *
	 * @param  array $subscription - reccuring order.
	 */
	function cancel_sub( $subscription ) {
		$skrill_log    = new WC_Logger();
		$logger_handle = 'skrill-' . gmdate( 'Ym' );

		$skrill_log->add( $logger_handle, 'process cancel subscription' . "\r\n" );

		$order_id                                   = $subscription->get_parent_id();
		$transaction_log                            = Skrill_Transactions_Model::get_transaction_by_order_id( $order_id );
		$cancel_subscription_parameters['email']    = get_option( 'skrill_merchant_account', '' );
		$cancel_subscription_parameters['password'] = get_option( 'skrill_api_passwd', '' );
		$cancel_subscription_parameters['action']   = 'cancel_rec';
		$cancel_subscription_parameters['trn_id']   = $transaction_log['transaction_id'];
		$cancel_status                              = Skrill_Payment::cancel_recurring_payment( $cancel_subscription_parameters, $payment_result );

		$skrill_log->add( $logger_handle, 'cancel response : ' . maybe_serialize( $cancel_status ) . "\r\n" );

	}

	add_action( 'rest_api_init', 'add_response_handler_api' );

	/**
	 * Register api endpoint to handle renewal payment from skrill
	 */
	function add_response_handler_api() {
		// use hook to receive response url.
		register_rest_route(
			'woocommerce_skrill_api',
			'response_url',
			array(
				'methods'  => 'POST',
				'callback' => 'handle_response_url',
			)
		);
	}

	/**
	 * Handle recurring response from skrill gateway
	 *
	 * @return string
	 */
	function handle_response_url() {
		require_once 'models/class-skrill-transactions-model.php';
		require_once 'includes/core/class-skrill-payment.php';
		$skrill_log    = new WC_Logger();
		$logger_handle = 'skrill-' . gmdate( 'Ym' );

		$skrill_log->add( $logger_handle, 'incoming rec status url' . "\r\n" );

		$skrill_log->add( $logger_handle, 'Get payment response from rec status_url' . "\r\n" );
		$payment_response = Skrill_Payment::get_status_response();
		$skrill_log->add( $logger_handle, 'Payment response from rec status_url', $payment_response . "\r\n" );
		$skrill_settings['merchant_id'] = get_option( 'skrill_merchant_id', '' );
		$skrill_settings['secret_word'] = get_option( 'skrill_secret_word', '' );

		$generate_md5_sig                                = Skrill_Payment::generate_md5_sig( $skrill_settings, $payment_response );
		$is_payment_signature_equals_generated_signature = Skrill_Payment::is_payment_signature_equals_generated_signature( $payment_response['md5sig'], $generate_md5_sig );
		$payment_response['is_payment_signature_equals_generated_signature'] = $is_payment_signature_equals_generated_signature;
		if ( ! $is_payment_signature_equals_generated_signature ) {
			$skrill_log->add( $logger_handle, 'Fraud detection' . "\r\n" );
			return 'Your payment is suspected as fraud, please contact merchant';
		}

		$skrill_log->add( $logger_handle, 'current date ' . gmdate( 'd/m/Y' ) . "\r\n" );

		if ( ! empty( $payment_response['rec_payment_type'] ) && ! empty( $payment_response['rec_payment_id'] )
		&& ! empty( $payment_response['transaction_id'] ) ) {
			$skrill_log->add( $logger_handle, 'process add rec payment into transction log' . "\r\n" );

			$transaction_log = Skrill_Transactions_Model::get_transaction_by_transaction_id(
				$payment_response['transaction_id']
			);

			if ( $transaction_log ) {

				$parent_status_url_response = maybe_unserialize( $transaction_log['payment_response'] );

				if ( $parent_status_url_response['rec_payment_id'] !== $payment_response['rec_payment_id'] ) {
					$skrill_log->add( $logger_handle, 'no parent rec id found' . "\r\n" );
					return 'no parent rec id found';
				}

				$date_time                                = Skrill_Payment::get_current_datetime();
				$random_number                            = Skrill_Payment::get_random_number( 4 );
				$transaction_log_params                   = transaction_log_parameters_converter( $payment_response );
				$transaction_log_params['transaction_id'] = gmdate( 'ymd' ) . $payment_response['order_id'] . $date_time . $random_number;
				Skrill_Transactions_Model::save_rec_payment( $transaction_log_params );

				$skrill_log->add( $logger_handle, 'success add rec payment to transaction log' . "\r\n" );
				$order = new WC_Order( $payment_response['order_id'] );
				WC_Subscriptions_Manager::cancel_subscriptions_for_order( $order );
				return 'success cancel rec payment to transaction log';
			}
			$skrill_log->add( $logger_handle, 'parent transaction is not found' . "\r\n" );
			return 'parent transaction is not found';
		} else {
			$skrill_log->add( $logger_handle, 'required paramter is missing' . "\r\n" );
			return 'required paramter is missing';
		}
	}

	/**
	 * Convert renwewal payment response into transaction log params
	 *
	 * @param  array $payment_response - payment response.
	 * @return array
	 */
	function transaction_log_parameters_converter( $payment_response = false ) {
		$transaction_log                      = array();
		$transaction_log['order_id']          = $payment_response['order_id'];
		$transaction_log['payment_method_id'] = $payment_response['payment_method_id'];
		$transaction_log['amount']            = $payment_response['amount'];
		$transaction_log['refunded_amount']   = '0';
		$transaction_log['currency']          = $payment_response['currency'];

		$transaction_log['customer_id'] = 0;

		if ( $payment_response ) {
			if ( isset( $payment_response['transaction_id'] ) ) {
				$transaction_log['transaction_id'] = $payment_response['transaction_id'];
			}
			if ( isset( $payment_response['mb_transaction_id'] ) ) {
				$transaction_log['mb_transaction_id'] = $payment_response['mb_transaction_id'];
			}
			$transaction_log['payment_type'] = get_rec_payment_type( $payment_response );
			if ( isset( $payment_response['status'] ) ) {
				$transaction_log['payment_status'] = $payment_response['status'];
			}
			$transaction_log['additional_information'] = set_rec_payment_additional_information( $payment_response );
			$transaction_log['payment_response']       = maybe_serialize( $payment_response );
		}

		return $transaction_log;
	}

	/**
	 * Set additional information for renewal paymen
	 *
	 * @param  array $payment_response - payment response.
	 * @return array
	 */
	function set_rec_payment_additional_information( $payment_response ) {
		$additional_information = '';
		$information            = array();
		if ( isset( $payment_response['ip_country'] ) && isset( $payment_response['payment_instrument_country'] ) ) {
			$information['order_origin']  = $payment_response['ip_country'];
			$information['order_country'] = $payment_response['payment_instrument_country'];
		}
		if ( isset( $payment_response['payment_type'] ) && 'WLT' === $payment_response['payment_type'] ) {
			if ( isset( $payment_response['pay_from_email'] ) ) {
				$information['skrill_account'] = $payment_response['pay_from_email'];
			}
		}
		$additional_information = maybe_serialize( $information );
		return $additional_information;
	}

	/**
	 * Get payment type of renewal payment
	 *
	 * @param  array $payment_response - payment response.
	 * @return string
	 */
	function get_rec_payment_type( $payment_response ) {
		if ( ! empty( $payment_response['payment_type'] ) ) {
			if ( 'NGP' === $payment_response['payment_type'] ) {
				return 'OBT';
			} else {
				return $payment_response['payment_type'];
			}
		}
		return $this->payment_method;
	}
}
