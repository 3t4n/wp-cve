<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Notification_Handler' ) ) {
	return;
}

use Payever\Sdk\Payments\Enum\Status;
use Payever\Sdk\Payments\Http\RequestEntity\NotificationRequestEntity;
use Payever\Sdk\Payments\Notification\NotificationHandlerInterface;
use Payever\Sdk\Payments\Notification\NotificationResult;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @codeCoverageIgnore
 */
class WC_Payever_Notification_Handler implements NotificationHandlerInterface {

	use WC_Payever_WP_Wrapper_Trait;

	/** @var WC_Payever_Order_Wrapper */
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
	 * @param NotificationRequestEntity $notification
	 * @param NotificationResult        $notificationResult
	 * @return bool|int|void
	 */
	public function handleNotification(
		NotificationRequestEntity $notification,
		NotificationResult $notificationResult
	) {
		$payever_status = $notification->getPayment()->getStatus();
		WC_Payever_Api::get_instance()->get_logger()->debug(
			'Processing payever status',
			array(
				'payever_status' => $payever_status,
			)
		);
		$shipped_status = $this->get_wp_wrapper()->get_option( WC_Payever_Helper::PAYEVER_SHIPPED_STATUS );
		$order_id       = $notification->getPayment()->getReference();
		$order          = $this->get_order_wrapper()->get_wc_order( (int) $order_id );
		$order_status   = $order->get_status();

		if ( $this->get_wp_wrapper()->version_compare( WOOCOMMERCE_VERSION, '2.2.0', '>=' ) ) {
			$order_status = 'wc-' . $order_status;
		}

		if ( ! $this->validate( $order_id, $notification, $notificationResult, $order_status, $payever_status ) ) {
			return false;
		}

		$created_at = $notification->getCreatedAt()->getTimestamp();
		if ( $order_status === $shipped_status ) {
			$notificationResult
				->addMessage( __( 'Order has been shipped so status can not be updated', 'payever-woocommerce-gateway' ) )
				->setOrderId( $order_id )
				->setCurrentOrderStatus( $order_status );

			return $this->get_wp_wrapper()->update_post_meta( (int) $order_id, 'notification_timestamp', $created_at );
		}

		$payload = wp_kses_post( sanitize_text_field( file_get_contents( 'php://input' ) ) );  // WPCS: input var ok, CSRF ok.
		$payload = json_decode( $payload, true );

		// User may not finish creating the order and restart the payment process.
		if ( $this->should_reject_if_expired_status( $payload, $payever_status ) ) {
			$notificationResult
				->addMessage( __( 'Notification expire processing is skipped; reason: order already processed' ) )
				->setOrderId( $order_id )
				->setCurrentOrderStatus( $order_status );

			return false;
		}

		$this->get_logger()->info(
			sprintf(
				'[Notification] Payload: %s',
				wp_json_encode( $payload, JSON_PRETTY_PRINT )
			)
		);

		// Handle capture/refund/cancel notification
		$payment = $payload['data']['payment'];
		$this->process( $payment, $order, $notification );

		if ( $notification->getAction() ) {
			$this->get_payment_action_model()->add_action(
				$order_id,
				$notification->getAction()->getUniqueIdentifier(),
				$notification->getAction()->getType(),
				$notification->getAction()->getAmount()
			);
		}

		$notificationResult
			->addMessage( __( 'Order status has been updated', 'payever-woocommerce-gateway' ) )
			->setOrderId( $order_id )
			->setPreviousOrderStatus( $order_status )
			->setCurrentOrderStatus( $order->get_status() );

		return $this->get_wp_wrapper()->update_post_meta( (int) $order_id, 'notification_timestamp', $created_at );
	}

	/**
	 * @param $payment
	 * @param $order
	 * @param $notification
	 * @return void
	 */
	private function process( $payment, $order, $notification ) {
		if (
			! empty( $payment['captured_items'] ) ||
			! empty( $payment['refunded_items'] ) ||
			! empty( $payment['capture_amount'] ) ||
			! empty( $payment['refund_amount'] ) ||
			! empty( $payment['cancel_amount'] )
		) {
			$this->handle_notification( $order, $payment );
		}

		$this->update_order( $notification->getPayment(), $order );
	}

