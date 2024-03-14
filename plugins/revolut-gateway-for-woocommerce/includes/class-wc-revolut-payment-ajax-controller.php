<?php
/**
 * Handles AJAX calls, related to Revolut Payment.
 *
 * @package    WooCommerce
 * @category   Payment Gateways
 * @author     Revolut
 * @since      3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Revolut_Payment_Ajax_Controller class.
 */
class WC_Revolut_Payment_Ajax_Controller {

	use WC_Gateway_Revolut_Helper_Trait;

	/**
	 * API client
	 *
	 * @var WC_Revolut_API_Client
	 */
	public $api_client;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->api_settings = revolut_wc()->api_settings;
		$this->api_client   = new WC_Revolut_API_Client( $this->api_settings );

		add_action( 'wc_ajax_wc_revolut_validate_checkout_fields', array( $this, 'wc_revolut_validate_checkout_fields' ) );
		add_action( 'wc_ajax_wc_revolut_validate_order_pay_form', array( $this, 'wc_revolut_validate_order_pay_form' ) );
		add_action( 'wc_ajax_wc_revolut_get_order_pay_billing_info', array( $this, 'wc_revolut_get_order_pay_billing_info' ) );
		add_action( 'wc_ajax_wc_revolut_get_customer_info', array( $this, 'wc_revolut_get_customer_info' ) );
		add_action( 'wc_ajax_wc_revolut_process_payment_result', array( $this, 'wc_revolut_process_payment_result' ) );
		add_action( 'wc_ajax_revolut_payment_request_cancel_order', array( $this, 'revolut_payment_request_ajax_cancel_order' ) );
		add_action( 'wc_ajax_revolut_payment_request_set_error_message', array( $this, 'revolut_payment_request_ajax_set_error_message' ) );
		add_action( 'wc_ajax_revolut_payment_request_log_error', array( $this, 'revolut_payment_request_ajax_log_error' ) );

