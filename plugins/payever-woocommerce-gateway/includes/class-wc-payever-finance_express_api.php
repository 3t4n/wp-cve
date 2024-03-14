<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Finance_Express_Api' ) ) {
	return;
}

use Payever\Sdk\Payments\Enum\Status;
use Payever\Sdk\Payments\Http\MessageEntity\AddressEntity;

/**
 * Class WC_Payever_Finance_Express_Api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class WC_Payever_Finance_Express_Api {
	use WC_Payever_WP_Wrapper_Trait;

	const CART_REFERENCE_PREFIX = 'cart_';
	const PROD_REFERENCE_PREFIX = 'prod_';

	/** @var WC_Payever_Order_Wrapper */
	private $order_wrapper;

	/**
	 * WC_Payever_Finance_Express_Api constructor.
	 */
	public function __construct() {
		add_action( 'woocommerce_api_payever_finance_express_success', array( &$this, 'finance_express_success' ) );
		add_action( 'woocommerce_api_payever_finance_express_cancel', array( &$this, 'finance_express_cancel' ) );
		add_action( 'woocommerce_api_payever_finance_express_failure', array( &$this, 'finance_express_failure' ) );
		add_action( 'woocommerce_api_payever_finance_express_notice', array( &$this, 'finance_express_notice' ) );
		add_action(
			'woocommerce_api_payever_finance_express_quotecallback',
			array( &$this, 'finance_express_quotecallback' )
		);
	}


	/**
	 * Quote callback for express widget
	 * SUCCESS URL: domain/wc-api/finance_express_quotecallback?amount=32.00&token=something
	 */
	public function finance_express_quotecallback() {

		try {
			$request       = wp_kses_post( sanitize_text_field( file_get_contents( 'php://input' ) ) ); // WPCS: input var ok, CSRF ok.
			$request_array = json_decode( $request );

			if ( ! isset( $request_array->shipping ) ) {
				throw new InvalidArgumentException( '[QuoteCallback]: Shipping was not set.' );
			}
			if ( ! isset( $_GET['amount'] ) || ! isset( $_GET['token'] ) ) {
				throw new InvalidArgumentException( '[QuoteCallback]: Amount or token was not set.' );
			}
			$amount       = floatval( wc_clean( $_GET['amount'] ) ); // WPCS: input var ok, CSRF ok.
			$amount_token = sanitize_text_field( wp_unslash( $_GET['token'] ) ); // WPCS: input var ok, CSRF ok.
			if ( md5( $amount . sha1( $amount ) ) !== $amount_token ) {
				throw new InvalidArgumentException(
					sprintf(
						'[QuoteCallback]: Invalid token provided for amount. Amount: %s, Token: %s',
						$amount,
						$amount_token
					)
				);
			}
			$shipping_address  = $request_array->shipping->shippingAddress;
			$available_methods = $this->getAvailableShippingMethods( $amount, $shipping_address );
			wp_send_json( array( 'shippingMethods' => $available_methods ) );
		} catch ( InvalidArgumentException $exception ) {
			WC_Payever_Api::get_instance()->get_logger()->critical( $exception );
			wp_send_json( array() );
		}
	}

	private function getAvailableShippingMethods( $amount, $shipping_address ) {
		$active_methods = array();
		$values         = array(
			'amount'  => $amount,
			'country' => $shipping_address->country,
			'city'    => $shipping_address->city,
			'state'   => $shipping_address->region,
			'zipcode' => $shipping_address->zipCode,
			'line1'   => $shipping_address->line1,
			'line2'   => $shipping_address->line2,
		);

		WC()->shipping->calculate_shipping( $this->get_shipping_packages( $values ) );
		$shipping_methods = WC()->shipping->packages;

		if ( $shipping_methods ) {
			$shipping_methods = array_shift( $shipping_methods );
			if ( isset( $shipping_methods['rates'] ) ) {
				foreach ( $shipping_methods['rates'] as $shipping_method ) {
					$active_methods[] = array(
						'price'     => number_format( $shipping_method->cost, 2, '.', '' ),
						'name'      => $shipping_method->label,
						'countries' => array( $values['country'] ),
						'reference' => $shipping_method->method_id,
					);
				}
			}
		}

		return $active_methods;
	}

	private function get_shipping_packages( $value ) {
		$packages = array(
			array(
				'contents'      => WC()->cart->cart_contents,
				'contents_cost' => $value['amount'],
				'destination'   => array(
					'country'   => $value['country'],
					'state'     => $value['region'],
					'postcode'  => $value['zipcode'],
					'city'      => $value['city'],
					'address'   => $value['line1'],
					'address_2' => $value['line2'],
				),
			),
		);

		return apply_filters( 'woocommerce_cart_shipping_packages', $packages );
	}


	/**
	 * Success and pending callback function
	 * SUCCESS URL: domain/wc-api/finance_express_success?reference=--PAYMENT-ID--
	 *
	 * @throws Exception
	 */
	public function finance_express_success() {
		$payment_id = ( isset( $_GET['reference'] ) ) ? sanitize_text_field( wp_unslash( $_GET['reference'] ) ) : ''; // WPCS: input var ok, CSRF ok.
		WC_Payever_Api::get_instance()->get_locker()->acquireLock( $payment_id, 30 );
		$payment = WC_Payever_Api::get_instance()
								->get_payments_api_client()
								->retrievePaymentRequest( $payment_id )
								->getResponseEntity()
								->getResult();

		try {
			$order = $this->process_order( $payment );
			WC_Payever_Api::get_instance()->get_locker()->releaseLock( $payment_id );

			wp_redirect( $order->get_checkout_order_received_url() );
		} catch ( Exception $exception ) {
			$url = $this->get_rejecting_url( $payment->getReference() );
			$this->get_wp_wrapper()->wc_add_notice(
				sprintf( __( 'Order hasn\'t been created: %s', 'payever-woocommerce-gateway' ), $exception->getMessage() ),
				'error'
			);
			WC_Payever_Api::getInstance()->get_locker()->releaseLock( $payment_id );

			wp_redirect( $url );
		}

		die();
	}

	/**
	 * Cancel callback function
	 * CANCEL URL: domain/wc-api/finance_express_cancel
	 *
	 * @throws Exception
	 */
	public function finance_express_cancel() {
		$this->get_wp_wrapper()->wc_add_notice(
			__( 'Payment has been cancelled.', 'payever-woocommerce-gateway' ),
			'error'
		);

		wp_redirect( get_permalink( woocommerce_get_page_id( 'shop' ) ) );
		die();
	}

	/**
	 * Failure callback function
	 * FAILURE URL: domain/wc-api/finance_express_failure?reference=--PAYMENT-ID--
	 *
	 * @throws Exception
	 */
	public function finance_express_failure() {
		$payment_id = ( isset( $_GET['reference'] ) ) ? sanitize_text_field( wp_unslash( $_GET['reference'] ) ) : ''; // WPCS: input var ok, CSRF ok.
		$payment    = WC_Payever_Api::get_instance()
									->get_payments_api_client()
									->retrievePaymentRequest( $payment_id )
									->getResponseEntity()
									->getResult();

		$this->get_wp_wrapper()->wc_add_notice(
			__( 'Payment has been refused.', 'payever-woocommerce-gateway' ),
			'error'
		);
		wp_redirect( $this->get_rejecting_url( $payment->getReference() ) );
		die();
	}

	/**
	 * Notice callback function
	 * NOTICE URL: domain/wc-api/finance_express_notice?reference=--PAYMENT-ID--
	 *
	 * @throws Exception
	 */
	public function finance_express_notice() {
		$payment_id = ( isset( $_GET['reference'] ) ) ? sanitize_text_field( wp_unslash( $_GET['reference'] ) ) : ''; // WPCS: input var ok, CSRF ok.
		WC_Payever_Api::get_instance()->get_locker()->acquireLock( $payment_id, 30 );

		try {
			$raw_data               = wp_kses_post( sanitize_text_field( file_get_contents( 'php://input' ) ) ); // WPCS: input var ok, CSRF ok.
			$raw_data               = json_decode( $raw_data, true );
			$notification_timestamp = strtotime( $raw_data['created_at'] );

			if ( $this->shouldUpdateNotification( $payment_id, $notification_timestamp ) ) {
				$payment = WC_Payever_Api::get_instance()
										->get_payments_api_client()
										->retrievePaymentRequest( $payment_id )
										->getResponseEntity()
										->getResult();

				$order = $this->process_order( $payment, false );

				// Handle capture/refund/cancel notification
				$this->process_notification( $raw_data, $payment, $order );

				update_post_meta( $this->get_order_id( $order ), 'notification_timestamp', $notification_timestamp );
				WC_Payever_Api::get_instance()->get_logger()->debug( sprintf( __( 'The order no.: %s has been successfully processed.', 'payever-woocommerce-gateway' ), $this->get_order_id( $order ) ) );
				WC_Payever_Api::get_instance()->get_locker()->releaseLock( $payment_id );

				wp_send_json(
					array(
						'result'  => 'success',
						'message' => sprintf( __( 'The order has been processed successfully.', 'payever-woocommerce-gateway' ), $this->get_order_id( $order ) ),
						'orderId' => $this->get_order_id( $order ),
					)
				);
			}
		} catch ( BadMethodCallException $bad_exception ) {
			WC_Payever_Api::get_instance()->get_locker()->releaseLock( $payment_id );
			WC_Payever_Api::get_instance()->get_logger()->debug( sprintf( __( 'The order processing has been skipped: %s', 'payever-woocommerce-gateway' ), $bad_exception->getMessage() ) );

			wp_send_json(
				array(
					'failed'  => 'error',
					'message' => $bad_exception->getMessage(),
				)
			);
		} catch ( Exception $exception ) {
			WC_Payever_Api::get_instance()->get_locker()->releaseLock( $payment_id );
			WC_Payever_Api::get_instance()->get_logger()->warning( sprintf( __( 'The order hasn\'t been processed: %s', 'payever-woocommerce-gateway' ), $exception->getMessage() ) );
			wp_send_json(
				array(
					'result'  => 'error',
					'message' => $exception->getMessage(),
				),
				400
			);
		}
	}

	/**
	 * @param $raw_data
	 * @param $payment
	 * @param $order
	 * @return $this
	 */
	private function process_notification( $raw_data, $payment, $order ) {
		$raw_payment = $raw_data['data']['payment'];
		if (
			( isset( $raw_payment['captured_items'] ) && count( $raw_payment['captured_items'] ) > 0 ) ||
			isset( $raw_payment['refunded_items'] ) && count( $raw_payment['refunded_items'] ) > 0 ||
			isset( $raw_payment['capture_amount'] ) && $raw_payment['capture_amount'] > 0 ||
			isset( $raw_payment['refund_amount'] ) && $raw_payment['refund_amount'] > 0 ||
			isset( $raw_payment['cancel_amount'] ) && $raw_payment['cancel_amount'] > 0
		) {
			$payment = array_merge( $raw_payment, $payment->toArray() );
			$handler = new WC_Payever_Notification_Handler();
			$handler->handle_notification( $order, $payment );

			return $this;
		}
		$this->update_order( $order, $payment );

		return $this;
	}

	/**
	 * @param $payment_id
	 * @param $notification_timestamp
	 *
	 * @return bool
	 */
	private function shouldUpdateNotification( $payment_id, $notification_timestamp ) {
		$order = $this->get_order_if_exists( $payment_id );
		if ( $order ) {
			$last_timestamp = get_post_meta( $this->get_order_id( $order ), 'notification_timestamp', true );
			if ( $last_timestamp > $notification_timestamp ) {
				throw new \BadMethodCallException( 'Notification rejected: newer notification already processed' );
			}

			$order_status = $order->get_status();
			if ( version_compare( WOOCOMMERCE_VERSION, '2.2.0', '>=' ) ) {
				$order_status = 'wc-' . $order_status;
			}

			if ( get_option( WC_Payever_Helper::PAYEVER_SHIPPED_STATUS ) === $order_status ) {
				throw new \BadMethodCallException( 'Order has been shipped so status can not be updated' );
			}
		}

		return true;
	}

	/**
	 * @param $payment
	 * @param $update_order
	 *
	 * @return WC_Order|WP_Error|null
	 * @throws WC_Data_Exception
	 */
	private function process_order( $payment, $update_order = 1 ) {
		if ( ! $this->is_successful_payment_status( $payment->getStatus() ) ) {
			throw new \BadMethodCallException( __( 'The payment hasn\'t been successful.', 'payever-woocommerce-gateway' ) );
		}

		$order = $this->get_order_if_exists( $payment->getId() );
		if ( ! $order ) {
			$order = $this->create_order( $payment );
		}

		if ( $update_order ) {
			$this->update_order( $order, $payment );
		}

		return $order;
	}

	private function create_order( $payment ) {
		$widget_cart     = array();
		$clear_cart      = false;
		$shipping_option = $payment->getShippingOption();

		// create shipping object
		$shipping = null;
		if ( $shipping_option ) {
			$shipping = new WC_Order_Item_Shipping();
			$shipping->set_method_title( $shipping_option->getName() );
			$shipping->set_method_id( $shipping_option->getCarrier() );
			$shipping->set_total( $shipping_option->getPrice() );
		}

		if ( false !== strpos( $payment->getReference(), self::CART_REFERENCE_PREFIX ) ) {
			$widget_cart = $this->build_widget_cart( $payment );
			$clear_cart  = true;
		}
		if ( false !== strpos( $payment->getReference(), self::PROD_REFERENCE_PREFIX ) ) {
			$reference = str_replace( self::PROD_REFERENCE_PREFIX, '', $payment->getReference() );
			$product   = wc_get_product( $reference );

			$product_price = ( is_a( $product, WC_Product::class ) ) ? wc_get_price_including_tax( $product ) : 0;
			$order_amount  = floatval( $product_price ) + floatval( $shipping ? $shipping->get_total() : 0 );

			if ( floatval( $order_amount ) !== floatval( $payment->getTotal() ) ) {
				$message = sprintf( __( 'The amount really paid (%s) is not equal to the product amount (%s).', 'payever-woocommerce-gateway' ), $payment->getTotal(), $order_amount );
				throw new \UnexpectedValueException( $message );
			}

			$widget_cart[] = array(
				'product'  => $product,
				'quantity' => 1,
			);
		}

		/** @var AddressEntity $payment_address */
		$payment_address = $payment->getAddress();

		/** @var WP_User $user */
		$user = get_user_by( 'login', $payment_address->getEmail() );
		if ( ! $user ) {
			$default_password = wp_generate_password();
			$user_id          = wp_create_user( $payment_address->getEmail(), $default_password, $payment_address->getEmail() );
			$user             = new WP_User( $user_id );
		}

		$order = wc_create_order( array( 'customer_id' => $user->ID ) );
		foreach ( $widget_cart as $widget_cart_item ) {
			$order->add_product( $widget_cart_item['product'], $widget_cart_item['quantity'] );
		}

		$order->set_address( $this->get_address( $payment_address ), 'billing' );
		$order->set_address( $this->get_address( $payment->getShippingAddress() ), 'shipping' );
		$this->set_order_currency( $order, $payment->getCurrency() );

		$order->set_payment_method( WC_Payever_Helper::instance()->add_payever_prefix( $payment->getPaymentType() ) );

		if ( $shipping ) {
			$order->add_item( $shipping );
		}

		$order->calculate_totals();

		if ( $clear_cart ) {
			WC()->cart->empty_cart();
		}

		return $order;
	}

	/**
	 * @param $order
	 * @param $currency
	 * @return $this
	 */
	private function set_order_currency( $order, $currency ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			$order->set_currency( $currency );
			return $this;
		}
		$order->order_currency = $currency;

		return $this;
	}

	/**
	 * @param AddressEntity $address_entity
	 *
	 * @return array
	 */
	private function get_address( $address_entity ) {
		return array(
			'first_name' => $address_entity->getFirstName(),
			'last_name'  => $address_entity->getLastName(),
			'email'      => $address_entity->getEmail(),
			'phone'      => $address_entity->getPhone(),
			'address_1'  => $address_entity->getStreet(),
			'address_2'  => $address_entity->getStreetNumber(),
			'city'       => $address_entity->getCity(),
			'postcode'   => $address_entity->getZipCode(),
			'country'    => $address_entity->getCountry(),
		);
	}

	/**
	 * @param $payment_id
	 *
	 * @return WC_Order|null
	 */
	private function get_order_if_exists( $payment_id ) {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT post_id FROM ' . esc_sql( $wpdb->postmeta ) . " WHERE meta_key = 'payment_id' AND meta_value = %s LIMIT 1",
				$payment_id
			)
		);
		if ( count( $results ) ) {
			return $this->get_order_wrapper()->get_wc_order( $results[0]->post_id );
		}

		return null;
	}

	/**
	 * @param $order
	 * @param $payment
	 */
	private function update_order( $order, $payment ) {
		$status_mapping = WC_Payever_Helper::instance()->get_payever_status_mapping();
		$wcStatus       = $status_mapping[ $payment->getStatus() ];

		if ( version_compare( WOOCOMMERCE_VERSION, '2.2.0', '>=' ) ) {
			$wcStatus = 'wc-' . $wcStatus;
		}
		$order->update_status( $wcStatus, 'Finance express order', true );

		if ( WC_Payever_Helper::instance()->is_santander( $payment->getPaymentType() ) ) {
			update_post_meta( $this->get_order_id( $order ), 'Santander application number', $payment->getPaymentDetails()->getApplicationNumber() );
		}
		$this->get_order_wrapper()->set_payment_id( $this->get_order_id( $order ), $payment->getId() );
		$pan_id = $payment->getPaymentDetails()->getUsageText();

		if ( ! empty( $pan_id ) ) {
			update_post_meta( $this->get_order_id( $order ), 'pan_id', $pan_id );
		}
	}

	/**
	 * @param string $status
	 *
	 * @return bool
	 */
	private static function is_successful_payment_status( $status ) {
		return in_array(
			$status,
			array(
				Status::STATUS_NEW,
				Status::STATUS_IN_PROCESS,
				Status::STATUS_ACCEPTED,
				Status::STATUS_PAID,
			)
		);
	}

	/**
	 * @param $order
	 *
	 * @return mixed
	 */
	private function get_order_id( $order ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			return $order->get_id();
		}

		return $order->id;
	}

	/**
	 * @param $payment
	 *
	 * @return array
	 */
	private function build_widget_cart( $payment ) {
		$widget_cart   = array();
		$payment_items = $payment->getItems();
		if ( ! empty( $payment_items && count( $payment_items ) ) ) {
			foreach ( $payment_items as $item ) {
				$product       = wc_get_product( $item->getIdentifier() );
				$widget_cart[] = array(
					'product'  => $product,
					'quantity' => intval( $item->getQuantity() ),
				);
			}

			return $widget_cart;
		}

		$cart_hash = WC()->cart->get_cart_hash();
		$reference = str_replace( self::CART_REFERENCE_PREFIX, '', $payment->getReference() );
		if ( $cart_hash !== $reference ) {
			throw new \UnexpectedValueException( __( 'Invalid cart hash validation', 'payever-woocommerce-gateway' ) );
		}

		$items = WC()->cart->get_cart();
		foreach ( $items as $item ) {
			$product_id    = $item['variation_id'] ?: $item['product_id'];
			$product       = wc_get_product( $product_id );
			$widget_cart[] = array(
				'product'  => $product,
				'quantity' => intval( $item['quantity'] ),
			);
		}

		return $widget_cart;
	}

	/**
	 * @return WC_Payever_Order_Wrapper
	 * @codeCoverageIgnore
	 */
	private function get_order_wrapper() {
		return null === $this->order_wrapper
			? $this->order_wrapper = new WC_Payever_Order_Wrapper()
			: $this->order_wrapper;
	}

	/**
	 * Returns rejecting url
	 *
	 * @param $reference
	 *
	 * @return string|void|null
	 */
	private function get_rejecting_url( $reference ) {
		if ( strpos( $reference, self::CART_REFERENCE_PREFIX ) !== false ) {
			return esc_url( version_compare( WOOCOMMERCE_VERSION, '2.5.0', '>=' ) ? wc_get_cart_url() : WC()->cart->get_cart_url() );
		}

		$product_reference = str_replace( self::PROD_REFERENCE_PREFIX, '', $reference );

		return get_permalink( $product_reference );
	}
}