	/**
	 * @param $order_id
	 * @param $notification
	 * @param $notification_result
	 * @param $order_status
	 * @param $payever_status
	 *
	 * @return bool
	 */
	private function validate( $order_id, $notification, $notification_result, $order_status, $payever_status ) {
		if ( Status::STATUS_NEW === $payever_status ) {
			$notification_result
				->addMessage( __( 'Notification processing is skipped; reason: stalled new status' ) )
				->setOrderId( $order_id )
				->setCurrentOrderStatus( $order_status );

			return false;
		}
		$created_at = $notification->getCreatedAt()->getTimestamp();

		if ( $this->should_reject_notification( (int) $order_id, $created_at ) ) {
			$notification_result
				->addMessage( __( 'Notification rejected: newer notification already processed', 'payever-woocommerce-gateway' ) )
				->setOrderId( $order_id )
				->setCurrentOrderStatus( $order_status );

			return false;
		}

		if ( ! $notification->getAction() ) {
			return true;
		}
		$payment_action = $this->get_payment_action_model()->get_item(
			$order_id,
			$notification->getAction()->getUniqueIdentifier(),
			$notification->getAction()->getSource()
		);

		// Check if action was executed on plugin side
		if ( $payment_action ) {
			$notification_result
				->addMessage( __( 'Notification rejected: notification already processed', 'payever-woocommerce-gateway' ) )
				->setOrderId( $order_id )
				->setCurrentOrderStatus( $order_status );
			return false;
		}

		return true;
	}

	/**
	 * @param array  $payload
	 * @param string $order_status
	 *
	 * @return bool
	 */
	private function should_reject_if_expired_status( $payload, $order_status ) {
		$status          = $payload['data']['payment']['status'];
		$specific_status = $payload['data']['payment']['specific_status'];

		return in_array( $status, array( Status::STATUS_DECLINED, Status::STATUS_FAILED ) )
			&& in_array( $specific_status, array( 'ORDER_EXPIRED', 'CHECKOUT_EXPIRED' ) )
			&& 'wp-pending' !== $order_status;
	}

	private function should_reject_notification( $order_id, $notification_timestamp ) {
		$last_timestamp = $this->get_wp_wrapper()->get_post_meta( $order_id, 'notification_timestamp', true );

		return $last_timestamp > $notification_timestamp;
	}

	/**
	 * @param WC_Order $order
	 * @param string   $payever_order_status
	 *
	 * @return void
	 */
	private function update_order_status( $order, $payever_order_status ) {
		$status_mapping = WC_Payever_Helper::instance()->get_payever_status_mapping();
		$payever_wc_status = $this->get_payever_status( $status_mapping, $payever_order_status );
		$order->update_status( $payever_wc_status );

		/**
		 * Woocommerce restocks products only on 'cancelled' status
		 */
		if ( 'wc-failed' === $payever_wc_status && version_compare( WOOCOMMERCE_VERSION, '3.5.0', '>=' ) ) {
			wc_maybe_increase_stock_levels( $this->get_order_id( $order ) );
		}
	}

	private function get_payever_status( $status_mapping, $payever_order_status ) {
		if ( $this->get_wp_wrapper()->version_compare( WOOCOMMERCE_VERSION, '2.2.0', '>=' ) ) {
			return 'wc-' . $status_mapping[ $payever_order_status ];
		}

		return $status_mapping[ $payever_order_status ];
	}

	/**
	 * Updating order data
	 *
	 * @param $payever_payment
	 * @param null           $order
	 *
	 * @return bool|int
	 */
	private function update_order( $payever_payment, $order = null ) {
		if ( ! $order ) {
			$order = $this->get_order_wrapper()->get_wc_order( (int) $payever_payment->getReference() );
		}

		$this->update_order_status( $order, $payever_payment->getStatus() );

		if ( WC_Payever_Helper::instance()->is_santander( $payever_payment->getPaymentType() ) ) {
			$this->get_wp_wrapper()->update_post_meta(
				(int) $payever_payment->getReference(),
				'Santander application number',
				$payever_payment->getPaymentDetails()->getApplicationNumber()
			);

			$pan_id = $payever_payment->getPaymentDetails()->getUsageText();
			if ( ! empty( $pan_id ) ) {
				$this->get_wp_wrapper()->update_post_meta( (int) $payever_payment->getReference(), 'pan_id', $pan_id );
			}
		}

		return $this->get_order_wrapper()->set_payment_id(
			(int) $payever_payment->getReference(),
			$payever_payment->getId()
		);
	}

