<?php
/**
 * PeachPay Stripe util class.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * .
 */
final class PeachPay_Stripe {

	const SUPPORTED_CURRENCIES = array(
		'USD',
		'AED',
		'AFN',
		'ALL',
		'AMD',
		'ANG',
		'AOA',
		'ARS',
		'AUD',
		'AWG',
		'AZN',
		'BAM',
		'BBD',
		'BDT',
		'BGN',
		'BIF',
		'BMD',
		'BND',
		'BOB',
		'BRL',
		'BSD',
		'BWP',
		'BYN',
		'BZD',
		'CAD',
		'CDF',
		'CHF',
		'CLP',
		'CNY',
		'COP',
		'CRC',
		'CVE',
		'CZK',
		'DJF',
		'DKK',
		'DOP',
		'DZD',
		'EGP',
		'ETB',
		'EUR',
		'FJD',
		'FKP',
		'GBP',
		'GEL',
		'GIP',
		'GMD',
		'GNF',
		'GTQ',
		'GYD',
		'HKD',
		'HNL',
		'HRK',
		'HTG',
		'HUF',
		'IDR',
		'ILS',
		'INR',
		'ISK',
		'JMD',
		'JPY',
		'KES',
		'KGS',
		'KHR',
		'KMF',
		'KRW',
		'KYD',
		'KZT',
		'LAK',
		'LBP',
		'LKR',
		'LRD',
		'LSL',
		'MAD',
		'MDL',
		'MGA',
		'MKD',
		'MMK',
		'MNT',
		'MOP',
		'MRO',
		'MUR',
		'MVR',
		'MWK',
		'MXN',
		'MYR',
		'MZN',
		'NAD',
		'NGN',
		'NIO',
		'NOK',
		'NPR',
		'NZD',
		'PAB',
		'PEN',
		'PGK',
		'PHP',
		'PKR',
		'PLN',
		'PYG',
		'QAR',
		'RON',
		'RSD',
		'RUB',
		'RWF',
		'SAR',
		'SBD',
		'SCR',
		'SEK',
		'SGD',
		'SHP',
		'SLE',
		'SLL',
		'SOS',
		'SRD',
		'STD',
		'SZL',
		'THB',
		'TJS',
		'TOP',
		'TRY',
		'TTD',
		'TWD',
		'TZS',
		'UAH',
		'UGX',
		'UYU',
		'UZS',
		'VND',
		'VUV',
		'WST',
		'XAF',
		'XCD',
		'XOF',
		'XPF',
		'YER',
		'ZAR',
		'ZMW',
	);

	const SUPPORTED_ZERO_DECIMAL_CURRENCIES = array(
		'BIF',
		'CLP',
		'DJF',
		'GNF',
		'JPY',
		'KMF',
		'KRW',
		'MGA',
		'PYG',
		'RWF',
		'UGX',
		'VND',
		'VUV',
		'XAF',
		'XOF',
		'XPF',
		'TWD',
	);

	/**
	 * Formats a stripe amount for displaying to merchants/customers.
	 *
	 * @param string $amount Stripe amount.
	 * @param string $currency_code Currency code returned from Stripe API.
	 */
	public static function display_amount( $amount, $currency_code ) {
		$amount = floatval( $amount );

		if ( ! in_array( $currency_code, self::SUPPORTED_ZERO_DECIMAL_CURRENCIES, true ) ) {
			$amount = $amount / 100;
		}

		return $amount;
	}

	/**
	 * Formats the statement descriptor for Stripe transactions by replacing placeholders with the order values.
	 *
	 * @param string   $descriptor_template Template that merchant has entered.
	 * @param WC_Order $order WooCommerce order object.
	 */
	public static function format_descriptor( $descriptor_template, $order ) {
		if ( ! $order instanceof WC_Order ) {
			return $descriptor_template;
		}

		$descriptor_template = str_replace( '{order_number}', $order->get_order_number(), $descriptor_template );

		$descriptor_template = str_replace( '{order_id}', $order->get_id(), $descriptor_template );

		return $descriptor_template;
	}

