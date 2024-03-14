<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Order_Total' ) ) {
	return;
}

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @codeCoverageIgnore
 */
class WC_Payever_Order_Total {

	use WC_Payever_Helper_Wrapper_Trait;
	use WC_Payever_WP_Wrapper_Trait;

	/** @var WC_Payever_Order_Wrapper */
	private $order_wrapper;

	/**
	 * Get Totals.
	 *
	 * @param int $order_id
	 *
	 * @return array{cancelled: float, captured: float, refunded: float, available_capture: float, available_cancel: float, available_refund: float}
	 */
	public function get_totals( $order_id ) {
		$items = $this->get_order_items( $order_id );

		$paid      = 0.0;
		$cancelled = 0.0;
		$captured  = 0.0;
		$refunded  = 0.0;
		foreach ( $items as $item ) {
			$unit_price = $item['unit_price'];
			$paid      += $unit_price * $item['qty'];

			$totals = $this->get_item_total( $item );

			$cancelled += $totals['cancelled'];
			$refunded += $totals['refunded'];
			$captured += $totals['captured'];
		}

		return array(
			'cancelled'         => $cancelled,
			'captured'          => $captured,
			'refunded'          => $refunded,
			'available_capture' => $paid - ( $captured + $cancelled ),
			'available_cancel'  => $paid - ( $captured + $cancelled ),
			'available_refund'  => $captured - $refunded,
		);
	}

	private function get_item_total( $item ) {
		$unit_price = $item['unit_price'];
		$totals = array(
			'cancelled' => $unit_price * $item['cancelled_qty'],
			'captured'  => $unit_price * $item['captured_qty'],
			'refunded'  => $unit_price * $item['refunded_qty'],
		);
		if ( array_key_exists( 'captured_amount', $item ) && $item['captured_amount'] > 0 ) {
			$totals['captured'] = $item['captured_amount'];
		}
		if ( array_key_exists( 'refunded_amount', $item ) && $item['refunded_amount'] > 0 ) {
			$totals['refunded'] = $item['refunded_amount'];
		}
		if ( array_key_exists( 'cancelled_amount', $item ) && $item['cancelled_amount'] > 0 ) {
			$totals['cancelled'] = $item['cancelled_amount'];
		}

		return $totals;
	}

	/**
	 * Get Order Items.
	 *
	 * @param $order_id
	 * @param int $item_id
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function get_order_items( $order_id, $item_id = 0 ) {
		$items = get_post_meta( $order_id, '_payever_order_items', true );
		$items = $this->convert_order_items_from_old_versions( $order_id, $items );

		// Store order items in metadata if not exists
		if ( empty( $items ) || ! is_array( $items ) ) {
			$order = $this->get_order_wrapper()->get_wc_order( $order_id );
			$items = array();

			$order_items = $order->get_items( array( 'line_item', 'fee', 'shipping' ) );
			foreach ( $order_items as $order_item ) {
				/** @var WC_Order_Item_Product|WC_Order_Item_Shipping|WC_Order_Item_Fee $order_item */
				$payever_order_item = new WC_Payever_Order_Item( $order_item );
				$type               = $payever_order_item->get_type();

				$unit_price = ( 'line_item' === $type )
					? $this->get_order_item_subtotal( $order_item )
					: $this->get_order_shipping_item_total( $order_item );