	/**
	 * Handle Action Notification.
	 *
	 * @param WC_order $order
	 * @param array    $payment Notification payment
	 * @return void
	 */
	public function handle_notification( $order, array $payment ) {
		$order_id = $this->get_order_id( $order );

		$this->get_logger()->info(
			sprintf(
				'[Notification] Handle payment action. Order ID: %s. Payment ID: %s',
				$order_id,
				$payment['id']
			)
		);
		$status = $payment['status'];

		// Handle capturing by amount
		if ( empty( $payment['captured_items'] ) && ! empty( $payment['capture_amount'] ) ) {
			$this->handle_shipping_goods_amount_notification( $order, $payment );
			return;
		}

		if ( ! empty( $payment['cancel_amount'] ) ) {
			$this->handle_cancel_amount_notification( $order, $payment );
		}

		if ( ! empty( $payment['captured_items'] ) ) {
			$this->handle_shipping_goods_items_notification( $order, $payment );
		}

		if ( in_array( $status, array( Status::STATUS_REFUNDED, Status::STATUS_CANCELLED ) ) ) {
			$this->handle_refund( $order, $payment );
		}
	}

	/**
	 * @param $order
	 * @param $payment
	 * @return void
	 */
	private function handle_refund( $order, $payment ) {
		// Handle refund by items
		if ( ! empty( $payment['refunded_items'] ) ) {
			$this->handle_refund_items_notification( $order, $payment );
			return;
		}

		// Handle refund by amount
		if ( empty( $payment['refunded_items'] ) && ! empty( $payment['refund_amount'] ) ) {
			$this->handle_refund_amount_notification( $order, $payment );
		}
	}

	/**
	 * @param $order
	 * @param array $payment
	 * @return bool
	 * @throws Exception
	 */
	private function handle_shipping_goods_items_notification( $order, array $payment ) {
		$order_id = $this->get_order_id( $order );

		$this->get_logger()->info(
			sprintf(
				'[Notification] Handle shipping items action. Order ID: %s. Payment ID: %s',
				$order_id,
				$payment['id']
			)
		);

		$capture_amount = $payment['capture_amount'];
		$total_captured = $payment['total_captured_amount'];
		$items          = $payment['captured_items'];

		// Update item qty
		$order_items = $this->get_order_total_model()->get_order_items( $order_id );

		// Attention: `captured_items` is historical, so update database. `capture_amount` is not historical.
		$processed_items = array();
		$amount = 0;
		$this
			->set_captured_items( $items, $order_items, $order, $processed_items, $amount )
			->set_shipping_item( $order_items, $items, $capture_amount, $processed_items, $payment );

		$this->get_wp_wrapper()->update_post_meta( $order_id, '_payever_order_items', $order_items );

		// Update order status
		if ( $order->get_total() - $total_captured <= 0.01 ) {
			$this->update_order_status( $order, Status::STATUS_PAID );
		}

		// Add success note
		$this->add_order_note(
			$order,
			Status::STATUS_PAID,
			$payment,
			$capture_amount,
			$processed_items
		);

		$this->get_logger()->info(
			sprintf(
				'[Notification] Captured items. Amount: %s. Transaction amount: %s. Items: %s',
				$amount,
				$capture_amount,
				wp_json_encode( $processed_items )
			),
			$items
		);

		return true;
	}

	/**
	 * @param $order_items
	 * @param $items
	 * @param $capture_amount
	 * @param $payment
	 * @param $processed_items
	 * @return $this
	 */
	private function set_shipping_item( &$order_items, $items, $capture_amount, $payment, &$processed_items ) {
		foreach ( $order_items as $key => $order_item ) {
			if ( 'shipping' !== $order_item['type'] ) {
				continue;
			}
			// Check if shipping was included
			$delivery_fee = isset( $payment['delivery_fee'] ) ? round( $payment['delivery_fee'], 2 ) : 0; //phpcs:ignore
			$calculated = 0;

			foreach ( $items as $tmp ) {
				$calculated += $tmp['price'] * $tmp['quantity'];
			}

			// Transaction could have delivery fee
			if ( $capture_amount - $calculated >= $delivery_fee ) {
				// Mark shipping captured
				$order_items[ $key ]['captured_qty'] += 1;
				$processed_items[] = array(
					'name'     => $order_items[ $key ]['name'],
					'quantity' => 1,
				);
			}
			break;
		}

		return $this;
	}

	/**
	 * @param $items
	 * @param $order_items
	 * @param $order
	 * @param $processed_items
	 * @param $amount
	 * @return $this
	 */
	private function set_captured_items( $items, &$order_items, $order, &$processed_items, &$amount ) {
		foreach ( $items as $item ) {
			$item_id = $this->get_order_total_model()->get_order_item_id_by_identifier( $order, $item['identifier'] );
			if ( ! $item_id ) {
				continue;
			}
			$quantity = $item['quantity'];
			$amount += $item['price'] * $quantity;

			foreach ( $order_items as $key => $order_item ) {
				if ( (string) $item_id === (string) $order_item['item_id'] ) {
					$order_items[ $key ]['captured_qty'] += $quantity;
					$processed_items[] = array(
						'name'     => $order_items[ $key ]['name'],
						'quantity' => $quantity,
					);
					break;
				}
			}
		}

		return $this;
	}

