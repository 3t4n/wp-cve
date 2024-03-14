<?php
/**
 * ConvertPlus Gateway class
 *
 * @package    StorePress\TwoCheckoutPaymentGateway
 * @since      1.0.0
 */

namespace StorePress\TwoCheckoutPaymentGateway\ConvertPlus;

defined( 'ABSPATH' ) || die( 'Keep Silent' );

use StorePress\TwoCheckoutPaymentGateway\Payment_Gateway;
use WC_Order;

/**
 * StorePress 2Checkout ConvertPlusGateway class.
 *
 * Extended by individual payment gateway style to handle payments.
 *
 * @class       ConvertPlus_Gateway
 * @extends     Payment_Gateway
 */
class ConvertPlus_Gateway extends Payment_Gateway {

	/**
	 * Process after gateway redirect.
	 *
	 * @return void
	 */
	public function process_gateway_redirect() {

		$data = stripslashes_deep( $_GET ); // phpcs:ignore.

		do_action( 'woo_2checkout_process_gateway_redirect', $data, $this );

		status_header( 200 );
		nocache_headers();

		$this->log( "Gateway Redirect Response \n" . print_r( $data, true ) ); // phpcs:ignore.

		if ( empty( $data['order-ext-ref'] ) ) {
			wp_die( '2Checkout Gateway Return no "order-ext-ref"', '2Checkout Response', array( 'response' => 500 ) );
		}

		if ( ! empty( $data['refno'] ) ) {

			$order_id       = absint( sanitize_text_field( $data['order-ext-ref'] ) );
			$order          = wc_get_order( $order_id );
			$transaction_id = sanitize_text_field( $data['refno'] );

			if ( ! $order ) {
				wp_die( sprintf( 'Order# %d is not available.', absint( $order_id ) ), '2Checkout Request', array( 'response' => 500 ) );
			}

			$this->log( "Gateway Return Signature: \n" . print_r( // phpcs:ignore.
					array(
						'wc generated' => $this->get_api()->generate_return_signature( $data, $this->buy_link_secret_word ),
						'2co returned' => $data['signature'],
					), true ) );

			if ( ! $this->get_api()->is_valid_return_signature( $data, $this->buy_link_secret_word ) ) {
				$order->update_status( 'failed', 'Order failed due to 2checkout signature mismatch.' );
				wc_add_notice( 'Order failed due to 2Checkout return signature mismatch.', 'error' );
				do_action( 'woo_2checkout_payment_signature_mismatch', $data, $this );
				wp_safe_redirect( wc_get_checkout_url() );
				exit;
			}

			// Order Received.
			$order->set_transaction_id( $transaction_id );
			$order->update_status( 'on-hold', 'Payment received and waiting for 2Checkout IPN response.' );
			WC()->cart->empty_cart();
			do_action( 'woo_2checkout_payment_processing', $order, $data, $this );
			wp_safe_redirect( $this->get_return_url( $order ) );
			exit;

		} else {
			wp_die( '2Checkout Gateway Return no refno', '2Checkout Response', array( 'response' => 500 ) );
		}
	}