	/**
	 * Formats an amount to use with stripe API's
	 *
	 * @param string|float $amount The amount to format.
	 * @param string       $currency_code Currency code returned from Stripe API.
	 */
	public static function format_amount( $amount, $currency_code ) {
		$amount = floatval( $amount );

		if ( in_array( $currency_code, self::SUPPORTED_ZERO_DECIMAL_CURRENCIES, true ) ) {
			return round( $amount );
		}

		return round( $amount * 100 );
	}

	/**
	 * Builds a href for linking to a merchants stripe dashboard.
	 *
	 * @param string      $stripe_mode A live or test mode url.
	 * @param string|null $connect_id The stripe account connect id to make the link for. Leave null to take from the registration.
	 * @param string      $path The path of the URL.
	 * @param string      $title The text the link should display.
	 * @param boolean     $echo_href To echo a href of the URL.
	 */
	public static function dashboard_url( $stripe_mode, $connect_id, $path, $title = null, $echo_href = true ) {
		if ( null === $connect_id ) {
			$connect_id = PeachPay_Stripe_Integration::connect_id();
		}

		$url = 'https://dashboard.stripe.com/';

		if ( 'live' === $stripe_mode ) {
			$url = $url . "connect/accounts/$connect_id/$path";
		} else {
			$url = $url . "test/connect/accounts/$connect_id/$path";
		}

		if ( $echo_href ) {
            // PHPCS:ignore
            echo "<a href='$url' target='_blank'>$title</a>";
		}

		return $url;
	}