	/**
	 * Handle shipping amount notification.
	 *
	 * @param WC_Order $order
	 * @param array    $payment
	 *
	 * @return void
	 */
	private function handle_shipping_goods_amount_notification( $order, array $payment ) {
		$order_id = $this->get_order_id( $order );

		$this->get_logger()->info(
			sprintf(
				'[Notification] Handle shipping amount action. Order ID: %s. Payment ID: %s',
				$order_id,
				$payment['id']
			)
		);

		$capture_amount = $payment['capture_amount'];

		/**
		 * Register requested amount per items
		 */
		$amount = $capture_amount;
		$this->get_order_total_model()->partial_capture( $amount, $order_id );

		// Update order status
		if ( $order->get_total() - $capture_amount <= 0.01 ) {
			$this->update_order_status( $order, Status::STATUS_PAID );
		}

		// Add success note
		$this->add_order_note( $order, Status::STATUS_PAID, $payment, $capture_amount );

		$this->get_logger()->info(
			sprintf(
				'[Notification] Shipped: %s.',
				$capture_amount
			)
		);
	}

	/**
	 * Handle refund items notification.
	 *
	 * @param WC_Order $order
	 * @param array    $payment
	 *
	 * @return void
	 */
	private function handle_refund_items_notification( $order, array $payment ) {
		$order_id = $this->get_order_id( $order );

		$this->get_logger()->info(
			sprintf(
				'[Notification] Handle refund items action. Order ID: %s. Payment ID: %s',
				$order_id,
				$payment['id']
			)
		);

		$amount        = 0;
		$refund_amount = $payment['refund_amount'];
		$items         = $payment['refunded_items'];

		// Update item qty
		$order_items = $this->get_order_total_model()->get_order_items( $order_id );

		// Attention: `refunded_items` is historical, so update database. `refund_amount` is not historical.
		$processed_items = array();
		$this->set_refunded_items( $items, $order, $order_items, $amount, $processed_items );
		$this->set_refunded_shipping_item( $items, $payment, $refund_amount, $order_items, $processed_items );
		$this->get_wp_wrapper()->update_post_meta( $order_id, '_payever_order_items', $order_items );

		// Add success note
		$this->add_order_note(
			$order,
			Status::STATUS_REFUNDED,
			$payment,
			$refund_amount,
			$processed_items
		);

		set_transient( 'pe_lock_refund_' . $order_id, $refund_amount, MINUTE_IN_SECONDS );

		$this->get_logger()->info(
			sprintf(
				'[Notification] Refunded items. Amount: %s. Transaction amount: %s. Items: %s',
				$amount,
				$refund_amount,
				wp_json_encode( $processed_items )
			)
		);
	}

	private function set_refunded_items( $items, $order, &$order_items, &$amount, &$processed_items ) {
		foreach ( $items as $item ) {
			$item_id = $this->get_order_total_model()->get_order_item_id_by_identifier( $order, $item['identifier'] );
			if ( ! $item_id ) {
				continue;
			}

			$quantity = $item['quantity'];
			$amount += $item['price'] * $quantity;

			foreach ( $order_items as $key => $order_item ) {
				if ( (string) $item_id === (string) $order_item['item_id'] ) {
					$order_items[ $key ]['refunded_qty'] += $quantity;

					$processed_items[] = array(
						'name'     => $order_items[ $key ]['name'],
						'quantity' => $quantity,
					);

					break;
				}
			}
		}

		return $this;
	}

	private function set_refunded_shipping_item( $items, $payment, $refund_amount, &$order_items, &$processed_items ) {
		foreach ( $order_items as $key => $order_item ) {
			if ( 'shipping' === $order_item['type'] ) {
				// Check if shipping was included
				$delivery_fee = isset( $payment['delivery_fee'] ) ? round( $payment['delivery_fee'], 2 ) : 0; //phpcs:ignore

				$calculated = 0;
				foreach ( $items as $tmp ) {
					$calculated += $tmp['price'] * $tmp['quantity'];
				}

				// Transaction could have delivery fee
				if ( $refund_amount - $calculated >= $delivery_fee ) {
					// Mark shipping captured
					$order_items[ $key ]['refunded_qty'] += 1;

					$processed_items[] = array(
						'name'     => $order_items[ $key ]['name'],
						'quantity' => 1,
					);
				}

				break;
			}
		}

		return $this;
	}