		if ( is_admin() && current_user_can( 'manage_options' ) ) {
			add_action( 'wp_ajax_wc_revolut_clear_records', array( $this, 'wc_revolut_clear_records' ) );
			add_action( 'wp_ajax_wc_revolut_onboard_applepay_domain', array( $this, 'wc_revolut_onboard_applepay_domain' ) );
		}
	}

	/**
	 * Process Revolut Order
	 *
	 * @throws Exception Exception.
	 */
	public function wc_revolut_process_payment_result() {
		$nonce_value = $_REQUEST['security'] ; // phpcs:ignore
		$logged_in_cookie = false;

		if ( ! wp_verify_nonce( $nonce_value, 'wc-revolut-process-payment-result' ) ) {
			$logged_in_cookie            = isset( $_COOKIE[ LOGGED_IN_COOKIE ] ) ? wc_clean( wp_unslash( $_COOKIE[ LOGGED_IN_COOKIE ] ) ) : false;
			$_COOKIE[ LOGGED_IN_COOKIE ] = '';
		}

		check_ajax_referer( 'wc-revolut-process-payment-result', 'security' );

		if ( $logged_in_cookie ) {
			$_COOKIE[ LOGGED_IN_COOKIE ] = $logged_in_cookie;
		}

		try {
			$wc_order_id                   = isset( $_POST['wc_order_id'] ) ? (int) wc_clean( wp_unslash( $_POST['wc_order_id'] ) ) : 0;
			$selected_gateway              = isset( $_POST['revolut_gateway'] ) ? wc_clean( wp_unslash( $_POST['revolut_gateway'] ) ) : '';
			$is_express_checkout           = isset( $_POST['is_express_checkout'] ) ? (bool) wc_clean( wp_unslash( $_POST['is_express_checkout'] ) ) : false;
			$reload_checkout               = isset( $_POST['reload_checkout'] ) ? (bool) wc_clean( wp_unslash( $_POST['reload_checkout'] ) ) : false;
			$revolut_payment_public_id     = isset( $_POST['revolut_public_id'] ) ? wc_clean( wp_unslash( $_POST['revolut_public_id'] ) ) : '';
			$revolut_payment_error         = isset( $_POST['revolut_payment_error'] ) ? wc_clean( wp_unslash( $_POST['revolut_payment_error'] ) ) : '';
			$wc_payment_token_id           = isset( $_POST[ 'wc-' . WC_Gateway_Revolut_CC::GATEWAY_ID . '-payment-token' ] ) ? wc_clean( wp_unslash( $_POST[ 'wc-' . WC_Gateway_Revolut_CC::GATEWAY_ID . '-payment-token' ] ) ) : '';
			$save_payment_method_requested = isset( $_POST['revolut_save_payment_method'] ) ? (bool) wc_clean( wp_unslash( $_POST['revolut_save_payment_method'] ) ) : false;
			$is_using_saved_payment_method = ! empty( $wc_payment_token_id ) && 'new' !== $wc_payment_token_id;

			if ( empty( $wc_order_id ) || empty( $selected_gateway ) || empty( $revolut_payment_public_id ) ) {
				$this->log_error(
					array(
						'wc_order_id'       => $wc_order_id,
						'selected_gateway'  => $selected_gateway,
						'revolut_public_id' => $revolut_payment_public_id,
					)
				);

				if ( empty( $revolut_payment_error ) ) {
					$revolut_payment_error = __( 'We are unable to process your order, please try again.', 'woocommerce' );
				}
				throw new Exception( $revolut_payment_error );
			}

			$revolut_gateway = new WC_Gateway_Revolut_CC();

			if ( 'revolut_pay' === $selected_gateway ) {
				$revolut_gateway = new WC_Gateway_Revolut_Pay();
			} elseif ( 'revolut_payment_request' === $selected_gateway ) {
				$revolut_gateway = new WC_Gateway_Revolut_Payment_Request();
			}

			$result = $revolut_gateway->process_payment(
				$wc_order_id,
				$revolut_payment_public_id,
				$is_express_checkout,
				$revolut_payment_error,
				$reload_checkout,
				false,
				$is_using_saved_payment_method,
				$save_payment_method_requested,
				$wc_payment_token_id
			);
		} catch ( Exception $e ) {
			$result = array(
				'messages' => $e->getMessage(),
				'result'   => 'fail',
				'redirect' => '',
			);
		}

		try {
			if ( ! empty( $wc_order_id ) && isset( $result['result'] ) && 'success' === $result['result'] ) {
				$result['order_id'] = $wc_order_id;
				apply_filters( 'woocommerce_payment_successful_result', $result, $wc_order_id );
			}
		} catch ( Exception $e ) {
			// if hook was unsuccessful do not prevent order process.
			$this->log_error( $e->getMessage() );
		}

		wp_send_json( $result );
	}

	/**
	 * Onboard Apple Pay domain
	 *
	 * @throws Exception Exception.
	 */
	public function wc_revolut_onboard_applepay_domain() {
		if ( ! check_admin_referer( 'wc-revolut-onboard-applepay-domain' ) ) {
			die;
		}

		try {
			$domain_name = str_replace( array( 'https://', 'http://' ), '', get_site_url() );

			$onboarding_file = untrailingslashit( ABSPATH ) . '/.well-known/apple-developer-merchantid-domain-association';

			$is_exist = fopen( $onboarding_file, 'r' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fopen

			if ( ! $is_exist ) {
				wp_send_json(
					array(
						'success' => false,
						'message' => 'Can not find Apple Pay on-boarding file: ' . $onboarding_file,
					)
				);
			}

			$request_body = array(
				'domain' => $domain_name,
			);

			$this->api_settings = revolut_wc()->api_settings;
			$this->api_client   = new WC_Revolut_API_Client( $this->api_settings, true );

			$response = $this->api_client->post( '/apple-pay/domains/register', $request_body );

			$revolut_payment_request_settings                                        = get_option( 'woocommerce_revolut_payment_request_settings', array() );
			$revolut_payment_request_settings['apple_pay_merchant_onboarded_domain'] = $domain_name;
			$revolut_payment_request_settings['apple_pay_merchant_onboarded_api_key'] = $this->api_client->api_key;
			$revolut_payment_request_settings['apple_pay_merchant_onboarded']         = 'yes';
			update_option( 'woocommerce_revolut_payment_request_settings', $revolut_payment_request_settings );

			wp_send_json(
				array(
					'success'  => true,
					'response' => $response,
				)
			);

		} catch ( Exception $e ) {
			$this->log_error( $e->getMessage() );
			wp_send_json(
				array(
					'success' => false,
					'message' => $e->getMessage(),
				)
			);
		}

		wp_send_json(
			array(
				'success' => false,
				'message' => 'Something went wrong.',
			)
		);
	}

	/**
	 * Clear unused order records
	 *
	 * @throws Exception Exception.
	 */
	public function wc_revolut_clear_records() {
		if ( ! check_admin_referer( 'wc-revolut-clear-records' ) ) {
			die;
		}

		try {
			global $wpdb;
			$result = $wpdb->query( $wpdb->prepare( 'DELETE FROM `' . $wpdb->prefix . 'wc_revolut_orders` WHERE wc_order_id is NUll or wc_order_id = "";' ) ); // phpcs:ignore

			if ( ! $result && ! empty( $wpdb->last_error ) ) {
				throw new Exception( $wpdb->last_error );
			}

			wp_send_json(
				array(
					'success' => true,
					'result'  => $result,
				)
			);
		} catch ( Exception $e ) {
			$this->log_error( $e->getMessage() );
			wp_send_json(
				array(
					'success' => false,
					'message' => $e->getMessage(),
				)
			);
		}
	}

	/**
	 * Validate checkout fields
	 *
	 * @throws Exception Exception.
	 */
	public function wc_revolut_validate_checkout_fields() {
		try {
			wc_maybe_define_constant( 'WOOCOMMERCE_CHECKOUT', true );

			if ( WC()->cart->is_empty() ) {
				/* translators: %s: shop cart url */
				throw new Exception( sprintf( __( 'Sorry, your session has expired. <a href="%s" class="wc-backward">Return to shop</a>', 'woocommerce' ), esc_url( wc_get_page_permalink( 'shop' ) ) ) );
			}

			$validate_checkout = new WC_Revolut_Validate_Checkout();
			$validate_checkout->validate_checkout_fields();

			if ( 0 === wc_notice_count( 'error' ) ) {
				wp_send_json(
					array(
						'result' => 'success',
					)
				);
			}

			$validate_checkout->return_ajax_failure_response();
		} catch ( Exception $e ) {
			wc_add_notice( $e->getMessage(), 'error' );
			$messages = wc_print_notices( true );
			$response = array(
				'result'   => 'failure',
				'messages' => isset( $messages ) ? $messages : '',
			);

			wp_send_json( $response );
		}
	}

	/**
	 * Validate checkout fields
	 *
	 * @throws Exception Exception.
	 */
	public function wc_revolut_validate_order_pay_form() {
		try {
			$nonce_value = wc_get_var( $_REQUEST['woocommerce-pay-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // phpcs:ignore

			if ( ! wp_verify_nonce( $nonce_value, 'woocommerce-pay' ) ) {
				throw new Exception( __( 'Something went wrong.', 'woocommerce' ) );
			}

			$order_id    = isset( $_POST['wc_order_id'] ) ? (int) wc_clean( wp_unslash( $_POST['wc_order_id'] ) ) : 0;
			$terms_field = isset( $_POST['terms-field'] ) ? (int) wc_clean( wp_unslash( $_POST['terms-field'] ) ) : 0;
			$order_key   = isset( $_POST['wc_order_key'] ) ? wc_clean( wp_unslash( $_POST['wc_order_key'] ) ) : '';
			$terms       = isset( $_POST['terms'] ) ? wc_clean( wp_unslash( $_POST['terms'] ) ) : '';
			$order       = wc_get_order( $order_id );

			if ( $order_id === $order->get_id() && hash_equals( $order->get_order_key(), $order_key ) && $order->needs_payment() ) {
				do_action( 'woocommerce_before_pay_action', $order );
				if ( ! empty( $terms_field ) && empty( $terms ) ) {
					throw new Exception( __( 'Please read and accept the terms and conditions to proceed with your order.', 'woocommerce' ) );
				}
			} else {
				throw new Exception( __( 'Something went wrong.', 'woocommerce' ) );
			}

			wp_send_json(
				array(
					'result' => 'success',
				)
			);
		} catch ( Exception $e ) {
			$this->log_error( 'wc_revolut_validate_order_pay_form: ' . $e->getMessage() );

			wc_add_notice( $e->getMessage(), 'error' );

			wp_send_json(
				array(
					'result'   => 'failure',
					'messages' => wc_print_notices( true ),
				)
			);
		}
	}

	/**
	 * Get billing info for manual order payments
	 */
	public function wc_revolut_get_order_pay_billing_info() {
		check_ajax_referer( 'wc-revolut-get-billing-info', 'security' );

		$order_id  = isset( $_POST['order_id'] ) ? (int) wc_clean( wp_unslash( $_POST['order_id'] ) ) : 0;
		$order_key = isset( $_POST['order_key'] ) ? wc_clean( wp_unslash( $_POST['order_key'] ) ) : '';

		$order = wc_get_order( $order_id );
		// validate order key.
		if ( $order && $order_key === $order->get_order_key() ) {
			$billing_address = $order->get_address( 'billing' );
			$billing_info    = array(
				'name'           => $billing_address['first_name'] . ' ' . $billing_address['last_name'],
				'email'          => $billing_address['email'],
				'phone'          => $billing_address['phone'],
				'billingAddress' => array(
					'countryCode' => $billing_address['country'],
					'region'      => $billing_address['state'],
					'city'        => $billing_address['city'],
					'streetLine1' => $billing_address['address_1'],
					'streetLine2' => $billing_address['address_2'],
					'postcode'    => $billing_address['postcode'],
				),
			);
			wp_send_json( $billing_info );
		}
		wp_send_json( array() );
	}

	/**
	 * Get billing info for payment method save
	 */
	public function wc_revolut_get_customer_info() {
		check_ajax_referer( 'wc-revolut-get-customer-info', 'security' );

		$customer_id = get_current_user_id();
		$customer    = new WC_Customer( $customer_id );
		// validate order key.
		if ( $customer_id ) {
			$billing_info = array(
				'name'  => $customer->get_first_name() . ' ' . $customer->get_last_name(),
				'email' => $customer->get_email(),
				'phone' => $customer->get_billing_phone(),
			);
			wp_send_json( $billing_info );
		} else {
			wp_send_json(
				array(
					'error' => true,
					'msg'   => 'Can not find customer address',
				)
			);
		}
		wp_die();
	}

	/**
	 * Cancel api order
	 */
	public function revolut_payment_request_ajax_cancel_order() {
		check_ajax_referer( 'wc-revolut-cancel-order', 'security' );
		$revolut_public_id = isset( $_POST['revolut_public_id'] ) ? wc_clean( wp_unslash( $_POST['revolut_public_id'] ) ) : '';

		$revolut_order_id = $this->get_revolut_order_by_public_id( $revolut_public_id );

		try {
			$revolut_gateway = new WC_Gateway_Revolut_CC();
			$revolut_gateway->action_revolut_order( $revolut_order_id, 'cancel' );
			$revolut_gateway->clear_temp_session( $revolut_order_id );
			$revolut_public_id = $this->create_revolut_order( $revolut_gateway->get_revolut_order_descriptor(), true );
			$revolut_gateway->set_revolut_express_checkout_public_id( $revolut_public_id );
			wp_send_json(
				array(
					'success'           => true,
					'revolut_public_id' => $revolut_public_id,
				)
			);
		} catch ( Exception $e ) {
			wp_send_json( array( 'success' => false ) );
			$this->log_error( $e );
		}
	}

	/**
	 * Set error message
	 */
	public function revolut_payment_request_ajax_set_error_message() {
		check_ajax_referer( 'wc-revolut-set-error-message', 'security' );
		$error_message = isset( $_POST['revolut_payment_request_error'] ) ? wc_clean( wp_unslash( $_POST['revolut_payment_request_error'] ) ) : '';

		if ( empty( $error_message ) ) {
			$error_message = __( 'Something went wrong', 'revolut-gateway-for-woocommerce' );
		}

		wc_add_notice( $error_message, 'error' );
	}

	/**
	 * Log error message
	 */
	public function revolut_payment_request_ajax_log_error() {
		check_ajax_referer( 'wc-revolut-log-errors', 'security' );
		$error_message = isset( $_POST['revolut_payment_request_error'] ) ? wc_clean( wp_unslash( $_POST['revolut_payment_request_error'] ) ) : '';
		$this->log_error( $error_message );
	}
}