	/**
	 * Process after payment received.
	 *
	 * @return void
	 */
	public function process_gateway_ipn_response() {

		if ( ! $_POST ) { // phpcs:ignore
			return;
		}

		// Don't alter any value otherwise 2checkout hash won't be matched.
		$data = stripslashes_deep( $_POST ); // phpcs:ignore

		do_action( 'woo_2checkout_gateway_process_ipn_response', $data, $this );

		status_header( 200 );
		nocache_headers();

		$transaction_id       = sanitize_text_field( $data['REFNO'] );
		$base_string_for_hash = $this->get_api()->generate_base_string_for_hash( $data );
		$ipn_receipt          = $this->get_api()->ipn_receipt_response( $data );

		// $this->log( "IPN Base String For Hash: \n" . print_r( $base_string_for_hash, true ) ); // phpcs:ignore
		$this->log( "IPN Response: \n" . print_r( $data, true ), 'info' ); // phpcs:ignore
		$this->log( "IPN receipt_response: \n" . print_r( $ipn_receipt, true ), 'info' ); // phpcs:ignore

		if ( $ipn_receipt ) {

			$order_id = absint( sanitize_text_field( $data['REFNOEXT'] ) );

			$order = wc_get_order( $order_id );

			if ( ! $order ) {
				echo wp_kses( $ipn_receipt, $this->get_api()->kses_receipt_response_allowed_html() );
				do_action( 'woo_2checkout_gateway_process_ipn_response_invalid_order', $data, $this );
				$this->log( sprintf( 'Order# %d is not available.', $order_id ), 'error' );
				exit();
			}

			// Test Payment.
			if ( isset( $data['TEST_ORDER'] ) && $data['TEST_ORDER'] ) {
				$order->add_order_note( 'IPN Response Received as Test Order' );
			}

			if ( isset( $data['ORDERSTATUS'] ) ) {
				switch ( $data['ORDERSTATUS'] ) {

					// Payment Authorized.
					case 'PAYMENT_AUTHORIZED':
						if ( ! $order->has_status( array( 'processing', 'completed' ) ) ) {
							$order->update_status( 'on-hold', 'Order PAYMENT AUTHORIZED by 2Checkout IPN.' );
							do_action( 'woo_2checkout_ipn_response_order_processing', $data['ORDERSTATUS'], $order, $data, $this );
						}
						break;

					// Completed Order.
					case 'COMPLETE':
						if ( ! $order->has_status( array( 'processing', 'completed' ) ) ) {
							$order->payment_complete( $transaction_id );
							update_user_meta( $order->get_customer_id(), 'woo_2checkout_previous_order', $transaction_id );
							do_action( 'woo_2checkout_ipn_response_order_complete', $data['ORDERSTATUS'], $order, $data, $this );
						}
						break;

					// Cancel Order.
					case 'CANCELED':
						if ( ! $order->has_status( array( 'cancelled' ) ) ) {
							$order->update_status( 'cancelled', 'Order CANCELED by 2Checkout IPN' );
							do_action( 'woocommerce_cancelled_order', $order->get_id() );
							do_action( 'woo_2checkout_ipn_response_order_canceled', $data['ORDERSTATUS'], $order, $data, $this );
						}
						break;

					// REVERSED Order.
					case 'REVERSED':
						if ( ! $order->has_status( array( 'processing', 'completed' ) ) ) {
							$order->update_status( 'failed', '2Checkout reverses order transactions that never reach the Complete/Finished stage. Shoppers never complete transactions for such purchases.' );
							do_action( 'woo_2checkout_ipn_response_order_refund', $data['ORDERSTATUS'], $order, $data, $this );
						}
						break;
					// REFUND Order.
					case 'REFUND':
						if ( ! $order->has_status( array( 'refunded' ) ) ) {
							$order->update_status( 'refunded', 'Order REFUND by 2Checkout IPN' );
							do_action( 'woo_2checkout_ipn_response_order_refund', $data['ORDERSTATUS'], $order, $data, $this );
						}
						break;

					default:
						$this->log( sprintf( "IPN Response: ORDERSTATUS = %s \n", $data['ORDERSTATUS'] ) . print_r( $data, true ), 'info' ); // phpcs:ignore
						break;
				}
			}

			echo wp_kses( $ipn_receipt, $this->get_api()->kses_receipt_response_allowed_html() );
		} else {
			$this->log( 'No IPN Receipt Response Code Generated.', 'error' );
			echo 'No IPN Receipt Generated.';
		}
		exit();
	}

	/**
	 * Get download url link.
	 *
	 * @param WC_Order $order Order.
	 *
	 * @return mixed|null
	 */
	public function get_checkout_order_received_url( WC_Order $order ) {
		$order_received_url = wc_get_endpoint_url( 'downloads', $order->get_id(), wc_get_page_permalink( 'my-account' ) );

		if ( 'yes' === get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
			$order_received_url = str_replace( 'http:', 'https:', $order_received_url );
		}

		$order_received_url = add_query_arg( 'key', $order->get_order_key(), $order_received_url );

		return apply_filters( 'woocommerce_get_checkout_order_received_url', $order_received_url, $order );
	}

	/**
	 * Get Generated ConvertPlus URL.
	 *
	 * @param array $parameters url params.
	 *
	 * @return string
	 */
	public function get_payment_url( array $parameters ): string {
		return $this->get_api()->convertplus_buy_link( $parameters, $this->merchant_code, $this->buy_link_secret_word );
	}