	/**
	 * Handle refund amount notification.
	 *
	 * @param WC_Order $order
	 * @param array    $payment
	 *
	 * @return void
	 */
	private function handle_refund_amount_notification( $order, array $payment ) {
		$order_id = $this->get_order_id( $order );

		$this->get_logger()->info(
			sprintf(
				'[Notification] Handle refund amount action. Order ID: %s. Payment ID: %s',
				$order_id,
				$payment['id']
			)
		);

		$refund_amount = $payment['refund_amount'];

		/**
		 * Register requested amount per items
		 */
		$amount = $refund_amount;
		$this->get_order_total_model()->partial_refund( $amount, $order_id );

		// Add success note
		$this->add_order_note( $order, Status::STATUS_REFUNDED, $payment, $refund_amount );

		$this->get_logger()->info(
			sprintf(
				'[Notification] Refunded: %s.',
				$amount
			)
		);
	}

	/**
	 * @param $order
	 * @param array $payment
	 * @return bool
	 * @throws Exception
	 */
	private function handle_cancel_amount_notification( $order, array $payment ) {
		$order_id = $this->get_order_id( $order );

		$this->get_logger()->info(
			sprintf(
				'[Notification] Handle cancel amount action. Order ID: %s. Payment ID: %s',
				$order_id,
				$payment['id']
			)
		);

		$cancel_amount   = $payment['cancel_amount'];
		$total_cancelled = $payment['total_canceled_amount'];

		/**
		 * Register requested amount per items
		 */
		$amount = $cancel_amount;
		$this->get_order_total_model()->partial_cancel( $amount, $order_id );

		// Update order status
		if ( $order->get_total() - $total_cancelled <= 0.01 ) {
			$this->update_order_status( $order, Status::STATUS_CANCELLED );
		}

		// Add success note
		$this->add_order_note( $order, Status::STATUS_CANCELLED, $payment, $cancel_amount );

		$this->get_logger()->info(
			sprintf(
				'[Notification] Cancelled: %s.',
				$cancel_amount
			)
		);

		return true;
	}

	/**
	 * Add order note with detailed information.
	 *
	 * @param WC_Order   $order
	 * @param string     $payment_status
	 * @param array      $payment
	 * @param float|int  $amount
	 * @param array|null $processed_items
	 *
	 * @return void
	 */
	private function add_order_note(
		$order,
		$payment_status,
		array $payment,
		$amount,
		$processed_items = null
	) {
		$comment = '';

		switch ( $payment_status ) {
			case Status::STATUS_PAID:
				$comment .= sprintf(
					__( 'Shipped %s successfully.', 'payever-woocommerce-gateway' ),
					$this->get_wp_wrapper()->wc_price( $amount )
				);

				break;

			case Status::STATUS_REFUNDED:
				$comment .= sprintf(
					__( 'Refunded %s successfully.', 'payever-woocommerce-gateway' ),
					$this->get_wp_wrapper()->wc_price( $amount )
				);

				break;

			case Status::STATUS_CANCELLED:
				$comment .= sprintf(
					__( 'Cancelled %s successfully.', 'payever-woocommerce-gateway' ),
					$this->get_wp_wrapper()->wc_price( $amount )
				);

				break;
		}

		// Append items
		if ( is_array( $processed_items ) ) {
			$processed = '<br />';
			foreach ( $processed_items as $processed_item ) {
				$processed .= $processed_item['quantity'] . ' x ' . $processed_item['name'] . '<br />';
			}

			$comment .= '<br />' . sprintf(
				__( 'Items: %s', 'payever-woocommerce-gateway' ),
				$processed
			);
		}

		// Append transaction info
		$comment .= '<br />' . sprintf(
			__( 'Transaction ID: %s; Status: %s. Specific status: %s.', 'payever-woocommerce-gateway' ),
			$payment['id'],
			strtolower( str_replace( 'STATUS_', '', $payment['status'] ) ),
			isset( $payment['specific_status'] ) ? $payment['specific_status'] : 'UNDEFINED'
		);

		$this->get_order_wrapper()->add_order_note( $order, '<p style="color: green;">' . $comment . '</p>' );
	}

	/**
	 * @return \Psr\Log\LoggerInterface
	 */
	private function get_logger() {
		return WC_Payever_Api::get_instance()->get_logger();
	}

	/**
	 * Get Order ID.
	 *
	 * @param WC_Order $order
	 *
	 * @return mixed
	 */
	private function get_order_id( $order ) {
		if ( method_exists( $order, 'get_id' ) ) {
			return $order->get_id();
		}

		return $order->id;
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
