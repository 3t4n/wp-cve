<?php

/**
 * Tilopay class extend from WC_Payment_Gateway, to process payment and update WOO order status.
 *
 * @package Tilopay
 */

namespace Tilopay;

use Automattic\WooCommerce\Utilities\OrderUtil;
use WC_Customer;

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class WCTilopay extends \WC_Payment_Gateway {

	public $redirectPaymentMethod;
	public $site_url;
	public $haveActiveHPOS = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $wc_currency;
		$this->id = 'tilopay';
		$this->method_title = __( 'TILOPAY', 'tilopay' );
		$this->method_description = __( 'TILOPAY.', 'tilopay' );

		// Load the settings
		$this->init_settings();

		// User defined settings
		$this->enabled = $this->settings['enabled'];
		$this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : 'Tilopay';
		$this->icon = isset( $this->settings['icon'] ) ? $this->settings['icon'] : '';
		$this->tpay_checkout_redirect = wc_get_checkout_url( );
		$this->tpay_key = $this->settings['tpay_key'];
		$this->tpay_mini_cuota = isset( $this->settings['tpay_mini_cuota'] ) ? $this->settings['tpay_mini_cuota'] : '';
		$this->tpay_tasa_cero = isset( $this->settings['tpay_tasa_cero'] ) ? $this->settings['tpay_tasa_cero'] : '';
		$this->tpay_user = $this->settings['tpay_user'];
		$this->tpay_password = $this->settings['tpay_password'];
		$this->tpay_capture = $this->settings['tpay_capture'];
		$this->tpay_capture_yes = $this->settings['tpay_capture_yes'];
		$this->tpay_logo_options = isset( $this->settings['tpay_logo_options'] ) ? $this->settings['tpay_logo_options'] : []; //array
		$this->tpay_redirect = $this->settings['tpay_redirect'];
		$this->init_form_fields( );
		$this->site_url = esc_url(home_url('/'));

		//$this->tpay_redirect == 'no' = Native | 'yes' = Redirect
		$this->nativePaymentMethod = false;
		if ( 'yes' == $this->tpay_redirect && isset( $this->tpay_redirect ) ) {
			$this->nativePaymentMethod = false;
		} else {
			$this->has_fields = true; //Direct Gateways
			$this->nativePaymentMethod = true;
		}

		// HPOS usage is enabled.
		$this->haveActiveHPOS = ( OrderUtil::custom_orders_table_usage_is_enabled() );

		//Pyament supported with tilopay
		$this->supports = \Tilopay\TilopayConfig::allowedPayments( );

		$this->hs = class_exists( 'WC_Subscriptions_Order' ); // true if substription order is activated

		if ( $this->hs ) {
			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'tpay_scheduled_subscription_payment' ), 10, 2 );
			add_action( 'woocommerce_scheduled_subscription_payment_retry', array( $this, 'tpay_retry_subscription_order' ), 1, 1 );
			add_action( 'woocommerce_subscriptions_changed_failing_payment_method_' . $this->id, array( $this, 'failing_payment_method' ), 10, 2 );
		}

		// Hooks
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

		//webhook to update order from tilopay( /?wc-api=tilopay_response_woo )
		add_action( 'woocommerce_api_tilopay_response_woo', array( $this, 'tpay_payment_webhook_process' ) );

		if ( $this->nativePaymentMethod ) {
			// We need custom JavaScript to obtain a token
			add_action( 'wp_enqueue_scripts', array( $this, 'tpay_payment_scripts' ), 9998 );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'tpay_css_front' ), 9998 );

		if ( sanitize_text_field( isset( $_REQUEST['message_error'] ) ) ) {
			// Clean
			wc_clear_notices();
			wc_add_notice( 'Error: ' . __( sanitize_text_field( $_REQUEST['message_error'] ), 'tilopay' ), 'error' );
		}

		$native_redirect = ( isset( $_REQUEST['cipherError'] ) )
			? sanitize_text_field( $_REQUEST['cipherError'] )
			: 0;

		/**
		 * Check if from ajax and have process_payment and order_id
		 */
		if ( false === $this->nativePaymentMethod) {
			$this->tpay_apply_redirect_to_payment_form( false );
		} else if (1 == $native_redirect) {
			$this->tpay_apply_redirect_to_payment_form( true );
		}

		//Get computed hash from Tilopay Server and compareted, the hash string have 64 characters
		$request_tpay_order_id = ( sanitize_text_field( isset( $_GET['tpt'] ) ) ) ? sanitize_text_field( $_GET['tpt'] ) : '';
		$computed_hash_hmac_tilopay_server = ( sanitize_text_field( isset( $_GET['OrderHash'] ) ) ) ? sanitize_text_field( $_GET['OrderHash'] ) : '';
		$request_order_id = ( sanitize_text_field( isset( $_GET['order'] ) ) ) ? sanitize_text_field( wp_unslash( $_GET['order'] ) ) : '';
		$request_code_payment = sanitize_text_field( isset( $_GET['code'] ) ) ? sanitize_text_field( $_GET['code'] ) : '';
		$request_auth_code_payment = sanitize_text_field( isset( $_GET['auth'] ) ) ? sanitize_text_field( $_GET['auth'] ) : '';
		$request_description = ( sanitize_text_field( isset( $_GET['description'] ) ) ) ? sanitize_text_field( $_GET['description'] ) : __( 'Unknown', 'tilopay' );
		$request_token_card = ( sanitize_text_field( isset( $_GET['crd'] ) ) ) ? sanitize_text_field( $_GET['crd'] ) : '';
		$selected_method = ( sanitize_text_field( isset( $_GET['selected_method'] ) ) ) ? sanitize_text_field( $_GET['selected_method'] ) : '';

		if ( !empty( $request_order_id ) ) {
			//remove file each time have been used
			if ( file_exists( WP_PLUGIN_DIR . '/tilopay/includes/3ds_payment.html' ) ) {
				unlink( WP_PLUGIN_DIR . '/tilopay/includes/3ds_payment.html' );
			}


			//Get Woocommerce order
			global $woocommerce;

			$order = wc_get_order( $request_order_id ); //working with notice php

			if ( $order ) {
				// Get the Customer billing email
				$billing_email= $order->get_billing_email( );

				if ( $this->haveActiveHPOS ) {
					// HPOS
					$existing_auth_code = $order->get_meta( 'tilopay_auth_code', true);
				} else {
					$existing_auth_code = get_post_meta( $request_order_id, 'tilopay_auth_code', true );
				}

				$textOrderTilopay = ( 'yes' == $this->tpay_capture ) ? __( 'Capture', 'tilopay' ) : __( 'Authorization', 'tilopay' );
				$orderType = ( 'yes' == $this->tpay_capture ) ? 1 : 0;
				//set last action done
				if ($this->haveActiveHPOS) {
					// HPOS
					$order->update_meta_data( 'tilopay_is_captured', $orderType );
				} else {
					update_post_meta( $request_order_id, 'tilopay_is_captured', $orderType );
				}

				if ( sanitize_text_field( isset( $_REQUEST['wp_cancel'] ) ) && 'yes' == sanitize_text_field( $_REQUEST['wp_cancel'] ) ) {
					//user cancel the process payment
					if ( sanitize_text_field( isset( $_REQUEST['order'] ) ) ) {

						$message_text = esc_html( __( '¡Process canceled by user!', 'tilopay' ) );
						if ($this->haveActiveHPOS) {
							//HPOS
							$order->update_meta_data( 'tpay_cancel', 'yes' );
							$order->save();
						} else {
							update_post_meta( sanitize_text_field( $_REQUEST['order'] ), 'tpay_cancel', 'yes' );
						}
						wc_add_notice( __( $message_text, 'tilopay' ), 'error' );
					}
				} else if ( !empty( $request_code_payment ) && 1 == $request_code_payment ) {

					//$order = new \WC_Order( $request_order_id );
					$amount = $order->get_total( );

					//computed hash_hmac
					$customer_computed_hash_hmac = $this->computed_customer_hash( $request_order_id, $amount, get_woocommerce_currency( ), $request_tpay_order_id, $request_code_payment, $request_auth_code_payment, $billing_email );

					//check approved order
					if ( !empty( $computed_hash_hmac_tilopay_server ) && 64 == strlen( $computed_hash_hmac_tilopay_server ) ) {

						/**
						 *
						 * Use hmac data to check if the response is from Tilopay or not
						 *
						 */
						if ( !hash_equals( $customer_computed_hash_hmac, $computed_hash_hmac_tilopay_server ) || ( empty( $request_auth_code_payment ) || strlen( $request_auth_code_payment ) < 6 ) ) {

							// translators: %s the order number.
							$order->add_order_note( __( 'Description:', 'tilopay' ) . sprintf( __( 'Invalid order confirmation, please check and try again or contact the seller to completed you order no.%s', 'tilopay' ), $request_order_id ) );
							$order->add_order_note( 'Order hash:', ' tpay:' . sanitize_text_field( $computed_hash_hmac_tilopay_server ) . '|website:' . sanitize_text_field( $customer_computed_hash_hmac ) );
							// translators: %s the order number.
							wc_add_notice( 'Error: ' . sprintf( __( 'Invalid order confirmation, please check and try again or contact the seller to completed you order no.%s', 'tilopay' ), $request_order_id ), 'error' );
							$order->update_status( 'failed' );

							// translators: %s the order number.
							$error_message_validation = sprintf( __( 'Invalid order confirmation, please check and try again or contact the seller to completed you order no.%s', 'tilopay' ), $request_order_id );
							$checkout_url = wc_get_checkout_url( );
							$pos = strpos( $checkout_url, '?' );
							if ( false === $pos ) {
								$checkout_url = $checkout_url . '?message_error=' . $error_message_validation;
							} else {
								$checkout_url = $checkout_url . '&message_error=' . $error_message_validation;
							}
							if ($this->haveActiveHPOS) {
								//Update changes
								//$order->save();
							}
							header('Cache-Control: no-cache, must-revalidate');
							header('Location: ' . $checkout_url, true, 307);
							exit;

						} else if ( hash_equals( $customer_computed_hash_hmac, $computed_hash_hmac_tilopay_server ) ) {
							//Check if nor already updated auth code
							if ( empty( $existing_auth_code ) ) {
								/**
								 *
								 * If payment was approve by Tilopay
								 * Process to pudate the order status payment.
								 *
								 */
								if ($this->haveActiveHPOS) {
									//HPOS
									$order->update_meta_data( 'tilopay_auth_code', $request_auth_code_payment );
								} else {
									update_post_meta( $request_order_id, 'tilopay_auth_code', $request_auth_code_payment );
								}
								$order->add_order_note( __( 'Authorization:', 'tilopay' ) . $request_auth_code_payment );
								$order->add_order_note( __( 'Code:', 'tilopay' ) . $request_code_payment );
								$order->add_order_note( __( 'Description:', 'tilopay' ) . $request_description );
								// translators: %s actions type.
								$order->add_order_note( sprintf( __( '%s Tilopay id:', 'tilopay' ), $textOrderTilopay ) . $request_tpay_order_id );


								//Save card for Woocommerce subscriptions payments
								if ( $this->hs && ( wcs_order_contains_subscription($request_order_id) || wcs_is_subscription($request_order_id) ) ) {
									if ($this->haveActiveHPOS) {
										//HPOS
										$order->update_meta_data( 'card', $request_token_card );
									} else {
										update_post_meta($request_order_id, 'card', $request_token_card);
									}
								}

								// Also store it the subscriptions for being purchased or paid the order.
								if ( function_exists('wcs_order_contains_subscription') && wcs_order_contains_subscription($request_order_id) ) {
									$subscriptions = wcs_get_subscriptions_for_order( $request_order_id );
								} elseif ( function_exists('wcs_order_contains_renewal') && wcs_order_contains_renewal($request_order_id) ) {
									$subscriptions = wcs_get_subscriptions_for_renewal_order($request_order_id);
								} else {
									$subscriptions = array( );
								}

								foreach ($subscriptions as $subscription) {
									$subscription_id = $subscription->get_id( );
									if ($this->haveActiveHPOS) {
										//HPOS
										$order->update_meta_data( 'card', $request_token_card );
										$order->update_meta_data( 'order', $order );
									} else {
										update_post_meta($subscription_id, 'card', $request_token_card);
										update_post_meta($subscription_id, 'order', $order);
									}
								}

								/**
								 *
								 * Update payment status
								 * Payment status: ( 'yes' == $this->tpay_capture ) ? payment complete : pending
								 *
								 */
								$order->update_status($this->tpay_get_order_status( ));

								//Check if car not ready empty
								if ( !WC( )->cart->is_empty( )) {
									$woocommerce->cart->empty_cart( );
								}

								if ($this->haveActiveHPOS) {
									//Update changes
									$order->save();
								}
								//Redirect to order details
								header('Cache-Control: no-cache, must-revalidate');
								header('Location: ' . $this->get_return_url($order), true, 307);

								wp_redirect( esc_url( $this->get_return_url($order) ) );
								exit;
							}
						} else {

							$error_message_validation = __( 'Description:', 'tilopay' ) . $request_description;
							//no hash or not equals
							$order->add_order_note( $error_message_validation );
							wc_add_notice( 'Error: ' . $error_message_validation, 'error' );

							$checkout_url = wc_get_checkout_url( );
							$pos = strpos( $checkout_url, '?' );
							if ( false === $pos ) {
								$checkout_url = $checkout_url . '?message_error=' . $error_message_validation;
							} else {
								$checkout_url = $checkout_url . '&message_error=' . $error_message_validation;
							}
							header('Cache-Control: no-cache, must-revalidate');
							header('Location: ' . $checkout_url, true, 307);
							exit;
						}
					} else {
						$order->add_order_note( 'Order hash:', ' tpay:' . sanitize_text_field( $computed_hash_hmac_tilopay_server ) . '|website:' . sanitize_text_field( $customer_computed_hash_hmac ) );
						// translators: %s the order number.
						$order->add_order_note( __( 'Description:', 'tilopay' ) . sprintf( __( 'Invalid order confirmation, please check and try again or contact the seller to completed you order no.%s', 'tilopay' ), $request_order_id ) );
						// translators: %s the order number.
						wc_add_notice( 'Error: ' . sprintf( __( 'Invalid order confirmation, please check and try again or contact the seller to completed you order no.%s', 'tilopay' ), $request_order_id ), 'error' );
						$order->update_status('failed');

						// translators: %s the order number.
						$error_message_validation = sprintf( __( 'Invalid order confirmation, please check and try again or contact the seller to completed you order no.%s', 'tilopay' ), $request_order_id );
						$checkout_url = wc_get_checkout_url( );
						$pos = strpos( $checkout_url, '?' );
						if ( false === $pos ) {
							$checkout_url = $checkout_url . '?message_error=' . $error_message_validation;
						} else {
							$checkout_url = $checkout_url . '&message_error=' . $error_message_validation;
						}
						header('Cache-Control: no-cache, must-revalidate');
						header('Location: ' . $checkout_url, true, 307);
						exit;

					} //. end check hash
				} else if ( 'Pending' == $request_code_payment && empty( $existing_auth_code ) ) {
					//Update order status to pending
					$order->update_status('on-hold');

					if ($this->haveActiveHPOS) {
						//HPOS
						$order->update_meta_data( 'tilopay_auth_code', $request_code_payment );
					} else {
						update_post_meta( $request_order_id, 'tilopay_auth_code', $request_code_payment );
					}
					$order->add_order_note( __( 'Code:', 'tilopay' ) . $request_code_payment );
					$order->add_order_note( __( 'Description:', 'tilopay' ) . $request_description );
					// translators: %s action type.
					$order->add_order_note( sprintf( __( '%s Tilopay id:', 'tilopay' ), $textOrderTilopay ) . $request_tpay_order_id );

					//Check if car not ready empty
					if ( !WC()->cart->is_empty() ) {
						WC()->cart->empty_cart();
					}
					if ($this->haveActiveHPOS) {
						//Update changes
						$order->save();
					}
					//Redirect to order details
					wp_redirect( esc_url( $this->get_return_url( $order ) ) );
					exit;
				} else {
					//error order not approved
					/**
					 * The payment was not approved by Tilopay
					 *
					 */
					$order->update_status('failed');
					$order->add_order_note( __( 'Order with failed payment', 'tilopay' ) );
					if ( !empty( $request_code_payment ) ) {
						$order->add_order_note( __( 'Code:', 'tilopay' ) . $request_code_payment );
					}
					$order->add_order_note( __( 'Description:', 'tilopay' ) . $request_description );
					//if SINPEMOVIL error mean is partial payment
					if ( 'SINPEMOVIL' == $selected_method ) {
						// translators: %1$s the message from tilopay, %2$s the order number.
						$request_description = sprintf( __( '%1$s, contact the seller to complete your order no.%2$s. If you try to pay again, your payment will be rejected.', 'tilopay' ), $request_description, $request_order_id );
					}

					$checkout_url = wc_get_checkout_url( );
					$pos = strpos( $checkout_url, '?' );
					if ( false === $pos ) {
						$checkout_url = $checkout_url . '?message_error=' . $request_description;
					} else {
						$checkout_url = $checkout_url . '&message_error=' . $request_description;
					}
					if ($this->haveActiveHPOS) {
						//Update changes
						$order->save();
					}
					$this->log( 'check out ' . $checkout_url );
					header('Cache-Control: no-cache, must-revalidate');
					header('Location: ' . $checkout_url, true, 307);
					exit;
				}
			}
		}
	}

	function tpay_apply_redirect_to_payment_form($show_error = false) {
		if ( isset( $_REQUEST['process_payment'] ) && 'tilopay' == $_REQUEST['process_payment'] && sanitize_text_field( isset( $_REQUEST['tlpy_payment_order'] ) ) ) {
			$order_id = sanitize_text_field( $_REQUEST['tlpy_payment_order'] );

			if ( $this->haveActiveHPOS ) {
				// HPOS
				$getOrder = wc_get_order( $order_id );
				$tpay_url_payment_form = $getOrder->get_meta( 'tilopay_html_form', true);
			} else {
				$tpay_url_payment_form = get_post_meta( $order_id, 'tilopay_html_form' )[0];
			}
			if ( true === $show_error ) {
				$tpay_url_payment_form = ( false === strpos( $tpay_url_payment_form, '?' ) )
					? $tpay_url_payment_form . '?paymentError=true'
					: $tpay_url_payment_form . '&paymentError=true';
			}

			//check if have html
			if ( '' != isset( $tpay_url_payment_form ) && $tpay_url_payment_form ) {

				$payment_form_tilopay = esc_url( $tpay_url_payment_form );
				if ( false !== strpos( $payment_form_tilopay, 'tilopay.com' ) ) {
					$redirect_url = esc_url( $payment_form_tilopay );
					wp_redirect( str_replace('&#038;', '&', $redirect_url) );
					exit;
				} else {
					$cross_domain_handle = ( false === strpos( $this->site_url, '?' ) )
						? $this->site_url . '?message_error=Cross domain redirect error, is not Tilopay redirect payment form.'
						: $this->site_url . '&message_error=Cross domain redirect error, is not Tilopay redirect payment form.';
					wp_redirect( esc_url( $cross_domain_handle ) );
					if ( function_exists( 'wc_add_notice' ) ) {
						// translators: %s the order number.
						wc_add_notice( 'Error: Cross domain redirect error, is not Tilopay redirect payment form.' . $tpay_url_payment_form, 'error' );
						exit;
					}
					exit;
				}

			}
		}
	}

	/**
	 * Hash customer
	 */
	public function computed_customer_hash( $external_orden_id, $amount, $currency, $tpay_order_id, $responseCode, $auth, $email ) {
		$hashKey = $tpay_order_id . '|' . $this->tpay_key . '|' . $this->tpay_password;
		$params = [
			'api_Key' => $this->tpay_key,
			'api_user' => $this->tpay_user,
			'orderId' => $tpay_order_id,
			'external_orden_id' => $external_orden_id,
			'amount' => number_format( $amount, 2 ),
			'currency' => $currency,
			'responseCode' => $responseCode,
			'auth' => $auth,
			'email' => $email
		];

		//computed customer hash_hmac
		return hash_hmac('sha256', http_build_query($params), $hashKey);
	}
	/**
	 * Notify of issues in wp-admin
	 */
	public function admin_notices() {
		if ( 'no' == $this->enabled ) {
			return;
		}
	}


	/**
	 * Logging method
	 *
	 * @paramstring $message
	 *
	 * @return void
	 */
	public function log( $message ) {
		if ( !class_exists( 'WC_Logger' ) ) {
			return;
		}
		if ( empty( $this->log ) ) {
			$this->log = new \WC_Logger();
		}

		$this->log->add( $this->id, $message );
	}

	/**
	 * Check if the gateway is available for use
	 *
	 * @return bool
	 */
	public function is_available() {
		$is_available = parent::is_available();

		// Only allow unencrypted connections when testing
		if ( !is_ssl( ) ) {
			$is_available = false;
		}
		return $is_available;
	}

	/**
	 * The tpay_scheduled_subscription_payment help to process payment.
	 *
	 * @param $amount_to_charge float The amount to charge.
	 * @param $renewal_order WC_Order A WC_Order object created to record the renewal payment.
	 */
	public function tpay_scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {
		$this->tpay_proccess_subscription( $renewal_order );
	}


	/**
	 * Tpay_proccess_subscription function.
	 * Process the subscription payments.
	 *
	 * @param $amount_to_charge float The amount to charge.
	 * @param $renewal_order WC_Order A WC_Order object created to record the renewal payment.
	 */
	public function tpay_proccess_subscription( $renewal_order ) {
		$subscriptions_ids = wcs_get_subscriptions_for_renewal_order( $renewal_order );
		foreach ( $subscriptions_ids as $subscription_id => $subscription_obj ) {
			$this->log( 'ID' . $subscription_id );
			break;
		}

		$card = '';
		if ( $this->haveActiveHPOS ) {
			// HPOS
			$card = $renewal_order->get_meta( 'card', true);
		} else {
			$card =get_post_meta( $subscription_id, 'card' )[0];
		}

		if ( '' === $card ) {
			$renewal_order->update_status( 'failed' );
			$renewal_order->add_order_note( __( 'Error: The subscription does not have an associated card.', 'tilopay' ) );
			$renewal_order->save( );
			return false;
		} else {
			$this->tpay_pay_order_with_token( $renewal_order, $card );
		}
	}

	/**
	 * Tpay_pay_order_with_token function.
	 * Process the subscription payment with the card token.
	 *
	 * @param object $order Order object from the buyer.
	 * @param string $token Card token to pay the order subscription
	 * @return boolean
	 */
	public function tpay_pay_order_with_token( $order, $token ) {

		$order = wc_get_order( $order );

		$headers = array(
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Accept-Language' => get_bloginfo( 'language' )
		 );
		$datajson = [
			'email' => $this->tpay_user,
			'password' => $this->tpay_password
		];

		$body = wp_json_encode( $datajson );

		$args = array(
			'body'=> $body,
			'timeout' => '300',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'=> true,
			'headers' => $headers,
			'cookies' => array( ),
		 );


		$response = wp_remote_post( TPAY_ENV_URL . 'login', $args );
		$result = json_decode( $response['body'] );

		$order_total = $order->get_total( );

		if ('' === $token) {
			$order->update_status( 'failed' );
			$order->add_order_note( __( 'Processing recurring payments requires a card token, recurrence does not have a card token.', 'tilopay' ) );
			$order->save( );
			return false;
		}

		if ('' === $this->tpay_key) {
			$order->update_status( 'failed' );
			$order->add_order_note( __( "There seems to be an issue with this website's integration with Tilopay, please inform the seller to complete your purchase.", 'tilopay' ) );
			$order->save( );
			return false;
		}

		// 'key' => 'required',
        // 'card' => 'required',
        // 'currency' => 'required',
        //  'amount' => 'required',
        //  'orderNumber' => 'required',
        //  'capture' => 'required',
        //  'email' => 'required|email',

		$datajson = [
			'redirect' => $this->tpay_checkout_redirect,
			'key' => $this->tpay_key,
			'amount' => $order_total,
			'currency' => get_woocommerce_currency( ),
			'email' => $order->billing_email,
			'orderNumber' => $order->get_id( ),
			'capture' => 'yes' == $this->tpay_capture ? '1' : '0',
			'card' => $token,
			'hashVersion' => 'V2',
			'callFrom' => 'Plugin woo'
		];
		$this->log( 'data' . print_r( $datajson, true ) );

		//Check if have a token
		if ( isset( $result->access_token ) ) {
			# Have token
			$headers = array(
				'Authorization' => 'bearer ' . $result->access_token,
				'Content-type' => 'application/json',
				'Accept' => 'application/json',
				'Accept-Language' => get_bloginfo( 'language' )
			 );

			$body = wp_json_encode( $datajson );
			$args = array(
				'body'=> $body,
				'timeout' => '300',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'=> true,
				'headers' => $headers,
				'cookies' => array( ),
			 );
			$response = wp_remote_post( TPAY_ENV_URL . 'processRecurrentPayment', $args );
			$this->log( 'data' . print_r( $response['body'], true ) );
			$result = json_decode( $response['body'] );


			if ( !empty( $result ) ) {
				//process Tilopay Response
				$this->tpay_process_response( $result, $order );
				return true;
			} else {
				$order->update_status( 'failed' );
				$order->add_order_note( json_encode( $result ) );
				$order->add_order_note( __( 'Connection error with TILOPAY, contact sac@tilopay.com.', 'tilopay' ) );
				$order->save( );
				return false;
			}
		} else {

			//Dont have token
			$order->update_status( 'failed' );
			$order->add_order_note( json_encode( $result ) . ' Pay With token ' . __LINE__  );
			$order->add_order_note( __( "There seems to be an issue with this website's integration with Tilopay, please inform the seller to complete your purchase.", 'tilopay' ) . ' Pay With token ' . __LINE__ );
			$order->save( );
			return false;
		} //.End check have token

	}


	/**
	 * Process_response function
	 * Store extra meta data for an order from a TYP Response.
	 *
	 * @since 2.3.0
	 * @return object
	 */
	public function tpay_process_response( $response, $order ) {
		$this->log( 'Processing response: ' . print_r( $response, true ) );

		$order_id = $order->get_id( );
		$user_id = $order->get_user_id( );

		$this->log( 'Response ' . $response->type );
		/**
		 * Validate body
		 * json decode
		 * $response = [
		 * "type": "200",
		 * "response" => "1" || "0",
		 * "description" => "some text",
		 * "auth": "123456"
		 * ]
		 *
		 * $response->response == 1 aprove by Tilopay || 0 Payment failed
		 */

		if ( '200' == $response->type && 1 == $response->response ) {

			$textOrderTilopay = ( 'yes' == $this->tpay_capture ) ? __( 'Capture', 'tilopay' ) : __( 'Authorization', 'tilopay' );
			$tpay_order_id = ( isset( $response->order_id ) ) ? $response->order_id : '';
			$this->log( 'Response ' . $response->auth );

			if ($this->haveActiveHPOS) {
				//HPOS
				$order->update_meta_data( 'tilopay_is_captured', $this->tpay_capture );
				$order->update_meta_data( 'authorization_number', $response->auth );
				$order->save();
			} else {
				//set last action done
				update_post_meta( $order_id, 'tilopay_is_captured', $this->tpay_capture );
				update_post_meta( $order_id, 'authorization_number', $response->auth );
			}

			//Add update post meta to the state user select
			$order->payment_complete( $response->auth );
			$order->add_order_note( __( 'Authorization:', 'tilopay' ) . $response->auth );
			$order->add_order_note( __( 'Code:', 'tilopay' ) . $response->response );
			$order->add_order_note( __( 'Description:', 'tilopay' ) . $response->description );
			// translators: %s action type.
			$order->add_order_note( sprintf( __( '%s Tilopay id:', 'tilopay' ), $textOrderTilopay ) . $tpay_order_id );
		} else {
			$responseText = isset( $response->description ) ? $response->description : '';
			$responseResult = ( !empty( $response->result ) && isset( $response->result )) ? $response->result : $responseText;
			$responseResult = ( !empty( $response->message ) && isset( $response->message ) ) ? $response->message : $responseResult;
			$responseResult = ( !empty( $responseResult ) ) ? $responseResult : 'validation response ' . wp_json_encode( $response );

			$order->update_status( 'failed' );
			// translators: %s message get from Tilopay call api.
			$order->add_order_note( sprintf( __( 'Payment processing failed. Please retry, Tilopay responded with error: %s', 'tilopay' ), $responseResult ) );
		}

		if ( is_callable( array( $order, 'save' ) ) ) {
			$order->save( );
		}

		do_action( 'wc_tilopay_process_response', $response, $order );

		return $response;
	}
	/**
	 * Get_icon function
	 * Return icons for card brands supported.
	 *
	 * @since 2.3.0
	 * @return string
	 */
	public function get_icon() {
		//is redirect
		if ( false === $this->nativePaymentMethod ) {
			//Here we are using grind system, css is located at tilopay-config-payment-front.css
			$icons_str = '';

			//first row with icons
			if ( is_array( $this->tpay_logo_options ) && !empty( $this->tpay_logo_options ) ) {
				$icons_str .= '<div class="Container-tilopay">
			<div class="Flex-tilopay">';
				foreach ( $this->tpay_logo_options as $key => $value ) {
					if ( in_array( $value, ['visa', 'mastercard', 'american_express', 'sinpemovil', 'credix', 'sistema_clave'] ) ) {
						//others
						$icons_str .= '<img class="Flex-item-tilopay" src="' . TPAY_PLUGIN_URL . '/assets/images/' . $value . '.svg" style="width: 51px;	max-width: 100%!important; max-height: 100%!important; margin-right: 3px;" />';
					}
				}
				$icons_str .= '</div>
			</div>';

				//next row BAC
				if ( in_array( 'mini_cuotas', $this->tpay_logo_options ) || in_array( 'tasa_cero', $this->tpay_logo_options ) ) {
					$icons_str .= '<div class="flex-container-tpay-bac">';
					if ( in_array( 'tasa_cero', $this->tpay_logo_options ) ) {
						$icons_str .= '<div>
				<img src="' . TPAY_PLUGIN_URL . '/assets/images/tasa-cero.png" style="width: 100%;max-height: none !important;" />
				</div>';
					}
					if ( in_array( 'mini_cuotas', $this->tpay_logo_options ) ) {
						$icons_str .= '
					<div>
				<img src="' . TPAY_PLUGIN_URL . '/assets/images/minicuotas.png" style="width: 100%;max-height: none !important;" />
				</div>';
					}
					$icons_str .= '
			</div>';
				}
			}

			$tpay_title_div = '';
			if ( null == $this->title || '' == $this->title ||  __( 'Pay with', 'tilopay' ) == $this->title ) {
				# Title with logo
				$tpay_title_div .= __( 'Pay with', 'tilopay' ) . '<img class="tpay-icon-c" src="' . TPAY_PLUGIN_URL . '/assets/images/tilopay_color.png" style="display: block;"/>';
			} else {
				//Only text
				$tpay_title_div .= $this->title;
			}
			?>
			<script type="text/javascript">
				(function($) {
					//remove default style
					$('label[for="payment_method_tilopay"]').remove();
					//append custom label
					var title_payment_method_tilopay = <?php echo wp_json_encode( wp_kses_post( $tpay_title_div ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?>;
					var labelElement  = document.createElement('label');
					labelElement.setAttribute('class', 'payment_method_tilopay');
					labelElement.setAttribute('for', 'payment_method_tilopay');
					labelElement.innerHTML = title_payment_method_tilopay;
					$('#payment_method_tilopay').parent().append(labelElement);

					//append logos
					var icon_payment_method_tilopay = <?php echo wp_json_encode( wp_kses_post( $icons_str ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?>;
					var div_icon = document.createElement('div');
					div_icon.innerHTML = icon_payment_method_tilopay;
					$('#payment_method_tilopay').parent().append(div_icon);
				})(jQuery);
			</script>
		<?php
		} //.is redirect

		//is not redirecy
		if ( $this->nativePaymentMethod ) {
			//Here we are using grind system, css is located at tilopay-config-payment-front.css
			$icons_str = $this->tpay_payment_method();
			$tpay_title_div = '';
			if ( null == $this->title || '' == $this->title || __( 'Pay with', 'tilopay' ) == $this->title ) {
				# Title with logo
				$tpay_title_div .= '<label class="payment_method_tilopay yes-r" for="payment_method_tilopay">' .
					__( 'Pay with', 'tilopay' ) . '<img class="tpay-icon-c" src="' . TPAY_PLUGIN_URL . '/assets/images/tilopay_color.png" style="display: block;"/>';
				$tpay_title_div .= '</label>';
			}

			?>
			<script type="text/javascript">
				( function( $ ) {
					//append logos
					var iconsStr = <?php echo wp_json_encode( wp_kses_post( $icons_str ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?>;
					var divIcon = document.createElement('div');
					divIcon.innerHTML = iconsStr;
					$('#wc-tilopay-cc-form').parent().append(divIcon);
				} )( jQuery );
			</script>
		<?php
		}
		return apply_filters( 'woocommerce_gateway_icon', $tpay_title_div, $this->id );
	}


	public function tpay_payment_method() {
		$icons_str = '';
		//first row with icons
		if ( is_array( $this->tpay_logo_options ) && !empty( $this->tpay_logo_options ) ) {
			$icons_str .= '<div class="Container-tilopay">
		<div class="Flex-tilopay">';
			foreach ( $this->tpay_logo_options as $key => $value ) {
				if ( in_array( $value, ['visa', 'mastercard', 'american_express', 'sinpemovil', 'credix', 'sistema_clave'] ) ) {
					//others
					$icons_str .= '<img class="Flex-item-tilopay" src="' . TPAY_PLUGIN_URL . '/assets/images/' . $value . '.svg" style="width: 51px;	max-width: 100%!important; max-height: 100%!important; margin-right: 3px;" />';
				}
			}
			$icons_str .= '</div>
		</div>';

			//next row BAC
			if ( in_array( 'mini_cuotas', $this->tpay_logo_options ) || in_array( 'tasa_cero', $this->tpay_logo_options ) ) {
				$icons_str .= '<div class="flex-container-tpay-bac">';
				if ( in_array( 'tasa_cero', $this->tpay_logo_options ) ) {
					$icons_str .= '<div>
			<img src="' . TPAY_PLUGIN_URL . '/assets/images/tasa-cero.png" style="width: 100%;max-height: none !important;" />
			</div>';
				}
				if ( in_array( 'mini_cuotas', $this->tpay_logo_options ) ) {
					$icons_str .= '
				<div>
			<img src="' . TPAY_PLUGIN_URL . '/assets/images/minicuotas.png" style="width: 100%;max-height: none !important;" />
			</div>';
				}
				$icons_str .= '
		</div>';
			}
		}
		return $icons_str;
	}

	public function tpay_retry_subscription_order( $order_id ) {
		$this->tpay_proccess_subscription( $order_id );
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 *
	 */
	public function init_form_fields() {
		$config_fields = \Tilopay\TilopayConfig::formConfigFields( );
		$this->form_fields = apply_filters( 'wc_tilopay_settings', $config_fields );
	}

	/**
	 * WOO Direct Gateways
	 * Output payment fields
	 *
	 * @returnvoid
	 * Woocommerce
	 */
	public function payment_fields() {

		$current_user = wp_get_current_user( );
		$current_user_email = ( isset( $current_user->user_email ) ) ? $current_user->user_email : 'init-default@tilopay.com';
		$order_id = absint( get_query_var( 'order-review' ) );

		// ok, let's display some description before the payment form
		if ( $this->method_description ) {
			//we need endpoint to check if cred are test or prod mode
			$this->method_description = '';
			// display the description with <p> tags etc.
			echo '<span id="environment" class=""></span>';
		}

		//$this->credit_card_form( );//default form
		if ( is_ajax() ) {

			//call SDK from tilopay-checkout.js
			?>
			<script type="text/javascript">
				initSDKTilopay();
			</script>
<?php
		}

		// I will echo( ) the form, but you can close PHP tags and print it directly in HTML
		echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">
		<input type="hidden" id="tpay_woo_checkout_nonce" name="tpay_woo_checkout_nonce" value="' . wp_create_nonce( $this->id . '-tpay-woo-action-nonce' ) . '">
		<input type="hidden" id="tpay_env" name="tpay_env" value="PROD">
		<input type="hidden" id="token_hash_card_tilopay" name="token_hash_card_tilopay" value="">
		<input type="hidden" id="token_hash_code_tilopay" name="token_hash_code_tilopay" value="">
		<input type="hidden" id="card_type_tilopay" name="card_type_tilopay" value="">
		<input type="hidden" id="pay_sinpemovil_tilopay" name="pay_sinpemovil_tilopay" value="0">
		<input type="hidden" id="woo_session_tilopay" name="woo_session_tilopay" value="0">
		<div id="loaderTpay" payFormTilopay>
		 <div class="spinnerTypayInit"></div>
		</div>
		<div class="payFormTilopay" >
		<div id="overlaySubscriptions" style="display: none;">
		<p id="overlayText" style="display: none;">' . esc_html__( 'Subscriptions payment is not allowed in test environment.', 'tilopay' ) . '</p>
		</div>
		<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout" id="tpay-sdk-error-div" style="display: none;">
		<ul class="woocommerce-error" role="alert" id="tpay-sdk-error">
		</ul>
		</div>
		<div class="form-row form-row-wide">
			 <label for="tlpy_payment_method" id="methodLabel" style="display: none;">' . esc_html__( 'Payment methods', 'tilopay' ) . '</label>
			 <select name="tlpy_payment_method" id="tlpy_payment_method" class="selectwc-credit-card-form-card-select" onchange="onchange_payment_method( this );" style="display: none;">
				 <option value="" selected disabled>' . esc_html__( 'Select payment method', 'tilopay' ) . '</option>
			 </select>
		</div>
		<div class="form-row form-row-wide" id="selectCard" style="display: none;">
		<label>' . esc_html__( 'Saved cards', 'tilopay' ) . '</label>
		<select name="cards" id="cards" onchange="onchange_select_card( );" >
			 <option value="" selected disabled>' . esc_html__( 'Select card', 'tilopay' ) . '</option>
		</select>
		</div>';

		// Add this action hook if you want your custom payment gateway to support it
		do_action( 'woocommerce_credit_card_form_start', $this->id );

		// I recommend to use inique IDs, because other gateways could already use #ccNo, #tlpy_cc_expiration_date, #cvc
		//<button type="button" id="pay-sinpemobil-tilopay" class="button payWithSinpeMovil" data-modal="#tilopay-m1">Pagar</button>
		echo '
	 <div id="divTpaySinpeMovil" style="display: none;">
			<p>' . esc_html__( 'The payment instructions with SINPE Móvil will be shown on the next screen.', 'tilopay' ) . ' </p><br>
		</div>
		<div id="divTpayCardForm">
		<div class="form-row form-row-wide" id="divCardNumber" style="display: none;">
		<label for="tlpy_cc_number">' . esc_html__( 'Card number', 'tilopay' ) . ' <span class="required">*</span></label>
		<input id="tlpy_cc_number" class="input-text wc-credit-card-form-card-number" inputmode="numeric" autocomplete="cc-number" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="•••• •••• •••• ••••" name="tlpy_cc_number">
		</div>
		<div class="form-row form-row-first" id="divCardDate" style="display: none;">
			<label for="tlpy_cc_expiration_date">' . esc_html__( 'Expiry date', 'tilopay' ) . ' <span class="required">*</span></label>
			<input id="tlpy_cc_expiration_date" class="input-text wc-credit-card-form-card-expiry" inputmode="numeric" autocomplete="cc-exp" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="MM / AA" name="tlpy_cc_expiration_date">
		</div>
		<div class="form-row form-row-last" id="divCardCvc" style="display: none;">
			<label for="tlpy_cvv">' . esc_html__( 'Card code ( CVC )', 'tilopay' ) . ' <span class="required">*</span></label>
			<input id="tlpy_cvv" class="input-text wc-credit-card-form-card-cvc" inputmode="numeric" autocomplete="off" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" maxlength="4" placeholder="CVV" name="tlpy_cvv" style="width:100px !important">
		</div>

		<div class="form-row" id="divSaveCard" style="display: none;">
		 <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
			<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="saveCard" id="saveCard">
			 <span class="woocommerce-terms-and-conditions-checkbox-text">' . esc_html__( 'Save card', 'tilopay' ) . '</span>
		 </label>
		</div>

		</div>

		<div class="clear"></div>';

		do_action( 'woocommerce_credit_card_form_end', $this->id );

		echo '<div class="clear"></div>
		</div>
		<div id="responseTilopay"></div>

		</fieldset>

		<!--Tilopay modal-->
		<div id="tilopay-m1" class="tilopay-modal-container">
		<div class="tilopay-overlay" data-modal="close"></div>
		<div class="tilopay-modal">
		 <h3>' . esc_html__( 'Pay with SINPE Móvil', 'tilopay' ) . '</h3>
			<p>' . esc_html__( 'To make the payment with SINPE Móvil, you must make sure to make the payment in the following way:', 'tilopay' ) . '<br>
			' . esc_html__( 'Telephone:', 'tilopay' ) . ' <strong id="tilopay-sinpemovil-number"></strong><br>
			' . esc_html__( 'Exact amount:', 'tilopay' ) . ' <strong>' . wp_json_encode( get_woocommerce_currency() ) . '</strong> <strong id="tilopay-sinpemovil-amount"></strong><br>
			' . esc_html__( 'Specify in the description:', 'tilopay' ) . ' <strong id="tilopay-sinpemovil-code"></strong><br>
			</p>
		<div class="tilopay-btn-group">
		<button type="button" class="button btn-tilopay-close-modal" data-modal="close" style="margin-right: 10px;">' .
		esc_html__( 'Cancel', 'tilopay' ) .
			'</button>
		<button type="button" id="process-tilopay" class="button alt process-sinpemovil-tilopay loading" desabled>' .
		esc_html__( 'Waiting payment', 'tilopay' ) .
			'</button>
		</div>
		</div>';
	}

	public function tpay_css_front() {
		if ( $this->nativePaymentMethod ) {
			//css logo payment frontend
			$logo_payment_frontend = gmdate( 'ymd-Gis', filemtime( TPAY_PLUGIN_DIR . '/assets/css/tilopay-config-payment-front.css' ) );
			wp_register_style( 'tilopay-payment-front', WP_PLUGIN_URL . '/tilopay/assets/css/tilopay-config-payment-front.css', false, $logo_payment_frontend );
			wp_enqueue_style( 'tilopay-payment-front' );
		} else {
			//css logo payment frontend
			$logo_payment_frontend = gmdate( 'ymd-Gis', filemtime( TPAY_PLUGIN_DIR . '/assets/css/tilopay-redirect-payment.css' ) );
			wp_register_style( 'tilopay-payment-redirect', WP_PLUGIN_URL . '/tilopay/assets/css/tilopay-redirect-payment.css', false, $logo_payment_frontend );
			wp_enqueue_style( 'tilopay-payment-redirect' );
		}
	}

	//enqueue tilopay-checkout.js
	public function tpay_payment_scripts() {

		// we need JavaScript to process a token only on cart/checkout pages, right?
		if ( !is_cart() && !is_checkout() && !isset( $_GET['pay_for_order'] ) ) {
			return;
		}

		// if our payment gateway is disabled, we do not have to enqueue JS too
		if ( 'no' === $this->enabled ) {
			return;
		}

		//SDK
		$tilopay_sdk= gmdate( 'ymd-Gis' );
		wp_enqueue_script( 'tilopay-SDK', TPAY_SDK_URL, array( ), $tilopay_sdk, true );

		//SDK KOUNT
		$kount_sdk= gmdate( 'ymd-Gis' );
		$sdKountUrl = 'https://storage.googleapis.com/tilo-uploads/assets/plugins/kount/kount-web-client-sdk-bundle.js?generation=1687791530716376';
		wp_enqueue_script( 'tilopay-kount-SDK', $sdKountUrl, array( ), $kount_sdk, true );

		// and this is our custom JS
		$my_checkoutjs_ver= gmdate( 'ymd-Gis', filemtime( TPAY_PLUGIN_DIR . '/assets/js/tilopay-checkout.js' ) );
		wp_register_script( 'tilopay-checkout', WP_PLUGIN_URL . '/tilopay/assets/js/tilopay-checkout.js', array( 'jquery' ), $my_checkoutjs_ver, true );

		//SDK Tokenx
		$tokenex_sdk_v= gmdate( 'ymd-Gis' );
		$sdTokenexUrl = 'https://api.tokenex.com/inpage/js/TokenEx-Lite.js';
		wp_enqueue_script( 'tilopay-tokenex-SDK', $sdTokenexUrl, array( ), $tokenex_sdk_v, false );

		// Init SDK data
		$getDataInit = $this->setDataInit( );
		wp_localize_script( 'tilopay-checkout', 'tilopayConfig', $getDataInit );
		wp_enqueue_script( 'tilopay-checkout' );
	}

	/**
	 * Validate payment fields on the frontend.
	 * __( 'Check credit or debit card details', 'tilopay' )
	 * implement nonce: https://developer.wordpress.org/reference/functions/wp_verify_nonce/
	 */
	public function validate_fields() {

		// Clean
		if ( function_exists( 'wc_clear_notices' ) ) {
			wc_clear_notices();
		}

		if ( $this->nativePaymentMethod  && ( isset( $_POST['tpay_woo_checkout_nonce'] ) && wp_verify_nonce( $_POST['tpay_woo_checkout_nonce'], $this->id . '-tpay-woo-action-nonce' ) ) ) {
			//check if sinpemovil SINPE ( 1: yes, 0: no )
			$payWithSinpeMovil = ( isset( $_POST['pay_sinpemovil_tilopay'] ) && '1' == $_POST['pay_sinpemovil_tilopay'] ) ? true : false;

			//Check if have suscription at cart
			$is_subscription = $this->tpay_check_have_subscription( );

			if ( $payWithSinpeMovil && $is_subscription ) {
				# mus select credicard payment
				if ( function_exists( 'wc_add_notice' ) ) {
					wc_add_notice( __( 'You cannot pay subscriptions with SINPE Movíl, please pay with a credit or debit card', 'tilopay' ), 'error' );
				}
				return false;
				exit;
			}

			//Subscription in test mode not allowed
			if ( isset( $_POST['tpay_env'] ) && 'PROD' !== $_POST['tpay_env'] && $is_subscription ) {
				if ( function_exists( 'wc_add_notice' ) ) {
					wc_add_notice( __( 'Subscriptions payment is not allowed in test environment.', 'tilopay' ), 'error' );
				}
				return false;
				exit;
			}

			//if not SINPE need to validate card form
			if ( !$payWithSinpeMovil ) {
				$token_hash_card_tilopay = ( sanitize_text_field( isset( $_POST['token_hash_card_tilopay'] ) ) && !empty( sanitize_text_field( $_POST['token_hash_card_tilopay'] ) ) ) ? sanitize_text_field( $_POST['token_hash_card_tilopay'] ) : '';
				$token_hash_code_tilopay = ( sanitize_text_field( isset( $_POST['token_hash_code_tilopay'] ) ) && !empty( sanitize_text_field( $_POST['token_hash_code_tilopay'] ) ) ) ? sanitize_text_field( $_POST['token_hash_code_tilopay'] ) : '';

				$newCard = ( sanitize_text_field( isset( $_POST['cards'] ) ) ) ? sanitize_text_field( $_POST['cards'] ) : 'newCard';
				$selectMethod = sanitize_text_field( isset( ( $_POST['tlpy_payment_method'] ) ) ) ? true : false;

				if ( !empty( $newCard ) && $selectMethod ) {
					if ( 'newCard' == $newCard ) {
						if ( ( empty( $_POST['tlpy_cc_number'] ) || empty( $_POST['tlpy_cc_expiration_date'] ) || empty( $_POST['tlpy_cvv'] ) ) &&
							( empty( $_POST['token_hash_code_tilopay'] ) || empty( $_POST['token_hash_card_tilopay'] ) )
						 ) {
							if ( function_exists( 'wc_add_notice' ) ) {
								wc_add_notice( __( 'Check credit or debit card details', 'tilopay' ), 'error' );
							}
							return false;
							exit;
						}
						if (isset( $_POST['tpay_env'] ) && 'PROD' === $_POST['tpay_env']) {
							//Check encript card
							if ('' == $token_hash_card_tilopay) {
								if ( function_exists( 'wc_add_notice' ) ) {
									wc_add_notice( __( 'Please contact the seller because we were unable to encrypt your card details to process the payment on Tilopay or refresh the page and try again.', 'tilopay' ), 'error' );
								}
								return false;
								exit;
							}
							//Check encript CVV
							if ('' == $token_hash_code_tilopay) {
								if ( function_exists( 'wc_add_notice' ) ) {
									wc_add_notice( __( 'Please contact the seller because we were unable to encrypt your card details to process the payment on Tilopay or refresh the page and try again.', 'tilopay' ), 'error' );
								}
								return false;
								exit;
							}
						}
					} else {
						if ( 'newCard' != $newCard && empty( $_POST['tlpy_cvv'] ) ) {
							if ( function_exists( 'wc_add_notice' ) ) {
								wc_add_notice( __( 'Check credit or debit card details', 'tilopay' ), 'error' );
							}
							return false;
							exit;
						}
						if (isset( $_POST['tpay_env'] ) && 'PROD' === $_POST['tpay_env']) {
							//Check encript CVV
							if ('' == $token_hash_code_tilopay) {
								if ( function_exists( 'wc_add_notice' ) ) {
									wc_add_notice( __( 'Please contact the seller because we were unable to encrypt your card details to process the payment on Tilopay or refresh the page and try again.', 'tilopay' ), 'error' );
								}
								return false;
								exit;
							}
						}
					}
				} else {
					if ( function_exists( 'wc_add_notice' ) ) {
						wc_add_notice( __( 'Check credit or debit card details', 'tilopay' ), 'error' );
					}
					return false;
					exit;
				}
			}
		}

		return true;
		exit;
	}

	/**
	 * WOO Direct Gateways
	 * Process the payment and return the result
	 *
	 * @param int $order_id
	 *
	 * @return array
	 * Woocommerce
	 */
	public function process_payment( $order_id ) {
		//Check if have nonce
		if ( isset( $posted_data['tpay_woo_checkout_nonce'] ) && false === wp_verify_nonce( $_POST['tpay_woo_checkout_nonce'], $this->id . '-tpay-woo-action-nonce' ) ) {
			return array(
				'messages' => 'wpnonce',
				'result' => 'failure',
				'redirect' => wc_get_checkout_url( ) //$this->get_return_url( $order )
			 );
		}

		global $woocommerce;
		$order = wc_get_order( $order_id );

		//is redirect
		if ( false === $this->nativePaymentMethod ) {
			return $this->tpay_get_redirect_payment_url($order, 'redirect', __LINE__);
		} //.is redirect

		//is embedded native payment
		if ( $this->nativePaymentMethod ) {

			// # Have token
			$order_total = $order->get_total();
			$subscription = $this->tpay_check_have_subscription();
			$tpay_env = ( sanitize_text_field( isset( $_POST['tpay_env'] ) ) != '' ) ? sanitize_text_field( $_POST['tpay_env'] ) : 'PROD';
			$tokenex_exist = ( 'PROD' == $tpay_env ) ? 'on' : 'off';

			//if checkbox on will tokenize the card
			$tokenize_new_card = ( sanitize_text_field( isset( $_POST['saveCard'] ) ) &&  'PROD' == $tpay_env ) ? sanitize_text_field( $_POST['saveCard'] ) : 'off';

			//check if sinpemovil
			$payWithSinpeMovil = ( sanitize_text_field( isset( $_POST['pay_sinpemovil_tilopay'] ) ) && 1 == $_POST['pay_sinpemovil_tilopay'] ) ? true : false;
			$codeSM = '';
			$referenceSinpe = '';
			if ( $payWithSinpeMovil ) {
				# paymen by sinpemovil is checked but payment
				//call api to check payment

				$tokenize_new_card = 'off'; // not need tokenize
				$tokenex_exist = 'off';

				$checkout_url = wc_get_checkout_url( );

				$pos = strpos( $checkout_url, '?' );
				$check_box_must_be_selected = ( !empty( $_POST['terms-field'] ) ) ? 1 : 0;
				$get_tlpy_payment_method = ( sanitize_text_field( isset( $_POST['tlpy_payment_method'] ) ) ) ? sanitize_text_field( $_POST['tlpy_payment_method'] ) : 'none';
				if ( false === $pos ) {
					$checkout_url = $checkout_url . '?process_payment=tilopay&tlpy_payment_order=' . $order_id . '&tlpy_payment_method=' . base64_encode( $get_tlpy_payment_method . '|' . $check_box_must_be_selected );
				} else {
					$checkout_url = $checkout_url . '&process_payment=tilopay&tlpy_payment_order=' . $order_id . '&tlpy_payment_method=' . base64_encode( $get_tlpy_payment_method . '|' . $check_box_must_be_selected );
				}

				return array(
					'result' => 'success',
					'redirect' => $checkout_url
				 );
			}

			//cards selected
			$selectCard = sanitize_text_field( isset( $_POST['cards'] ) ) ? sanitize_text_field( $_POST['cards'] ) : 'otra';
			//2 card is from tilopay, 1 newaone
			$tokenFromTilopay = ( 'newCard' == $selectCard || 'otra' == $selectCard ) ? '1' : '2';
			//if token from tilopay, pass card token
			$savedTokenCard = ( 2 == $tokenFromTilopay ) ? sanitize_text_field( $_POST['cards'] ) : 'otra';

			$get_token_hash_card_tilopay = sanitize_text_field( isset( $_POST['token_hash_card_tilopay'] ) )
			? sanitize_text_field( $_POST['token_hash_card_tilopay'] )
			: '';
			$token_hash_card_tilopay = ( $get_token_hash_card_tilopay && 'PROD' != $tpay_env )
				? str_replace( ' ', '', $get_token_hash_card_tilopay )
				: $get_token_hash_card_tilopay;

			$tlpy_cvv_cipher = sanitize_text_field( isset( $_POST['token_hash_code_tilopay'] ) ) ? sanitize_text_field( $_POST['token_hash_code_tilopay'] ) : '';

			//Raw
			$tlpy_cvv = sanitize_text_field( isset( $_POST['tlpy_cvv'] ) ) ? sanitize_text_field( $_POST['tlpy_cvv'] ) : '';
			// Only prod
			if ( 'PROD' == $tpay_env && false === $payWithSinpeMovil) {
				//Check card cipher
				if(1 == $tokenFromTilopay && ctype_digit(str_replace( ' ', '', $get_token_hash_card_tilopay )) ) {
					// If card or cvv are only number mean encription error
					return $this->tpay_get_redirect_payment_url($order, 'native', __LINE__);
				}
				//Check cvv cipher
				if(ctype_digit($tlpy_cvv_cipher)) {
					// If card or cvv are only number mean encription error
					return $this->tpay_get_redirect_payment_url($order, 'native', __LINE__);
				}
			}

			//check if type subscriptions and if save card not set set to tokenize on
			if ( 1 == $subscription) {
				$tokenize_new_card = ( 'otra' == $savedTokenCard && 'on' != $tokenize_new_card ) ? 'on' : $tokenize_new_card;
			}

			if ($this->haveActiveHPOS) {
				//HPOS
				$order->update_meta_data( 'tpay_capture', $this->tpay_capture );
				$order->save();
			} else {
				update_post_meta( $order_id, 'tpay_capture', $this->tpay_capture );
			}

			$checkout_url = wc_get_checkout_url();

			//$tilopayToken = $this->tpay_get_token_sdk( );

			$bodyRequestData = [
				'key' => $this->tpay_key,
				'amount' => $order_total,
				'amount_sinpe' => $order_total,
				'taxes' => '0',
				'currency' =>get_woocommerce_currency( ),
				'billToFirstName' => $order->get_billing_first_name( ),
				'billToLastName' => $order->get_billing_last_name( ),
				'billToAddress' => $order->get_billing_address_1( ),
				'billToAddress2' => $order->get_billing_address_2( ),
				'billToCity' => $order->get_billing_city( ),
				'billToState' => $order->get_billing_state( ),
				'billToZipPostCode' => $order->get_billing_postcode( ),
				'billToCountry' => $order->get_billing_country( ),
				'billToTelephone' => $order->get_billing_phone( ),
				'billToEmail' => $order->get_billing_email( ),
				'orderNumber' => $order_id,
				'capture' => 'yes' == $this->tpay_capture ? '1' : '0',
				'sessionId' => sanitize_text_field( isset( $_POST['woo_session_tilopay'] ) ) ? sanitize_text_field( $_POST['woo_session_tilopay'] ) : 'WOO-' . time( ),
				'redirect' => $this->tpay_checkout_redirect,
				'tokenex_exist' => $tokenex_exist,
				'subscription' => $subscription,
				'cvv' => $tlpy_cvv,
				'cvvEncrypted' => $tlpy_cvv_cipher,
				'card' => $token_hash_card_tilopay,
				'expDate' => sanitize_text_field( isset( $_POST['tlpy_cc_expiration_date'] ) ) ? str_replace( array( '-', '_', '/', ' ' ), '', sanitize_text_field( $_POST['tlpy_cc_expiration_date'] ) ) : '',
				'type_card' => $tokenFromTilopay,
				'card_list' => $savedTokenCard,
				//'code' => null,
				'tokenize' => $tokenize_new_card,
				'method' => sanitize_text_field( isset( $_POST['tlpy_payment_method'] ) ) ? sanitize_text_field( $_POST['tlpy_payment_method'] ) : '',
				//'brand' => isset( $_POST['card_type_tilopay'] ) ? $_POST['card_type_tilopay'] : '',
				'cardType' => sanitize_text_field( isset( $_POST['card_type_tilopay'] ) ) ? sanitize_text_field( $_POST['card_type_tilopay'] ) : '',
				'platform' => 'woocommerce-nativo',
				//'codeSM' => $codeSM,
				//'referenceSinpe' => $referenceSinpe,
				'lang' =>get_bloginfo( 'language' ),
				'platform_reference' => $this->tpay_platform_detail( ),
				'hashVersion' => 'V2'
			];

			$getPaymentResponse = $this->tpay_call_to_make_order_payment( $bodyRequestData );

			if ( $getPaymentResponse && $getPaymentResponse['redirect'] && isset( $getPaymentResponse['enpoint'] ) ) {
				//redirect to endpoint
				/**
				 * Cas 1: Not 3ds, we redirect to checkout with order status approved or rejected.
				 * Case 2: Redirect html file to process 3ds challenger or get back checkout with order status approved or rejected.
				 */

				$redirect_checkout_url = $getPaymentResponse['enpoint'];

				$checkQuery = strpos( $redirect_checkout_url, '?' );

				if ( false === $checkQuery ) {
					$redirect_checkout_url = $redirect_checkout_url . '?ver=' . time( );
				} else {
					$redirect_checkout_url = $redirect_checkout_url . '&ver=' . time( );
				}

				return array(
					'result' => 'success',
					'redirect' => $redirect_checkout_url
				 );
			}

			return array(
				'result' => 'failure',
				'redirect' => $checkout_url //$this->get_return_url( $order )
			 );
		} //. embedded native payment

		//if nothing stop and arrive this show error
		return array(
			'messages' => 'wpnonce',
			'result' => 'failure',
			'redirect' => wc_get_checkout_url() //$this->get_return_url( $order )
		 );
	}

	/**
	 *
	 * Process to get payment url
	 *
	 */
	function tpay_get_redirect_payment_url($order, $call_from = 'redirect', $line = 0) {
		$headers = array(
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Accept-Language' => get_bloginfo( 'language' )
		 );
		$datajson = [
			'email' => $this->tpay_user,
			'password' => $this->tpay_password
		];

		$body = wp_json_encode( $datajson );

		$args = array(
			'body'=> $body,
			'timeout' => '300',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'=> true,
			'headers' => $headers,
			'cookies' => array( ),
		 );


		$response = wp_remote_post( TPAY_ENV_URL . 'login', $args );
		$result = json_decode( $response['body'] );

		//Check if have a token
		if ( !is_wp_error( $response )&& isset( $result->access_token ) ) {
			$order_id = $order->get_id();
			# Have token
			$order_total = $order->get_total( );
			$datajson = [
				'redirect' => $this->tpay_checkout_redirect,
				'key' => $this->tpay_key,
				'amount' => $order_total,
				'currency' => get_woocommerce_currency( ),
				'billToFirstName' => $order->get_billing_first_name( ),
				'billToLastName' => $order->get_billing_last_name( ),
				'billToAddress' => $order->get_billing_address_1( ),
				'billToAddress2' => $order->get_billing_address_2( ),
				'billToCity' => $order->get_billing_city( ),
				'billToState' => $order->get_billing_state( ),
				'billToZipPostCode' => $order->get_billing_postcode( ),
				'billToCountry' => $order->get_billing_country( ),
				'billToTelephone' => $order->get_billing_phone( ),
				'billToEmail' => $order->get_billing_email( ),
				'orderNumber' => $order_id,
				'capture' => 'yes' == $this->tpay_capture ? '1' : '0',
				'subscription' => 0,
				'platform' => 'woocommerce-redirect',
				'lang' =>get_bloginfo( 'language' ),
				'platform_reference' => $this->tpay_platform_detail( ),
				'hashVersion' => 'V2'
			];
			if ( $this->hs && ( wcs_order_contains_subscription( $order_id ) || wcs_is_subscription( $order_id ) ) ) {
				$datajson['subscription'] = 1;
			}

			$headers = array(
				'Authorization' => 'bearer ' . $result->access_token,
				'Content-type' => 'application/json',
				'Accept' => 'application/json',
				'Accept-Language' => get_bloginfo( 'language' )
			 );

			$body = wp_json_encode( $datajson );
			$this->log( $body );
			$args = array(
				'body'=> $body,
				'timeout' => '300',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'=> true,
				'headers' => $headers,
				'cookies' => array( ),
			 );
			$response = wp_remote_post( TPAY_ENV_URL . 'processPayment', $args );
			$result = json_decode( $response['body'] );

			if ( 100 == $result->type ) {
				$tpay_url_payment_form = $result->url;

				if ($this->haveActiveHPOS) {
					//HPOS
					$order->update_meta_data( 'tilopay_html_form', $tpay_url_payment_form );
					$order->update_meta_data( 'tpay_capture', $this->tpay_capture );
					//If nativo call just redirect to Tilopay form
					if ('native' === $call_from) {
						$order->update_meta_data( 'tpay_was_redirect_native', 'yes' );
					}
					$order->save();
				} else {
					update_post_meta( $order_id, 'tilopay_html_form', $tpay_url_payment_form );
					update_post_meta( $order_id, 'tpay_capture', $this->tpay_capture );
					//If nativo call just redirect to Tilopay form
					if ('native' === $call_from) {
						update_post_meta( $order_id, 'tpay_was_redirect_native', 'yes' );
					}
				}

				$checkout_url = wc_get_checkout_url( );
				//If nativo call just redirect to Tilopay form
				$for_native_error = '';
				if ('native' === $call_from) {
					$for_native_error = '&cipherError=1';
				};

				$pos = strpos( $checkout_url, '?' );

				if ( false === $pos ) {
					$checkout_url = $checkout_url . '?process_payment=tilopay&tlpy_payment_order=' . $order_id . $for_native_error;
				} else {
					$checkout_url = $checkout_url . '&process_payment=tilopay&tlpy_payment_order=' . $order_id . $for_native_error;
				}

				return array(
					'result' => 'success',
					'redirect' => $checkout_url
				 );
			} else if ( in_array( $result->type, [400, 401, 402, 403, 404] ) ) {
				//Key not found
				wc_add_notice( __( "There seems to be an issue with this website's integration with Tilopay, please inform the seller to complete your purchase.", 'tilopay' ) . ' Process Payment ' . __LINE__, 'error' );
			} else if ( 300 == $result->type ) {
				wc_add_notice( __( 'You have license errors, please try again.', 'tilopay' ), 'error' );
			} else {
				//Defult message
				wc_add_notice( __( "There seems to be an issue with this website's integration with Tilopay, please inform the seller to complete your purchase.", 'tilopay' ) . ' Process Payment ' . __LINE__, 'error' );
			}
		} else {
			//Dont have token
			wc_add_notice( __( "There seems to be an issue with this website's integration with Tilopay, please inform the seller to complete your purchase.", 'tilopay' ) . ' Process Payment ' . __LINE__, 'error' );
		} //.End check have token
	}

	/**
	 *
	 * Process payment modifications
	 *
	 */
	public function tpay_process_payment_modification( $order_id, $type, $order_total ) {
		/**
		 * $type:
		 * 1 = Capture ( captura )
		 * 2 = Refund ( reembolso )
		 * 3 = Reversal ( reverso )
		 */
		$order = wc_get_order( $order_id );
		$headers = array(
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Accept-Language' => get_bloginfo( 'language' )
		 );
		$datajson = [
			'email' => $this->tpay_user,
			'password' => $this->tpay_password
		];

		$body = wp_json_encode( $datajson );

		$args = array(
			'body'=> $body,
			'timeout' => '300',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'=> true,
			'headers' => $headers,
			'cookies' => array( ),
		 );


		$getCallTpayAPI = wp_remote_post( TPAY_ENV_URL . 'login', $args );
		if ( is_wp_error( $getCallTpayAPI ) ) {
			return false;
		}

		//All is ok
		$getTpayResponseDecode = json_decode( $getCallTpayAPI['body'] );

		//Check if have a token
		if ( isset( $getTpayResponseDecode->access_token ) ) {
			# Have token
			$headers = array(
				'Authorization' => 'bearer ' . $getTpayResponseDecode->access_token,
				'Content-type' => 'application/json',
				'Accept' => 'application/json',
				'Accept-Language' => get_bloginfo( 'language' )
			 );

			$datajson = [
				'orderNumber' => $order_id,
				'key' => $this->tpay_key,
				'amount' => $order_total,
				'type' => $type,
				'hashVersion' => 'V2',
				'platform' => ( $this->nativePaymentMethod ) ? 'woocommerce-nativo' : 'woocommerce-redirect',
				'platform_reference' => $this->tpay_platform_detail( ),
			];
			$body = wp_json_encode( $datajson );
			$this->log( $body );
			$args = array(
				'body'=> $body,
				'timeout' => '300',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'=> true,
				'headers' => $headers,
				'cookies' => array( ),
			 );
			$getCallTpayAPIModification = wp_remote_post( TPAY_ENV_URL . 'processModification', $args );
			if ( is_wp_error( $getCallTpayAPIModification ) ) {
				return false;
			}

			$getModificationResDecode = json_decode( $getCallTpayAPIModification['body'] );

			return $getModificationResDecode;
		} //.End check have token

		//Error default
		return false;
	}


	/**
	 * All payment icons that work with TILOPAY. Some icons references
	 * WC core icons.
	 *
	 * @since 2.3.0
	 * @return array
	 */
	public function payment_icons() {
		return apply_filters(
			'tilopay',
			array(
				'visa' => '<img src="' . TPAY_PLUGIN_URL . '/assets/images/visa.svg" style="float: none; max-height: 20px; margin:0px 10px" alt="Visa" />',
				'amex' => '<img src="' . TPAY_PLUGIN_URL . '/assets/images/amex.svg" style="float: none; max-height: 20px; margin:0px 10px"alt="Ame" />',
				'mastercard' => '<img src="' . TPAY_PLUGIN_URL . '/assets/images/mastercard.svg" style="float: none; max-height: 20px; margin:0px 10px" alt="Mastercard" />',
			 )
		 );
	}

	/**
	 * Check incoming requests for Tilopay Webhook data and process them.
	 */
	public function tpay_payment_webhook_process() {
		if (
			isset( $_SERVER['REQUEST_METHOD'] ) && ( 'POST' !== $_SERVER['REQUEST_METHOD'] )
			|| !isset( $_GET['wc-api'] )
		 ) {
			return;
		}

		//is post method and wc-api;
		$request_body		= file_get_contents( 'php://input' );
		$responseJson		= ( object ) json_decode( $request_body );
		if ( JSON_ERROR_NONE !== json_last_error( ) ) {
			return wp_send_json( array(
				'code' => 404,
				'message' => 'Unknown request body json',
				'data' => json_decode( $request_body )
			 ), 404 );
			exit;
		}

		//json decode successefully
		if ( ( !empty( $responseJson->orderNumber ) && isset( $responseJson->orderNumber ) ) &&
			( !empty( $responseJson->code ) && isset( $responseJson->code ) ) &&
			( !empty( $responseJson->orderHash ) && isset( $responseJson->orderHash ) ) &&
			( !empty( $responseJson->tpt ) && isset( $responseJson->tpt ) )
		 ) {

			$orderNumber = $responseJson->orderNumber;
			$code = $responseJson->code;
			$tpay_order_id = $responseJson->tpt;
			$auth = $responseJson->auth;
			//Get Woocommerce order
			global $woocommerce;

			$order = wc_get_order( $orderNumber );
			$orderHash = $responseJson->orderHash;
			if ( !empty( $orderHash ) && isset( $orderHash ) && 64 == strlen( $orderHash ) ) {

				$amount = $order->get_total( );

				// Get the Customer billing email
				$billing_email= $order->get_billing_email( );

				// Get the customer or user id from the order object
				$customer_id = $order->get_customer_id( );

				//computed hash_hmac
				$customerOrderHash = $this->computed_customer_hash($orderNumber, $amount, get_woocommerce_currency( ), $tpay_order_id, $code, $auth, $billing_email);

				//Use hmac data to check if the response is from Tilopay or not
				if ( !hash_equals( $customerOrderHash, $orderHash ) ) {

					$order->add_order_note( __( 'Description:', 'tilopay' ) . __( 'Order with failed payment', 'tilopay' ) );
					// translators: %s the order number.
					$get_traslate_message = sprintf( __( 'Invalid order confirmation, please check and try again or contact the seller to completed you order no.%s', 'tilopay' ), $orderNumber ) . ', order has status: ' . $order->get_status( );
					//response api
					return wp_send_json( array(
						'code' => 400,
						'message' => $get_traslate_message,
						'data' => json_decode( $request_body )
					 ), 400 );
					exit;
				} else if ( hash_equals( $customerOrderHash, $orderHash ) && !empty( $code ) ) {
					/**
					 * The hmac hash is equal from Tiloay server,
					 * Process to check if the payment was approved to pudate the order status payment.
					 */

					if ( 1 == $code && !empty( $responseJson->auth ) && isset( $responseJson->auth ) ) {
						$auth = $responseJson->auth;
						/**
						 * If payment was approve by Tilopay
						 * Process to pudate the order status payment.
						 */

						if ( $this->haveActiveHPOS ) {
							// HPOS
							$existing_auth_code = $order->get_meta( 'tilopay_auth_code', true);
						} else {
							$existing_auth_code = get_post_meta( $orderNumber, 'tilopay_auth_code', true );
						}
						//check if order is pending || on-hold to update status
						if ( in_array( $order->get_status( ), ['on-hold'] ) && !empty( $existing_auth_code ) && 'Pending' == $existing_auth_code ) {
							//set processing
							$order->update_status( 'processing' );
							if ($this->haveActiveHPOS) {
								//HPOS
								$order->update_meta_data( 'tilopay_auth_code', $auth );
								$order->save();
							} else {
								update_post_meta( $orderNumber, 'tilopay_auth_code', $auth );
							}
							// translators: %s action type.
							$order->add_order_note( sprintf( __( '%s Tilopay id:', 'tilopay' ), 'PayCash' ) . $tpay_order_id );
							$order->add_order_note( __( 'Authorization:', 'tilopay' ) . $auth );
							$order->add_order_note( __( 'Code:', 'tilopay' ) . $code );
							$order->add_order_note( __( 'Description:', 'tilopay' ) . __( 'Payment was successfully', 'tilopay' ) );
						}
						//response api
						return wp_send_json( array(
							'code' => 200,
							'message' => 'Great order update to ' . $order->get_status( ),
							'data' => json_decode( $request_body )
						 ), 200 );
						exit;
					} else if ( 'Pending' == $code ) {
						if ( empty( $existing_auth_code ) ) {
							//Update order status to pending
							$order->update_status( 'on-hold' );
							$order->add_order_note( __( 'Code:', 'tilopay' ) . $code );
						}
						$order->add_order_note( __( 'Description:', 'tilopay' ) . __( 'Payment is pending.', 'tilopay' ) ); //si lo devuelve
						//Response api
						return wp_send_json( array(
							'code' => 200,
							'message' => 'Order has status: ' . $order->get_status( ),
							'data' => json_decode( $request_body )
						 ), 200 );
						exit;
					} else {
						//The payment was not approved by Tilopay
						$order->update_status( 'failed' );
						$order->add_order_note( __( 'Order with failed payment', 'tilopay' ) );
						$order->add_order_note( __( 'Code:', 'tilopay' ) . $code );

						//Response api
						return wp_send_json( array(
							'code' => 200,
							'message' => __( 'Order with failed payment', 'tilopay' ) . ', order has status: ' . $order->get_status( ),
							'data' => json_decode( $request_body )
						 ), 200 );
						exit;
					}
				} else {
					//No hash or not equals
					$order->add_order_note( __( 'Description:', 'tilopay' ) . __( 'Order with failed payment', 'tilopay' ) );

					//Response api
					return wp_send_json( array(
						'code' => 400,
						'message' => __( 'Order with failed payment', 'tilopay' ) . ', order has status: ' . $order->get_status( ),
						'data' => json_decode( $request_body )
					 ), 400 );
					exit;
				}
			}
		}

		return wp_send_json( array(
			'code' => 500,
			'message' => 'Unknown error',
			'data' => json_decode( $request_body )
		 ), 500 );
		exit;
	}

	public function tpay_get_token_sdk() {
		$headers = array(
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Accept-Language' => get_bloginfo( 'language' )
		 );
		$datajson = [
			'apiuser' => $this->tpay_user,
			'password' => $this->tpay_password,
			'key' => $this->tpay_key
		];

		$body = wp_json_encode( $datajson );

		$args = array(
			'body'=> $body,
			'timeout' => '300',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'=> true,
			'headers' => $headers,
			'cookies' => array( ),
		 );


		$response = wp_remote_post( TPAY_ENV_URL . 'loginSdk', $args );
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$result = json_decode( $response['body'] );

		//"expires_in": 86400
		//Check if have a token
		if ( isset( $result->access_token ) ) {
			return $result->access_token;
		}
		return false;
	}


	/**
	 * Helper to set SDK config
	 *
	 * @return array
	 */
	public function setDataInit() {
		/**
		 * To get global
		 * Global $woocommerce; ( $woocommerce->cart->total ) ? $woocommerce->cart->total : 99999
		 */

		$time = time( );
		$is_user_logged_in = 0;
		$email_current_user = 'john-doe-' . $time . '@tilopay.com';
		$firstname_current_user = 'John';
		$lastname_current_user = 'Doe';
		//from WOO customer
		$billing_phone_current_user= '88888888';
		$billing_address_1_current_user= 'San Jose';
		$billing_address_2_current_user= 'Aserri';
		$billing_city_current_user = 'SJO';
		$billing_state_current_user= 'SJO';
		$billing_postcode_current_user = '1001';
		$billing_country_current_user= 'CR';
		if ( is_user_logged_in( ) ) {
			$is_user_logged_in = 1;
			$current_user = wp_get_current_user( );
			$email_current_user = ( $current_user ) ? $current_user->user_email : $email_current_user;
			// Get an instance of the WC_Customer Object from the user ID
			$customer = new WC_Customer( $current_user->ID );

			//from WOO customer
			$billing_phone_current_user= ( $customer->get_billing_phone( ) != null ) ? $customer->get_billing_phone( ) : $billing_phone_current_user;
			$billing_address_1_current_user= ( $customer->get_billing_address_1( ) != null ) ? $customer->get_billing_address_1( ) : $billing_address_1_current_user;
			$billing_address_2_current_user= ( $customer->get_billing_address_2( ) != null ) ? $customer->get_billing_address_2( ) : $billing_address_2_current_user;
			$billing_city_current_user = ( $customer->get_billing_city( ) != null ) ? $customer->get_billing_city( ) : $billing_city_current_user;
			$billing_state_current_user= ( $customer->get_billing_state( ) != null ) ? $customer->get_billing_state( ) : $billing_state_current_user;
			$billing_postcode_current_user = ( $customer->get_billing_postcode( ) != null ) ? $customer->get_billing_postcode( ) : $billing_postcode_current_user;
			$billing_country_current_user= ( $customer->get_billing_country( ) != null ) ? $customer->get_billing_country( ) : $billing_country_current_user;
		}

		$envMode = __( 'TEST MODE ENABLED. In test mode, you can use the card numbers listed in', 'tilopay' );
		$integrationError =__( "There seems to be an issue with this website's integration with Tilopay, please inform the seller to complete your purchase.", 'tilopay' ) . ' Set Data Init ' . __LINE__;

		//check total with tax incluide
		$cart_total_price = wc_prices_include_tax()
			? WC( )->cart->get_cart_contents_total() + WC( )->cart->get_cart_contents_tax()
			: WC( )->cart->get_cart_contents_total();
		$wooSessionTpay = ( WC()->cart->get_cart_hash() ) ? 'WOO-' . time() . '-' . WC()->cart->get_cart_hash() : 'WOO-' . time();
		//should 32 characters
		$wooSessionTpay = ( strlen( $wooSessionTpay ) <= 25 ) ? $wooSessionTpay : substr( $wooSessionTpay, 0, ( 25 - strlen( $wooSessionTpay ) ) );

		return array(
			'token' => $this->tpay_get_token_sdk( ),
			'currency' =>get_woocommerce_currency( ),
			'language' => get_bloginfo( 'language' ),
			'amount' => $cart_total_price,
			'amount_sinpe' => $cart_total_price,
			'billToFirstName' => $firstname_current_user,
			'billToLastName' => $lastname_current_user,
			'billToAddress' => $billing_address_1_current_user,
			'billToAddress2' => $billing_address_2_current_user,
			'billToCity' => $billing_city_current_user,
			'billToState' => $billing_state_current_user,
			'billToZipPostCode' => $billing_postcode_current_user,
			'billToCountry' => $billing_country_current_user,
			'billToTelephone' => $billing_phone_current_user,
			'billToEmail' => $email_current_user,
			'orderNumber' => 'init-default-' . time( ),
			'capture' => 'yes' == $this->tpay_capture ? '1' : '0',
			'redirect' => $this->tpay_checkout_redirect,
			'subscription' => 0,
			'platform' => 'woocommerce',
			'platform_reference' => $this->tpay_platform_detail( ),
			'envMode' => $envMode,
			'integrationError' => $integrationError,
			'newCardText' => __( 'Pay with another card', 'tilopay' ),
			'userDataIn' => $is_user_logged_in,
			'cardError' => __( 'Check credit or debit card details', 'tilopay' ),
			'urlTilopay' => TPAY_BASE_URL,
			'Key' => $this->tpay_key,
			'tpayPluginUrl' => TPAY_PLUGIN_URL,
			'hashVersion' => 'V2',
			'haveSubscription' => $this->tpay_check_have_subscription( ),
			'wooSessionTpay' => $wooSessionTpay,
		 );
	}

	/**
	 * Make platformDetail
	 *
	 * @return json object
	 */
	public function tpay_platform_detail() {
		$wooVersion = ( null != WC_VERSION ) ? WC_VERSION : null;
		// Get the WP Version global.
		global $wp_version;
		$wpVersion = ( $wp_version ) ? $wp_version : null;
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		return json_encode( [
			'pluginTilopay' => 'V->' . TPAY_PLUGIN_VERSION,
			'woocommerce' => 'V->' . $wooVersion,
			'WordPress' => 'V->' . $wpVersion,
			'HPOS' => true,
			'userAgentWP' => $user_agent
			] );
	}

	public function tpay_call_to_make_order_payment( $bodyRequest ) {
		$headers = array(
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Accept-Language' => get_bloginfo( 'language' )
		 );

		$body = wp_json_encode( $bodyRequest );

		$args = array(
			'body'=> $body,
			'timeout' => '300',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'=> true,
			'headers' => $headers,
			'cookies' => array(),
			'method'=> 'POST',
		 );


		$response = wp_remote_post( TPAY_BASE_URL . 'admin/processPaymentFAC', $args );

		if ( is_wp_error( $response ) ) {
			//erros
			wc_add_notice( 'Error: ' . __( "There seems to be an issue with this website's integration with Tilopay, please inform the seller to complete your purchase.", 'tilopay' ) . ' Make Order Payment ' . __LINE__, 'error' );
			return false;
		}

		$getBody = json_decode( $response['body'], true );

		if ( $getBody ) {
			switch ( $getBody['type'] ) {
				case '400':
					# Tokenex

					$callTokenex = false;
					if ( 'TOKENEX' == $getBody['card']['brand'] ) {
						# callback Tilopay
						$callTokenex = $this->tpay_callback_tilopay_to_process_tokenex( $getBody );
					}

					return [
						'status' => ( $callTokenex ) ? '200' : '400',
						'type' => $getBody['type'],
						'enpoint' => ( $response ) ? $callTokenex : wc_get_checkout_url( ),
						'redirect' => ( $callTokenex ) ? true : false,
						'data' => $getBody,
					];
					break;
				case '100':
					# 3ds
					$getHtml = $getBody['htmlFormData'];
					$temp_3ds_url_file = '';
					//if 3ds html not empty
					if ( '' != $getHtml ) {
						//make temp 3ds file
						$temp_3ds_url_file = $this->tpay_make_temp_3ds_file( $getHtml );
					}

					return [
						'status' => ( $temp_3ds_url_file ) ? '200' : '400',
						'type' => $getBody['type'],
						'enpoint' => $temp_3ds_url_file,
						'redirect' => true,
						'data' => $getBody,
					];
					break;
				case '200':
					# reload or approved
					return [
						'status' => ( $getBody['url'] ) ? '200' : '400',
						'type' => $getBody['type'],
						'enpoint' => $getBody['url'],
						'redirect' => true,
						'data' => $getBody,
					];
					break;
				default:
					# error
					$getErrorResponse = sanitize_text_field( isset( $getBody['result'] ) ) ? sanitize_text_field( $getBody['result'] ) : '';
					$error_message_validation = 'Your payment could not be processed, please check and try again';
					wc_add_notice( 'Error: ' . __( $error_message_validation, 'tilopay' ) . ' ' . $getErrorResponse, 'error' );
					return [
						'status' => '400',
						'type' => $getBody['type'],
						'enpoint' => wc_get_checkout_url( ),
						'redirect' => false,
						'data' => $getErrorResponse,
					];
					break;
			}
		}
		//default
		return false;
	}

	/**
	 * Helper to call tokenex and process payment 3ds or just redirect
	 *
	 * @param array
	 * @return mix bolean || string
	 */
	public function tpay_callback_tilopay_to_process_tokenex( $bodyRequest ) {


		$headers = array(
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Accept-Language' => get_bloginfo( 'language' )
		 );

		$body = wp_json_encode( ['data' => $bodyRequest] );

		$args = array(
			'body'=> $body,
			'timeout' => '300',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'=> true,
			'headers' => $headers,
			'cookies' => array( ),
			'method'=> 'POST',
		 );


		$response = wp_remote_post( TPAY_BASE_URL . 'admin/processPaymentTokenex', $args );

		if ( is_wp_error( $response ) ) {
			//erros
			wc_add_notice( 'Error: ' . __( "There seems to be an issue with this website's integration with Tilopay, please inform the seller to complete your purchase.", 'tilopay' ) . ' Process Tokenex ' . __LINE__, 'error' );
			return false;
		}
		$getBody = json_decode( $response['body'], true );

		if ( $getBody ) {
			if ( '100' == $getBody['type'] ) {
				# get html
				$getHtml = isset( $getBody['htmlFormData'] ) ? $getBody['htmlFormData'] : '';
				$temp_3ds_url_file = false;
				//if 3ds html not empty
				if ( '' != $getHtml ) {
					//insert script to auto clik #tilopay_place
					$insertScript = '<script>$( "#tilopay_place", window.parent.document ).trigger( "click" );</script></body>';
					$getHtml = str_replace( '</body>', $insertScript, $getHtml );
					//make temp 3ds file
					$temp_3ds_url_file = $this->tpay_make_temp_3ds_file( $getHtml );
				}
				//string url to redirect from temp file
				return $temp_3ds_url_file;
			} else if ( '200' == $getBody['type'] ) {
				//string url to redirect
				return $getBody['url'];
			} else {
				//string error
				return $getBody['result'];
			}
		}
		return false;
	}

	public function tpay_make_temp_3ds_file( $getHtml ) {
		//insert script to show spinner Tilopay
		$insertScript = '<body><style>#loading{position: absolute;left: 50%;top: 50%;z-index: 1;width: 150px;height: 150px;margin: -75px 0 0 -75px;border: 16px solid #f3f3f3;border-radius: 50%;border-top: 16px solid #ff3644 ;width: 120px;height: 120px;animation: spin 2s linear infinite;}@keyframes spin {0% { transform: rotate( 0deg ); }100% { transform: rotate( 360deg ); }}</style><div class="d-flex justify-content-center"><div class="spinner-border" role="status" ><span class="sr-only" id="loading"></span></div></div>';
		$getHtml = str_replace( '<body>', $insertScript, $getHtml );

		$fileName = '3ds_payment.html';
		$folder = WP_PLUGIN_DIR . '/tilopay/includes/' . $fileName;
		//remove file each time have been used
		if ( file_exists( $folder ) ) {
			unlink( $folder );
		}
		$file_3ds_temp = fopen( $folder, 'a' );
		fputs( $file_3ds_temp, $getHtml );
		fclose( $file_3ds_temp );
		//retur url
		return plugins_url( $fileName, __FILE__ );
	}

	/**
	 *
	 * Update payment status
	 * $this->tpay_capture_yes = Have the user order status config from admin
	 * Payment status: ( 'yes' == $this->tpay_capture ) ? set user status config : pending
	 *
	 */
	public function tpay_get_order_status() {
		if ( 'yes' == $this->tpay_capture ) {
			return ( $this->tpay_capture_yes ) ? $this->tpay_capture_yes : 'processing';
		}
		//Default
		return 'pending';
	}

	/**
	 * Check product have suscriptions
	 */
	public function tpay_check_have_subscription() {
		$is_subscription = 0;
		//Check if have suscription at cart
		if ( $this->hs ) {
			if ( !empty( WC()->cart ) ) {
				$cart_items = WC()->cart->get_cart();

				foreach ( $cart_items as $cart_item_key => $cart_item ) {
					$product_id = $cart_item['product_id'];
					$product = wc_get_product( $product_id );

					if ( $product && 'subscription' === $product->get_type() ) {
						$is_subscription = 1;
						break;
					}
				}
			} else {
				$order = wc_get_order( );
				if ( $order ) {
					$order_id = $order->get_id( );
					$is_subscription = ( wcs_order_contains_subscription( $order_id ) || wcs_is_subscription( $order_id ) );
				}
			}
		}
		return $is_subscription;
	}

	/**
	 * Check if using redirect or native.
	 */
	public function isNativePayment() {
		return $this->nativePaymentMethod;
	}
}