	/**
	 * Create 2Checkout ConvertPlus Params
	 *
	 * @param WC_Order $order Order.
	 *
	 * @return mixed|null
	 */
	public function payment_args( WC_Order $order ) {

		$ship_to_different_address = ! empty( $_POST['ship_to_different_address'] ) && ! wc_ship_to_billing_address_only(); // phpcs:ignore.

		$args = array();

		$args['dynamic'] = true;

		if ( $this->demo ) {
			$args['test'] = true;
		}

		// Set Billing Information.
		$args['email'] = sanitize_email( $order->get_billing_email() );
		$args['name']  = esc_html( $order->get_formatted_billing_full_name() );

		if ( $order->get_billing_phone() ) {
			$args['phone'] = esc_html( $order->get_billing_phone() );
		}

		// Send customer country code. Two-digits code. Ex: "UK".
		if ( $order->get_billing_country() ) {
			$args['country'] = esc_html( $order->get_billing_country() );
		}

		// Send customer state code. Two-digits code. Ex: "CA".
		if ( $order->get_billing_state() ) {
			$bill_country  = $order->get_billing_country();
			$bill_state    = $order->get_billing_state();
			$args['state'] = esc_html( WC()->countries->get_states( $bill_country )[ $bill_state ] );
		}

		// Send customer city.
		if ( $order->get_billing_city() ) {
			$args['city'] = esc_html( $order->get_billing_city() );
		}

		if ( $order->has_billing_address() ) {
			$args['address']  = esc_html( $order->get_billing_address_1() );
			$args['address2'] = esc_html( $order->get_billing_address_2() );
		}

		if ( $order->get_billing_postcode() ) {
			$args['zip'] = esc_html( $order->get_billing_postcode() );
		}

		if ( $order->get_billing_company() ) {
			$args['company-name'] = esc_html( $order->get_billing_company() );
		}

		// Send Company name.
		// When sending the Company name, the Company checkout flow will be triggered.
		// Otherwise, the default checkout flow for ConvertPlus is the individual flow.
		if ( $order->get_billing_company() ) {
			$args['company-name'] = esc_html( $order->get_billing_company() );
		}

		// Send company Fiscal code.
		// When sending the Fiscal code, the Company checkout flow will be triggered only if also another company flow relevant parameter will be also sent (Company name and/or Tax office).
		// Otherwise, the default checkout flow for ConvertPlus is the individual flow.
		$fiscal_code = apply_filters( 'woo_2checkout_billing_fiscal_code', '', $order, $this );

		if ( ! empty( $fiscal_code ) ) {
			$args['fiscal-code'] = esc_html( $fiscal_code );
		}

		// Send company Tax office.
		// When sending the Tax office, the Company checkout flow will be triggered.
		// Otherwise, the default checkout flow for ConvertPlus is the individual flow.
		$tax_office = apply_filters( 'woo_2checkout_billing_tax_office', '', $order, $this );

		if ( ! empty( $tax_office ) ) {
			$args['tax-office'] = esc_html( $tax_office );
		}

		// Delivery / Shipping Information.
		// $order->needs_shipping_address()
		// WC()->cart->needs_shipping_address()
		// .
		if ( wc_shipping_enabled() && $order->needs_shipping_address() ) {

			$args['ship-name'] = $ship_to_different_address ? esc_html( $order->get_formatted_shipping_full_name() ) : esc_html( $order->get_formatted_billing_full_name() );

			// Send customer delivery country code. Two-digits code. Ex: "UK".
			if ( $order->get_shipping_country() || $order->get_billing_country() ) {
				$args['ship-country'] = $ship_to_different_address ? esc_html( $order->get_shipping_country() ) : esc_html( $order->get_billing_country() );
			}

			// Send customer delivery state code. Two-digits code. Ex: "CA".
			if ( $order->get_shipping_state() || $order->get_billing_state() ) {
				$ship_country       = $ship_to_different_address ? $order->get_shipping_country() : $order->get_billing_country();
				$ship_state         = $ship_to_different_address ? $order->get_shipping_state() : $order->get_billing_state();
				$args['ship-state'] = esc_html( WC()->countries->get_states( $ship_country )[ $ship_state ] );
			}

			if ( $order->has_shipping_address() || $order->has_billing_address() ) {
				$args['ship-address']  = $ship_to_different_address ? esc_html( $order->get_shipping_address_1() ) : esc_html( $order->get_billing_address_1() );
				$args['ship-address2'] = $ship_to_different_address ? esc_html( $order->get_shipping_address_2() ) : esc_html( $order->get_billing_address_2() );
			}

			if ( $order->get_shipping_postcode() || $order->get_billing_postcode() ) {
				$args['ship-zip'] = $ship_to_different_address ? esc_html( $order->get_shipping_postcode() ) : esc_html( $order->get_billing_postcode() );
			}
		}

		// Product information.
		$product_info                 = array();
		$product_info['prod']         = array();
		$product_info['opt']          = array();
		$product_info['price']        = array();
		$product_info['qty']          = array();
		$product_info['tangible']     = array();
		$product_info['type']         = array();
		$product_info['item-ext-ref'] = array();

		// Products.
		if ( count( $order->get_items() ) > 0 ) {
			foreach ( $order->get_items() as $item ) {

				// $item = new WC_Order_Item_Product(); // WC_Order_Item

				$product = $item->get_product();

				if ( ! $product ) {
					continue;
				}

				$product_info['prod'][]  = $this->format_item_name( $item->get_name() );
				$product_info['price'][] = $this->format_item_price( $order->get_item_total( $item ) );
				$product_info['qty'][]   = $item->get_quantity(); // get_item_total

				if ( $product->is_downloadable() || $product->is_virtual() ) {
					$product_info['type'][] = 'digital';
				} else {
					$product_info['type'][] = 'physical';
				}

				$product_info['item-ext-ref'][] = $product->get_id();
			}
		}

		// Tax.
		if ( wc_tax_enabled() && 0 < $order->get_total_tax() ) {

			if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) {
				foreach ( $order->get_tax_totals() as $tax ) {
					$product_info['type'][]         = 'tax';
					$product_info['prod'][]         = esc_html( $tax->label );
					$product_info['price'][]        = $this->format_item_price( $tax->amount );
					$product_info['qty'][]          = 1;
					$product_info['item-ext-ref'][] = '';
				}
			} else {
				$product_info['type'][]         = 'tax';
				$product_info['prod'][]         = esc_html( WC()->countries->tax_or_vat() );
				$product_info['price'][]        = $this->format_item_price( $order->get_total_tax() );
				$product_info['qty'][]          = 1;
				$product_info['item-ext-ref'][] = '';
			}
		}

