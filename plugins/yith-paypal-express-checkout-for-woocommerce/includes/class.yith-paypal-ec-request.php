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
} // Exit if accessed directly.


if ( ! class_exists( 'YITH_PayPal_EC_Request' ) ) {

	/**
	 * Class YITH_PayPal_EC_Request
	 */
	class YITH_PayPal_EC_Request {

		/**
		 * Single instance of the class
		 *
		 * @var YITH_PayPal_EC_Request
		 */
		protected static $instance;

		/**
		 * Request fields
		 *
		 * @var array
		 */
		protected $request_fields = array();

		/**
		 * List of default params to send for the request
		 *
		 * @var array|mixed|void
		 */
		protected $default_args = array();

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_PayPal_EC_Request
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
		 * @param string $api_username API username.
		 * @param string $api_password API password.
		 * @param string $api_signature API signature.
		 * @param string $api_version API version.
		 * @param string $api_subject API subject.
		 * @since  1.0.0
		 */
		public function __construct( $api_username, $api_password, $api_signature, $api_version, $api_subject ) {

			$this->add_fields(
				array(
					'USER'      => $api_username,
					'PWD'       => $api_password,
					'SIGNATURE' => $api_signature,
					'VERSION'   => $api_version,
				)
			);

			if ( ! empty( $api_subject ) ) {
				$this->add_field( 'SUBJECT', $api_subject );
			}

			$this->default_args = $this->set_default_args();

		}

		/**
		 * Set default args for the request
		 *
		 * @since  1.2.0
		 */
		private function set_default_args() {
			$helper = yith_paypal_ec()->ec;
			// translators: placeholder name of the site.
			$description = sprintf( __( 'Orders with %s', 'yith-paypal-express-checkout-for-woocommerce' ), get_bloginfo( 'name' ) );

			/**
			 * List of default arguments used in the request
			 *
			 * @var array
			 */
			$defaults = array(
				'billing_type'             => 'MerchantInitiatedBillingSingleAgreement',
				'billing_description'      => $helper::format_item_name( $description ),
				'no_shipping'              => 0,
				'page_style'               => '',
				'brand_name'               => $helper::format_item_name( $helper->brand_name ),
				'landing_page'             => ( 'login' === $helper->checkout_style ) ? 'Login' : 'Billing',
				'logo'                     => esc_url_raw( $helper->logo ),
				'header'                   => esc_url_raw( $helper->header ),
				'payment_action'           => $helper->payment_action,
				'billing_agreement_custom' => '',
				'return_url'               => '',
				'cancel_url'               => '',
				'currency'                 => get_woocommerce_currency(),
				'addoverride'              => 1,
				'solution_type'            => 'Sole',
				'payment_type'             => 'yes' === $helper->instant_payments ? 'InstantOnly' : 'Any',
				'return_fraud_filters'     => 1,
			);

			return apply_filters( 'yith_paypal_ec_default_options_request', $defaults );
		}

		/**
		 * Starts an Express Checkout transaction
		 *
		 * @param array $args Arguments.
		 */
		public function set_express_checkout( $args ) {

			$this->add_field( 'METHOD', 'SetExpressCheckout' );
			$args = wp_parse_args( $args, $this->default_args );

			$new_params = array(
				'RETURNURL'          => $args['return_url'],
				'CANCELURL'          => $args['cancel_url'],
				'NOTIFYURL'          => WC()->api_request_url( 'yith_paypal_ec' ),
				'PAGESTYLE'          => $args['page_style'],
				'BRANDNAME'          => $args['brand_name'],
				'HDRIMG'             => $args['header'],
				'LOGOIMG'            => $args['logo'],
				'LANDINGPAGE'        => $args['landing_page'],
				'NOSHIPPING'         => $args['no_shipping'],
				'SOLUTIONTYPE'       => $args['solution_type'],
				'SHIPTOSTREET'       => WC()->customer->get_shipping_address(),
				'SHIPTOSTREET2'      => WC()->customer->get_shipping_address_2(),
				'SHIPTOCITY'         => WC()->customer->get_shipping_city(),
				'SHIPTOSTATE'        => WC()->customer->get_shipping_state(),
				'SHIPTOZIP'          => WC()->customer->get_shipping_postcode(),
				'SHIPTOCOUNTRYCODE'  => WC()->customer->get_shipping_country(),
				'REQCONFIRMSHIPPING' => 1,
			);

			if ( 'cart' !== $args['from'] ) {
				$new_params['ADDROVERRIDE'] = 1;
			}

			if ( isset( $args['custom'] ) ) {
				$new_params['CUSTOM'] = $args['custom'];
			}

			if ( isset( $args['get_billing_agreement'] ) && $args['get_billing_agreement'] ) {
				$new_params['L_BILLINGTYPE0']                 = 'MerchantInitiatedBillingSingleAgreement';
				$new_params['L_BILLINGAGREEMENTDESCRIPTION0'] = $args['billing_description'];
				$new_params['L_BILLINGAGREEMENTCUSTOM0']      = $args['billing_agreement_custom'];
			}

			if ( is_user_logged_in() ) {
				$customer_id              = get_current_user_id();
				$new_params['SHIPTONAME'] = get_user_meta( $customer_id, 'shipping_first_name', true ) . ' ' . get_user_meta( $customer_id, 'shipping_last_name', true );
			}

			$new_params = apply_filters( 'yith_paypal_ec_set_express_checkout_request_parameters', $new_params );
			$this->add_fields( $new_params );

			/**
			 * Order
			 *
			 * @var WC_Order $order
			 */
			if ( isset( $args['order'] ) && $args['order'] instanceof WC_Order ) {
				$order = $args['order'];
				// if there's an order this should be payed.
				if ( empty( $order->get_total() ) ) {

					$this->add_fields(
						array(
							'PAYMENTREQUEST_0_AMT'         => 0,
							// a zero amount is use so that no DoExpressCheckout action is required and instead CreateBillingAgreement is used to first create a billing agreement not attached to any order and then DoReferenceTransaction is used to charge both the initial order and renewal order amounts.
							'PAYMENTREQUEST_0_ITEMAMT'     => 0,
							'PAYMENTREQUEST_0_SHIPPINGAMT' => 0,
							'PAYMENTREQUEST_0_TAXAMT'      => 0,
							'PAYMENTREQUEST_0_CURRENCYCODE' => $args['currency'],
							'PAYMENTREQUEST_0_CUSTOM'      => $args['custom'],
							'PAYMENTREQUEST_0_PAYMENTACTION' => $args['payment_action'],
							'PAYMENTREQUEST_0_NOTIFYURL'   => WC()->api_request_url( 'yith_paypal_ec' ),
							'PAYMENTREQUEST_0_SHIPTONAME'  => $new_params['SHIPTONAME'],
							'PAYMENTREQUEST_0_SHIPTOSTREET' => $new_params['SHIPTOSTREET'],
							'PAYMENTREQUEST_0_SHIPTOSTREET2' => $new_params['SHIPTOSTREET2'],
							'PAYMENTREQUEST_0_SHIPTOCITY'  => $new_params['SHIPTOCITY'],
							'PAYMENTREQUEST_0_SHIPTOSTATE' => $new_params['SHIPTOSTATE'],
							'PAYMENTREQUEST_0_SHIPTOZIP'   => $new_params['SHIPTOZIP'],
							'PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE' => $new_params['SHIPTOCOUNTRYCODE'],
						)
					);

					if ( 'sale' === $args['payment_action'] && 'yes' === yith_paypal_ec()->ec->instant_payments ) {
						$this->add_field( 'PAYMENTREQUEST_0_ALLOWEDPAYMENTMETHOD', 'InstantPaymentOnly' );
					}
				} else {

					$this->add_payment_fields( $args['order'], $args );

				}
			} elseif ( 'cart' === $args['from'] ) {
				$this->add_payment_fields_from_cart( $args );
			}
		}

		/**
		 * Sets GetExpressCheckoutDetails Request Fields
		 *
		 * @param string $token Token.
		 *
		 * @return void
		 */
		public function get_express_checkout_details( $token ) {
			$this->add_field( 'METHOD', 'GetExpressCheckoutDetails' );
			$this->add_field( 'TOKEN', $token );
		}

		/**
		 * Sets DoExpressCheckoutPayment Request Fields
		 *
		 * @param string   $token Token.
		 * @param WC_Order $order Order.
		 * @param array    $args Arguments.
		 *
		 * @return void
		 */
		public function do_express_checkout_payment( $token, $order, $args = array() ) {

			$this->add_field( 'METHOD', 'DoExpressCheckoutPayment' );

			$this->add_fields(
				array(
					'TOKEN'            => $token,
					'PAYERID'          => $args['payer_id'],
					'BUTTONSOURCE'     => 'YITH_PAYPAL',
					'RETURNFMFDETAILS' => 1,
					'CUSTOM'           => $args['custom'],
				)
			);

			$this->add_payment_fields( $order, $args );
		}

		/**
		 * Do capture
		 *
		 * @param array $args Arguments.
		 */
		public function do_capture( $args ) {

			$this->add_field( 'METHOD', 'DoCapture' );

			$this->add_fields(
				array(
					'AUTHORIZATIONID' => $args['transaction_id'],
					'AMT'             => $args['amount'],
					'CURRENCYCODE'    => $args['currency'],
					'COMPLETETYPE'    => 'Complete',
				)
			);

		}


		/**
		 * Sets CreateBillingAgreement Request Fields
		 *
		 * @param string $token Token.
		 */
		public function create_billing_agreement( $token ) {
			$this->add_field( 'METHOD', 'CreateBillingAgreement' );
			$this->add_field( 'TOKEN', $token );
		}


		/**
		 * Gets Details about a transaction
		 *
		 * @param string $transaction_id Transaction id.
		 */
		public function get_transaction_details( $transaction_id ) {
			$this->add_field( 'TRANSACTIONID', $transaction_id );
			$this->add_field( 'METHOD', 'GetTransactionDetails' );
		}

		/**
		 * Gets Details about a transaction
		 *
		 * @param string $transaction_id Transaction id.
		 */
		public function do_void( $transaction_id ) {
			$this->add_field( 'AUTHORIZATIONID', $transaction_id );
			$this->add_field( 'METHOD', 'DoVoid' );
		}


		/**
		 * Refund Transaction Request.
		 *
		 * @param array $args Arguments.
		 */
		public function refund_transaction( $args ) {
			$this->add_fields( $args );
			$this->add_field( 'METHOD', 'RefundTransaction' );
		}

		/**
		 * Sets DoReferenceTransaction Request Fields
		 *
		 * @param string   $billing_agreement Billing Agreements.
		 * @param WC_Order $order Order.
		 * @param array    $args Arguments.
		 *
		 * @return void
		 */
		public function do_reference_transaction( $billing_agreement, $order, $args = array() ) {

			$this->add_field( 'METHOD', 'DoReferenceTransaction' );
			$args = wp_parse_args( $args, $this->default_args );

			$this->add_fields(
				array(
					'REFERENCEID'      => $billing_agreement,
					'BUTTONSOURCE'     => 'YITH_EC_PAYPAL',
					'RETURNFMFDETAILS' => $args['return_fraud_filters'],
					'PAYMENTACTION'    => $args['payment_action'],
					'PAYMENTTYPE'      => $args['payment_type'],
					'NOTIFYURL'        => $args['notify_url'],
				)
			);

			$this->add_payment_fields( $order, $args, true );

		}

		/**
		 * Add payments fields from cart
		 *
		 * @param array $args Arguments.
		 * @param bool  $old_version If comes from the old version.
		 */
		protected function add_payment_fields_from_cart( $args, $old_version = false ) {
			$helper                    = yith_paypal_ec()->ec;
			$order_subtotal            = 0;
			$order_total_amount        = 0;
			$order_total_amount_to_pay = WC()->cart->get_total( 'edit' );
			$payment_item_details      = array();
			$discounts                 = 0;

			foreach ( WC()->cart->cart_contents as $cart_item_key => $values ) {
				/**
				 * Current product
				 *
				 * @var WC_Product $_product
				 */
				$_product = $values['data'];
				$amount   = $helper::round( $values['line_subtotal'] / $values['quantity'] );

				$payment_item_details[] = array(
					'NAME'    => $helper::format_item_name( $_product->get_name() ),
					'DESC'    => $helper::format_item_name( $_product->get_short_description() ),
					'AMT'     => $amount,
					'QTY'     => $values['quantity'],
					'ITEMURL' => $_product->get_permalink(),
				);

				$order_subtotal += $amount * $values['quantity'];
			}

			WC()->cart->calculate_totals();
			// Adds fees inside the request.
			foreach ( WC()->cart->get_fees() as $fee ) {
				$payment_item_details[] = array(
					'NAME' => $helper::format_item_name( $fee->name ),
					'AMT'  => $helper::round( $fee->total ),
					'QTY'  => 1,
				);
				$order_subtotal        += $fee->total;
			}

			// Adds discounts inside the request.
			if ( WC()->cart->get_discount_total() > 0 ) {
				$payment_item_details[] = array(
					'NAME' => $helper::format_item_name( apply_filters( 'yith_paypal_ec_total_discount_request_label', __( 'Total Discount', 'yith-paypal-express-checkout-for-woocommerce' ) ) ),
					'AMT'  => -$helper::round( WC()->cart->get_discount_total() ),
					'QTY'  => 1,
				);

				$order_subtotal -= WC()->cart->get_discount_total();
			}

			if ( defined( 'YITH_YWGC_PREMIUM' ) ) {
				if ( isset( WC()->cart->applied_gift_cards_amounts ) ) {
					foreach ( WC()->cart->applied_gift_cards_amounts as $code => $amount ) {
						$gift = YITH_YWGC()->get_gift_card_by_code( $code );

						if ( $gift->exists() ) {
							$amount                     = apply_filters( 'yith_ywgc_gift_card_amount_before_deduct', $amount );
							$payment_item_details[]     = array(
								'NAME' => $helper::format_item_name( __( 'Gift Card: ', 'yith-paypal-express-checkout-for-woocommerce' ) . $code ),
								'AMT'  => -$helper::round( $amount ),
								'QTY'  => 1,
							);
							$order_subtotal            -= $amount;
							$order_total_amount_to_pay -= $amount;
						}
					}
				}
			}

			$count = 0;
			foreach ( $payment_item_details as $item ) {
				foreach ( $item as $key => $value ) {
					$this->add_field( "L_PAYMENTREQUEST_0_{$key}{$count}", $value );
				}
				$order_total_amount += $helper::round( $item['AMT'] * $item['QTY'] );
				$count++;
			}

			$order_total_amount += WC()->cart->get_shipping_total() + WC()->cart->get_total_tax();

			// Adds order information to the request.
			$order_requests = array(
				'AMT'           => $order_total_amount_to_pay,
				'CURRENCYCODE'  => get_woocommerce_currency(),
				'ITEMAMT'       => $helper::round( $order_subtotal ),
				'SHIPPINGAMT'   => $helper::round( WC()->cart->get_shipping_total() ),
				'TAXAMT'        => $helper::round( WC()->cart->get_total_tax() ),
				'INVNUM'        => '',
				'PAYMENTACTION' => $args['payment_action'],
				'NOTIFYURL'     => WC()->api_request_url( 'yith_paypal_ec' ),
				'CUSTOM'        => '',
			);

			$balance = floatval( $order_requests['ITEMAMT'] ) + floatval( $order_requests['SHIPPINGAMT'] ) + floatval( $order_requests['TAXAMT'] );
			if ( $order_requests['AMT'] != $balance && 0 != $order_requests['TAXAMT'] ) { //phpcs:ignore
				$order_requests['TAXAMT'] = $order_requests['AMT'] - $order_requests['ITEMAMT'] - $order_requests['SHIPPINGAMT'];
			} elseif ( $order_requests['AMT'] != $balance && 0 != $order_requests['SHIPPINGAMT'] ) { //phpcs:ignore
				$order_requests['SHIPPINGAMT'] = $order_requests['AMT'] - $order_requests['ITEMAMT'] - $order_requests['TAXAMT'];
			}

			if ( 'sale' === $args['payment_action'] && 'yes' === yith_paypal_ec()->ec->instant_payments ) {
				$order_requests['ALLOWEDPAYMENTMETHOD'] = 'InstantPaymentOnly';
			}

			if ( $old_version ) {
				foreach ( $order_requests as $key => $value ) {
					$this->add_field( $key, $value );
				}
			} else {
				foreach ( $order_requests as $key => $value ) {
					$this->add_field( "PAYMENTREQUEST_0_{$key}", $value );
				}
			}

		}

		/**
		 * Adds fields relative to the payment in the request
		 *
		 * @param WC_Order $order Order.
		 * @param array    $args Arguments.
		 * @param bool     $old_version If comes from an old version or not.
		 */
		protected function add_payment_fields( $order, $args, $old_version = false ) {
			$helper               = yith_paypal_ec()->ec;
			$order_subtotal       = 0;
			$payment_item_details = array();
			$order_id             = yit_get_prop( $order, 'id' );
			$order_total_shipping = $helper::round( $order->get_shipping_total() );
			$order_total_tax      = $helper::round( $order->get_total_tax() );
			$order_total_amount   = $order_total_shipping + $order_total_tax;
			$total_amount_to_pay  = $helper::round( $order->get_total() );
			$currency             = yit_get_prop( $order, 'currency' );

			// Adds products items.
			foreach ( $order->get_items() as $order_item ) {
				$_product = new WC_Product( $order_item['product_id'] );

				$payment_item_details[] = array(
					'NAME'    => $helper::format_item_name( $_product->get_title() ),
					'DESC'    => $helper::get_order_item_description( $order_item ),
					'AMT'     => $helper::round( $order->get_item_subtotal( $order_item ) ),
					'QTY'     => ( ! empty( $order_item['qty'] ) ) ? absint( $order_item['qty'] ) : 1,
					'ITEMURL' => $_product->get_permalink(),
				);

				$order_subtotal += $order->get_item_subtotal( $order_item ) * $order_item['qty'];
			}

			// Adds fees inside the request.
			foreach ( $order->get_fees() as $fee ) {
				$payment_item_details[] = array(
					'NAME' => $helper::format_item_name( $fee['name'] ),
					'AMT'  => $helper::round( $fee['line_total'] ),
					'QTY'  => 1,
				);

				$order_subtotal += $fee['line_total'];
			}

			// Adds discounts inside the request.
			if ( $order->get_total_discount() > 0 ) {
				$payment_item_details[] = array(
					'NAME' => $helper::format_item_name( apply_filters( 'yith_paypal_ec_total_discount_request_label', __( 'Total Discount', 'yith-paypal-express-checkout-for-woocommerce' ) ) ),
					'AMT'  => -$helper::round( $order->get_total_discount() ),
					'QTY'  => 1,
				);

				$order_subtotal -= $order->get_total_discount();
			}

			if ( defined( 'YITH_YWGC_PREMIUM' ) ) {
				$order_gift_cards = yit_get_prop( $order, '_ywgc_applied_gift_cards', true );

				if ( $order_gift_cards ) {
					foreach ( $order_gift_cards as $code => $amount ) {
						$amount                 = apply_filters( 'ywgc_gift_card_amount_order_total_item', $amount, YITH_YWGC()->get_gift_card_by_code( $code ) );
						$payment_item_details[] = array(
							'NAME' => $helper::format_item_name( __( 'Gift Card: ', 'yith-paypal-express-checkout-for-woocommerce' ) . $code ),
							'AMT'  => -$helper::round( $amount ),
							'QTY'  => 1,
						);
						$order_subtotal        -= $amount;
					}
				}
			}

			$count = 0;
			foreach ( $payment_item_details as $item ) {
				foreach ( $item as $key => $value ) {
					$this->add_field( "L_PAYMENTREQUEST_0_{$key}{$count}", $value );
				}
				$order_total_amount += $helper::round( $item['AMT'] * $item['QTY'] );
				$count++;
			}

			// Adds order information to the request.
			$order_requests = array(
				'AMT'              => $total_amount_to_pay,
				'CURRENCYCODE'     => $currency,
				'ITEMAMT'          => $helper::round( $order_subtotal ),
				'SHIPPINGAMT'      => $helper::round( $order_total_shipping ),
				'TAXAMT'           => $order_total_tax,
				'INVNUM'           => $helper->invoice_prefix . ltrim( $order->get_order_number(), __( '#', 'yith-paypal-express-checkout-for-woocommerce' ) ),
				'PAYMENTACTION'    => $args['payment_action'],
				'NOTIFYURL'        => WC()->api_request_url( 'yith_paypal_ec' ),
				'PAYMENTREQUESTID' => $order_id,
				'CUSTOM'           => $args['custom'],
			);

			$balance = floatval( $order_requests['ITEMAMT'] ) + floatval( $order_requests['SHIPPINGAMT'] ) + floatval( $order_requests['TAXAMT'] ); //phpcs:ignore
			if ( $order_requests['AMT'] != $balance && 0 != $order_requests['TAXAMT'] ) { //phpcs:ignore
				$order_requests['TAXAMT'] = $order_requests['AMT'] - $order_requests['ITEMAMT'] - $order_requests['SHIPPINGAMT'];
			} elseif ( $order_requests['AMT'] != $balance && 0 != $order_requests['SHIPPINGAMT'] ) { //phpcs:ignore
				$order_requests['SHIPPINGAMT'] = $order_requests['AMT'] - $order_requests['ITEMAMT'] - $order_requests['TAXAMT'];
			}

			if ( 'sale' === $args['payment_action'] && 'yes' === yith_paypal_ec()->ec->instant_payments ) {
				$order_requests['ALLOWEDPAYMENTMETHOD'] = 'InstantPaymentOnly';
			}

			if ( $old_version ) {
				foreach ( $order_requests as $key => $value ) {
					$this->add_field( $key, $value );
				}
			} else {
				foreach ( $order_requests as $key => $value ) {
					$this->add_field( "PAYMENTREQUEST_0_{$key}", $value );
				}
			}

		}

		/**
		 * Add field to general $request_fields
		 *
		 * @param string $key Key.
		 * @param mixed  $value Value.
		 */
		private function add_field( $key, $value ) {
			$this->request_fields[ $key ] = $value;
		}

		/**
		 * Add a list of fields in the general $request_fields
		 *
		 * @param array $fields Fields.
		 */
		private function add_fields( $fields ) {
			foreach ( $fields as $key => $field ) {
				$this->add_field( $key, $field );
			}
		}

		/**
		 * Returns the string of the request, if $safe is true
		 * the sensitive fields are masked by *
		 *
		 * @param bool $safe Safe or not.
		 *
		 * @return mixed|string
		 */
		public function get_body( $safe = false ) {
			$body = http_build_query( $this->request_fields, '', '&' );

			if ( $safe ) {
				$hide_fields    = array( 'USER', 'PWD', 'SIGNATURE' );
				$request_fields = $this->request_fields;
				foreach ( $hide_fields as $field ) {
					if ( isset( $request_fields[ $field ] ) ) {
						$request_fields[ $field ] = str_repeat( '*', strlen( $request_fields[ $field ] ) );
					}
				}

				$body = print_r( $request_fields, true ); //phpcs:ignore
			}

			return $body;
		}

	}

}