				$items[] = array(
					'order_id'         => $order_id,
					'name'             => $payever_order_item->get_name(),
					'type'             => $type,
					'item_id'          => $payever_order_item->get_id(),
					'unit_price'       => $unit_price,
					'qty'              => $payever_order_item->get_quantity(),
					'cancelled_qty'    => 0,
					'captured_qty'     => 0,
					'refunded_qty'     => 0,
					'captured_amount'  => 0,
					'refunded_amount'  => 0,
					'cancelled_amount' => 0,
					'is_payment_fee'   => is_a( $order_item, 'WC_Order_Item_Fee' ),
				);
			}
			update_post_meta( $order_id, '_payever_order_items', $items );
		}
		if ( ! $item_id ) {
			return $items;
		}
		foreach ( $items as $item ) {
			if ( (string) $item_id === (string) $item['item_id'] ) {
				return $item;
			}
		}
		throw new Exception( 'Unable to find item ' . $item_id );
	}

	/**
	 * Get Order ItemID by Identifier.
	 *
	 * @param WC_Order $order
	 * @param string $identifier
	 *
	 * @return int|false
	 */
	public function get_order_item_id_by_identifier( $order, $identifier ) {
		$order_items = $order->get_items( array( 'line_item', 'fee', 'shipping', 'coupon' ) );
		foreach ( $order_items as $order_item ) {
			/** @var WC_Order_Item_Product $order_item */
			/** @var WC_Order_Item_Product|WC_Order_Item_Shipping|WC_Order_Item_Fee $order_item */
			$payever_order_item = new WC_Payever_Order_Item( $order_item );

			if ( $identifier === $this->get_order_item_identifier( $order, $order_item, $payever_order_item ) ) {
				return $payever_order_item->get_id();
			}
		}

		return false;
	}

	/**
	 * @param $order
	 * @param $order_item
	 * @param $payever_order_item
	 *
	 * @return false|mixed|string
	 */
	private function get_order_item_identifier( $order, $order_item, $payever_order_item ) {
		if ( $this->is_type( $order, $order_item, 'variable' ) ) {
			return $payever_order_item->get_variation_id();
		}
		if ( $this->is_type( $order, $order_item, 'line_item' ) ) {
			return $payever_order_item->get_product_id();
		}
		if ( $this->is_type( $order, $order_item, 'fee' ) ) {
			return 'fee-' . $payever_order_item->get_id();
		}
		if ( $this->is_type( $order, $order_item, 'coupon' ) ) {
			return 'discount';
		}
		if ( $this->is_type( $order, $order_item, 'shipping' ) ) {
			return $payever_order_item->get_id();
		}

		return false;
	}

	/**
	 * Register requested amount per items
	 *
	 * @param $amount
	 * @param $order_id
	 * @throws Exception
	 */
	public function partial_capture( &$amount, $order_id ) {
		$order_items          = $this->get_order_items( $order_id );
		$remained_for_capture = $amount;

		/**
		 * capture Payment fee first
		 */
		foreach ( $order_items as &$item ) {
			if ( array_key_exists( 'is_payment_fee', $item ) && $item['is_payment_fee'] ) {
				$this->partial_capture_per_item( $item, $remained_for_capture );
				break;
			}
		}

		/**
		 * capture other items
		 */
		foreach ( $order_items as &$item ) {
			if ( array_key_exists( 'is_payment_fee', $item ) && $item['is_payment_fee'] ) {
				continue;
			}
			$this->partial_capture_per_item( $item, $remained_for_capture );
		}

		if ( $remained_for_capture && $remained_for_capture <= 0.01 ) {
			$amount -= $remained_for_capture;
		}

		update_post_meta( $order_id, '_payever_order_items', $order_items );
	}

	/**
	 * Register requested amount per items
	 *
	 * @param $amount
	 * @param $order_id
	 * @throws Exception
	 */
	public function partial_refund( &$amount, $order_id ) {
		$order_items         = $this->get_order_items( $order_id );
		$remained_for_refund = $amount;

		/**
		 * refund Payment fee first
		 */
		foreach ( $order_items as &$item ) {
			if ( array_key_exists( 'is_payment_fee', $item ) && $item['is_payment_fee'] ) {
				$this->partial_refund_per_item( $item, $remained_for_refund );
				break;
			}
		}

		/**
		 * refund other items
		 */
		foreach ( $order_items as &$item ) {
			if ( array_key_exists( 'is_payment_fee', $item ) && $item['is_payment_fee'] ) {
				continue;
			}
			$this->partial_refund_per_item( $item, $remained_for_refund );
		}

		if ( $remained_for_refund && $remained_for_refund <= 0.01 ) {
			$amount -= $remained_for_refund;
		}

		update_post_meta( $order_id, '_payever_order_items', $order_items );
	}

	/**
	 * Register requested amount per items
	 *
	 * @param $amount
	 * @param $order_id
	 * @throws Exception
	 */
	public function partial_cancel( &$amount, $order_id ) {
		$order_items         = $this->get_order_items( $order_id );
		$remained_for_cancel = $amount;

		/**
		 * cancel Payment fee first
		 */
		foreach ( $order_items as &$item ) {
			if ( array_key_exists( 'is_payment_fee', $item ) && $item['is_payment_fee'] ) {
				$this->partial_cancel_per_item( $item, $remained_for_cancel );
				break;
			}
		}

		/**
		 * cancel other items
		 */
		foreach ( $order_items as &$item ) {
			if ( array_key_exists( 'is_payment_fee', $item ) && $item['is_payment_fee'] ) {
				continue;
			}
			$this->partial_cancel_per_item( $item, $remained_for_cancel );
		}

		if ( $remained_for_cancel && $remained_for_cancel <= 0.01 ) {
			$amount -= $remained_for_cancel;
		}

		update_post_meta( $order_id, '_payever_order_items', $order_items );
	}

	/**
	 * @param $item
	 * @param $remained_for_capture
	 *
	 * @return $this
	 */
	private function partial_capture_per_item( &$item, &$remained_for_capture ) {
		if ( ! $remained_for_capture ) {
			return $this;
		}
		$item_captured = 0;
		if ( array_key_exists( 'captured_amount', $item ) ) {
			$item_captured = $item['captured_amount'];
		}

		$item_qty = $item['qty'] - $item['cancelled_qty'] - $item['refunded_qty'];
		if ( ! $item_qty ) {
			return $this;
		}

		$row_total = $item['unit_price'] * $item_qty;
		$remain_total = round( $row_total - $item_captured, 2 );
		if ( $remain_total < 0.001 ) {
			return $this;
		}

		if ( $remain_total >= $remained_for_capture ) {
			$item['captured_amount'] += $remained_for_capture;
			$item['captured_qty']    = floor( $item['captured_amount'] / $item['unit_price'] );
			$remained_for_capture    = 0;
			return $this;
		}

		$item['captured_amount'] += $remain_total;
		$item['captured_qty'] = $item_qty;
		$remained_for_capture -= $remain_total;

		return $this;
	}

	/**
	 * @param $item
	 * @param $remained_for_refund
	 *
	 * @return $this
	 */
	private function partial_refund_per_item( &$item, &$remained_for_refund ) {
		if ( ! $remained_for_refund ) {
			return $this;
		}
		$item_refunded = 0;

		if ( array_key_exists( 'refunded_amount', $item ) ) {
			$item_refunded = $item['refunded_amount'];
		}

		$item_qty = $item['qty'] - $item['cancelled_qty'] - $item['refunded_qty'];
		if ( ! $item_qty ) {
			return $this;
		}

		$row_total    = $item['unit_price'] * $item_qty;
		$remain_total = round( $row_total - $item_refunded, 2 );
		if ( $remain_total < 0.001 ) {
			return $this;
		}

		if ( $remain_total >= $remained_for_refund ) {
			$item['refunded_amount'] += $remained_for_refund;
			$item['refunded_qty']    = floor( $item['refunded_amount'] / $item['unit_price'] );
			$remained_for_refund     = 0;
			return $this;
		}

		$item['refunded_amount'] += $remain_total;
		$item['refunded_qty'] = $item_qty;
		$remained_for_refund -= $remain_total;

		return $this;
	}

	/**
	 * @param $item
	 * @param $remained_for_cancel
	 *
	 * @return $this
	 */
	private function partial_cancel_per_item( &$item, &$remained_for_cancel ) {
		if ( ! $remained_for_cancel ) {
			return $this;
		}
		$item_cancel = 0;

		if ( array_key_exists( 'refunded_amount', $item ) ) {
			$item_cancel = $item['refunded_amount'];
		}

		$item_qty             = $item['qty'] - $item['cancelled_qty'] - $item['refunded_qty'];
		if ( ! $item_qty ) {
			return $this;
		}

		$row_total    = $item['unit_price'] * $item_qty;
		$remain_total = round( $row_total - $item_cancel, 2 );
		if ( $remain_total < 0.001 ) {
			return $this;
		}

		if ( $remain_total >= $remained_for_cancel ) {
			$item['cancelled_amount'] += $remained_for_cancel;
			$item['cancelled_qty']    = floor( $item['cancelled_amount'] / $item['unit_price'] );
			$remained_for_cancel     = 0;
			return $this;
		}

		$item['cancelled_amount'] += $remain_total;
		$item['cancelled_qty'] = $item_qty;
		$remained_for_cancel  -= $remain_total;

		return $this;
	}

	/**
	 * Backward compatibility
	 *
	 * @return array
	 */
	private function convert_order_items_from_old_versions( $order_id, $items ) {
		$captures = get_post_meta( $order_id, '_payever_partial_capture', true );
		if ( empty( $items ) && ! empty( $captures ) ) {
			$order = $this->get_order_wrapper()->get_wc_order( $order_id );
			$order_items = $order->get_items( array( 'line_item', 'fee', 'shipping' ) );

			$items = array();
			foreach ( $captures as $capture ) {
				foreach ( $order_items as $order_item ) {
					/** @var WC_Order_Item_Product|WC_Order_Item_Shipping|WC_Order_Item_Fee $order_item */
					$payever_order_item = new WC_Payever_Order_Item( $order_item );
					if ( $capture['item_id'] === $payever_order_item->get_id() ) {
						$items[] = array(
							'order_id'         => $capture['order_id'],
							'name'             => $capture['name'],
							'item_id'          => $capture['item_id'],
							'unit_price'       => $capture['amount'],
							'qty'              => $payever_order_item->get_quantity(),
							'cancelled_qty'    => 0,
							'captured_qty'     => $capture['qty'],
							'refunded_qty'     => 0,
							'captured_amount'  => 0,
							'refunded_amount'  => 0,
							'cancelled_amount' => 0,
							'is_payment_fee'   => is_a( $order_item, 'WC_Order_Item_Fee' ),
						);
						break;
					}
				}
			}
			update_post_meta( $order_id, '_payever_order_items', $items );
		}
		return $items;
	}

	/**
	 * @param WC_Order_Item_Shipping $order_item
	 *
	 * @return float|int
	 */
	private function get_order_shipping_item_total( $order_item ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			return $order_item->get_total() + $order_item->get_total_tax();
		}

		return $order_item['total'] + $order_item['total_tax'];
	}

	/**
	 * @param WC_Order_Item_Product $order_item
	 *
	 * @return float|int
	 */
	private function get_order_item_subtotal( $order_item ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			$qty = $order_item->get_quantity();
			$item_total = $qty > 0 ? ( ( $order_item->get_subtotal() + $order_item->get_subtotal_tax() ) / $qty ) : 0;

			return $item_total;
		}
		$qty = $order_item['quantity'];
		$item_total = $qty > 0 ? ( ( $order_item['subtotal'] + $order_item['subtotal_tax'] ) / $qty ) : 0;

		return $item_total;
	}

	/**
	 * Check Order item type.
	 *
	 * @param WC_Order $order
	 * @param WC_Order_Item|array $order_item
	 * @param string $type
	 *
	 * @return false|mixed
	 */
	private function is_type( $order, $order_item, $type ) {
		if ( is_object( $order_item ) ) {
			return $order_item->is_type( $type );
		}

		$product = $order->get_product_from_item( $order_item );
		if ( is_object( $product ) ) {
			return $product->is_type( $type );
		}

		return false;
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
}