		// Support Custom Fees. Add custom fee from "woocommerce_cart_calculate_fees" hook.
		if ( 0 < count( $order->get_fees() ) ) {
			foreach ( $order->get_fees() as $item ) {

				// new WC_Order_Item_Fee()
				$product_info['type'][]         = 'tax';
				$product_info['prod'][]         = $this->format_item_name( $item->get_name() );
				$product_info['price'][]        = $this->format_item_price( $item->get_total() );
				$product_info['qty'][]          = 1;
				$product_info['item-ext-ref'][] = '';
			}
		}

		// Shipping.
		if ( wc_shipping_enabled() && 0 < $order->get_shipping_total() ) {

			/* translators: Shipping Method Name */
			$shipping_name = $this->format_item_name( sprintf( esc_html__( 'Shipping via %s', 'woo-2checkout' ), $order->get_shipping_method() ) );

			$product_info['type'][]         = 'shipping';
			$product_info['prod'][]         = $shipping_name;
			$product_info['price'][]        = $this->format_item_price( $order->get_shipping_total() );
			$product_info['qty'][]          = 1;
			$product_info['item-ext-ref'][] = '';
		}

		$args['return-url']  = esc_url( $this->get_gateway_return_url() );
		$args['return-type'] = 'redirect'; // use redirect or link.
		$args['currency']    = get_woocommerce_currency();
		$args['language']    = $this->shop_language();

		if ( absint( $order->get_customer_id() ) > 0 ) {
			$args['customer-ext-ref'] = $order->get_customer_id();
		}

		$args['order-ext-ref'] = $order->get_id();
		$args['tpl']           = 'default'; // default, one-column .

		$args['prod']         = implode( ';', $product_info['prod'] );
		$args['price']        = implode( ';', $product_info['price'] );
		$args['qty']          = implode( ';', $product_info['qty'] );
		$args['type']         = implode( ';', $product_info['type'] );
		$args['item-ext-ref'] = implode( ';', $product_info['item-ext-ref'] );

		return apply_filters( 'woo_2checkout_convert_plus_payment_args', $args, $product_info, $order, $this );
	}

	/**
	 * Process Payment.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return array
	 */
	public function process_payment( $order_id ): array {

		$order      = wc_get_order( $order_id );
		$parameters = $this->payment_args( $order );

		$this->log( "PAYMENT ARGS:\n" . print_r( $parameters, true ) ); // phpcs:ignore.

		$payment_url = $this->get_payment_url( $parameters );

		if ( ! empty( $payment_url ) ) {
			return array(
				'result'   => 'success',
				'redirect' => $payment_url,
			);
		} else {
			return array(
				'messages' => esc_html__( 'Failed to Generate 2Checkout ConvertPlus URL.', 'woo-2checkout' ),
				'result'   => 'failure',
			);
		}
	}
}
