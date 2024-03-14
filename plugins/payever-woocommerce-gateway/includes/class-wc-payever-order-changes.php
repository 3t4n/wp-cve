<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Order_Changes' ) ) {
	return;
}

use Payever\Sdk\Payments\Action\ActionDeciderInterface;
use Payever\Sdk\Payments\Http\RequestEntity\ShippingGoodsPaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\PaymentItemEntity;
use Payever\Sdk\Payments\Http\RequestEntity\ShippingDetailsEntity;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class WC_Payever_Order_Changes {

	use WC_Payever_Helper_Wrapper_Trait;
	use WC_Payever_WP_Wrapper_Trait;
	use WC_Payever_Action_Decider_Wrapper_Trait;
	use WC_Payever_Api_Wrapper_Trait;

	/** @var WC_Payever_Order_Wrapper */
	private $order_wrapper;

	/**
	 * @var WC_Payever_Payment_Action
	 */
	private $payment_action_model;

	/**
	 * @param WC_Payever_WP_Wrapper|null $wp_wrapper
	 */
	public function __construct( $wp_wrapper = null ) {
		if ( null !== $wp_wrapper ) {
			$this->set_wp_wrapper( $wp_wrapper );
		}
		$this->get_wp_wrapper()->add_action(
			'woocommerce_order_status_changed',
			array(
				$this,
				'order_status_changed_transaction',
			),
			0,
			3
		);
		$this->get_wp_wrapper()->add_action(
			'woocommerce_email_after_order_table',
			array(
				$this,
				'add_panid_to_email',
			),
			20,
			4
		);
	}

	/**
	 * Order status handler
	 *
	 * @param int $order_id
	 * @param string $old_status
	 * @param string $new_status
	 *
	 * @return void
	 * @throws Exception
	 */
	public function order_status_changed_transaction( $order_id, $old_status, $new_status ) {
		$type = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : ''; // WPCS: input var ok, CSRF ok.
		if ( 'notice' === $type ) {
			return;
		}

		try {
			$this->execute_payment_action( $new_status, $order_id, $old_status );
		} catch ( Exception $exception ) {
			/**
			 * Reset tokens in case some of them caused this error
			 */
			WC_Payever_Api::get_instance()->get_payments_api_client()->getTokens()->clear()->save();

			WC_Admin_Meta_Boxes::add_error(
				sprintf(
					esc_html__(
						'Unable to initiate the payment action. Error: %1$s.',
						'payever-woocommerce-gateway'
					),
					$exception->getMessage()
				)
			);
		}
	}

	private function ship_by_amount( $api, $order, $order_id, $payment_id ) {
		$order_totals_model = WC_Payever_Helper::instance()->get_order_total_model();
		$order_totals       = $order_totals_model->get_totals( $order_id );
		if ( ! $order_totals ) {
			throw new Exception( sprintf( "couldn't find order totals for orderId: %s", $order_id ) );
		}
		if ( ! isset( $order_totals['available_capture'] ) ) {
			throw new Exception( sprintf( 'available_capture not available for orderId: %s', $order_id ) );
		}
		$available_capture     = $order_totals['available_capture'];
		$shipping_goods_entity = new ShippingGoodsPaymentRequest();
		$shipping_goods_entity->setAmount( $available_capture );

		if ( $order->get_shipping_method() ) {
			$shipping_details = new ShippingDetailsEntity();
			$shipping_details->setShippingMethod( $order->get_shipping_method() );
			$shipping_goods_entity->setShippingDetails( $shipping_details );
		}
		$payment_action = $this->get_payment_action_model();
		$identifier = $payment_action->add_shipping_action( $order_id );
		$api->shippingGoodsPaymentRequest( $payment_id, $shipping_goods_entity, $identifier );
		$order_totals_model->partial_capture( $available_capture, $order_id );
		$this->get_order_wrapper()->add_order_note(
			$order,
			__(
				'<p style="color: green;">Shipped ' . $this->get_wp_wrapper()->wc_price( $available_capture ) . ' successfully</p>',
				'payever-woocommerce-gateway'
			)
		);

		return $this;
	}

	private function ship_by_amount_ivy( $api, $order, $order_id, $payment_id ) {
		$shipping_goods_entity = new ShippingGoodsPaymentRequest();
		$shipping_goods_entity->setAmount( (float) $order->get_total() );

		$payment_action = $this->get_payment_action_model();
		$identifier = $payment_action->add_shipping_action( $order_id, $order->get_total() );
		$api->shippingGoodsPaymentRequest( $payment_id, $shipping_goods_entity, $identifier );
		$this->get_order_wrapper()->add_order_note(
			$order,
			__(
				'<p style="color: green;">Transaction has been captured successfully</p>',
				'payever-woocommerce-gateway'
			)
		);

		return $this;
	}

	/**
	 * @param $order
	 * @param $shipped_items
	 * @param $shipped_order_items
	 * @param $shipping_amount
	 * @return $this
	 */
	private function collect_items_for_ship( $order, &$shipped_items, &$shipped_order_items, &$shipping_amount ) {
		foreach ( $order->get_items( array( 'line_item', 'fee', 'shipping' ) ) as $item ) {
			if ( is_a( $item, 'WC_Order_Item_Shipping' ) ) {
				$payever_order_item = new WC_Payever_Order_Item( $item );
				$shipping_amount    = $payever_order_item->get_total() + $payever_order_item->get_total_tax();

				$shipped_order_items[] = array(
					'item_id'  => $payever_order_item->get_id(),
					'quantity' => 1,
				);

				continue;
			}
			if ( is_a( $item, 'WC_Order_Item_Fee' ) ) {
				// Fee
				/** @var WC_Order_Item_Fee $item */
				$payment_item_entity = new PaymentItemEntity();
				$payever_order_item  = new WC_Payever_Order_Item( $item );
				$total               = abs( $payever_order_item->get_total() + $payever_order_item->get_total_tax() );
				$name                = $payever_order_item->get_name();
				$item_id             = $payever_order_item->get_id();

				$payment_item_entity
					->setName( $name )
					->setIdentifier( 'fee-' . $item_id )
					->setPrice( round( $total, 2 ) )
					->setQuantity( 1 );

				$shipped_items[]       = $payment_item_entity;
				$shipped_order_items[] = array(
					'item_id'  => $item_id,
					'quantity' => 1,
				);

				continue;
			}
			/** @var WC_Order_Item_Product $item */
			$payment_item_entity = new PaymentItemEntity();
			$payever_order_item  = new WC_Payever_Order_Item( $item );
			$total               = abs( $payever_order_item->get_subtotal() + $payever_order_item->get_subtotal_tax() );
			$quantity            = abs( $payever_order_item->get_quantity() );
			$item_id             = $payever_order_item->get_id();

			$payment_item_entity
				->setName( $payever_order_item->get_name() )
				->setIdentifier( strval( $item_id ) )
				->setPrice( round( $total / $quantity, 2 ) )
				->setQuantity( intval( $quantity ) );

			$shipped_items[]       = $payment_item_entity;
			$shipped_order_items[] = array(
				'item_id'  => $item_id,
				'quantity' => intval( $quantity ),
			);
		}

		return $this;
	}

	/**
	 * @param $order
	 * @return bool
	 */
	private function has_order_shipments( $order ) {
		if ( function_exists( 'wc_gzd_get_shipments_by_order' ) ) {
			$shipments = wc_gzd_get_shipments_by_order( $order );
			foreach ( $shipments as $shipment ) {
				if ( ! $shipment->is_shipped() ) {
					continue;
				}
				return true;
			}
		}

		return false;
	}

	private function set_shipping_details( $order, $shipping_goods_entity ) {
		// Germanized Shipments for WooCommerce
		$has_shipments = $this->has_order_shipments( $order );

		if ( $has_shipments ) {
			// Use shipping details provided by `Germanized Shipments for WooCommerce`
			$shipments = wc_gzd_get_shipments_by_order( $order );

			foreach ( $shipments as $shipment ) {
				/** @var \Vendidero\Germanized\Shipments\Shipment $shipment */
				if ( ! $shipment->is_shipped() ) {
					continue;
				}

				$shipping_details = new ShippingDetailsEntity();
				$shipping_details
					->setShippingCarrier( $shipment->get_shipping_provider_title() )
					->setShippingDate( $shipment->get_date_sent()->date_i18n() )
					->setReturnCarrier( $shipment->get_shipping_provider_title() )
					->setShippingMethod( $shipment->get_shipping_provider_title() );

				if ( $shipment->has_tracking() ) {
					$shipping_details
						->setTrackingNumber( $shipment->get_tracking_id() )
						->setTrackingUrl( $shipment->get_tracking_url() )
						->setReturnTrackingNumber( $shipment->get_tracking_id() )
						->setReturnTrackingUrl( $shipment->get_tracking_url() );
				}

				$shipping_goods_entity->setShippingDetails( $shipping_details );

				break;
			}
		} elseif ( $order->get_shipping_method() ) {
			$shipping_details = new ShippingDetailsEntity();
			$shipping_details->setShippingMethod( $order->get_shipping_method() );
			$shipping_goods_entity->setShippingDetails( $shipping_details );
		}

		return $this;
	}

	/**
	 * Executing payment action
	 *
	 * @param string $status_action
	 * @param int $order_id
	 *
	 * @return bool
	 * @throws Exception
	 */
	private function execute_payment_action( $status_action, $order_id ) {
		$payment_id = $this->get_order_wrapper()->get_payment_id( $order_id );
		$order      = $this->get_order_wrapper()->get_wc_order( $order_id );

		if ( ! $this->get_helper_wrapper()->validate_order_payment_method( $order ) || empty( $payment_id ) ) {
			return false;
		}

		$shipped_status = $this->get_shipping_status();
		$api            = $this->get_api_wrapper()->get_payments_api_client();
		$action_decider = $this->get_action_decider_wrapper()->get_action_decider( $api );
		$payment_action = $this->get_payment_action_model();

		if (
			'cancelled' === $status_action
			&& $action_decider->isCancelAllowed( $payment_id, false )
		) {
			$identifier = $payment_action->add_cancel_action( $order_id );
			$api->cancelPaymentRequest( $payment_id, null, $identifier );
			$this->get_order_wrapper()->add_order_note(
				$order,
				__(
					'<p style="color: green;">Transaction has been cancelled successfully</p>',
					'payever-woocommerce-gateway'
				)
			);
			return true;
		}

		if (
			$status_action === $shipped_status
			&& $this->ship( $action_decider, $order, $order_id, $api, $payment_id )
		) {
			return true;
		}

		if (
			'refunded' === $status_action
			&& $this->refund( $action_decider, $api, $order, $payment_id, $order_id )
		) {
			return true;
		}

		return false;
	}

	private function get_shipping_status() {
		$shipped_status = $this->get_wp_wrapper()->get_option( WC_Payever_Helper::PAYEVER_SHIPPED_STATUS ) ?: WC_Payever_Helper::DEFAULT_SHIPPED_STATUS;

		return str_replace( 'wc-', '', $shipped_status );
	}

	/**
	 * @param $action_decider
	 * @param $order
	 * @param $order_id
	 * @param $api
	 * @param $payment_id
	 * @return bool
	 * @throws Exception
	 */
	private function ship( $action_decider, $order, $order_id, $api, $payment_id ) {
		if ( $this->is_allow_ship_by_amount( $action_decider, $payment_id, $order_id ) ) {
			$this->ship_by_amount( $api, $order, $order_id, $payment_id );
			return true;
		}

		if ( $action_decider->isShippingAllowed( $payment_id, false ) ) {
			$this->ship_by_items( $api, $order, $order_id, $payment_id );
			return true;
		}

		return false;
	}

	/**
	 * @param $action_decider
	 * @param $payment_id
	 * @param $order_id
	 * @return bool
	 */
	private function is_allow_ship_by_amount( $action_decider, $payment_id, $order_id ) {
		return $action_decider->isPartialShippingAllowed( $payment_id, false )
			&& WC_Payever_Helper::instance()->is_allow_order_capture_by_amount( $order_id )
			&& false === WC_Payever_Helper::instance()->is_allow_order_capture_by_qty( $order_id );
	}

	/**
	 * @param $api
	 * @param $order
	 * @param $order_id
	 * @param $payment_id
	 * @return true|void
	 * @throws Exception
	 */
	private function ship_by_items( $api, $order, $order_id, $payment_id ) {
		$payment_method = WC_Payever_Helper::instance()->get_payment_method( $order );

		if ( WC_Payever_Helper::instance()->is_ivy( $payment_method ) ) {
			$this->ship_by_amount_ivy( $api, $order, $order_id, $payment_id );
			return true;
		}
		$shipped_items       = array();
		$shipped_order_items = array();
		$shipping_amount     = 0.0;
		$this->collect_items_for_ship( $order, $shipped_items, $shipped_order_items, $shipping_amount );

		$shipping_goods_entity = new ShippingGoodsPaymentRequest();
		$shipping_goods_entity
			->setDeliveryFee( (float) $shipping_amount )
			->setPaymentItems( $shipped_items );

		$this->set_shipping_details( $order, $shipping_goods_entity );
		$payment_action = $this->get_payment_action_model();
		$identifier     = $payment_action->add_shipping_action( $order_id, $order->get_total() );

		$api->shippingGoodsPaymentRequest( $payment_id, $shipping_goods_entity, $identifier );
		$order_items = WC_Payever_Helper::instance()->get_order_total_model()->get_order_items( $order_id );

		foreach ( $shipped_order_items as $item ) {
			foreach ( $order_items as $key => $order_item ) {
				if ( (string) $item['item_id'] === (string) $order_item['item_id'] ) {
					$order_items[ $key ]['captured_qty'] += $item['quantity'];
				}
			}
		}
		update_post_meta( $order_id, '_payever_order_items', $order_items );

		$this->get_order_wrapper()->add_order_note(
			$order,
			__(
				'<p style="color: green;">Shipped ' . $this->get_wp_wrapper()->wc_price( $order->get_remaining_refund_amount() ) . ' successfully!</p>',
				'payever-woocommerce-gateway'
			)
		);
	}

	/**
	 * @param $action_decider
	 * @param $api
	 * @param $order
	 * @param $payment_id
	 * @param $order_id
	 * @return bool
	 * @throws Exception
	 */
	private function refund( $action_decider, $api, $order, $payment_id, $order_id ) {
		if ( ! $action_decider->isRefundAllowed( $payment_id, false ) ) {
			return false;
		}
		$refunds = $order->get_refunds();
		$refund  = array_shift( $refunds );

		$amount          = $this->get_refund_amount( $refund );
		$payment_action  = $this->get_payment_action_model();
		$identifier      = $payment_action->add_refund_action( $order_id, $amount );
		$refund_response = $this->get_api_wrapper()->refund_payment_request( $api, $payment_id, $amount, $identifier );
		$transaction_id  = $refund_response->getResponseEntity()->getCall()->getId();

		$this->get_order_wrapper()->add_order_note(
			$order,
			__(
				'<p style="color: green;">Refunded ' . $this->get_wp_wrapper()->wc_price( $amount ) . '. Transaction ID: ' . $transaction_id . '</p>',
				'payever-woocommerce-gateway'
			)
		);

		return true;
	}

	/**
	 * @param $refund
	 * @return mixed
	 */
	private function get_refund_amount( $refund ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			return $refund->get_amount();
		}

		return $refund->get_refund_amount();
	}

	/**
	 * @deprecated Remove this method
	 * @param WC_Order $order
	 * @param bool $sent_to_admin
	 * @param string $plain_text
	 * @param object|bool $email
	 *
	 * @return bool
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function add_panid_to_email( $order, $sent_to_admin, $plain_text, $email = null ) {
		$payment_method = $this->get_helper_wrapper()->get_payment_method( $order );
		if ( is_object( $email ) && $this->get_helper_wrapper()->is_payever_method( $payment_method ) ) {
			if ( 'customer_processing_order' === $email->id ||
				 'customer_on_hold_order' === $email->id || //phpcs:ignore
				 'customer_invoice' === $email->id //phpcs:ignore
			) {
				include WC_PAYEVER_PLUGIN_PATH . '/views/email-panid-details.php';
			}

			return true;
		}

		return false;
	}

	/**
	 * @param WC_Payever_Order_Wrapper $order_wrapper
	 *
	 * @return $this
	 * @codeCoverageIgnore
	 */
	public function set_order_wrapper( WC_Payever_Order_Wrapper $order_wrapper ) {
		$this->order_wrapper = $order_wrapper;

		return $this;
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
	 * @return WC_Payever_Payment_Action
	 * @codeCoverageIgnore
	 */
	private function get_payment_action_model() {
		return null === $this->payment_action_model
			? $this->payment_action_model = new WC_Payever_Payment_Action()
			: $this->payment_action_model;
	}
}