	/**
	 * Creates a payment intent in stripe.
	 *
	 * @param WC_Order $order The woocommerce order to create a payment intent for.
	 * @param array    $payment_intent_params The parameters for the payment intent.
	 * @param array    $order_details The order details needed to create the payment intent.
	 * @param string   $mode The mode to place the payment in.
	 */
	public static function create_payment( $order, $payment_intent_params, $order_details, $mode ) {
		$request = peachpay_json_remote_post(
			peachpay_api_url( $mode ) . 'api/v2/stripe/payment-intent',
			array(
				'data_format' => 'body',
				'headers'     => array(
                    // PHPCS:ignore
					'Content-Type'            => 'application/json; charset=utf-8',
					'PeachPay-Mode'           => $mode,
					'PeachPay-Merchant-Id'    => peachpay_plugin_merchant_id(),
					'PeachPay-Transaction-Id' => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'transaction_id' ),
					'PeachPay-Session-Id'     => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'session_id' ),
					'PeachPay-Plugin-Version' => PEACHPAY_VERSION,
					'Idempotency-Key'         => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'transaction_id' ),
				),
				'body'        => wp_json_encode(
					array(
						'payment_intent_params' => $payment_intent_params,
						'order_details'         => $order_details,
					)
				),
			)
		);

		if ( isset( $request['error'] ) && is_array( $request['error'] ) ) {
			$order->update_status(
				'failed',
				sprintf(
					// translators: The failure reason
					__(
						'PeachPay API request failed:
%s

',
						'peachpay-for-woocommerce'
					),
					implode( "\n", $request['error'] )
				)
			);

			return null;
		}

		$json = $request['result'];

		if ( ! $json['success'] ) {
			if ( function_exists( 'wc_add_notice' ) ) {
				wc_add_notice( __( 'Payment error: ', 'peachpay-for-woocommerce' ) . $json['message'], 'error' );
			}

			// translators: The payment method title, The failure reason
			$order->update_status( 'failed', sprintf( __( 'Stripe %1$s payment failed: %2$s', 'peachpay-for-woocommerce' ), $order->get_payment_method_title(), $json['message'] ) );
			return null;
		}

		$data = $json['data'];
		self::calculate_payment_state( $order, $data );

		return $data['payment_intent_details'];
	}

	/**
	 * Captures a stripe payment.
	 *
	 * @param WC_order $order The order to capture a payment intent for.
	 * @param int      $capture_amount The amount of the order to capture in stripe amount format.
	 */
	public static function capture_payment( $order, $capture_amount ) {
		$mode    = PeachPay_Stripe_Order_Data::get_payment_intent( $order, 'mode' );
		$request = peachpay_json_remote_post(
			peachpay_api_url( $mode ) . 'api/v2/stripe/payment-intent/capture',
			array(
				'data_format' => 'body',
				'headers'     => array(
                    // PHPCS:ignore
					'Content-Type'            => 'application/json; charset=utf-8',
					'PeachPay-Mode'           => $mode,
					'PeachPay-Merchant-Id'    => peachpay_plugin_merchant_id(),
					'PeachPay-Session-Id'     => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'session_id' ),
					'PeachPay-Transaction-Id' => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'transaction_id' ),
					'PeachPay-Plugin-Version' => PEACHPAY_VERSION,
					'Idempotency-Key'         => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'transaction_id' ),
				),
				'body'        => wp_json_encode(
					array(
						'payment_intent_id' => PeachPay_Stripe_Order_Data::get_payment_intent( $order, 'id' ),
						'amount_to_capture' => $capture_amount,
					)
				),
			)
		);

		if ( isset( $request['error'] ) && is_array( $request['error'] ) ) {
			return array(
				'success' => false,
				'message' => sprintf(
					// translators: The failure reason
					__(
						'PeachPay API request failed:
%s

',
						'peachpay-for-woocommerce'
					),
					implode( "\n", $request['error'] )
				),
			);
		}

		$json = $request['result'];

		if ( ! $json['success'] ) {
			return array(
				'success' => false,
				'message' => $json['message'],
			);
		}

		self::calculate_payment_state( $order, $json['data'] );

		return array(
			'success' => true,
			'message' => 'Success.',
		);
	}

	/**
	 * Voids a stripe payment.
	 *
	 * @param WC_order                                                          $order The order to void a payment intent for.
	 * @param 'abandoned'|'duplicate'|'fraudulent'|'requested_by_customer'|null $cancellation_reason The reason to cancel.
	 */
	public static function void_payment( $order, $cancellation_reason = null ) {
		$mode    = PeachPay_Stripe_Order_Data::get_payment_intent( $order, 'mode' );
		$request = peachpay_json_remote_post(
			peachpay_api_url( $mode ) . 'api/v2/stripe/payment-intent/void',
			array(
				'data_format' => 'body',
				'headers'     => array(
					'Content-Type'            => 'application/json; charset=utf-8',
					'PeachPay-Mode'           => $mode,
					'PeachPay-Merchant-Id'    => peachpay_plugin_merchant_id(),
					'PeachPay-Session-Id'     => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'session_id' ),
					'PeachPay-Transaction-Id' => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'transaction_id' ),
					'PeachPay-Plugin-Version' => PEACHPAY_VERSION,
					'Idempotency-Key'         => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'transaction_id' ),
				),
				'body'        => wp_json_encode(
					array(
						'payment_intent_id'   => PeachPay_Stripe_Order_Data::get_payment_intent( $order, 'id' ),
						'cancellation_reason' => $cancellation_reason,
					)
				),
			)
		);

		if ( isset( $request['error'] ) && is_array( $request['error'] ) ) {
			return array(
				'success' => false,
				'message' => sprintf(
					// translators: The failure reason
					__(
						'PeachPay API request failed:
%s

',
						'peachpay-for-woocommerce'
					),
					implode( "\n", $request['error'] )
				),
			);
		}

		$json = $request['result'];

		if ( ! $json['success'] ) {
			return array(
				'success' => false,
				'message' => $json['message'],
			);
		}

		self::calculate_payment_state( $order, $json['data'] );

		return array(
			'success' => true,
			'message' => 'Success.',
		);
	}

	/**
	 * Refunds a stripe payment.
	 *
	 * @param WC_order                                              $order The order to void a payment intent for.
	 * @param int|null                                              $amount The amount to refund in stripe zero decimal format. Passing null will refund the entire/remaining amount of the order.
	 * @param 'duplicate'|'fraudulent'|'requested_by_customer'|null $reason The reason to refund.
	 */
	public static function refund_payment( $order, $amount = null, $reason = null ) {
		$mode    = PeachPay_Stripe_Order_Data::get_payment_intent( $order, 'mode' );
		$request = peachpay_json_remote_post(
			peachpay_api_url( $mode ) . 'api/v2/stripe/refund',
			array(
				'data_format' => 'body',
				'headers'     => array(
					'Content-Type'            => 'application/json; charset=utf-8',
					'PeachPay-Mode'           => $mode,
					'PeachPay-Merchant-Id'    => peachpay_plugin_merchant_id(),
					'PeachPay-Session-Id'     => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'session_id' ),
					'PeachPay-Transaction-Id' => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'transaction_id' ),
					'PeachPay-Plugin-Version' => PEACHPAY_VERSION,
					'Idempotency-Key'         => PeachPay_Stripe_Order_Data::get_peachpay( $order, 'transaction_id' ),
				),
				'body'        => wp_json_encode(
					array(
						'payment_intent_id'      => PeachPay_Stripe_Order_Data::get_payment_intent( $order, 'id' ),
						'charge_id'              => PeachPay_Stripe_Order_Data::get_charge( $order, 'id' ),
						'amount'                 => $amount,
						'reason'                 => $reason,
						'refund_application_fee' => PeachPay_Stripe_Order_Data::has_service_fee( $order ),
					)
				),
			)
		);

		if ( isset( $request['error'] ) && is_array( $request['error'] ) ) {
			return array(
				'success' => false,
				'message' => sprintf(
					// translators: The failure reason
					__(
						'PeachPay API request failed:
%s

',
						'peachpay-for-woocommerce'
					),
					implode( "\n", $request['error'] )
				),
			);
		}

		$json = $request['result'];

		if ( ! $json['success'] ) {
			return array(
				'success' => false,
				'message' => $json['message'],
			);
		}

		self::calculate_payment_state( $order, $json['data'] );

		$refunds  = PeachPay_Stripe_Order_Data::get_charge( $order, 'refunds' );
		$refunded = PeachPay_Stripe_Order_Data::get_charge( $order, 'refunded' );
		if ( null !== $refunds ) {
			$refund = array_shift( $refunds );

			$refund_id     = $refund['id'];
			$refund_amount = wc_price( self::display_amount( $refund['amount'], strtoupper( $refund['currency'] ) ), array( 'currency' => strtoupper( $refund['currency'] ) ) );
			if ( $refunded ) {
				// translators: %1$s the payment method title, %3$s Refund amount, %4$s Refund Id.
				$order->add_order_note( sprintf( __( 'Stripe %1$s payment refunded %2$s (Refund Id: %3$s)', 'peachpay-for-woocommerce' ), $order->get_payment_method_title(), $refund_amount, $refund_id ) );
			} else {
				// translators: %1$s the payment method title, %3$s Refund amount, %4$s Refund Id.
				$order->add_order_note( sprintf( __( 'Stripe %1$s payment partially refunded %2$s (Refund Id: %3$s)', 'peachpay-for-woocommerce' ), $order->get_payment_method_title(), $refund_amount, $refund_id ) );
			}
		}

		return array(
			'success' => true,
			'message' => 'Success.',
		);
	}

	/**
	 * Calculates the payment state to change a orders status.
	 *
	 * @param WC_Order $order The WC stripe order to calculate its order status.
	 * @param array    $payment_details The latest stripe payment details.
	 * @param string   $reason An optional message to include with some order status changes. Mostly for indicating error messages.
	 */
	public static function calculate_payment_state( $order, $payment_details = null, $reason = '' ) {
		$old_transaction_status = PeachPay_Stripe_Order_Data::get_payment_intent( $order, 'status' );

		if ( null !== $payment_details ) {
			if ( isset( $payment_details['payment_intent_details'] ) ) {
				PeachPay_Stripe_Order_Data::set_payment_intent_details( $order, $payment_details['payment_intent_details'] );
			}

			if ( isset( $payment_details['payment_method_details'] ) ) {
				PeachPay_Stripe_Order_Data::set_payment_method_details( $order, $payment_details['payment_method_details'] );
				if ( 'card' === PeachPay_Stripe_Order_Data::get_payment_method( $order, 'type' ) ) {
					if ( 'peachpay_stripe_card' === $order->get_payment_method() ) {
						PeachPay_Stripe_Card_Gateway::set_payment_method_title( $order );
					} elseif ( 'peachpay_stripe_applepay' === $order->get_payment_method() ) {
						PeachPay_Stripe_Applepay_Gateway::set_payment_method_title( $order );
					} elseif ( 'peachpay_stripe_googlepay' === $order->get_payment_method() ) {
						PeachPay_Stripe_Googlepay_Gateway::set_payment_method_title( $order );
					}
				}
				if ( 'us_bank_account' === PeachPay_Stripe_Order_Data::get_payment_method( $order, 'type' ) && 'peachpay_stripe_achdebit' === $order->get_payment_method() ) {
					PeachPay_Stripe_Achdebit_Gateway::set_payment_method_title( $order );
				}
			}

			if ( isset( $payment_details['charge_details'] ) ) {
				PeachPay_Stripe_Order_Data::set_charge_details( $order, $payment_details['charge_details'] );
			}
		}

		$order_status   = $order->get_status();
		$payment_status = PeachPay_Stripe_Order_Data::get_payment_intent( $order, 'status' );
		$charge_id      = PeachPay_Stripe_Order_Data::get_charge( $order, 'id' );
		if ( null !== $charge_id ) {
			$order->set_transaction_id( $charge_id );
		}

		if ( $old_transaction_status === $payment_status ) {
			if ( ! isset( $payment_details['type'] ) ) {
				return;
			}

			if ( 'charge.dispute.created' === $payment_details['type'] && PeachPay_Stripe_Advanced::get_setting( 'dispute_created' ) === 'yes' ) {
				self::handle_dispute_created( $order, $payment_details );
			}

			if ( 'charge.dispute.closed' === $payment_details['type'] && PeachPay_Stripe_Advanced::get_setting( 'dispute_closed' ) === 'yes' ) {
				self::handle_dispute_closed( $order, $payment_details );
			}

			return;
		}

		$save_payment_method = PeachPay_Stripe_Order_Data::is_save_to_account( $order, 'save_to_account' );

		if ( 'succeeded' === $payment_status && ! $order->is_paid() ) {
			$order->payment_complete();
			if ( 'on-hold' === $order_status ) {
				// translators: %1$s Payment method title,  %2$s charge id.
				$order->add_order_note( sprintf( __( 'Stripe %1$s payment captured. Payment is now complete (Charge Id: %2$s)', 'peachpay-for-woocommerce' ), $order->get_payment_method_title(), $charge_id ) );
			} else {
				// translators: %1$s Payment method title,  %2$s charge id.
				$order->add_order_note( sprintf( __( 'Stripe %1$s payment complete (Charge Id: %2$s)', 'peachpay-for-woocommerce' ), $order->get_payment_method_title(), $charge_id ) );
			}

			if ( $save_payment_method ) {
				$user_id   = PeachPay_Stripe_Order_Data::is_save_to_account( $order, 'user_id' );
				$save_card = new PeachPay_Stripe_Card_Gateway();
				$save_card->create_payment_token( $order, $user_id );
				//unset save to account flag
				PeachPay_Stripe_Order_Data::save_to_account( $order, array() );
			}
		} elseif ( 'requires_capture' === $payment_status ) {
			if ( $save_payment_method ) {
				$user_id   = PeachPay_Stripe_Order_Data::is_save_to_account( $order, 'user_id' );
				$save_card = new PeachPay_Stripe_Card_Gateway();
				$save_card->create_payment_token( $order, $user_id );
				//unset save to account flag
				PeachPay_Stripe_Order_Data::save_to_account( $order, array() );
			}
			// translators: %1$s Payment method title, %2$s amount ,%3$s charge id.
			$order->set_status( 'on-hold', sprintf( __( 'Stripe %1$s payment authorized for %2$s (Charge Id: %3$s)', 'peachpay-for-woocommerce' ), $order->get_payment_method_title(), wc_price( $order->get_total(), array( 'currency' => $order->get_currency() ) ), $charge_id ) );
		} elseif ( 'requires_payment_method' === $payment_status ) {
			// translators: %1$s Payment method title, %2$s reason
			$order->set_status( 'failed', sprintf( __( 'Stripe %1$s payment failed. Reason: %2$s', 'peachpay-for-woocommerce' ), $order->get_payment_method_title(), $reason ) );
		} elseif ( 'processing' === $payment_status ) {
			// translators: %1$s Payment method title,  %2$s charge id.
			$order->set_status( 'on-hold', sprintf( __( 'Stripe %1$s payment is processing (Charge Id: %2$s)', 'peachpay-for-woocommerce' ), $order->get_payment_method_title(), $charge_id ) );
		} elseif ( 'canceled' === $payment_status ) {
			// translators: %1$s Payment method title
			$order->set_status( 'cancelled', sprintf( __( 'Stripe %1$s payment was canceled. %2$s', 'peachpay-for-woocommerce' ), $order->get_payment_method_title(), $reason ) );
		}

		$order->save();
	}

	/**
	 * Creates a stripe setup intent.
	 *
	 * @param string $session_id the session the setup intent belongs to.
	 * @param array  $setup_intent_params The parameters for the setup intent.
	 */
	public static function setup_payment( $session_id, $setup_intent_params ) {
		$mode    = PeachPay_Stripe_Integration::mode();
		$request = peachpay_json_remote_post(
			peachpay_api_url( $mode ) . 'api/v2/stripe/setup-intent',
			array(
				'data_format' => 'body',
				'headers'     => array(
                    // PHPCS:ignore
					'Content-Type'            => 'application/json; charset=utf-8',
					'PeachPay-Mode'           => $mode,
					'PeachPay-Merchant-Id'    => peachpay_plugin_merchant_id(),
					'PeachPay-Session-Id'     => $session_id,
					'PeachPay-Plugin-Version' => PEACHPAY_VERSION,
					'Idempotency-Key'         => wp_generate_uuid4(),
				),
				'body'        => wp_json_encode(
					array(
						'setup_intent_params' => $setup_intent_params,
					)
				),
			)
		);

		if ( isset( $request['error'] ) && is_array( $request['error'] ) ) {
			return array(
				'success' => false,
				'message' => sprintf(
					// translators: The failure reason
					__(
						'PeachPay API request failed:
%s

',
						'peachpay-for-woocommerce'
					),
					implode( "\n", $request['error'] )
				),
			);
		}

		$json = $request['result'];

		if ( $json['success'] ) {
			self::set_customer( get_current_user_id(), $json['data']['setup_intent_details']['customer'] );
			if ( WC()->session ) {
				WC()->session->set( 'peachpay_setup_intent_details', $json['data'] );
			}
		}

		return $json;
	}

	/**
	 * Gets the stripe customer for the logged in user or returns null if one does not exist.
	 *
	 * @param int                    $user_id The id of the logged in user.
	 * @param "live"|"test"|"detect" $peachpay_mode The mode to get the customer id.
	 */
	public static function get_customer( $user_id, $peachpay_mode = 'detect' ) {

		if ( 0 === $user_id ) {
			return null;
		}

		$peachpay_mode = PeachPay_Stripe_Integration::mode( $peachpay_mode );
		$customer_id   = get_user_meta( $user_id, "_peachpay_stripe_{$peachpay_mode}_customer", true );
		if ( ! $customer_id || ! is_array( $customer_id ) ) {
			delete_user_meta( $user_id, "_peachpay_stripe_{$peachpay_mode}_customer" );
			return null;
		}

		if ( PeachPay_Stripe_Integration::connect_id() !== $customer_id['connect_id'] ) {
			delete_user_meta( $user_id, "_peachpay_stripe_{$peachpay_mode}_customer" );
			return null;
		}

		return $customer_id['customer_id'];
	}

	/**
	 * Adds a stripe customer to a woocommerce user.
	 *
	 * @param int                    $user_id The id of the logged in user.
	 * @param string                 $customer_id The stripe customer id.
	 * @param "live"|"test"|"detect" $peachpay_mode The mode to get the customer id.
	 */
	public static function set_customer( $user_id, $customer_id, $peachpay_mode = 'detect' ) {

		if ( 0 === $user_id ) {
			return false;
		}

		if ( ! empty( self::get_customer( $user_id ) ) ) {
			return false;
		}

		$peachpay_mode = PeachPay_Stripe_Integration::mode( $peachpay_mode );
		return add_user_meta(
			$user_id,
			"_peachpay_stripe_{$peachpay_mode}_customer",
			array(
				'customer_id' => $customer_id,
				'connect_id'  => PeachPay_Stripe_Integration::connect_id(),
			),
			true
		);
	}

	/**
	 * Handles disputed order by displaying additional order notes and changing order status.
	 *
	 * @param WC_Order $order The WC stripe order to calculate its order status.
	 * @param array    $dispute_details The stripe order dispute details.
	 */
	private static function handle_dispute_created( $order, $dispute_details ) {
		$charge_id      = PeachPay_Stripe_Order_Data::get_charge( $order, 'id' );
		$dispute_status = strtoupper( $dispute_details['status'] );
		// translators: %1$s charge id,  %2$s dispute status.
		$order->add_order_note( sprintf( __( 'A dispute has been created for charge Id: %1$s. Dispute status: %2$s.', 'peachpay-for-woocommerce' ), $charge_id, $dispute_status ) );

		PeachPay_Stripe_Order_Data::set_prev_status( $order, array( 'prev_status' => $order->get_status() ) );

		if ( PeachPay_Stripe_Advanced::get_setting( 'dispute_order_status' ) === 'on-hold' ) {
			$order->set_status( 'on-hold' );
		} elseif ( PeachPay_Stripe_Advanced::get_setting( 'dispute_order_status' ) === 'cancelled' ) {
			$order->set_status( 'cancelled' );
		} elseif ( PeachPay_Stripe_Advanced::get_setting( 'dispute_order_status' ) === 'refunded' ) {
			$dispute_message = isset( $payment_details['status_message'] ) ? $payment_details['status_message'] : 'Order was disputed.';
			$refund          = new WC_Order_Refund();
			$refund->set_amount( $order->get_total() - $order->get_total_refunded() );
			$refund->set_parent_id( $order->get_id() );
			$refund->set_reason( $dispute_message );
			$refund->set_refunded_by( get_current_user_id() );
			$refund->save();

			// translators: %1$s the payment method title, %3$s Refund amount, %4$s Refund Id.
			$order->add_order_note( sprintf( __( 'Stripe %1$s payment refunded %2$s (Transaction Id: %3$s)', 'peachpay-for-woocommerce' ), $order->get_payment_method_title(), wc_price( $order->get_total(), array( 'currency' => $order->get_currency() ) ), $charge_id ) );
			$order->set_status( 'refunded' );
		}

		$order->save();
	}

	/**
	 * Handle closed dispute orders.
	 *
	 * @param WC_Order $order The WC stripe order to calculate its order status.
	 * @param array    $dispute_details The stripe order dispute details.
	 */
	private static function handle_dispute_closed( $order, $dispute_details ) {
		$dispute_id = $dispute_details['dispute_id'];
		$result     = $dispute_details['status'];

		// translators: %1$s charge id,  %2$s dispute status.
		$order->add_order_note( sprintf( __( 'Dispute %1$s has been closed. Result: %2$s.', 'peachpay-for-woocommerce' ), $dispute_id, $result ) );

		if ( 'lost' === $result ) {
			$order->set_status( 'failed' );
		} elseif ( 'won' === $result ) {
			if ( $order->get_status() === 'refunded' ) {
				$refunds = $order->get_refunds();
				foreach ( $refunds as $refund ) {
					$refund->delete();
				}
			}
			$status = PeachPay_Stripe_Order_Data::get_prev_status( $order, 'prev_status' );
			$order->set_status( $status );
		}

		$order->save();
	}
}
