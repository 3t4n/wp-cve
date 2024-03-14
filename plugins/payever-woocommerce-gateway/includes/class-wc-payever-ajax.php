<?php

use Payever\Sdk\Payments\PaymentsApiClient;
use Payever\Sdk\Payments\Http\RequestEntity\PaymentItemEntity;
use Payever\Sdk\Payments\Http\RequestEntity\ShippingGoodsPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\ShippingDetailsEntity;

defined( 'ABSPATH' ) || exit;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class WC_Payever_Ajax {
	use WC_Payever_WP_Wrapper_Trait;

	/**
	 * @var PaymentsApiClient
	 */
	private $payments_api_client;

	/**
	 * @var WC_Payever_Order_Wrapper
	 */
	private $order_wrapper;

	/**
	 * @var WC_Payever_Order_Total
	 */
	private $order_total_model;

	/**
	 * @var WC_Payever_Payment_Action
	 */
	private $payment_action_model;

	/**
	 * Add actions
	 */
	public function __construct() {
		add_action( 'wp_ajax_payever_capture_item', array( $this, 'capture_item' ) );
		add_action( 'wp_ajax_payever_cancel_item', array( $this, 'cancel_item' ) );
	}

	/**
	 * Payment capture action
	 * @return void
	 */
	public function capture_item() {
		if ( empty( $_POST['items'] ) && ! empty( $_POST['amount'] ) ) {
			/**
			 * Capture by amount
			 */
			$this->capture_by_amount();
			return;
		}
		/**
		 * Capture by qty
		 */
		$this->capture_by_items();
	}

	/**
	 * Capture by amount.
	 *
	 * @return void
	 */
	private function capture_by_amount() {
		$amount   = ! empty( $_POST['amount'] ) ? wc_clean( wp_unslash( $_POST['amount'] ) ) : false; // WPCS: input var ok, CSRF ok.
		$order_id = ! empty( $_POST['order_id'] ) ? wc_clean( wp_unslash( $_POST['order_id'] ) ) : false; // WPCS: input var ok, CSRF ok.
		$comment  = isset( $_POST['comment'] ) ? wc_clean( wp_unslash( $_POST['comment'] ) ) : null; // WPCS: input var ok, CSRF ok.

		try {
			if ( ! WC_Payever_Helper::instance()->is_allow_order_capture_by_amount( $order_id ) ) {
				throw new \Exception( 'This capture method not allowed.' );
			}
			/**
			 * Validate amount
			 */
			$this->validate_captured_amount( $amount, $order_id );

			/**
			 * Register requested amount per items
			 */
			$this->order_total_model->partial_capture( $amount, $order_id );

			/**
			 * Configure shipping request
			 */
			$shipping_goods_entity = new ShippingGoodsPaymentRequest();
			$this->set_shipping_information_from_post( $shipping_goods_entity );
			$shipping_goods_entity
				->setAmount( $amount )
				->setReason( $comment );
		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'error' => $e->getMessage(),
				)
			);
			return;
		}

		$payment_id = get_post_meta( $order_id, WC_Payever_Gateway::PAYEVER_PAYMENT_ID, true );
		try {
			/**
			 * get order
			 */
			$order = $this->get_order_wrapper()->get_wc_order( $order_id );

			$payment_action = $this->get_payment_action_model();
			$identifier     = $payment_action->add_shipping_action( $order_id, $amount );

			/**
			 * Make shipping goods request by amount
			 */
			$response = $this->get_payments_api_client()->shippingGoodsPaymentRequest(
				$payment_id,
				$shipping_goods_entity,
				$identifier
			);

			if ( ! $response ) {
				throw new \Exception( 'Something wrong. Please try bit later.' );
			}

			if ( $shipping_goods_entity->getShippingDetails() ) {
				$this->add_order_tracking( $order, $shipping_goods_entity->getShippingDetails() );
			}

			/**
			 * Add success note
			 */
			$this->get_order_wrapper()->add_order_note(
				$order,
				__(
					'<p style="color: green;">Shipped ' . $this->get_wp_wrapper()->wc_price( $amount ) . ' successfully</p>', //phpcs:ignore
					'payever-woocommerce-gateway'
				)
			);

			wp_send_json_success();
		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'error' => $e->getMessage(),
				)
			);
		}
	}

	/**
	 * @param $amount
	 * @param $order_id
	 *
	 * @return bool
	 * @throws Exception
	 */
	private function validate_captured_amount( &$amount, $order_id ) {
		if ( $amount <= 0 ) {
			throw new Exception(
				sprintf( 'Wrong amount %s value.', $amount )
			);
		}
		$totals = $this->get_order_total_model()->get_totals( $order_id );
		if ( $amount > $totals['available_capture'] ) {
			$amount = $totals['available_capture'];
		}
		return true;
	}

	/**
	 * @param ShippingGoodsPaymentRequest $shipping_request
	 *
	 * @return $this
	 */
	private function set_shipping_information_from_post( $shipping_request ) {
		$tracking_number = apply_filters(
			'pe_tracking_number',
			isset( $_POST['tracking_number'] ) ? wc_clean( wp_unslash( $_POST['tracking_number'] ) ) : '' // WPCS: input var ok, CSRF ok.
		);

		$tracking_url = apply_filters(
			'pe_tracking_url',
			isset( $_POST['tracking_url'] ) ? wc_clean( wp_unslash( $_POST['tracking_url'] ) ) : '' // WPCS: input var ok, CSRF ok.
		);

		$shipping_provider = apply_filters(
			'pe_shipping_provider',
			isset( $_POST['shipping_provider'] ) ? wc_clean( wp_unslash( $_POST['shipping_provider'] ) ) : '' // WPCS: input var ok, CSRF ok.
		);

		$shipping_date = apply_filters(
			'pe_shipping_date',
			isset( $_POST['shipping_date'] ) ? wc_clean( wp_unslash( $_POST['shipping_date'] ) ) : '' // WPCS: input var ok, CSRF ok.
		);
		if ( ! empty( $tracking_number ) || ! empty( $tracking_url ) || ! empty( $shipping_provider ) ) {
			$shipping_details = new ShippingDetailsEntity();
			$shipping_details
				->setTrackingNumber( $tracking_number )
				->setTrackingUrl( $tracking_url )
				->setShippingCarrier( $shipping_provider )
				->setShippingDate( $shipping_date )
				->setReturnCarrier( $shipping_provider )
				->setReturnTrackingNumber( $tracking_number )
				->setShippingMethod( $shipping_provider )
				->setReturnTrackingUrl( $tracking_url );

			$shipping_request->setShippingDetails( $shipping_details );
		}
		return $this;
	}

	/**
	 * @param $items
	 * @param WC_Order $order
	 * @return array
	 * @throws Exception
	 */
	private function get_prepared_payment_items_data( $items, $order ) {
		if ( count( $items ) === 0 ) {
			throw new Exception(
				'Amount doesn\'t match, for capture please use qty inputs inside each item.'
			);
		}
		$result = array(
			'items'        => array(),
			'captured'     => 0.0,
			'delivery_fee' => 0.0,
			'payment_fee'  => 0.0,
		);
		foreach ( $items as $item ) {
			$item_id  = $item['item_id'];
			$quantity = $item['qty'];

			/** @var WC_Order_Item $item */
			$item        = $order->get_item( $item_id );
			$order_item  = $this->get_order_total_model()->get_order_items( $order->get_id(), $item_id );

			if ( is_a( $item, 'WC_Order_Item_Shipping' ) ) {
				$result['delivery_fee'] = $order_item['unit_price'];
				continue;
			}
			if ( is_a( $item, 'WC_Order_Item_Fee' ) ) {
				$result['payment_fee'] = $order_item['unit_price'];
				continue;
			}

			$this->validate_qty( $item, $quantity + $order_item['captured_qty'] );

			$product_id = $this->get_item_product_id( $item );

			$result['captured'] += $order_item['unit_price'] * $quantity;
			$payment_entity = new PaymentItemEntity();
			$payment_entity->setIdentifier( strval( $product_id ) )
				->setName( $this->get_order_item_name( $item ) )
				->setPrice( round( $order_item['unit_price'], 2 ) )
				->setQuantity( $quantity );
			$result['items'][] = $payment_entity;
		}

		return $result;
	}

	private function validate_qty( $item, $quantity ) {
		if ( $quantity > $item->get_quantity() ) {
			throw new Exception( 'Qty is more than left qty.' );
		}

		return $this;
	}

	/**
	 * Capture by items.
	 *
	 * @return void
	 */
	private function capture_by_items() {
		$order_id   = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0; // WPCS: input var ok, CSRF ok.
		$items      = isset( $_POST['items'] ) ? (array) wc_clean( wp_unslash( $_POST['items'] ) ) : array(); // WPCS: input var ok, CSRF ok.
		$comment    = isset( $_POST['comment'] ) ? wc_clean( wp_unslash( $_POST['comment'] ) ) : null; // WPCS: input var ok, CSRF ok.

		$payment_id = $this->get_order_wrapper()->get_payment_id( $order_id );
		$order      = $this->get_order_wrapper()->get_wc_order( $order_id );
		try {
			if ( ! WC_Payever_Helper::instance()->is_allow_order_capture_by_qty( $order_id ) ) {
				throw new \Exception( 'This capture method not allowed.' );
			}
			// Calculate amount of selected items
			$amount = $this->get_amount( $order_id, $items );

			$this->validate_captured_amount( $amount, $order_id );
			$items_data = $this->get_prepared_payment_items_data( $items, $order );

			// Shipping goods payment request
			$shipping_goods_entity = new ShippingGoodsPaymentRequest();
			$shipping_goods_entity
				->setDeliveryFee( $items_data['delivery_fee'] )
				->setPaymentItems( $items_data['items'] )
				->setPaymentFee( $items_data['payment_fee'] )
				->setReason( $comment );

			$this->set_shipping_information_from_post( $shipping_goods_entity );

			$payment_action = $this->get_payment_action_model();
			$identifier     = $payment_action->add_shipping_action( $order_id, $amount );
			$this->capture_items_request(
				$payment_id,
				$shipping_goods_entity,
				$order_id,
				$items,
				$order,
				$items_data,
				$identifier
			);

			wp_send_json_success();
		} catch ( Exception $exception ) {
			wp_send_json_error( array( 'error' => $exception->getMessage() ) );
		}
	}

	private function capture_items_request(
		$payment_id,
		$shipping_goods_entity,
		$order_id,
		$items,
		$order,
		$items_data,
		$identifier
	) {
		$response = $this->get_payments_api_client()->shippingGoodsPaymentRequest(
			$payment_id,
			$shipping_goods_entity,
			$identifier
		);

		if ( ! $response ) {
			throw new \Exception( 'Something wrong. Please try bit later.' );
		}
		// Update item qty
		$order_items = $this->get_order_total_model()->get_order_items( $order_id );
		foreach ( $items as $item ) {
			$item_id  = $item['item_id'];
			$quantity = $item['qty'];

			foreach ( $order_items as $key => $order_item ) {
				if ( (string) $item_id === (string) $order_item['item_id'] ) {
					$order_items[ $key ]['captured_qty'] += $quantity;
				}
			}
		}
		update_post_meta( $order_id, '_payever_order_items', $order_items );

		// Save shipping information
		if ( $shipping_goods_entity->getShippingDetails() ) {
			$delivery_details = $shipping_goods_entity->getShippingDetails();
			$this->add_order_tracking( $order, $delivery_details );
		}

		$this->get_order_wrapper()->add_order_note(
			$order,
			__(
				'<p style="color: green;">Shipped ' . $this->get_wp_wrapper()->wc_price( $items_data['captured'] + $items_data['delivery_fee'] + $items_data['payment_fee'] ) . ' successfully</p>', //phpcs:ignore
				'payever-woocommerce-gateway'
			)
		);

		set_transient(
			'pe_lock_ship_' . $order_id,
			$items_data['captured'] + $items_data['delivery_fee'] + $items_data['payment_fee'],
			MINUTE_IN_SECONDS
		);

		return $this;
	}

	/**
	 * @param WC_Order $order
	 * @param ShippingDetailsEntity $delivery_details
	 *
	 * @return $this
	 */
	private function add_order_tracking( $order, $delivery_details ) {
		$order_id = $order->get_id();
		update_post_meta( $order_id, '_payever_tracking_number', $delivery_details->getTrackingNumber() );
		update_post_meta( $order_id, '_payever_tracking_url', $delivery_details->getTrackingUrl() );
		update_post_meta( $order_id, '_payever_shipping_provider', $delivery_details->getShippingCarrier() );
		update_post_meta( $order_id, '_payever_shipping_date', $delivery_details->getShippingDate() );

		// Call WooCommerce Shipment Tracking if it's installed
		if ( function_exists( 'wc_st_add_tracking_number' ) ) {
			wc_st_add_tracking_number(
				$order_id,
				$delivery_details->getTrackingNumber(),
				$delivery_details->getShippingCarrier(),
				$delivery_details->getShippingDate(),
				$delivery_details->getTrackingUrl()
			);
		}

		// Call `pe_add_tracking` action
		do_action(
			'pe_add_tracking_data',
			$order,
			$delivery_details->getTrackingNumber(),
			$delivery_details->getShippingCarrier(),
			$delivery_details->getShippingDate(),
			$delivery_details->getTrackingUrl()
		);
		return $this;
	}

	/**
	 * @param $items
	 * @param $order_id
	 * @return bool
	 * @throws Exception
	 */
	private function validate_items_before_cancel( $items, $order_id ) {
		if ( 0 === count( $items ) ) {
			throw new Exception(
				'Amount doesn\'t match, for cancel please use qty inputs inside each item.'
			);
		}

		$totals = $this->get_order_total_model()->get_totals( $order_id );
		$amount = $this->get_amount( $order_id, $items );
		if ( $amount > $totals['available_cancel']
			|| round( $totals['cancelled'] + $amount, 2 ) > $totals['available_cancel']
		) {
			throw new Exception( 'Cancel amount is higher than order remaining amount.' );
		}
		return true;
	}

	/**
	 * Cancel action.
	 *
	 * @return void
	 */
	public function cancel_item() {
		$order_id  = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0; // WPCS: input var ok, CSRF ok.
		$items     = isset( $_POST['items'] ) ? (array) wc_clean( wp_unslash( $_POST['items'] ) ) : array(); // WPCS: input var ok, CSRF ok.
		$payment_id = $this->get_order_wrapper()->get_payment_id( $order_id );
		$order      = $this->get_order_wrapper()->get_wc_order( $order_id );

		try {
			$this->validate_items_before_cancel( $items, $order_id );
			$payment_items = array();
			$cancelled     = 0.0;
			$delivery_fee  = 0.0;
			foreach ( $items as $item ) {
				$item_id  = $item['item_id'];
				$quantity = $item['qty'];

				/** @var WC_Order_Item $item */
				$item        = $order->get_item( $item_id );
				$order_item  = $this->get_order_total_model()->get_order_items( $order_id, $item_id );

				if ( is_a( $item, 'WC_Order_Item_Shipping' ) ) {
					$delivery_fee = $order_item['unit_price'];
					continue;
				}

				$this->validate_qty( $item, $quantity + $order_item['cancelled_qty'] );

				$product_id = $this->get_item_product_id( $order_item );

				$cancelled += $order_item['unit_price'] * $quantity;
				$payment_entity = new PaymentItemEntity();
				$payment_entity->setIdentifier( strval( $product_id ) )
							->setName( $this->get_order_item_name( $item ) )
							->setPrice( round( $order_item['unit_price'], 2 ) )
							->setQuantity( $quantity );

				$payment_items[] = $payment_entity;
			}

			$amount         = $this->get_amount( $order_id, $items );
			$payment_action = $this->get_payment_action_model();
			$identifier     = $payment_action->add_cancel_action( $order_id, $amount );

			$this->cancel_request(
				$payment_id,
				$payment_items,
				$delivery_fee,
				$order_id,
				$items,
				$order,
				$cancelled,
				$identifier
			);

			wp_send_json_success();
		} catch ( Exception $exception ) {
			wp_send_json_error( array( 'error' => $exception->getMessage() ) );
		}
	}

	private function get_item_product_id( $item ) {
		$product_id = is_array( $item ) && isset( $item['product_id'] )
			? (int) $item['product_id'] : false;
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			/** @var WC_Order_Item_Product $item */
			$product_id = is_callable( array( $item, 'get_product_id' ) ) ? $item->get_product_id() : false; //phpcs:ignore
		}

		return $product_id;
	}

	private function cancel_request(
		$payment_id,
		$payment_items,
		$delivery_fee,
		$order_id,
		$items,
		$order,
		$cancelled,
		$identifier
	) {
		// Cancel payment request
		$response = $this->get_payments_api_client()->cancelItemsPaymentRequest(
			$payment_id,
			$payment_items,
			$delivery_fee,
			$identifier
		);
		if ( ! $response ) {
			return $this;
		}
		// Update item qty
		$order_items = $this->get_order_total_model()->get_order_items( $order_id );
		foreach ( $items as $item ) {
			$item_id  = $item['item_id'];
			$quantity = $item['qty'];

			foreach ( $order_items as $key => $order_item ) {
				if ( (string) $item_id === (string) $order_item['item_id'] ) {
					$order_items[ $key ]['cancelled_qty'] += $quantity;
				}
			}

			update_post_meta( $order_id, '_payever_order_items', $order_items );
		}

		$this->get_order_wrapper()->add_order_note(
			$order,
			__(
				'<p style="color: green;">Cancelled ' . $this->get_wp_wrapper()->wc_price( $cancelled + $delivery_fee ) . ' successfully</p>', //phpcs:ignore
				'payever-woocommerce-gateway'
			)
		);

		set_transient(
			'pe_lock_cancel_' . $order_id,
			$cancelled + $delivery_fee,
			MINUTE_IN_SECONDS
		);

		return $this;
	}

	/**
	 * Calculate amount of selected items including taxes.
	 *
	 * @param mixed $order_id
	 * @param array $items
	 *
	 * @return float|int
	 * @throws Exception
	 */
	private function get_amount( $order_id, $items ) {
		$amount = 0.0;

		foreach ( $items as $item ) {
			$item_id  = $item['item_id'];
			$quantity = $item['qty'];

			$item = $this->get_order_total_model()->get_order_items( $order_id, $item_id );
			if ( ! $item ) {
				throw new Exception( 'Unable to get order item ' . $item_id );
			}

			$amount += $item['unit_price'] * $quantity;
		}

		return $amount;
	}

	/**
	 * @param WC_Order_Item $order_item
	 *
	 * @return string
	 */
	private function get_order_item_name( $order_item ) {

		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			return $order_item->get_name();
		}

		return $order_item['name'];
	}

	/**
	 * @return PaymentsApiClient
	 * @throws Exception
	 * @codeCoverageIgnore
	 */
	private function get_payments_api_client() {
		$api = new WC_Payever_API_Wrapper();

		return null === $this->payments_api_client
			? $this->payments_api_client = $api->get_payments_api_client()
			: $this->payments_api_client;
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
	 * @return WC_Payever_Order_Total
	 * @codeCoverageIgnore
	 */
	private function get_order_total_model() {
		return null === $this->order_total_model
			? $this->order_total_model = new WC_Payever_Order_Total()
			: $this->order_total_model;
	}

	/**
	 * @return WC_Payever_Payment_Action
	 * @codeCoverageIgnore
	 */
	private function get_payment_action_model() {
		return null === $this->payment_action_model
			? $this->payment_action_model = new WC_Payever_Payment_Action()
			: $this->payment_action_model;
	}
}
